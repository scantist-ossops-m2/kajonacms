<?php
/*"******************************************************************************************************
*   (c) 2004-2006 by MulchProductions, www.mulchprod.de                                                 *
*   (c) 2007-2016 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
*	$Id$                                   *
********************************************************************************************************/

namespace Kajona\Flow\Admin;

use Kajona\Flow\System\FlowActionAbstract;
use Kajona\Flow\System\FlowConditionAbstract;
use Kajona\Flow\System\FlowConfig;
use Kajona\Flow\System\FlowGraphWriter;
use Kajona\Flow\System\FlowStatus;
use Kajona\Flow\System\FlowStatusFilter;
use Kajona\Flow\System\FlowTransition;
use Kajona\System\Admin\AdminEvensimpler;
use Kajona\System\Admin\AdminInterface;
use Kajona\System\System\AdminskinHelper;
use Kajona\System\System\ArraySectionIterator;
use Kajona\System\System\Link;
use Kajona\System\System\Model;
use Kajona\System\System\ModelInterface;
use Kajona\System\System\Objectfactory;

/**
 * Admin class to setup status transition flows
 *
 * @author christoph.kappestein@gmail.com
 *
 * @objectListFlow Kajona\Flow\System\FlowConfig
 *
 * @objectListStep Kajona\Flow\System\FlowStatus
 * @objectNewStep Kajona\Flow\System\FlowStatus
 * @objectEditStep Kajona\Flow\System\FlowStatus
 * @objectFilterStep Kajona\Flow\System\FlowStatusFilter
 *
 * @objectListTransition Kajona\Flow\System\FlowTransition
 * @objectNewTransition Kajona\Flow\System\FlowTransition
 * @objectEditTransition Kajona\Flow\System\FlowTransition
 *
 * @objectListTransitionAction Kajona\Flow\System\FlowActionAbstract
 * @objectNewTransitionAction Kajona\Flow\System\FlowActionAbstract
 * @objectEditTransitionAction Kajona\Flow\System\FlowActionAbstract
 *
 * @objectListTransitionCondition Kajona\Flow\System\FlowConditionAbstract
 * @objectNewTransitionCondition Kajona\Flow\System\FlowConditionAbstract
 * @objectEditTransitionCondition Kajona\Flow\System\FlowConditionAbstract
 *
 * @module flow
 * @moduleId _flow_module_id_
 */
class FlowAdmin extends AdminEvensimpler implements AdminInterface
{
    /**
     * @return array
     */
    public function getOutputModuleNavi()
    {
        $arrReturn = array();
        $arrReturn[] = array("view", Link::getLinkAdmin($this->getArrModule("modul"), "listFlow", "", $this->getLang("list_flow"), "", "", true, "adminnavi"));
        return $arrReturn;
    }

    public function renderAdditionalActions(Model $objListEntry)
    {
        $arrActions = parent::renderAdditionalActions($objListEntry);

        if ($objListEntry instanceof FlowConfig) {
            $arrActions[] = $this->objToolkit->listButton(Link::getLinkAdmin($this->getArrModule("modul"), "listStep", "&systemid=" . $objListEntry->getSystemid(), "", $this->getLang("action_steps"), "icon_kriterium"));
        } elseif ($objListEntry instanceof FlowStatus) {
            $arrActions[] = $this->objToolkit->listButton(Link::getLinkAdmin($this->getArrModule("modul"), "listTransition", "&systemid=" . $objListEntry->getSystemid(), "", $this->getLang("action_transitions"), "icon_project"));
        } elseif ($objListEntry instanceof FlowTransition) {
            $arrActions[] = $this->objToolkit->listButton(Link::getLinkAdmin($this->getArrModule("modul"), "listTransitionAction", "&systemid=" . $objListEntry->getSystemid(), "", $this->getLang("action_transition_action"), "icon_play"));
            $arrActions[] = $this->objToolkit->listButton(Link::getLinkAdmin($this->getArrModule("modul"), "listTransitionCondition", "&systemid=" . $objListEntry->getSystemid(), "", $this->getLang("action_transition_condition"), "icon_table"));
        }

        return $arrActions;
    }

