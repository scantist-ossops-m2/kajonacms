<?php
/*"******************************************************************************************************
*   (c) 2004-2006 by MulchProductions, www.mulchprod.de                                                 *
*   (c) 2007 by Kajona, www.kajona.de                                                                   *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
* 																										*
* 	class_element_news.php																				*
* 	Admin-class of the news element																		*
*																										*
*-------------------------------------------------------------------------------------------------------*
*	$Id$                                       *
********************************************************************************************************/
//Base-Class
include_once(_adminpath_."/class_element_admin.php");
//Interface
include_once(_adminpath_."/interface_admin_element.php");

include_once(_systempath_."/class_modul_news_category.php");

/**
 * Class representing the admin-part of the news element
 *
 * @package modul_news
 *
 */
class class_element_news extends class_element_admin implements interface_admin_element {

	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		$arrModule["name"] 			= "element_news";
		$arrModule["author"] 		= "sidler@mulchprod.de";
		$arrModule["moduleId"] 		= _pages_elemente_modul_id_;
		$arrModule["table"] 		= _dbprefix_."element_news";
		$arrModule["modul"]			= "elemente";

		$arrModule["tableColumns"]     = "news_category|char,news_view|number,news_mode|number,news_detailspage|char,news_template|char";

		parent::__construct($arrModule);
	}

    /**
	 * Returns a form to edit the element-data
	 *
	 * @param mixed $arrElementData
	 * @return string
	 */
	public function getEditForm($arrElementData)	{
		$strReturn = "";
		//Load all newscats available
        $arrRawCats = class_modul_news_category::getCategories();
        $arrCats = array();
        foreach ($arrRawCats as $objOneCat)
            $arrCats[$objOneCat->getSystemid()] = $objOneCat->getStrTitle();

		//Build the form
		$strReturn .= $this->objToolkit->formInputDropdown("news_category", $arrCats, $this->getText("news_category"), (isset($arrElementData["news_category"]) ? $arrElementData["news_category"] : "" ));
		$strReturn .= $this->objToolkit->formInputPageSelector("news_detailspage", $this->getText("news_detailspage"), (isset($arrElementData["news_detailspage"]) ? $arrElementData["news_detailspage"] : ""));

		$arrView = array( 0 => $this->getText("news_view_list"),
					      1 => $this->getText("news_view_detail"));
        $strReturn .= $this->objToolkit->formInputDropdown("news_view", $arrView, $this->getText("news_view"), (isset($arrElementData["news_view"]) ? $arrElementData["news_view"] : "" ));

        $arrMode = array( 1 => $this->getText("news_mode_archive"),
					      0 => $this->getText("news_mode_normal"));
        $strReturn .= $this->objToolkit->formInputDropdown("news_mode", $arrMode, $this->getText("news_mode"), (isset($arrElementData["news_mode"]) ? $arrElementData["news_mode"] : "" ));
		//Load the available templates
		include_once(_systempath_."/class_filesystem.php");
		$objFilesystem = new class_filesystem();
		$arrTemplates = $objFilesystem->getFilelist("/templates/portal/modul_news", ".tpl");
		$arrTemplatesDD = array();
		if(count($arrTemplates) > 0) {
			foreach($arrTemplates as $strTemplate) {
				$arrTemplatesDD[$strTemplate] = $strTemplate;
			}
		}
		$strReturn .= $this->objToolkit->formInputDropdown("news_template", $arrTemplatesDD, $this->getText("news_template"), (isset($arrElementData["news_template"]) ? $arrElementData["news_template"] : "" ));
        //and finally offer the different modes
		return $strReturn;
	}


} //class_element_absatz.php
?>