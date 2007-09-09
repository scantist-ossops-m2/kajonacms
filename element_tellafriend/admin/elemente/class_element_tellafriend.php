<?php
/*"******************************************************************************************************
*   (c) 2004-2006 by MulchProductions, www.mulchprod.de                                                 *
*   (c) 2007 by Kajona, www.kajona.de                                                                   *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
* 																										*
* 	class_element_tellafriend.php																		*
* 	Admin-class of the tellafriend-element															    *
*																										*
*-------------------------------------------------------------------------------------------------------*
*	$Id$                              *
********************************************************************************************************/

//Base-Class
include_once(_adminpath_."/class_element_admin.php");
//Interface
include_once(_adminpath_."/interface_admin_element.php");

/**
 * Class to handle the admin-stuff of the tellafriend-element
 *
 * @package  modul_pages
 */
class class_element_tellafriend extends class_element_admin implements interface_admin_element {

	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		$arrModul["name"] 			= "element_tellafriend";
		$arrModul["author"] 		= "sidler@mulchprod.de";
		$arrModul["moduleId"] 		= _pages_elemente_modul_id_;
		$arrModul["table"] 		    = _dbprefix_."element_tellafriend";
		$arrModul["modul"]			= "elemente";
		$arrModul["tableColumns"]   = "tellafriend_template|char,tellafriend_error|char,tellafriend_success|char";

		parent::__construct($arrModul);
	}

   /**
	 * Returns a form to edit the element-data
	 *
	 * @param mixed $arrElementData
	 * @return string
	 */
	public function getEditForm($arrElementData) {
		$strReturn = "";

		//Build the form
		//Load the available templates
		include_once(_systempath_."/class_filesystem.php");
		$objFilesystem = new class_filesystem();
		$arrTemplates = $objFilesystem->getFilelist("/templates/portal/element_tellafriend", ".tpl");
		$arrTemplatesDD = array();
		if(count($arrTemplates) > 0) {
			foreach($arrTemplates as $strTemplate) {
				$arrTemplatesDD[$strTemplate] = $strTemplate;
			}
		}
		$strReturn .= $this->objToolkit->formInputDropdown("tellafriend_template", $arrTemplatesDD, $this->getText("tellafriend_template"), (isset($arrElementData["tellafriend_template"]) ? $arrElementData["tellafriend_template"] : "" ));
		$strReturn .= $this->objToolkit->formInputPageSelector("tellafriend_error", $this->getText("tellafriend_error"), (isset($arrElementData["tellafriend_error"]) ? $arrElementData["tellafriend_error"] : ""));
		$strReturn .= $this->objToolkit->formInputPageSelector("tellafriend_success", $this->getText("tellafriend_success"), (isset($arrElementData["tellafriend_success"]) ? $arrElementData["tellafriend_success"] : ""));

		return $strReturn;
	}


}
?>