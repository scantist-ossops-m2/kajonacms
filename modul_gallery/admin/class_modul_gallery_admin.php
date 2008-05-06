<?php
/*"******************************************************************************************************
*   (c) 2004-2006 by MulchProductions, www.mulchprod.de                                                 *
*   (c) 2007-2008 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
* 																										*
* 	class_modul_gallery_admin.php    																	*
* 	Admin-Part of the gallery-module                                                                    *
*																										*
*-------------------------------------------------------------------------------------------------------*
*	$Id$                               *
********************************************************************************************************/

//Base class & interface
include_once(_adminpath_."/class_admin.php");
include_once(_adminpath_."/interface_admin.php");
//needed models
include_once(_systempath_."/class_modul_gallery_gallery.php");
include_once(_systempath_."/class_modul_gallery_pic.php");


/**
 * Admin-Class of the gallery-module. Used to sync the galleries with the filesystem and to define picture-properties
 *
 * @package modul_gallery
 */
class class_modul_gallery_admin extends class_admin implements interface_admin  {

	/**
	 * Construcut
	 *
	 */
	public function __construct() {
		$arrModule["name"] 				= "modul_gallery";
		$arrModule["author"] 			= "sidler@mulchprod.de";
		$arrModule["moduleId"] 			= _bildergalerie_modul_id_;
		$arrModule["table"] 			= _dbprefix_."gallery_gallery";
		$arrModule["table2"]			= _dbprefix_."gallery_pic";
		$arrModule["modul"]				= "gallery";
		//Base class
		parent::__construct($arrModule);
	}

	/**
	 * Action-block. Controlles the further behaviour of the class
	 *
	 * @param string $strAction
	 */
	public function action($strAction = "") {
		$strReturn = "";
        if($strAction == "")
            $strAction = "list";

        try {

    		if($strAction == "list")
    			$strReturn = $this->actionList();

    		//Galeriefunktionen--------------------------------
    		if($strAction == "newGallery")
    			$strReturn .= $this->actionNewGallery();

    		if($strAction == "editGallery")
    			$strReturn .= $this->actionNewGallery("edit");

    		if($strAction == "saveGallery") {
    		    if($this->validateForm()) {
        			$strReturn .= $this->actionSaveGallery();
        			if($strReturn == "")
                        $this->adminReload(_indexpath_."?admin=1&module=".$this->arrModule["modul"]);
        		    }
    		    else {
    		        if($this->getParam("mode") == "new")
    		            $strReturn .= $this->actionNewGallery();
    		        else
    		            $strReturn .= $this->actionNewGallery("edit");
    		    }
    		}

    		if($strAction == "syncGallery")
    			$strReturn .= $this->actionSyncGallery();

    		if($strAction == "massSync")
    			$strReturn .= $this->actionMassSync();

    		if($strAction == "deleteGallery") {
    			$strReturn .= $this->actionDeleteGallery();
    			if($strReturn == "")
                    $this->adminReload(_indexpath_."?admin=1&module=".$this->arrModule["modul"]);
    		}

    		//Bildfunktionen-----------------------------------
    		if($strAction == "showGallery")
    			$strReturn .= $this->actionShowGallery();

    		if($strAction == "editImage") {
    			$strReturn .= $this->actionEditImage();
    			if($strReturn == "")
                    $this->adminReload(_indexpath_."?admin=1&module=".$this->arrModule["modul"]."&action=showGallery&systemid=".$this->getPrevId());
    		}

    		if($strAction == "status") {
                $this->setStatus();
                $this->adminReload(_indexpath_."?admin=1&module=".$this->arrModule["modul"]."&action=showGallery&systemid=".$this->getPrevId());
    		}

    		if($strAction == "sortUp") {
    			$strReturn = $this->actionSort("up");
    			$this->adminReload(_indexpath_."?admin=1&module=".$this->arrModule["modul"]."&action=showGallery&systemid=".$this->getPrevId());
    		}

    		if($strAction == "sortDown") {
    			$strReturn = $this->actionSort("down");
    		    $this->adminReload(_indexpath_."?admin=1&module=".$this->arrModule["modul"]."&action=showGallery&systemid=".$this->getPrevId());
    		}

        }
        catch (class_exception $objException) {
		    $objException->processException();
		    $strReturn = "An internal error occured: ".$objException->getMessage();
		}

		$this->strOutput = $strReturn;
	}

	public function getOutputContent() {
		return $this->strOutput;
	}

