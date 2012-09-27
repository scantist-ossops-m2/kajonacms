<?php
/*"******************************************************************************************************
*   (c) 2004-2006 by MulchProductions, www.mulchprod.de                                                 *
*   (c) 2007-2012 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
*	$Id: class_modul_workflows_admin.php 4843 2012-08-06 10:22:23Z sidler $					    *
********************************************************************************************************/


/**
 * Admin class of the workflows-module. Responsible for editing workflows and organizing them.
 *
 * @package module_workflows
 * @author sidler@mulchprod.de
 */
class class_module_workflows_admin extends class_admin_simple implements interface_admin {

    const STR_LIST_HANDLER = "STR_LIST_HANDLER";

    /**
     * Constructor
     */
    public function __construct() {
        $this->setArrModuleEntry("moduleId", _workflows_module_id_);
        $this->setArrModuleEntry("modul", "workflows");
        parent::__construct();

        //set default action
        if($this->getParam("action") == "") {
            $this->setAction("myList");
        }
    }


    public function getOutputModuleNavi() {
        $arrReturn = array();
        $arrReturn[] = array("view", getLinkAdmin($this->getArrModule("modul"), "myList", "", $this->getLang("module_mylist"), "", "", true, "adminnavi"));
        $arrReturn[] = array("edit", getLinkAdmin($this->getArrModule("modul"), "list", "", $this->getLang("commons_list"), "", "", true, "adminnavi"));
        $arrReturn[] = array("", "");
        $arrReturn[] = array("right1", getLinkAdmin($this->getArrModule("modul"), "listHandlers", "", $this->getLang("actionListHandlers"), "", "", true, "adminnavi"));
        $arrReturn[] = array("", "");
        $arrReturn[] = array("right", getLinkAdmin("right", "change", "&changemodule=" . $this->arrModule["modul"], $this->getLang("commons_module_permissions"), "", "", true, "adminnavi"));
        //$arrReturn[] = array("edit", getLinkAdmin($this->arrModule["modul"], "triggerWorkflows", "", $this->getLang("module_trigger"), "", "", true, "adminnavi"));
        return $arrReturn;
    }

    /**
     * Renders the form to create a new entry
     *
     * @return string
     * @permissions edit
     */
    protected function actionNew() {
        return "";
    }

    /**
     * Renders the form to edit an existing entry
     *
     * @return string
     * @permissions edit
     */
    protected function actionEdit() {
        $objInstance = class_objectfactory::getInstance()->getObject($this->getSystemid());

        if($objInstance instanceof class_module_workflows_handler && $objInstance->rightRight1()) {
            $this->adminReload(getLinkAdminHref($this->getArrModule("modul"), "editHandler", "&systemid=" . $objInstance->getSystemid()));
        }

        return "";
    }


    /**
     * Creates a list of all workflows-instances currently available.
     *
     * @return string
     * @autoTestable
     * @permissions view
     */
    protected function actionList() {

        $objIterator = new class_array_section_iterator(class_module_workflows_workflow::getObjectCount());
        $objIterator->setPageNumber($this->getParam("pv"));
        $objIterator->setArraySection(class_module_workflows_workflow::getAllworkflows($objIterator->calculateStartPos(), $objIterator->calculateEndPos()));

        return $this->renderList($objIterator);
    }


    /**
     * Creates a list of workflow-instances available for the current user
     *
     * @return string
     * @autoTestable
     * @permissions view
     */
    protected function actionMyList() {

        $objIterator = new class_array_section_iterator(class_module_workflows_workflow::getPendingWorkflowsForUserCount(array_merge(array($this->objSession->getUserID()), $this->objSession->getGroupIdsAsArray())));
        $objIterator->setPageNumber($this->getParam("pv"));
        $objIterator->setArraySection(class_module_workflows_workflow::getPendingWorkflowsForUser(array_merge(array($this->objSession->getUserID()), $this->objSession->getGroupIdsAsArray()), $objIterator->calculateStartPos(), $objIterator->calculateEndPos()));

        return $this->renderList($objIterator);
    }