    public function renderTagAction(Model $objListEntry)
    {
        if ($objListEntry instanceof FlowConfig) {
            return "";
        } elseif ($objListEntry instanceof FlowStatus) {
            return "";
        } elseif ($objListEntry instanceof FlowActionAbstract) {
            return "";
        } elseif ($objListEntry instanceof FlowConditionAbstract) {
            return "";
        }

        return parent::renderTagAction($objListEntry);
    }

    public function renderPermissionsAction(Model $objListEntry)
    {
        if ($objListEntry instanceof FlowConfig) {
            return "";
        } elseif ($objListEntry instanceof FlowStatus) {
            return "";
        } elseif ($objListEntry instanceof FlowActionAbstract) {
            return "";
        } elseif ($objListEntry instanceof FlowConditionAbstract) {
            return "";
        }

        return parent::renderPermissionsAction($objListEntry);
    }

    public function renderStatusAction(Model $objListEntry, $strAltActive = "", $strAltInactive = "")
    {
        if ($objListEntry instanceof FlowConfig) {
            return "";
        } elseif ($objListEntry instanceof FlowStatus) {
            return "";
        }

        return parent::renderStatusAction($objListEntry, $strAltActive, $strAltInactive);
    }

    public function renderCopyAction(Model $objListEntry)
    {
        if ($objListEntry instanceof FlowConfig) {
            return "";
        } elseif ($objListEntry instanceof FlowStatus) {
            return "";
        } elseif ($objListEntry instanceof FlowActionAbstract) {
            return "";
        } elseif ($objListEntry instanceof FlowConditionAbstract) {
            return "";
        }

        return parent::renderCopyAction($objListEntry);
    }

    protected function renderEditAction(Model $objListEntry, $bitDialog = false)
    {
        if ($objListEntry instanceof FlowConfig) {
            return "";
        }

        return parent::renderEditAction($objListEntry, $bitDialog);
    }

    protected function renderDeleteAction(ModelInterface $objListEntry)
    {
        if ($objListEntry instanceof FlowConfig) {
            return "";
        }

        return parent::renderDeleteAction($objListEntry);
    }

    protected function getOutputNaviEntry(ModelInterface $objInstance)
    {
        if ($objInstance instanceof FlowConfig) {
            return Link::getLinkAdmin("flow", "listStep", "&systemid=" . $objInstance->getSystemid(), $objInstance->getStrDisplayName());
        } elseif ($objInstance instanceof FlowStatus) {
            return Link::getLinkAdmin("flow", "listTransition", "&systemid=" . $objInstance->getSystemid(), $objInstance->getStrDisplayName());
        } elseif ($objInstance instanceof FlowTransition) {
            return Link::getLinkAdmin("flow", "listTransition", "&systemid=" . $objInstance->getPrevId(), $objInstance->getStrDisplayName());
        } elseif ($objInstance instanceof FlowActionAbstract) {
            return Link::getLinkAdmin("flow", "listTransitionAction", "&systemid=" . $objInstance->getPrevId(), $objInstance->getStrDisplayName());
        } elseif ($objInstance instanceof FlowConditionAbstract) {
            return Link::getLinkAdmin("flow", "listTransitionCondition", "&systemid=" . $objInstance->getPrevId(), $objInstance->getStrDisplayName());
        } else {
            return null;
        }
    }