	public function getOutputModuleNavi() {
	    $arrReturn = array();
        $arrReturn[] = array("right", getLinkAdmin("right", "change", "&changemodule=".$this->arrModule["modul"],  $this->getText("modul_rechte"), "", "", true, "adminnavi"));
        $arrReturn[] = array("", "");
	    $arrReturn[] = array("view", getLinkAdmin($this->arrModule["modul"], "list", "", $this->getText("modul_liste"), "", "", true, "adminnavi"));
     	$arrReturn[] = array("edit", getLinkAdmin($this->arrModule["modul"], "newGallery", "", $this->getText("galerie_neu"), "", "", true, "adminnavi"));
        $arrReturn[] = array("", "");
     	$arrReturn[] = array("right1", getLinkAdmin($this->arrModule["modul"], "massSync", "", $this->getText("gallery_masssync"), "", "", true, "adminnavi"));
     	return $arrReturn;
    }


	protected function getRequiredFields() {
        $strAction = $this->getAction();
        $arrReturn = array();
        if($strAction == "saveGallery") {
            $arrReturn["gallery_title"] = "string";
            $arrReturn["gallery_path"] = "folder";
        }
        if($strAction == "editImage") {
            $arrReturn["pic_name"] = "string";
        }

        return $arrReturn;
    }

// --- ListenFunktionen ---------------------------------------------------------------------------------


	/**
	 * Creates a list of all available galleries
	 *
	 * @return string
	 */
	private function actionList() {
		$strReturn = "";
		//Pruefen der Modul-Rechte
		if($this->objRights->rightView($this->getModuleSystemid($this->arrModule["modul"]))) {
			//Load galleries
			$arrObjGalleries = class_modul_gallery_gallery::getGalleries();
			$intI = 0;
			//Iterate over all galleries
			foreach($arrObjGalleries as $objOneGallery) {
				//Check specific rights
				if($objOneGallery->rightView()) {
				    $strAction = "";
				    if($objOneGallery->rightView())
			   		    $strAction .= $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "showGallery", "&systemid=".$objOneGallery->getSystemid(), "", $this->getText("galerie_anzeigen"), "icon_folderActionOpen.gif"));
			   		if($objOneGallery->rightRight1())
			   		    $strAction .= $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"],  "syncGallery", "&systemid=".$objOneGallery->getSystemid(), "", $this->getText("galerie_syncro"), "icon_sync.gif"));
			   		if($objOneGallery->rightEdit())
			   		    $strAction .= $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"],  "editGallery", "&systemid=".$objOneGallery->getSystemid(), "", $this->getText("galerie_bearbeiten"), "icon_pencil.gif"));
			   		if($objOneGallery->rightDelete())
			   		    $strAction .= $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "deleteGallery", "&systemid=".$objOneGallery->getSystemid(), "", $this->getText("galerie_loeschen"), "icon_ton.gif"));
			   		if($objOneGallery->rightRight())
		   			    $strAction .= $this->objToolkit->listButton(getLinkAdmin("right", "change", "&systemid=".$objOneGallery->getSystemid(), "", $this->getText("galerie_rechte"), getRightsImageAdminName($objOneGallery->getSystemid())));
			   		$strReturn .= $this->objToolkit->listRow2Image(getImageAdmin("icon_gallery.gif"), $objOneGallery->getStrTitle(), $strAction, $intI++);
				}
			}
			if($this->objRights->rightEdit($this->getModuleSystemid($this->arrModule["modul"])))
			    $strReturn .= $this->objToolkit->listRow2Image("", "", getLinkAdmin($this->arrModule["modul"], "newGallery", "", $this->getText("galerie_neu"), $this->getText("galerie_neu"), "icon_blank.gif"), $intI++);

			if(uniStrlen($strReturn) != 0)
			    $strReturn = $this->objToolkit->listHeader().$strReturn.$this->objToolkit->listFooter();

			if(count($arrObjGalleries) == 0)
				$strReturn .= $this->getText("galerie_liste_leer");
		}
		else
			$strReturn = $this->getText("fehler_recht");

		return $strReturn;
	}