    /**
     * Shows technical details of a workflow-instance
     *
     * @return string
     * @permissions edit
     */
    protected function actionShowDetails() {
        $strReturn = "";
        $objWorkflow = new class_module_workflows_workflow($this->getSystemid());


        $intI = 0;
        $strReturn .= $this->objToolkit->formHeadline($this->getLang("workflow_general"));

        $strReturn .= $this->objToolkit->listHeader();
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_class"), "", $objWorkflow->getStrClass(), $intI++);
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_systemid"), "", $objWorkflow->getStrAffectedSystemid(), $intI++);
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_trigger"), "", dateToString($objWorkflow->getObjTriggerdate()), $intI++);
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_runs"), "", $objWorkflow->getIntRuns(), $intI++);
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_status"), "", $this->getLang("workflow_status_" . $objWorkflow->getIntState()), $intI++);

        $strResponsible = "";
        foreach(explode(",", $objWorkflow->getStrResponsible()) as $strOneId) {
            if(validateSystemid($strOneId)) {
                if($strResponsible != "") {
                    $strResponsible .= ", ";
                }

                $objUser = new class_module_user_user($strOneId, false);
                if($objUser->getStrUsername() != "") {
                    $strResponsible .= $objUser->getStrUsername();
                }
                else {
                    $objGroup = new class_module_user_group($strOneId);
                    $strResponsible .= $objGroup->getStrName();
                }
            }
        }
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_responsible"), "", $strResponsible, $intI++);

        $strCreator = "";
        if(validateSystemid($objWorkflow->getStrOwner())) {
            $objUser = new class_module_user_user($objWorkflow->getStrOwner(), false);
            $strCreator .= $objUser->getStrUsername();
        }
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_owner"), "", $strCreator, $intI++);
        $strReturn .= $this->objToolkit->listFooter();

        $strReturn .= $this->objToolkit->formHeadline($this->getLang("workflow_params"));
        $strReturn .= $this->objToolkit->listHeader();
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_int1"), "", $objWorkflow->getIntInt1(), $intI++);
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_int2"), "", $objWorkflow->getIntInt2(), $intI++);
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_char1"), "", $objWorkflow->getStrChar1(), $intI++);
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_char2"), "", $objWorkflow->getStrChar2(), $intI++);
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_date1"), "", $objWorkflow->getLongDate1(), $intI++);
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_date2"), "", $objWorkflow->getLongDate2(), $intI++);
        $strReturn .= $this->objToolkit->genericAdminList("", $this->getLang("workflow_text"), "", $objWorkflow->getStrText(), $intI++);
        $strReturn .= $this->objToolkit->listFooter();

        $strReturn .= $this->objToolkit->formHeader(getLinkAdminHref($this->arrModule["modul"], "list"));
        $strReturn .= $this->objToolkit->formInputSubmit($this->getLang("commons_back"));
        $strReturn .= $this->objToolkit->formClose();

        return $strReturn;
    }

    /**
     * Uses the workflow-manager to trigger the scheduling and execution of workflows
     *
     * @return string
     * @permissions edit
     */
    protected function actionTriggerWorkflows() {
        $strReturn = "";
        $objWorkflowController = new class_workflows_controller();
        $objWorkflowController->scheduleWorkflows();
        $objWorkflowController->runWorkflows();

        $this->adminReload(getLinkAdminHref($this->arrModule["modul"], "list"));

        return $strReturn;
    }


