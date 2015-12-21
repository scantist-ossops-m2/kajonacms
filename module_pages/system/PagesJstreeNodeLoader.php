<?php
/*"******************************************************************************************************
*   (c) 2004-2006 by MulchProductions, www.mulchprod.de                                                 *
*   (c) 2007-2015 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
*	$Id$                                   *
********************************************************************************************************/

namespace Kajona\Pages\System;

use Kajona\System\System\InterfaceJStreeNodeLoader;
use class_carrier;
use class_objectfactory;
use class_adminskin_helper;
use class_link;

/**
 * @author stefan.meyer1@yahoo.de
 *
 * @module pages
 * @moduleId _pages_modul_id_
 */
class PagesJstreeNodeLoader implements InterfaceJStreeNodeLoader
{

    const NODE_TYPE_PAGE = "page";
    const NODE_TYPE_FOLDER = "folder";

    private $objToolkit = null;

    /**
     * class_module_prozessverwaltung_processnode constructor.
     */
    public function __construct()
    {
        $this->objToolkit = class_carrier::getInstance()->getObjToolkit("admin");
    }

    public function getChildNodes($strSystemId) {
        $arrNodes = array();

        //1. Get Page
        /** @var PagesPage $objSingleProcess */
        $objSingleProcess = class_objectfactory::getInstance()->getObject($strSystemId);

        //2. Handle Children
        $arrChildrenProcesse = $this->getChildrenObjects($objSingleProcess);

        //3. Prozesse Childs
        foreach($arrChildrenProcesse as $objSubProcess) {
            $arrNodes[] = $this->getNode($objSubProcess->getStrSystemid());
        }


        return $arrNodes;
    }


    private function getNodeFolder(PagesFolder $objSingleEntry) {
        $strLink = "";
        if ($objSingleEntry->rightEdit()) {
            $strLink = class_link::getLinkAdminHref("pages", "list", "systemid=".$objSingleEntry->getSystemid(), false);
        }

        $arrNode = array(
            "id" => $objSingleEntry->getSystemid(),
            "text" => class_adminskin_helper::getAdminImage($objSingleEntry->getStrIcon())."&nbsp;".$objSingleEntry->getStrDisplayName(),
            "a_attr"  => array(
                "href"     => $strLink,
            ),
            "type" => "folder",
            "children" => count($this->getChildrenObjects($objSingleEntry)) > 0
        );

        return $arrNode;
    }

    private function getNodePage(PagesPage $objSingleEntry) {

        $strTargetId = $objSingleEntry->getSystemid();
        if ($objSingleEntry->getIntType() == PagesPage::$INT_TYPE_ALIAS && PagesPage::getPageByName($objSingleEntry->getStrAlias()) != null) {
            $strTargetId = PagesPage::getPageByName($objSingleEntry->getStrAlias())->getSystemid();
        }

        $strLink = "";
        if ($objSingleEntry->getIntType() == PagesPage::$INT_TYPE_ALIAS && class_objectfactory::getInstance()->getObject($strTargetId)->rightEdit()) {
            $strLink = class_link::getLinkAdminHref("pages_content", "list", "systemid=".$strTargetId, false);
        }
        else if ($objSingleEntry->getIntType() == PagesPage::$INT_TYPE_PAGE && $objSingleEntry->rightEdit()) {
            $strLink = class_link::getLinkAdminHref("pages_content", "list", "systemid=".$objSingleEntry->getSystemid(), false);
        }

        $arrNode = array(
            "id" => $objSingleEntry->getSystemid(),
            "text" => class_adminskin_helper::getAdminImage($objSingleEntry->getStrIcon())."&nbsp;".$objSingleEntry->getStrDisplayName(),
            "a_attr"  => array(
                "href"     => $strLink,
            ),
            "type" => "page",
            "children" => count($this->getChildrenObjects($objSingleEntry)) > 0
        );

        return $arrNode;
    }

    public function getNode($strSystemId) {

        //1. Get Process
        /** @var PagesPage $objSinglePage */
        $objSingleEntry = class_objectfactory::getInstance()->getObject($strSystemId);

        if ($objSingleEntry instanceof PagesFolder) {
            return $this->getNodeFolder($objSingleEntry);
        }
        if ($objSingleEntry instanceof PagesPage) {
            return $this->getNodePage($objSingleEntry);
        }

        return null;
    }

    private function getChildrenObjects($objPage) {
        //Handle Children
        $arrPages = PagesFolder::getPagesAndFolderList($objPage->getSystemid());
        $arrPages = array_values(array_filter($arrPages, function($objPage) {return $objPage->rightView();}));
        return $arrPages;
    }
}