// --- Galeriefunktionen --------------------------------------------------------------------------------

	/**
	 * Creates a form to edit / create a gallery
	 *
	 * @param string $strMode
	 * @return string
	 */
	private function actionNewGallery($strMode = "new") {
		$strReturn = "";
		if($strMode == "new") {
			//right
			if($this->objRights->rightView($this->getModuleSystemid($this->arrModule["modul"]))) {
			    //Build a form
			    $strReturn .= $this->objToolkit->getValidationErrors($this);
			    $strReturn .= $this->objToolkit->formHeader(_indexpath_."?admin=1&module=gallery&action=saveGallery");
                $strReturn .= $this->objToolkit->formInputText("gallery_title", $this->getText("gallery_title"), $this->getParam("gallery_title"));
                $strReturn .= $this->objToolkit->formInputText("gallery_path", $this->getText("gallery_path"), $this->getParam("gallery_path"), "inputText", getLinkAdminPopup("folderview", "folderList", "&form_element=gallery_path&folder=/portal/pics", $this->getText("browser"), $this->getText("browser"), "icon_externalBrowser.gif", 500, 500, "ordneransicht"));
			    $strReturn .= $this->objToolkit->formInputHidden("mode", "new");
			    $strReturn .= $this->objToolkit->formInputHidden("systemid", "0");
			    $strReturn .= $this->objToolkit->formInputSubmit($this->getText("speichern"));
			    $strReturn .= $this->objToolkit->formClose();
			}
			else
				$strReturn = $this->getText("fehler_recht");
		}
		elseif ($strMode == "edit") {
			if($this->objRights->rightEdit($this->getSystemid())) 	{
			    //Load the gallery
			    $objGallery = new class_modul_gallery_gallery($this->getSystemid());
			    //Build a form
			    $strReturn .= $this->objToolkit->getValidationErrors($this);
			    $strReturn .= $this->objToolkit->formHeader(_indexpath_."?admin=1&module=gallery&action=saveGallery");
                $strReturn .= $this->objToolkit->formInputText("gallery_title", $this->getText("gallery_title"), $objGallery->getStrTitle());
                $strReturn .= $this->objToolkit->formInputText("gallery_path", $this->getText("gallery_path"), $objGallery->getStrPath(), "inputText", getLinkAdminPopup("folderview", "folderList", "&form_element=gallery_path&folder=/portal/pics", $this->getText("browser"), $this->getText("browser"), "icon_externalBrowser.gif", 500, 500, "ordneransicht"));
			    $strReturn .= $this->objToolkit->formInputHidden("mode", "edit");
			    $strReturn .= $this->objToolkit->formInputHidden("systemid", $this->getSystemid());
			    $strReturn .= $this->objToolkit->formInputSubmit($this->getText("speichern"));
			    $strReturn .= $this->objToolkit->formClose();
			}
			else
				$strReturn = $this->getText("fehler_recht");
		}
		return $strReturn;
	}

	/**
	 * Saves a new or modified gallery
	 *
	 * @return string "" in case of success
	 */
	private function actionSaveGallery() {
		$strReturn = "";
		//Modus checken
		if($this->getParam("mode") == "new") {
			//Rechte checken
			if($this->objRights->rightView($this->getModuleSystemid($this->arrModule["modul"]))) {
			    $objGallery = new class_modul_gallery_gallery();
			    $objGallery->setStrPath($this->getParam("gallery_path"));
			    $objGallery->setStrTitle($this->getParam("gallery_title"));

			    if(!$objGallery->saveObjectToDb())
			        throw new class_exception("Error saving object to db", class_exception::$level_ERROR);

			}
			else
				$strReturn = $this->getText("fehler_recht");
		}
		elseif ($this->getParam("mode") == "edit") {
			//Right-Check
			if($this->objRights->rightEdit($this->getSystemid())) {
			    $objGallery = new class_modul_gallery_gallery($this->getSystemid());
			    $objGallery->setStrPath($this->getParam("gallery_path"));
			    $objGallery->setStrTitle($this->getParam("gallery_title"));

				if(!$objGallery->updateObjectToDb())
					throw new class_exception("Error updating object to db", class_exception::$level_ERROR);
			}
			else
				$strReturn = $this->getText("fehler_recht");
		}
		return $strReturn;
	}

	/**
	 * Deletes a gallery and all images or shows the warning box
	 *
	 * @return string "" in case of success
	 */
	private function actionDeleteGallery() {
		$strReturn = "";
		//Rechte-Check
		if($this->objRights->rightDelete($this->getSystemid())) {
			//Warning?
			if($this->getParam("loeschen_final") == "") {
			    $objGallery = new class_modul_gallery_gallery($this->getSystemid());
				$strName = $objGallery->getStrTitle();
				$strReturn .= $this->objToolkit->warningBox($strName.$this->getText("galerie_loeschen_frage")."<a href=\""._indexpath_."?admin=1&module=".$this->arrModule["modul"]."&action=deleteGallery&systemid=".$this->getSystemid()."&loeschen_final=1\">".$this->getText("galerie_loeschen_link"));
			}
			else {
				//Invoke the deletion
				if(class_modul_gallery_gallery::deleteGalleryRecursive($this->getSystemid())) {
				    if(!class_modul_gallery_gallery::deleteGallery($this->getSystemid()))
				        throw new class_exception($this->getText("galerie_loeschen_fehler"), class_exception::$level_ERROR);
				}
				else
					throw new class_exception($this->getText("galerie_loeschen_fehler"), class_exception::$level_ERROR);

			}
		}
		else
			$strReturn .= $this->getText("fehler_recht");

		return $strReturn;
	}