    public function getNewEntryAction($strListIdentifier, $bitDialog = false)
    {
        $strAction = parent::getNewEntryAction($strListIdentifier, $bitDialog);
        $strSystemId = $this->getParam("systemid");

        if ($strListIdentifier == "liststep") {
            return $this->objToolkit->listButton(
                Link::getLinkAdmin(
                    $this->getArrModule("modul"), "newStep", "&systemid=".$strSystemId,
                    $this->getLang("commons_list_new"), $this->getLang("commons_list_new"), "icon_new"
                )
            );
        } elseif ($strListIdentifier == "listFlow") {
            return "";
        } elseif ($strListIdentifier == "listTransitionAction") {
            $arrLinks = array();
            $objFlow = $this->getFlowFromSystemId($strSystemId);
            $arrActions = $objFlow->getHandler()->getAvailableActions();

            foreach ($arrActions as $strActionClass) {
                $objAction = new $strActionClass();
                $arrLinks[] = $this->objToolkit->listButton(
                    Link::getLinkAdmin(
                        $this->getArrModule("modul"), $this->getActionNameForClass("newTransitionAction", null), "&systemid=".$this->getSystemid()."&class=".$strActionClass.$this->getStrPeAddon(), $objAction->getTitle(), $objAction->getTitle(), "icon_textfield", $objAction->getTitle()
                    )
                );
            }

            return $arrLinks;
        } elseif ($strListIdentifier == "listTransitionCondition") {
            $arrLinks = array();
            $objFlow = $this->getFlowFromSystemId($strSystemId);
            $arrActions = $objFlow->getHandler()->getAvailableConditions();

            foreach ($arrActions as $strConditionClass) {
                $objCondition = new $strConditionClass();
                $arrLinks[] = $this->objToolkit->listButton(
                    Link::getLinkAdmin(
                        $this->getArrModule("modul"), $this->getActionNameForClass("newTransitionCondition", null), "&systemid=".$this->getSystemid()."&class=".$strConditionClass.$this->getStrPeAddon(), $objCondition->getTitle(), $objCondition->getTitle(), "icon_textfield", $objCondition->getTitle()
                    )
                );
            }

            return $arrLinks;
        } else {
            return $strAction;
        }
    }

    /**
     * @return string
     * @permissions view
     */
    public function actionListStep()
    {
        $this->setStrCurObjectTypeName('step');
        $this->setCurObjectClassName(FlowStatus::class);

        $objFlow = $this->objFactory->getObject($this->getParam("systemid"));

        /* Create list */
        $objFilter = FlowStatusFilter::getOrCreateFromSession();
        $objArraySectionIterator = new ArraySectionIterator(FlowStatus::getObjectCountFiltered($objFilter, $this->getSystemid()));
        $objArraySectionIterator->setPageNumber((int)($this->getParam("pv") != "" ? $this->getParam("pv") : 1));
        $objArraySectionIterator->setArraySection(FlowStatus::getObjectListFiltered($objFilter, $this->getSystemid(), $objArraySectionIterator->calculateStartPos(), $objArraySectionIterator->calculateEndPos()));

        /* Render list and filter */
        $strList = $this->renderList($objArraySectionIterator, true, "list".$this->getStrCurObjectTypeName());
        $strGraph = FlowGraphWriter::write($objFlow);

        $strHtml = "<div class='row'>";
        $strHtml .= "<div class='col-md-6'>" . $strList . "</div>";
        $strHtml .= "<div class='col-md-6'><div id='flow-graph' class='mermaid' style='color:#fff;'>" . $strGraph . "</div></div>";
        $strHtml .= "</div>";

        $strHtml .= <<<HTML
<script type="text/javascript">
    require(['mermaid', 'loader'], function(mermaid, loader){
        loader.loadFile(["/core/module_flow/scripts/mermaid/mermaid.forest.css"], function(){
            mermaid.init(undefined, $("#flow-graph"));
        });
    });
</script>
HTML;

        return $strHtml;
    }

