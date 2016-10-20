<?php
/*"******************************************************************************************************
*   (c) 2004-2006 by MulchProductions, www.mulchprod.de                                                 *
*   (c) 2007-2016 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
********************************************************************************************************/

namespace Kajona\Navigation\System;

use Kajona\Pages\Portal\ElementPortal;
use Kajona\Pages\System\PagesFolder;
use Kajona\Pages\System\PagesPage;
use Kajona\Pages\System\PagesPageelement;
use Kajona\System\System\AdminListableInterface;
use Kajona\System\System\Classloader;
use Kajona\System\System\LanguagesLanguage;
use Kajona\System\System\Model;
use Kajona\System\System\ModelInterface;
use Kajona\System\System\Objectfactory;
use Kajona\System\System\OrmObjectlist;
use Kajona\System\System\OrmObjectlistRestriction;
use Kajona\System\System\Resourceloader;
use Kajona\System\System\StringUtil;

/**
 * Model for a navigation point itself
 *
 * @package module_navigation
 * @author sidler@mulchprod.de
 * @targetTable navigation.navigation_id
 * @sortManager Kajona\System\System\CommonSortmanager
 *
 * @module navigation
 * @moduleId _navigation_modul_id_
 */
class NavigationPoint extends Model implements ModelInterface, AdminListableInterface
{

    /**
     * @var string
     * @tableColumn navigation.navigation_name
     * @tableColumnDatatype char254
     * @fieldMandatory
     * @fieldType Kajona\System\Admin\Formentries\FormentryText
     * @fieldLabel commons_name
     *
     * @addSearchIndex
     */
    private $strName = "";

    /**
     * @var string
     * @tableColumn navigation.navigation_page_e
     * @tableColumnDatatype char254
     * @fieldType objecttags
     * @fieldLabel navigation_page_e
     *
     * @addSearchIndex
     */
    private $strPageE = "";

    /**
     * @var string
     * @tableColumn navigation.navigation_page_i
     * @tableColumnDatatype char254
     * @fieldType page
     * @fieldLabel navigation_page_i
     *
     * @addSearchIndex
     */
    private $strPageI = "";

    /**
     * @var string
     * @tableColumn navigation.navigation_folder_i
     * @tableColumnDatatype char20
     * @addSearchIndex
     */
    private $strFolderI = "";

    /**
     * @var string
     * @tableColumn navigation.navigation_target
     * @tableColumnDatatype char254
     * @fieldType Kajona\System\Admin\Formentries\FormentryDropdown
     * @fieldDDValues [_self => navigation_tagetself],[_blank => navigation_tagetblank]
     * @fieldLabel navigation_target
     */
    private $strTarget = "";

    /**
     * @var string
     * @tableColumn navigation.navigation_image
     * @tableColumnDatatype char254
     * @fieldType Kajona\System\Admin\Formentries\FormentryImage
     * @fieldLabel commons_image
     */
    private $strImage = "";

    /**
     * Internal field, used for navigation nodes added by other modules
     *
     * @var string
     */
    private $strLinkAction = "";

    /**
     * Internal field, used for navigation nodes added by other modules
     *
     * @var string
     */
    private $strLinkSystemid = "";

    /**
     * Indicates if the node is generated by either a real navigation-tree / a page-tree or
     * by a foreign node injecting new nodes into the tree.
     *
     * @var bool
     */
    private $bitIsForeignNode = false;

    private $bitIsPagealias = false;


    /**
     * Returns the name to be used when rendering the current object, e.g. in admin-lists.
     *
     * @return string
     */
    public function getStrDisplayName()
    {
        return $this->getStrName();
    }

    /**
     * Returns the icon the be used in lists.
     * Please be aware, that only the filename should be returned, the wrapping by getImageAdmin() is
     * done afterwards.
     *
     * @return string the name of the icon, not yet wrapped by getImageAdmin()
     */
    public function getStrIcon()
    {
        return "icon_treeLeaf";
    }

    /**
     * In nearly all cases, the additional info is rendered left to the action-icons.
     *
     * @return string
     */
    public function getStrAdditionalInfo()
    {
        $strNameInternal = $this->getStrPageI();
        $strNameExternal = $this->getStrPageE();
        $strNameFolder = "";
        if (validateSystemid($this->getStrFolderI())) {
            $objFolder = new PagesFolder($this->getStrFolderI());
            $strNameFolder = $objFolder->getStrName();
        }

        return $strNameInternal.$strNameExternal.$strNameFolder;
    }

    /**
     * If not empty, the returned string is rendered below the common title.
     *
     * @return string
     */
    public function getStrLongDescription()
    {
        return "";
    }