// --- Synchronisierungsfunktionen ----------------------------------------------------------------------

	/**
	 * Starts the syncro of a gallery
	 *
	 * @return string
	 */
	private function actionSyncGallery() {
		$strReturn = "";
		if($this->objRights->rightRight1($this->getSystemid())) {
			//Load the gall data
			$objGallery = new class_modul_gallery_gallery($this->getSystemid());
			//Call the recursive fuction
			$arrSyncs = class_modul_gallery_pic::syncRecursive($objGallery->getSystemid(), $objGallery->getStrPath());
			$strReturn = $this->getText("syncro_ende");
			$strReturn .= $this->objToolkit->getTextRow($this->getText("sync_add").$arrSyncs["insert"].$this->getText("sync_del").$arrSyncs["delete"].$this->getText("sync_upd").$arrSyncs["update"]);

			//flush cache
			$this->flushCompletePagesCache();
		}
		else
			$strReturn = $this->getText("fehler_recht");

		return $strReturn;
	}

	/**
	 * Synchronizes all galleries available, if rights given
	 *
	 * @return string
	 */
	private function actionMassSync() {
        $strReturn = "";
		//rights
		if($this->objRights->rightRight1($this->getModuleSystemid($this->arrModule["modul"]))) {

		    //load all galleries
		    $arrGalleries = class_modul_gallery_gallery::getGalleries();
		    $arrSyncs = array( "insert" => 0, "delete" => 0, "update" => 0);
		    foreach($arrGalleries as $objOneGallery) {
		        if($objOneGallery->rightRight1()) {
                    $arrTemp = class_modul_gallery_pic::syncRecursive($objOneGallery->getSystemid(), $objOneGallery->getStrPath());
                    $arrSyncs["insert"] += $arrTemp["insert"];
                    $arrSyncs["delete"] += $arrTemp["delete"];
                    $arrSyncs["update"] += $arrTemp["update"];
		        }
		    }
		    $strReturn = $this->getText("syncro_ende");
			$strReturn .= $this->objToolkit->getTextRow($this->getText("sync_add").$arrSyncs["insert"].$this->getText("sync_del").$arrSyncs["delete"].$this->getText("sync_upd").$arrSyncs["update"]);

			//Flush cache
			$this->flushCompletePagesCache();
		}
		else
			$strReturn = $this->getText("fehler_recht");

		return $strReturn;
	}