    /**
     * @return string
     * @permissions view
     */
    protected function actionStepBrowser()
    {
        $this->setArrModuleEntry("template", "/folderview.tpl");

        $strReturn = "";
        $strFormElement = $this->getParam("form_element");

        $objStatus = $this->objFactory->getObject($this->getSystemid());
        $arrStatus = array();

        if ($objStatus instanceof FlowConfig) {
            $arrStatus = $objStatus->getArrStatus();
        } elseif ($objStatus instanceof FlowStatus) {
            /** @var FlowConfig $objFlow */
            $objFlow = $this->objFactory->getObject($objStatus->getPrevId());
            $arrStatus = $objFlow->getArrStatus();
        }

        $strReturn .= $this->objToolkit->listHeader();
        foreach ($arrStatus as $objStatus) {
            if (!$objStatus->rightView()) {
                continue;
            }

            $strAction = "";
            $strAction .= $this->objToolkit->listButton(
                "<a href=\"#\" title=\"".$this->getLang("prozess_uebernehmen")."\" rel=\"tooltip\" onClick=\"require('v4skin').setObjectListItems(
                    '".$strFormElement."', [{strSystemId: '".$objStatus->getSystemid()."', strDisplayName: '".addslashes($objStatus->getStrName())."', strIcon:'".addslashes(getImageAdmin("icon_treeLeaf", "", true))."'}], null, '".addslashes(getImageAdmin("icon_delete", "", true))."'); parent.KAJONA.admin.folderview.dialog.hide();\">"
                .AdminskinHelper::getAdminImage("icon_accept") . "</a>"
            );

            $strReturn .= $this->objToolkit->simpleAdminList($objStatus, $strAction);
        }

        $strReturn .= $this->objToolkit->listFooter();

        if (count($arrStatus) == 0) {
            $strReturn .= $this->getLang("commons_list_empty");
        }

        return $strReturn;
    }

    /**
     * @return string
     * @permissions edit
     */
    public function actionNewTransitionAction()
    {
        $this->setCurObjectClassName($this->getParam("class"));
        $this->setStrCurObjectTypeName("TransitionAction");
        return parent::actionNew();
    }

    /**
     * @return string
     * @permissions edit
     */
    public function actionEditTransitionAction()
    {
        $objAction = Objectfactory::getInstance()->getObject($this->getSystemid());
        $this->setCurObjectClassName($objAction->getStrRecordClass());
        $this->setStrCurObjectTypeName("TransitionAction");
        return parent::actionEdit();
    }

    /**
     * @return string
     * @permissions edit
     */
    public function actionSaveTransitionAction()
    {
        $this->setCurObjectClassName($this->getParam("class"));
        $this->setStrCurObjectTypeName("TransitionAction");
        return parent::actionSave();
    }

    /**
     * @return string
     * @permissions edit
     */
    public function actionNewTransitionCondition()
    {
        $this->setCurObjectClassName($this->getParam("class"));
        $this->setStrCurObjectTypeName("TransitionCondition");
        return parent::actionNew();
    }

    /**
     * @return string
     * @permissions edit
     */
    public function actionEditTransitionCondition()
    {
        $objAction = Objectfactory::getInstance()->getObject($this->getSystemid());
        $this->setCurObjectClassName($objAction->getStrRecordClass());
        $this->setStrCurObjectTypeName("TransitionCondition");
        return parent::actionEdit();
    }

    /**
     * @return string
     * @permissions edit
     */
    public function actionSaveTransitionCondition()
    {
        $this->setCurObjectClassName($this->getParam("class"));
        $this->setStrCurObjectTypeName("TransitionCondition");
        return parent::actionSave();
    }

    protected function getActionNameForClass($strAction, $objInstance)
    {
        if ($objInstance instanceof FlowActionAbstract) {
            return $strAction . "TransitionAction";
        } elseif ($objInstance instanceof FlowConditionAbstract) {
            return $strAction . "TransitionCondition";
        } else {
            return parent::getActionNameForClass($strAction, $objInstance);
        }
    }

    /**
     * @param $strSystemId
     * @return FlowConfig|null
     */
    private function getFlowFromSystemId($strSystemId)
    {
        $objFlow = null;
        $objObject = Objectfactory::getInstance()->getObject($strSystemId);
        $arrSystemIds = $objObject->getPathArray();
        foreach ($arrSystemIds as $strSystemId) {
            $objObject = $this->objFactory->getObject($strSystemId);
            if ($objObject instanceof FlowConfig) {
                return $objObject;
            }
        }
        return null;
    }
}