    /**
     * Loads all navigation points one layer under the given systemid
     *
     * @param string $strSystemid
     * @param bool $bitJustActive
     * @param null $intStart
     * @param null $intEnd
     *
     * @internal param $bool
     * @return NavigationPoint[]
     * @static
     */
    public static function getNaviLayer($strSystemid, $bitJustActive = false, $intStart = null, $intEnd = null)
    {
        $objOrm = new OrmObjectlist();
        if ($bitJustActive) {
            $objOrm->addWhereRestriction(new OrmObjectlistRestriction(" AND system_status = 1 ", array()));
        }
        return $objOrm->getObjectList(get_called_class(), $strSystemid, $intStart, $intEnd);
    }


    /**
     * Generates a navigation layer for the portal.
     * Either based on the "real" navigation as maintained in module navigation
     * or generated out of the linked pages-folders.
     * If theres a link to a folder, the first page/folder within the folder is
     * linked to the current point.
     *
     * @param string $strSystemid
     *
     * @return NavigationPoint
     */
    public static function getDynamicNaviLayer($strSystemid)
    {

        $arrReturn = array();

        //split modes  - regular navigation or generated out of the pages / folders

        /** @var $objNode NavigationPoint|NavigationTree */
        $objNode = Objectfactory::getInstance()->getObject($strSystemid);

        //current node is a navigation-node
        if ($objNode instanceof NavigationPoint || $objNode instanceof NavigationTree) {

            //check where the point links to - navigation-point or pages-entry
            if ($objNode instanceof NavigationTree && validateSystemid($objNode->getStrFolderId())) {
                $arrReturn = self::loadPageLevelToNavigationNodes($objNode->getStrFolderId());
            }
            else {
                $arrReturn = self::getNaviLayer($strSystemid, true);
            }
        }
        //current node belongs to pages
        else if ($objNode instanceof PagesPage || $objNode instanceof PagesFolder) {
            //load the page-level below
            $arrReturn = self::loadPageLevelToNavigationNodes($strSystemid);
        }


        return $arrReturn;
    }


    /**
     * Loads all navigation-points linking on the passed page
     *
     * @param string $strPagename
     *
     * @static
     * @return NavigationPoint[]
     */
    public static function loadPagePoint($strPagename)
    {
        $objOrm = new OrmObjectlist();
        $objOrm->addWhereRestriction(new OrmObjectlistRestriction(" AND system_status = 1 ", array()));
        $objOrm->addWhereRestriction(new OrmObjectlistRestriction(" AND navigation_page_i = ? ", array($strPagename)));
        return $objOrm->getObjectList(get_called_class());
    }


    /**
     * Loads the level of pages and/or folders stored under a single systemid.
     * Transforms a page- or a folder-node into a navigation-node.
     * This node is used for portal-actions only, so there's no way to edit the node.
     *
     * @param string $strSourceId
     *
     * @return NavigationPoint[]|array
     * @since 3.4
     */
    private static function loadPageLevelToNavigationNodes($strSourceId)
    {

        $arrPages = PagesPage::getObjectListFiltered(null, $strSourceId);
        $arrReturn = array();

        //transform the sublevel
        foreach ($arrPages as $objOneEntry) {
            //validate status
            if ($objOneEntry->getIntRecordStatus() == 0 || !$objOneEntry->rightView()) {
                continue;
            }

            $objLanguage = new LanguagesLanguage();

            if ($objOneEntry instanceof PagesPage) {

                //validate if the page to be linked has a template assigned and at least a single element created
                $arrElementsOnPage = PagesPageelement::getPlainElementsOnPage($objOneEntry->getSystemid(), true, $objLanguage->getStrPortalLanguage(), true);
                $arrElementsOnPage = array_filter($arrElementsOnPage, function($arrRow) {
                    return $arrRow["page_element_ph_placeholder"] != "blocks" && !StringUtil::startsWith($arrRow["page_element_ph_placeholder"], "master");
                });
                if ($objOneEntry->getIntType() == PagesPage::$INT_TYPE_ALIAS
                    || ($objOneEntry->getStrTemplate() != "" && count($arrElementsOnPage) > 0)
                ) {

                    $objPoint = new NavigationPoint();
                    $objPoint->setStrName($objOneEntry->getStrBrowsername() != "" ? $objOneEntry->getStrBrowsername() : $objOneEntry->getStrName());
                    $objPoint->setIntRecordStatus(1);

                    //if in alias mode, then check what type of target is requested
                    if ($objOneEntry->getIntType() == PagesPage::$INT_TYPE_ALIAS) {
                        $strAlias = StringUtil::toLowerCase($objOneEntry->getStrAlias());
                        if (StringUtil::indexOf($strAlias, "http") !== false) {
                            $objPoint->setStrPageE($objOneEntry->getStrAlias());
                        }
                        else {
                            $objPoint->setStrPageI($objOneEntry->getStrAlias());
                        }

                        $objPoint->setStrTarget($objOneEntry->getStrTarget());
                    }
                    else {
                        $objPoint->setStrPageI($objOneEntry->getStrName());
                    }

                    $objPoint->setSystemid($objOneEntry->getSystemid());

                    $arrReturn[] = $objPoint;
                }
            }
        }

        //merge with elements on the page - if given
        /** @var $objInstance PagesPage */
        $objInstance = Objectfactory::getInstance()->getObject($strSourceId);
        if ($objInstance instanceof PagesPage) {

            if ($objInstance->getIntType() != PagesPage::$INT_TYPE_ALIAS) {
                $arrReturn = array_merge($arrReturn, self::getAdditionalEntriesForPage($objInstance));
            }

        }

        return $arrReturn;
    }