// --- Dateifunktionen ----------------------------------------------------------------------------------

	/**
	 * Creates a form to edit an image oder saves the passed data
	 *
	 * @return string "" in case of success
	 */
	private function actionEditImage() {
		$strReturn = "";
		//Rights?
		if($this->objRights->rightEdit($this->getSystemid())) {
		    $bitValidated = true;
		    if($this->getParam("save") != "" && !$this->validateForm()) {
		        $bitValidated = false;
		        $this->setParam("save", "");
		    }

			//mode?
			if($this->getParam("save") == "") {
			    $strImage = "";
				//form
				$objImage = new class_modul_gallery_pic($this->getSystemid());

				//File or folder?
				if(is_file(_realpath_.$objImage->getStrFilename())) {
				    //To create a preview, check if to scale it
					$arrImagesize = getimagesize(_realpath_.$objImage->getStrFilename());
					if($arrImagesize[0] < 300 && $arrImagesize[1] < 300)
						$strImage = "<img src=\""._webpath_.$objImage->getStrFilename()."\" />";
					else {

						$intRelation = $arrImagesize[0] / $arrImagesize[1];	//Breite / Hoehe. >0: hoch <0: quer
						if($intRelation > 0) {
							$intMaxWidth = "300";
							$intMaxHeigt = (int)($intMaxWidth/ $intRelation);
						}
						else {
							$intMaxHeigt = "300";
							$intMaxWidth = (int)($intMaxHeigt * $intRelation);
						}
						$strImage = "<img src=\""._webpath_."/image.php?image=".$objImage->getStrFilename()."&amp;maxWidth=300&amp;maxHeight=300\" />";
					}
				}

				//path-navigation
				$strReturn .= $this->generatePathNavi();
				//Build the form
				if(!$bitValidated)
				    $strReturn .= $this->objToolkit->getValidationErrors($this);
				$strReturn .= $this->objToolkit->formHeader(_indexpath_."?admin=1&module=".$this->arrModule["modul"]."&action=editImage");
                $strReturn .= $this->objToolkit->formInputText("pic_name", $this->getText("pic_name"), $objImage->getStrName());
                $strReturn .= $this->objToolkit->formInputText("pic_subtitle", $this->getText("pic_subtitle"), $objImage->getStrSubtitle());
                $strReturn .= $this->objToolkit->formWysiwygEditor("pic_description", $this->getText("pic_description"), $objImage->getStrDescription(), "minimal");
				$strReturn .= $this->objToolkit->formInputHidden("save", "1");
				$strReturn .= $this->objToolkit->formInputHidden("systemid", $this->getSystemid());
				$strReturn .= $this->objToolkit->formInputSubmit($this->getText("speichern"));
				//additional infos about the image
				if(is_file(_realpath_.$objImage->getStrFilename())) {
				    $strReturn .= $this->objToolkit->formTextRow($this->getText("pic_size").$arrImagesize[0]."x".$arrImagesize[1]);
				    $strReturn .= $this->objToolkit->formTextRow($this->getText("pic_filename").basename($objImage->getStrFilename()));
				    $strReturn .= $this->objToolkit->formTextRow($this->getText("pic_folder").dirname($objImage->getStrFilename()));
				}
				$strReturn .= $this->objToolkit->formTextRow($strImage);
				$strReturn .= $this->objToolkit->formClose();
			}
			elseif ($this->getParam("save") == "1") {
				//Update the opbject
				$objImage = new class_modul_gallery_pic($this->getSystemid());
				$objImage->setStrName($this->getParam("pic_name"));
				$objImage->setStrDescription($this->getParam("pic_description"));
				$objImage->setStrSubtitle($this->getParam("pic_subtitle"));
				if(!$objImage->updateObjectToDb())
				    throw new class_exception($this->getText("bild_speichern_fehler"), class_exception::$level_ERROR);

				//flush cache
				$this->flushCompletePagesCache();
			}
		}
		else
			$strReturn = $this->getText("fehler_recht");
		return $strReturn;
	}


	/**
	 * Shows all files & folders under the current systemid
	 *
	 * @return string
	 */
	private function actionShowGallery() {
		$strReturn = "";
		//rights
		if($this->objRights->rightView($this->getSystemid())) {
		    //path navi
		    $strReturn .= $this->generatePathnavi();
			//Load all files
			$arrFiles = class_modul_gallery_pic::loadFilesDB($this->getSystemid());

			$strListID = generateSystemid();
			$strReturn .= $this->objToolkit->dragableListHeader($strListID);
			//maybe, a link one level up is neede
			$strTemp = $this->getPrevId($this->getSystemid());
			$intI = 0;
			if($strTemp != "0") {
				$strReturn .= $this->objToolkit->listRow3("..", "", $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "showGallery", "&systemid=".$strTemp, "", $this->getText("ordner_hoch"), "icon_folderActionLevelup.gif")), getImageAdmin("icon_folderClosed.gif"), $intI++);
			}

			if(count($arrFiles) > 0) {
				foreach($arrFiles as $objOneFile) {
					$intStatus = $objOneFile->getStatus();
					if($intStatus == 1) 	{
				 		$strStatus = $this->getText("status_active");
				 		$strStatusImage = "icon_enabled.gif";
				 	}
				 	else {
				 		$strStatus = $this->getText("status_inactive");
				 		$strStatusImage = "icon_disabled.gif";
				 	}

				 	//create a icon
				 	if($objOneFile->getIntType() == 0) 	{
				 		$arrTemp = $this->objToolkit->mimeType(basename($objOneFile->getStrFilename()));
				 		$strPic = $arrTemp[2];
				 		$strText = $arrTemp[0];
				 	}
				 	else {
				 		$strPic = "icon_folderClosed.gif";
				 		$strText = "Ordner";
				 	}

				 	//And build the row itself
				 	$strName = $objOneFile->getStrName()." (".basename($objOneFile->getStrFilename()).")";
				 	$strCenter = ($objOneFile->getIntType() == 0 ? bytesToString($objOneFile->getIntSize()) ." - ": "") ;
				 	$strCenter .= ($objOneFile->getIntType() == 0 ? $objOneFile->getIntHits()." Hits": "");
			   		//If folder, a link to open
			   		$strAction = "";
			   		if($objOneFile->getIntType() == 1 && $objOneFile->rightView())
			   			$strAction .= $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "showGallery", "&systemid=".$objOneFile->getSystemid(), "", $this->getText("ordner_oeffnen"), "icon_folderActionOpen.gif"));

			   		if($this->objRights->rightEdit($objOneFile->getSystemid())) {
			   		    if($objOneFile->getIntType() == 1)
			   			    $strAction .= $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "editImage", "&systemid=".$objOneFile->getSystemid(), "", $this->getText("ordner_bearbeiten"), "icon_pencil.gif"));
			   			else
			   			    $strAction .= $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "editImage", "&systemid=".$objOneFile->getSystemid(), "", $this->getText("bild_bearbeiten"), "icon_pencil.gif"));
				   		$strAction .= $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "sortUp", "&systemid=".$objOneFile->getSystemid(), "", $this->getText("sortierung_hoch"), "icon_arrowUp.gif"));
				   		$strAction .= $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "sortDown", "&systemid=".$objOneFile->getSystemid(), "", $this->getText("sortierung_runter"), "icon_arrowDown.gif"));
				   		$strAction .= $this->objToolkit->listButton(getLinkAdmin($this->arrModule["modul"], "status", "&systemid=".$objOneFile->getSystemid(), "", $strStatus, $strStatusImage));
			   		}
			   		if($this->objRights->rightRight($objOneFile->getSystemid()))
			   			$strAction .= $this->objToolkit->listButton(getLinkAdmin("right", "change", "&systemid=".$objOneFile->getSystemid(), "", $this->getText("bild_rechte"), getRightsImageAdminName($objOneFile->getSystemid())));

                    // if no folder, attach a thumbnail-tooltip
                    if ($objOneFile->getIntType() == 1) {
                        $strReturn .= $this->objToolkit->listRow3($strName, $strCenter, $strAction, getImageAdmin($strPic), $intI++, $objOneFile->getSystemid());
                    } else {
    			   		$strImage = "<img src=\\'"._webpath_."/image.php?image=".$objOneFile->getStrFilename()."&maxWidth=100&maxHeight=100\\' />";

    					$strReturn .= $this->objToolkit->listRow3($strName, $strCenter, $strAction, getImageAdmin($strPic, $strImage, true), $intI++, $objOneFile->getSystemid());
                    }
				}
			}
			else
				$strReturn .= $this->objToolkit->listRow2($this->getText("liste_bilder_leer"), "", $intI++);

			$strReturn .= $this->objToolkit->dragableListFooter($strListID);
		}
		else
			$strReturn = $this->getText("fehler_recht");

		return $strReturn;
	}




