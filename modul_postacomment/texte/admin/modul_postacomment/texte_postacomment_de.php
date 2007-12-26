<?php
/*"******************************************************************************************************
*   (c) 2004-2006 by MulchProductions, www.mulchprod.de                                                 *
*   (c) 2007-2008 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
* 																										*
* 	texte_postacomment_de.php																			*
* 	Admin language file for module_postacomment															*
*																										*
*-------------------------------------------------------------------------------------------------------*
*	$Id$                                   *
********************************************************************************************************/

// --- Module texts -------------------------------------------------------------------------------------
$text["modul_titel"]                = "Kommentare";
$text["modul_rechte"]				= "Modul-Rechte";
$text["module_list"]				= "Liste";

$text["liste_leer"]                 = "Keine Posts vorhanden";

$text["permissions_header"]         = array(
            							0 => "Anzeigen",
            							1 => "Bearbeiten",
            							2 => "Löschen",
            							3 => "Rechte",
            							4 => "Kommentieren",	 //Recht1
            							5 => "",                 //Recht2
            							6 => "",
            							7 => "",
            							8 => ""
            							);

$text["status_active"]              = "Status ändern (ist aktiv)";
$text["status_inactive"]            = "Status ändern (ist inaktiv)";

$text["postacomment_edit"]          = "Kommentar bearbeiten";
$text["postacomment_delete"]        = "Kommentar löschen";
$text["postacomment_rights"]        = "Rechte bearbeiten";

$text["postacomment_delete_question"] = " | Kommentar wirklich löschen?";
$text["postacomment_delete_link"]   = "Löschen";

$text["postacomment_username"]      = "Name:";
$text["postacomment_title"]         = "Titel:";
$text["postacomment_comment"]       = "Kommentar:";

$text["required_postacomment_username"] = "Name";
$text["required_postacomment_comment"]  = "Kommentar";

$text["submit"]                     = "Speichern";

$text["postacomment_filter"]        = "Seiten-Filter:";
$text["postacomment_dofilter"]      = "Filtern";

// --- Quickhelp texts ----------------------------------------------------------------------------------

$text["quickhelp_list"]             = "Sämtliche Kommentare, die von Benutzern über das Portal abgegeben wurden, sind in dieser
									   Liste sichtbar. <br />Eine Zeile hat dabei den Aufbau <br/><br/>
									   Seitenname  (Sprache) | Datum <br/>
								       Benutzername | Betreff <br />
								       Nachricht <br/><br />
									   Über den Filter am Kopf der Liste kann nach Posts einer einzelnen Seite gefiltert werden.
								       Lange Nachrichten werden in der Liste abgeschnitten, um den kompletten Text zu lesen sollte einfach die
									   Aktion Bearbeiten gewählt werden.";

$text["quickhelp_editPost"]        = "In dieser Ansicht können die Daten eines Kommentares verändert werden.";

?>