    /**
     * Triggers all subelements in order to fetch the additional navigation
     * entries.
     *
     * @param PagesPage $objPage
     *
     * @see ElementPortal::getNavigationEntries()
     * @return NavigationPoint[]|array
     * @since 4.0
     */
    private static function getAdditionalEntriesForPage(PagesPage $objPage)
    {
        $arrReturn = array();
        $objLanguage = new LanguagesLanguage();
        $arrPlainElements = PagesPageelement::getPlainElementsOnPage($objPage->getSystemid(), true, $objLanguage->getStrPortalLanguage());

        $strOldPageName = $objPage->getParam("page");

        foreach ($arrPlainElements as $arrOneElementOnPage) {
            //Build the class-name for the object

            $strFilename = Resourceloader::getInstance()->getPathForFile("/portal/elements/".$arrOneElementOnPage["element_class_portal"]);
            $objInstance = Classloader::getInstance()->getInstanceFromFilename($strFilename, "Kajona\\Pages\\Portal\\ElementPortal", null, array(new PagesPageelement($arrOneElementOnPage["system_id"])), true);

            if ($objInstance::providesNavigationEntries()) {

                /** @var  ElementPortal $objInstance */
                $objInstance->setParam("page", $objPage->getStrName());

                $arrNavigationPoints = $objInstance->getNavigationEntries();
                if ($arrNavigationPoints !== false) {
                    $arrReturn = array_merge($arrReturn, $arrNavigationPoints);
                }
            }

        }

        $objPage->setParam("page", $strOldPageName);

        return $arrReturn;
    }


    /**
     * @return string
     */
    public function getStrName()
    {
        return $this->strName;
    }

    /**
     * @return string
     */
    public function getStrPageI()
    {
        return StringUtil::toLowerCase($this->strPageI);
    }

    /**
     * @return string
     */
    public function getStrPageE()
    {
        return $this->strPageE;
    }

    /**
     * @return string
     */
    public function getStrTarget()
    {
        return $this->strTarget != "" ? $this->strTarget : "_self";
    }

    /**
     * @return string
     */
    public function getStrImage()
    {
        return $this->strImage;
    }

    /**
     * @param string $strName
     *
     * @return void
     */
    public function setStrName($strName)
    {
        $this->strName = $strName;
    }

    /**
     * @param string $strPageE
     *
     * @return void
     */
    public function setStrPageE($strPageE)
    {
        $this->strPageE = $strPageE;
    }

    /**
     * @param string $strPageI
     *
     * @return void
     */
    public function setStrPageI($strPageI)
    {
        $this->strPageI = $strPageI;
    }

    /**
     * @param string $strTarget
     *
     * @return void
     */
    public function setStrTarget($strTarget)
    {
        $this->strTarget = $strTarget;
    }

    /**
     * @param string $strImage
     *
     * @return void
     */
    public function setStrImage($strImage)
    {
        $strImage = StringUtil::replace(_webpath_, "", $strImage);
        $this->strImage = $strImage;
    }

    /**
     * @return string
     * @return void
     */
    public function getStrFolderI()
    {
        return $this->strFolderI;
    }

    /**
     * @param string $strFolderI
     *
     * @return void
     */
    public function setStrFolderI($strFolderI)
    {
        $this->strFolderI = $strFolderI;
    }

    /**
     * @param string $strLinkAction
     *
     * @return void
     */
    public function setStrLinkAction($strLinkAction)
    {
        $this->strLinkAction = $strLinkAction;
    }

    /**
     * @return string
     */
    public function getStrLinkAction()
    {
        return $this->strLinkAction;
    }

    /**
     * @param string $strLinkSystemid
     *
     * @return void
     */
    public function setStrLinkSystemid($strLinkSystemid)
    {
        $this->strLinkSystemid = $strLinkSystemid;
    }

    /**
     * @return string
     */
    public function getStrLinkSystemid()
    {
        return $this->strLinkSystemid;
    }

    /**
     * @param boolean $bitIsForeignNode
     *
     * @return void
     */
    public function setBitIsForeignNode($bitIsForeignNode)
    {
        $this->bitIsForeignNode = $bitIsForeignNode;
    }

    /**
     * @return boolean
     */
    public function getBitIsForeignNode()
    {
        return $this->bitIsForeignNode;
    }

    /**
     * @param string $bitIsPagenode
     *
     * @return void
     */
    public function setBitIsPagealias($bitIsPagenode)
    {
        $this->bitIsPagealias = $bitIsPagenode;
    }

    /**
     * @return bool
     */
    public function getBitIsPagealias()
    {
        return $this->bitIsPagealias;
    }


}