// --- Sortierung ---------------------------------------------------------------------------------------

	/**
	 * Sorts a record
	 *
	 * @param string $strDirection up || down
	 */
	private function actionSort($strDirection = "up") {
	    if($strDirection == "up")
	       $this->setPosition($this->getSystemid(), "upwards");
	    else
	       $this->setPosition($this->getSystemid(), "downwards");
	}

	/**
	 * Generates a pathnavigation
	 *
	 * @return string
	 */
	private function generatePathNavi() {
	    $arrEntries = array();
	    $arrPathIds = $this->getPathArray();
	    $arrEntries[] = getLinkAdmin($this->arrModule["modul"], "", "", "&nbsp;/&nbsp;");
	    //the first one is the repo itself
	    $objGallery = new class_modul_gallery_gallery(array_shift($arrPathIds));
	    $arrEntries[] = getLinkAdmin($this->arrModule["modul"], "showGallery", "&systemid=".$objGallery->getSystemid(), $objGallery->getStrTitle());
	    foreach ($arrPathIds as $strOneId) {
	        $objPic = new class_modul_gallery_pic($strOneId);
	    	$arrEntries[] = getLinkAdmin($this->arrModule["modul"], "showGallery", "&systemid=".$strOneId, $objPic->getStrName());
	    }
	    //if editing a folder / image, remove the last one
	    if($this->getAction() == "editImage")
	       array_pop($arrEntries);

	    return $this->objToolkit->getPathNavigation($arrEntries);
	}


} //class_modul_gallery_admin

?>