    /**
     * Creates the form to perform the current workflow-step
     *
     * @return string
     * @permissions view
     */
    protected function actionShowUi() {
        $strReturn = "";

        $objWorkflow = new class_module_workflows_workflow($this->getSystemid());
        if($objWorkflow->getIntState() != class_module_workflows_workflow::$INT_STATE_SCHEDULED || !$objWorkflow->getObjWorkflowHandler()->providesUserInterface()) {
            return $this->getLang("commons_error_permissions");
        }

        $arrIdsToCheck = array_merge(array($this->objSession->getUserID()), $this->objSession->getGroupIdsAsArray());
        $arrIdsOfTask = explode(",", $objWorkflow->getStrResponsible());

        //ui given? current user responsible?
        if($objWorkflow->getObjWorkflowHandler()->providesUserInterface() && ($objWorkflow->getStrResponsible() == "" ||
            //magic: the difference of the tasks' ids and the users' ids should be less than the count of the task-ids - then at least one id matches
            count(array_diff($arrIdsOfTask, $arrIdsToCheck)) < count($arrIdsOfTask))
        ) {


            $strCreator = "";
            if(validateSystemid($objWorkflow->getStrOwner())) {
                $objUser = new class_module_user_user($objWorkflow->getStrOwner(), false);
                $strCreator .= $objUser->getStrUsername();
            }
            $strInfo = $this->objToolkit->getTextRow($this->getLang("workflow_owner") . " " . $strCreator);

            $strResponsible = "";
            foreach(explode(",", $objWorkflow->getStrResponsible()) as $strOneId) {
                if(validateSystemid($strOneId)) {
                    if($strResponsible != "") {
                        $strResponsible .= ", ";
                    }

                    $objUser = new class_module_user_user($strOneId, false);
                    if($objUser->getStrUsername() != "") {
                        $strResponsible .= $objUser->getStrUsername();
                    }
                    else {
                        $objGroup = new class_module_user_group($strOneId);
                        $strResponsible .= $objGroup->getStrName();
                    }
                }
            }
            $strInfo .= $this->objToolkit->getTextRow($this->getLang("workflow_responsible") . " " . $strResponsible);
            $strReturn .= $this->objToolkit->getFieldset($this->getLang("workflow_general"), $strInfo);

            $strReturn .= $this->objToolkit->formHeader(getLinkAdminHref($this->arrModule["modul"], "saveUI"));
            $strReturn .= $objWorkflow->getObjWorkflowHandler()->getUserInterface();
            $strReturn .= $this->objToolkit->formInputHidden("systemid", $objWorkflow->getSystemid());

            $strReturn .= $this->objToolkit->formInputSubmit($this->getLang("commons_save"));
            $strReturn .= $this->objToolkit->formClose();

        }
        else {
            $strReturn .= $this->getLang("commons_error_permissions");
        }

        return $strReturn;
    }


    /**
     * Calls the handler to process the values collected by the ui before.
     *
     * @throws class_exception
     * @return string
     * @permissions view
     */
    protected function actionSaveUi() {
        $strReturn = "";
        $objWorkflow = new class_module_workflows_workflow($this->getSystemid());

        $arrIdsToCheck = array_merge(array($this->objSession->getUserID()), $this->objSession->getGroupIdsAsArray());
        $arrIdsOfTask = explode(",", $objWorkflow->getStrResponsible());

        //ui given? current user responsible?
        if(
            $objWorkflow->getObjWorkflowHandler()->providesUserInterface() &&
            (
                $objWorkflow->getStrResponsible() == "" ||
                //magic: the difference of the tasks' ids and the users' ids should be less than the count of the task-ids - then at least one id matches
                count(array_diff($arrIdsOfTask, $arrIdsToCheck)) < count($arrIdsOfTask)
            )
        ) {
            $objHandler = $objWorkflow->getObjWorkflowHandler();
            $objHandler->processUserInput($this->getAllParams());

            if($objWorkflow->getBitSaved() == true) {
                throw new class_exception("Illegal state detected! Workflow was already saved before!", class_exception::$level_FATALERROR);
            }

            $objWorkflow->updateObjectToDb();

            $this->adminReload(getLinkAdminHref($this->arrModule["modul"], "myList"));
        }
        else {
            $strReturn .= $this->getLang("commons_error_permissions");
        }

        return $strReturn;
    }

    protected function renderEditAction(class_model $objListEntry, $bitDialog = false) {
        if($objListEntry instanceof class_module_workflows_handler) {
            return $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "editHandler", "&systemid=" . $objListEntry->getSystemid(), "", $this->getLang("actionEditHandler"), "icon_edit.png"));
        }
        return parent::renderEditAction($objListEntry, $bitDialog);
    }


    protected function renderUnlockAction(interface_model $objListEntry) {
        if($objListEntry instanceof class_module_workflows_handler) {
            return "";
        }
        return parent::renderUnlockAction($objListEntry);
    }

    protected function renderDeleteAction(interface_model $objListEntry) {
        if($objListEntry instanceof class_module_workflows_handler) {
            return "";
        }
        return parent::renderDeleteAction($objListEntry);
    }

    protected function renderStatusAction(class_model $objListEntry) {
        if($objListEntry instanceof class_module_workflows_handler) {
            return "";
        }
        if($objListEntry instanceof class_module_workflows_workflow) {
            $strStatusIcon = "";
            if($objListEntry->getIntState() == class_module_workflows_workflow::$INT_STATE_NEW) {
                $strStatusIcon = getImageAdmin("icon_workflowNew.png", $this->getLang("workflow_status_" . $objListEntry->getIntState()));
            }
            if($objListEntry->getIntState() == class_module_workflows_workflow::$INT_STATE_SCHEDULED) {
                $strStatusIcon = getImageAdmin("icon_workflowScheduled.png", $this->getLang("workflow_status_" . $objListEntry->getIntState()));
            }
            if($objListEntry->getIntState() == class_module_workflows_workflow::$INT_STATE_EXECUTED) {
                $strStatusIcon = getImageAdmin("icon_workflowExecuted.png", $this->getLang("workflow_status_" . $objListEntry->getIntState()));
            }

            if($strStatusIcon != "") {
                return $this->objToolkit->listButton($strStatusIcon);
            }
        }
        return parent::renderStatusAction($objListEntry);
    }

    protected function renderPermissionsAction(class_model $objListEntry) {
        if($objListEntry instanceof class_module_workflows_handler) {
            return "";
        }
        return parent::renderPermissionsAction($objListEntry);
    }

    protected function renderTagAction(class_model $objListEntry) {
        if($objListEntry instanceof class_module_workflows_handler) {
            return "";
        }
        return parent::renderTagAction($objListEntry);
    }

    protected function renderCopyAction(class_model $objListEntry) {
        if($objListEntry instanceof class_module_workflows_handler) {
            return "";
        }
        return parent::renderCopyAction($objListEntry);
    }

    protected function renderAdditionalActions(class_model $objListEntry) {
        if($objListEntry instanceof class_module_workflows_handler) {
            return array(
                $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "instantiateHandler", "&systemid=" . $objListEntry->getSystemid(), "", $this->getLang("actionInstantiateHandler"), "icon_workflowTrigger.png"))
            );
        }
        if($objListEntry instanceof class_module_workflows_workflow) {
            $arrReturn = array();
            if($objListEntry->getIntState() == class_module_workflows_workflow::$INT_STATE_SCHEDULED && $objListEntry->getObjWorkflowHandler()->providesUserInterface()) {
                $arrReturn[] = $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "showUI", "&systemid=" . $objListEntry->getSystemid(), "", $this->getLang("workflow_ui"), "icon_workflow_ui.png"));
            }

            if($objListEntry->rightEdit()) {
                $arrReturn[] = $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "showDetails", "&systemid=" . $objListEntry->getSystemid(), "", $this->getLang("actionShowDetails"), "icon_lens.png"));
            }

            return $arrReturn;
        }
        return parent::renderAdditionalActions($objListEntry);
    }

    protected function getNewEntryAction($strListIdentifier, $bitDialog = false) {
        return "";
    }

    protected function renderChangeHistoryAction(class_model $objListEntry) {
        if($objListEntry instanceof class_module_workflows_handler) {
            return "";
        }
        return parent::renderChangeHistoryAction($objListEntry);
    }


    /**
     * Lists all handlers available to the system
     *
     * @return string
     * @autoTestable
     * @permissions right1
     */
    protected function actionListHandlers() {
        class_module_workflows_handler::synchronizeHandlerList();

        $strReturn = $this->objToolkit->formHeadline($this->getLang("actionListHandlers"));

        $objIterator = new class_array_section_iterator(class_module_workflows_handler::getObjectCount());
        $objIterator->setPageNumber($this->getParam("pv"));
        $objIterator->setArraySection(class_module_workflows_handler::getObjectList("", $objIterator->calculateStartPos(), $objIterator->calculateEndPos()));

        $strReturn .= $this->renderList($objIterator, false, self::STR_LIST_HANDLER);
        return $strReturn;

    }

    /**
     * Renders the form to edit a workflow-handlers default values
     *
     * @param class_admin_formgenerator $objForm
     *
     * @return string
     * @permissions right1
     */
    protected function actionEditHandler(class_admin_formgenerator $objForm = null) {
        $strReturn = "";
        $objHandler = new class_module_workflows_handler($this->getSystemid());
        //check rights
        if($objHandler->rightEdit()) {
            if($objForm == null) {
                $objForm = $this->getHandlerForm($objHandler);
            }


            $strReturn .= $this->objToolkit->formHeadline($objHandler->getObjInstanceOfHandler()->getStrName() . " (" . $objHandler->getStrHandlerClass() . ")");
            $strReturn .= $objForm->renderForm(getLinkAdminHref($this->getArrModule("modul"), "saveHandler"));
            return $strReturn;
        }
        else {
            $strReturn .= $this->getLang("commons_error_permissions");
        }

        return $strReturn;
    }


    private function getHandlerForm(class_module_workflows_handler $objHandler) {
        $objForm = new class_admin_formgenerator("handler", $objHandler);
        $objForm->generateFieldsFromObject();

        $arrNames = $objHandler->getObjInstanceOfHandler()->getConfigValueNames();

        $objForm->getField("configval1")->setStrLabel((isset($arrNames[0]) ? $arrNames[0] : $this->getLang("workflow_handler_val1")));
        $objForm->getField("configval2")->setStrLabel((isset($arrNames[1]) ? $arrNames[1] : $this->getLang("workflow_handler_val2")));
        $objForm->getField("configval3")->setStrLabel((isset($arrNames[2]) ? $arrNames[2] : $this->getLang("workflow_handler_val3")));

        return $objForm;
    }

    /**
     * @return string
     * @permissions right1
     */
    protected function actionSaveHandler() {

        $objHandler = new class_module_workflows_handler($this->getSystemid());

        if($objHandler->rightRight1()) {

            $objForm = $this->getHandlerForm($objHandler);
            if(!$objForm->validateForm()) {
                return $this->actionEditHandler($objForm);
            }

            $objForm->updateSourceObject();
            $objHandler->updateObjectToDb();

            $this->adminReload(getLinkAdminHref($this->getArrModule("modul"), "listHandlers", ""));
            return "";
        }
        else {
            return $this->getLang("commons_error_permissions");
        }
    }

    /**
     * @return string
     * @permissions right1
     */
    protected function actionInstantiateHandler() {
        $strReturn = "";

        $objHandler = new class_module_workflows_handler($this->getSystemid());
        $strReturn .= $this->objToolkit->formHeadline($objHandler->getObjInstanceOfHandler()->getStrName() . " (" . $objHandler->getStrHandlerClass() . ")");
        $strReturn .= $this->objToolkit->formHeader(getLinkAdminHref($this->getArrModule("modul"), "startInstance"));
        $strReturn .= $this->objToolkit->formInputText("instance_systemid", $this->getLang("instance_systemid"));
        $strReturn .= $this->objToolkit->formInputUserSelector("instance_responsible", $this->getLang("instance_responsible"));
        $strReturn .= $this->objToolkit->formInputHidden("instance_responsible_id", "");
        $strReturn .= $this->objToolkit->formInputHidden("systemid", $this->getSystemid());
        $strReturn .= $this->objToolkit->formInputSubmit($this->getLang("commons_save"));

        return $strReturn;
    }

    /**
     * @return string
     * @permissions right1
     */
    protected function actionStartInstance() {
        $strReturn = "";

        $objHandler = new class_module_workflows_handler($this->getSystemid());
        $objWorkflow = new class_module_workflows_workflow();
        $objWorkflow->setStrClass($objHandler->getStrHandlerClass());
        $objWorkflow->setStrAffectedSystemid($this->getParam("instance_systemid"));
        $objWorkflow->setStrResponsible($this->getParam("instance_responsible_id"));
        $objWorkflow->updateObjectToDb();
        $this->adminReload(getLinkAdminHref($this->getArrModule("modul"), "list"));

        return $strReturn;
    }

}

