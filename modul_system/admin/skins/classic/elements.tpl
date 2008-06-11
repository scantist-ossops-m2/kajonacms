/*"******************************************************************************************************
*   (c) 2004-2006 by MulchProductions, www.mulchprod.de                                                 *
*   (c) 2007-2008 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
* 																										*
* 	elements.tpl																						*
* 	Elements-File for the kajona-classic skin															*
*																										*
*-------------------------------------------------------------------------------------------------------*
*	$Id$											    *
********************************************************************************************************/

This skin-file is used for the Kajona admin classic skin and can be used as a sample file to create
your own cool skin. Just modify the sections you'd like to. Don't forget the css file and the basic
templates!



---------------------------------------------------------------------------------------------------------
-- LIST ELEMENTS ----------------------------------------------------------------------------------------

Optional Element to start a list
<list_header>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
</list_header>

Header to use when creating drag n dropable lists. places an id an loads the needed js-scripts in the
background using the ajaxHelper.
Loads the yui-script-helper and adds the table to the drag-n-dropable tables getting parsed later
<dragable_list_header>
<script type="text/javascript">
	kajonaAjaxHelper.loadDragNDropBase();
	if(arrayTableIds == null)
        var arrayTableIds = new Array("%%listid%%");
    else
        arrayTableIds[(arrayTableIds.length +0)] = "%%listid%%";

    kajonaAjaxHelper.addFileToLoad("admin/scripts/dragdrophelper_tr.js");
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="%%listid%%" class="dragList">
</dragable_list_header>

Optional Element to close a list
<list_footer>
</table>
</list_footer>

<dragable_list_footer>
</table>
</dragable_list_footer>

Row in a list containing 2 Elements, NO leading picture
Part 1 - every 2nd entry
<list_row_2_1>
	<tr id="%%listitemid%%" class="adminListRow1" onmouseover="this.className='adminListRow1Over'" onmouseout="this.className='adminListRow1'">
		<td width="21%"><img src="_skinwebpath_/trans.gif" width="3" height="5">%%title%%</td>
	    <td width="79%" align="right">%%actions%%</td>
	 </tr>
</list_row_2_1>
Part 2 - every 2nd entry. Useful if different css-classes are used every single row
<list_row_2_2>
	<tr id="%%listitemid%%" class="adminListRow2" onmouseover="this.className='adminListRow2Over'" onmouseout="this.className='adminListRow2'">
		<td width="21%"><img src="_skinwebpath_/trans.gif" width="3" height="5">%%title%%</td>
	    <td width="79%" align="right">%%actions%%</td>
	 </tr>
</list_row_2_2>

Row in a list containing 2 Elements and a leading picture
Part 1 - every 2nd entry
<list_row_2image_1>
	<tr id="%%listitemid%%" class="adminListRow1" onmouseover="this.className='adminListRow1Over'" onmouseout="this.className='adminListRow1'">
		<td>%%image%%</td>
		<td width="50%"><img src="_skinwebpath_/trans.gif" width="3" height="5">%%title%%</td>
	    <td width="50%" align="right">%%actions%%</td>
	 </tr>
</list_row_2image_1>

Part 2 - every 2nd entry. Usefull if different css-classes are used every single row
<list_row_2image_2>
	<tr id="%%listitemid%%" class="adminListRow2" onmouseover="this.className='adminListRow2Over'" onmouseout="this.className='adminListRow2'">
		<td>%%image%%</td>
		<td width="50%"><img src="_skinwebpath_/trans.gif" width="3" height="5">%%title%%</td>
	    <td width="50%" align="right">%%actions%%</td>
	 </tr>
</list_row_2image_2>

Row in a list containing 2 Elements, NO leading picture, 2nd variation
Used rather for info-lists than for edit-lists, e.g. the systeminfos
Part 1 - every 2nd entry
<list_row_2_1_b>
	<tr id="%%listitemid%%" class="adminListRow1" onmouseover="this.className='adminListRow1Over'" onmouseout="this.className='adminListRow1'">
		<td width="21%"><img src="_skinwebpath_/trans.gif" width="3" height="5">%%title%%</td>
	    <td width="79%" align="left">%%actions%%</td>
	 </tr>
</list_row_2_1_b>

Part 2 - every 2nd entry. Usefull if different css-classes are used every single row
<list_row_2_2_b>
	<tr id="%%listitemid%%" class="adminListRow2" onmouseover="this.className='adminListRow2Over'" onmouseout="this.className='adminListRow2'">
		<td width="21%"><img src="_skinwebpath_/trans.gif" width="3" height="5">%%title%%</td>
	    <td width="79%" align="left">%%actions%%</td>
	 </tr>
</list_row_2_2_b>

Row in a list containing 3 Elements AND A LEADING IMAGE
Part 1 - every 2nd entry
<list_row_3_1>
	<tr id="%%listitemid%%" class="adminListRow1" onmouseover="this.className='adminListRow1Over'" onmouseout="this.className='adminListRow1'">
		<td>%%image%%</td>
		<td width="41%"><img src="_skinwebpath_/trans.gif" width="3" height="5">%%title%%</td>
	    <td width="40%" align="left">%%center%%</td>
	    <td width="19%" align="right">%%actions%%</td>
	 </tr>
</list_row_3_1>

Part 2 - every 2nd entry. Usefull if different css-classes are used every single row
<list_row_3_2>
	<tr id="%%listitemid%%" class="adminListRow2" onmouseover="this.className='adminListRow2Over'" onmouseout="this.className='adminListRow2'">
		<td>%%image%%</td>
		<td width="41%"><img src="_skinwebpath_/trans.gif" width="3" height="5">%%title%%</td>
	    <td width="40%" align="left">%%center%%</td>
	    <td width="19%" align="right">%%actions%%</td>
	 </tr>
</list_row_3_2>

Divider to split up a page in logical sections
<divider><br />
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
  		<td class="%%class%%">&nbsp;</td>
	</tr>
</table>
</divider>

data list header. Used to open a table to print data
<datalist_header>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
</datalist_header>

data list footer. at the bottom of the datatable
<datalist_footer>
</table>
</datalist_footer>

One Column in a row (header record) - the header, the content, the footer
<datalist_column_head_header>
	<tr class="adminListRow1">
</datalist_column_head_header>

<datalist_column_head>
		<td><strong>%%value%%</strong></td>
</datalist_column_head>

<datalist_column_head_footer>
	</tr>
</datalist_column_head_footer>

One Column in a row (data record) - the header, the content, the footer, providing the option of two styles
<datalist_column_header_1>
	<tr class="adminListRow1" onmouseover="this.className='adminListRow1Over'" onmouseout="this.className='adminListRow1'">
</datalist_column_header_1>

<datalist_column_1>
		<td align="left" valign="top">%%value%%</td>
</datalist_column_1>

<datalist_column_footer_1>
	</tr>
</datalist_column_footer_1>

<datalist_column_header_2>
	<tr class="adminListRow2" valign="top" onmouseover="this.className='adminListRow2Over'" onmouseout="this.className='adminListRow2'">
</datalist_column_header_2>

<datalist_column_2>
		<td align="left">%%value%%</td>
</datalist_column_2>

<datalist_column_footer_2>
	</tr>
</datalist_column_footer_2>



---------------------------------------------------------------------------------------------------------
-- ACTION ELEMENTS --------------------------------------------------------------------------------------

Element containing one button / action, multiple put together, e.g. to edit or delete a record.
To avoid side-effects, no line-break in this case -> not needed by default, but in classics-style!
<list_button><img src="_skinwebpath_/trans.gif" width="3" height="5">%%content%%</list_button>

---------------------------------------------------------------------------------------------------------
-- FORM ELEMENTS ----------------------------------------------------------------------------------------

<form_start>
<form name="%%name%%" method="POST" action="%%action%%" enctype="%%enctype%%">
</form_start>

<form_close>
</form>
</form_close>

Dropdown
<input_dropdown>
<table width="90%" cellpadding="2" cellspacing="0">
	<tr>
      <td width="30%" class="listecontent" align="right">%%title%%</td>
      <td><select name="%%name%%" id="%%name%%" class="%%class%%" %%disabled%%>%%options%%</select></td>
    </tr>
</table>
</input_dropdown>

<input_dropdown_row>
<option value="%%key%%">%%value%%</option>
</input_dropdown_row>

<input_dropdown_row_selected>
<option value="%%key%%" selected>%%value%%</option>
</input_dropdown_row_selected>

Checkbox
<input_checkbox>
<table width="90%" cellpadding="2" cellspacing="0">
	<tr>
      <td width="30%" class="listecontent" align="right">%%title%%</td>
      <td><div align="left"><input name="%%name%%" type="checkbox" id="%%name%%" value="checked" %%checked%% /></div></td>
    </tr>
</table>
</input_checkbox>

Regular Hidden-Field
<input_hidden>
	<input name="%%name%%" type="hidden" id="hidden_%%name%%" value="%%value%%" />
</input_hidden>

Regular Text-Field
<input_text>
<table width="90%" cellpadding="2" cellspacing="0">
	<tr>
		<td width="30%" class="listecontent" align="right">%%title%%</td>
		<td><input name="%%name%%" type="text" id="%%name%%" value="%%value%%" class="%%class%%" %%readonly%% /> %%opener%%</td>
	</tr>
</table>
</input_text>

Textarea
<input_textarea>
<table width="90%" cellpadding="2" cellspacing="0">
	<tr>
		<td width="30%" class="listecontent" align="right">%%title%%</td>
		<td><textarea name="%%name%%" id="%%name%%" class="%%class%%">%%value%%</textarea></td>
	</tr>
</table>
</input_textarea>

Regular Password-Field
<input_password>
<table width="90%" cellpadding="2" cellspacing="0">
	<tr>
		<td width="30%" class="listecontent" align="right">%%title%%</td>
		<td><input name="%%name%%" type="password" id="%%name%%" value="%%value%%" class="%%class%%" /></td>
	</tr>
</table>
</input_password>

Upload-Field
<input_upload>
<table width="90%" cellpadding="2" cellspacing="0">
	<tr>
		<td width="30%" class="listecontent" align="right">%%title%%</td>
		<td><input name="%%name%%" type="file" id="%%name%%" class="%%class%%" /></td>
	</tr>
</table>
</input_upload>

Regular Submit-Button
<input_submit>
<table width="90%" cellpadding="2" cellspacing="0">
	<tr>
		<td width="30%" class="listecontent"></td>
		<td><input type="submit" name="%%name%%" value="%%value%%" class="inputSubmit" %%eventhandler%% /></td>
	</tr>
</table>
</input_submit>

An easy date-selector
If you want to use the js-date-picker, leave %%calendarCommands%% at the end of the section
in addition, a container for the calendar is needed. use %%calendarContainerId%% as an identifier
If the calendar is used, you HAVE TO create a js-function named "calClose_%%calendarContainerId%%". This
function is called after selecting a date, e.g. to hide the calendar
<input_date_simple>
<table width="90%" cellpadding="2" cellspacing="0">
	<tr>
		<td width="30%" class="listecontent" align="right" valign="top" style="padding-top: 6px;">%%title%%</td>
		<td>
    		<table>
    		  <tr>
    		      <td>
    		          <input name="%%titleDay%%" id="%%titleDay%%" type="text" class="%%class%%" size="2" maxlength="2" value="%%valueDay%%" />
            		  <input name="%%titleMonth%%" id="%%titleMonth%%" type="text" class="%%class%%" size="2" maxlength="2" value="%%valueMonth%%" />
            		  <input name="%%titleYear%%" id="%%titleYear%%" type="text" class="%%class%%" size="4" maxlength="4" value="%%valueYear%%" />
            	  </td>
            	  <td>
            		  <a href="javascript:fold('%%calendarContainerId%%', initCal_%%calendarContainerId%%);"><img src="_skinwebpath_/pics/icon_calendar.gif" border="0" /></a>
    		      </td>
    		  </tr>
    		</table>
	    </td>
	</tr>
	<tr>
	   <td></td>
	   <td><div id="%%calendarContainerId%%" style="display: none;"></div><script type="text/javascript"> function calClose_%%calendarContainerId%%() { fold('%%calendarContainerId%%'); }; </script></td>
	</tr>
</table>
%%calendarCommands%%
</input_date_simple>

A page-selector.
If you want to use ajax to load a list of proposals on entering a char,
place ajaxScript before the closing input_pageselector-tag and make sure, that you
have a surrounding div with class "ac_container" and a div with id "%%name%%_container" and class
"ac_results" inside the "ac_container", to generate a resultlist
<input_pageselector>
<table width="90%" cellpadding="2" cellspacing="0">
	<tr>
		<td width="30%" class="listecontent" align="right">%%title%%</td>
		<td>
		  <div class="ac_container">
		     <input name="%%name%%" type="text" id="%%name%%" value="%%value%%" class="%%class%%" /> %%opener%%
		     <div id="%%name%%_container" class="ac_results"></div>
		  </div>
		</td>
	</tr>
</table>
%%ajaxScript%%
</input_pageselector>

---------------------------------------------------------------------------------------------------------
-- MISC ELEMENTS ----------------------------------------------------------------------------------------
Used to fold elements / hide/unhide elements
<layout_folder>
<div id="%%id%%" style="display: %%display%%;">%%content%%<br /><br /></div>
</layout_folder>

Same as above, but using an image to fold / unfold the content
<layout_folder_pic>
%%link%%<br /><br /><div id="%%id%%" style="display: %%display%%;">%%content%%</div>
</layout_folder_pic>

A precent-beam to illustrate proportions
<percent_beam>
<table cellpadding="0" cellspacing="0" class="listecontent" width="%%length%%">
    <tr>
        <td width="50">%%percent%% % </td>
	    <td width="%%width%%"><img src="_skinwebpath_/black.gif" width="1" height="15" /><img src="_skinwebpath_/pics/icon_progressbar.gif" width="%%beamwidth%%" height="10" />%%transTillEnd%%<img src="_skinwebpath_/black.gif" width="1" height="15" /></td>
	</tr>
</table>
</percent_beam>

A fieldset to structure logical sections
<misc_fieldset>
<fieldset class="%%class%%"><legend>%%title%%</legend>%%content%%</fieldset><br />
</misc_fieldset>

<graph_container>
<div align="center"><img src="%%imgsrc%%" /></div>
</graph_container>

---------------------------------------------------------------------------------------------------------
-- SPECIAL SECTIONS -------------------------------------------------------------------------------------

The login-Form is being displayed, when the user has to log in.
Needed Elements: %%error%%, %%form%%
<login_form>
  <table width="200" cellspacing="0">
    <tr>
      <td class="modulheadkurz">%%loginTitle%%</td>
    </tr>
	  <tr >
      <td class="modullinie"></td>
    </tr>
    <tr>
      <td class="listenframe">
      <table width="200" class="text1">
        <tr>
          <td>%%error%%</td>
        </tr>
        <tr>
          <td align="right">%%form%%</td>
        </tr>
      </table>
      </td>
    </tr>
  </table>
</login_form>

Part to display the login status, user is logged in
<logout_form>
<table width="190" cellspacing="0" cellpadding="0">
  <tr>
    <td class="modulheadkurz">Login</td>
  </tr>
  <tr>
    <td class="modullinie"></td>
  </tr>
  <tr>
    <td class="listenframe"><table width="190" cellpadding="0" cellspacing="0" class="text">
      <tr>
        <td style="padding-left: 10px">%%statusTitle%%</td>
      </tr>
      <tr>
        <td style="padding-left: 10px">%%name%%</td>
      </tr>
      <tr>
	    <td><a href="%%dashboard%%" class="adminModuleNavi"><div class="moduleNavi" onmouseover="this.className='moduleNaviSelected'" onmouseout="this.className='moduleNavi'">%%dashboardTitle%%</div></a></td>
	  </tr>
	  <tr>
	    <td><a href="%%profile%%" class="adminModuleNavi"><div class="moduleNavi" onmouseover="this.className='moduleNaviSelected'" onmouseout="this.className='moduleNavi'">%%profileTitle%%</div></a></td>
	  </tr>
      <tr>
        <td><a href="%%logout%%" class="adminModuleNavi"><div class="moduleNavi" onmouseover="this.className='moduleNaviSelected'" onmouseout="this.className='moduleNavi'">%%logoutTitle%%</div></a></td>
      </tr>
    </table></td>
  </tr>
</table>
</logout_form>

Shown, wherever the attention of the user is needed
<warning_box>
<table align="center"><tr><td>
<table class="%%class%%">
  <tr>
    <td>%%content%%</td>
  </tr>
</table>
</td></tr></table>
</warning_box>

Used to print plain text
<text_row>
<span class="%%class%%">%%text%%</span><br />
</text_row>

Used tp print plaintext in a form
<text_row_form>
<table width="90%" cellpadding="2" cellspacing="0">
	<tr>
		<td width="30%" class="%%class%%"></td>
		<td class="%%class%%">%%text%%</td>
	</tr>
</table>
</text_row_form>

This Section is used to display a few special details about the current page being edited
<page_infobox>
 <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="statusPages">
  <tr>
    <td width="18%">%%pagenameTitle%%</td>
    <td width="72%">%%pagename%%</td>
    <td width="10%"></td>
  </tr>
  <tr>
    <td width="18%">%%pagetemplateTitle%%</td>
    <td width="72%">%%pagetemplate%%</td>
    <td width="10%"></td>
  </tr>
  <tr>
    <td>%%lastuserTitle%%</td>
    <td>%%lastuser%%</td>
    <td></td>
  </tr>
  <tr>
    <td>%%lasteditTitle%%</td>
    <td>%%lastedit%%</td>
    <td align="right">%%pagepreview%%</td>
  </tr>
</table><br /><br />
</page_infobox>

Infobox used by the filemanager
<filemanager_infobox>
<table width="98%" class="status_filemanager">
  <tr>
    <td>%%foldertitle%% %%folder%%
    <div align="right"></div></td>
    <td width="20%"><div align="right">%%nrfilestitle%% %%files%%</div></td>
  </tr>
  <tr>
    <td width="73%" class="text">%%actions%%</td>
    <td valign="middle"><div align="right">%%nrfoldertitle%% %%folders%%</div></td>
  </tr>
  <tr>
    <td colspan="2">%%extraactions%%</td>
  </tr>
</table>
</filemanager_infobox>

---------------------------------------------------------------------------------------------------------
-- RIGHTS MANAGEMENT ------------------------------------------------------------------------------------

The following sections specify the layout of the rights-mgmt

<rights_form_header>
	<div align="left">%%backlink%% | %%desc%% %%record%% <br /><br /></div>
</rights_form_header>

<rights_form_form>
<table width="98%"  border="0" cellspacing="0" cellpadding="0">
	<tr class="adminListRow1">
		<td width=\"19%\">&nbsp;</td>
		<td width=\"9%\">%%title0%%</td>
		<td width=\"9%\">%%title1%%</td>
		<td width=\"9%\">%%title2%%</td>
		<td width=\"9%\">%%title3%%</td>
		<td width=\"9%\">%%title4%%</td>
		<td width=\"9%\">%%title5%%</td>
		<td width=\"9%\">%%title6%%</td>
		<td width=\"9%\">%%title7%%</td>
		<td width=\"9%\">%%title8%%</td>
	</tr>
	%%rows%%
</table>
%%inherit%%
</rights_form_form>

<rights_form_row_1>
	<tr class="adminListRow1">
		<td width=\"19%\">%%group%%</td>
		<td width=\"9%\">%%box0%%</td>
		<td width=\"9%\">%%box1%%</td>
		<td width=\"9%\">%%box2%%</td>
		<td width=\"9%\">%%box3%%</td>
		<td width=\"9%\">%%box4%%</td>
		<td width=\"9%\">%%box5%%</td>
		<td width=\"9%\">%%box6%%</td>
		<td width=\"9%\">%%box7%%</td>
		<td width=\"9%\">%%box8%%</td>
	</tr>
</rights_form_row_1>
<rights_form_row_2>
	<tr class="adminListRow2">
		<td width=\"19%\">%%group%%</td>
		<td width=\"9%\">%%box0%%</td>
		<td width=\"9%\">%%box1%%</td>
		<td width=\"9%\">%%box2%%</td>
		<td width=\"9%\">%%box3%%</td>
		<td width=\"9%\">%%box4%%</td>
		<td width=\"9%\">%%box5%%</td>
		<td width=\"9%\">%%box6%%</td>
		<td width=\"9%\">%%box7%%</td>
		<td width=\"9%\">%%box8%%</td>
	</tr>
</rights_form_row_2>

<rights_form_inherit>
<table width="90%" cellpadding="2" cellspacing="0">
	<tr>
      <td width="10%" class="listecontent">%%title%%</td>
      <td><div align="left"><input name="%%name%%" type="checkbox" id="%%name%%" value="1" onclick="this.blur();" onchange="checkRightMatrix();" %%checked%% /></div></td>
    </tr>
</table>
</rights_form_inherit>

---------------------------------------------------------------------------------------------------------
-- FOLDERVIEW -------------------------------------------------------------------------------------------

<folderview_detail_frame>

<table class="folderviewDetail" align="center">
	%%rows%%
</table>

</folderview_detail_frame>
<folderview_detail_row>
	<tr class="text">
		<td align="left"></td>
		<td align="left">%%title%%</td>
		<td align="left">%%name%%</td>
		<td align="right"></td>
	</tr>
</folderview_detail_row>

---------------------------------------------------------------------------------------------------------
-- WYSIWYG EDITOR ---------------------------------------------------------------------------------------

NOTE: This section not just defines the layout, it also inits the wysiwyg editor. Change settings with care!

The textarea-field to replace by the editor. If the editor can't be loaded, a plain textfield is shown instead
<wysiwyg_fckedit>
<table cellpadding="0" cellspacing="0" border="0" width="90%">
	<tr>
		<td class="text" valign="top" width="30%" align="right">%%title%%</td>
		<td><textarea name="%%name%%" id="%%name%%" class="wysiwyg">%%content%%</textarea></td>
	</tr>
</table>
</wysiwyg_fckedit>

A few settings to customize the editor. Up to now, those are:
Width, Height
<wysiwyg_fckedit_inits>
    objFCKeditor.Width = 600;
    objFCKeditor.Height = 400;
</wysiwyg_fckedit_inits>

---------------------------------------------------------------------------------------------------------
-- MODULE NAVIGATION ------------------------------------------------------------------------------------

The surrounding of the module-navigation (NOT THE MODULE-ACTIONS!)
<modulenavi_main>
    <table width="190" cellspacing="0" cellpadding="0">
		<tr>
			<td class="modulheadkurz">Administration</td>
		</tr>
		<tr>
			<td class="modullinie"></td>
		</tr>
		<tr>
			<td class="listenframe">%%rows%%</td>
		</tr>
	</table>
</modulenavi_main>

One row representing one module
Possible: %%name%%, %%link%%, %%href%%
<modulenavi_main_row>
<a href="%%href%%" class="adminModuleNavi"><div class="moduleNavi" onmouseover="this.className='moduleNaviSelected'" onmouseout="this.className='moduleNavi'">%%name%%</div></a>
</modulenavi_main_row>

<modulenavi_main_row_selected>
<a href="%%href%%" class="adminModuleNaviSelected"><div class="moduleNaviSelected">%%name%%</div></a>
</modulenavi_main_row_selected>

<modulenavi_main_row_first>
<a href="%%href%%" class="adminModuleNavi"><div class="moduleNavi" onmouseover="this.className='moduleNaviSelected'" onmouseout="this.className='moduleNavi'">%%name%%</div></a>
</modulenavi_main_row_first>

<modulenavi_main_row_selected_first>
<a href="%%href%%" class="adminModuleNaviSelected"><div class="moduleNaviSelected">%%name%%</div></a>
</modulenavi_main_row_selected_first>

<modulenavi_main_row_last>
<a href="%%href%%" class="adminModuleNavi"><div class="moduleNavi" onmouseover="this.className='moduleNaviSelected'" onmouseout="this.className='moduleNavi'">%%name%%</div></a>
</modulenavi_main_row_last>

<modulenavi_main_row_selected_last>
<a href="%%href%%" class="adminModuleNaviSelected"><div class="moduleNaviSelected">%%name%%</div></a>
</modulenavi_main_row_selected_last>

---------------------------------------------------------------------------------------------------------
-- INTERNAL MODULE-ACTION NAVIGATION --------------------------------------------------------------------

The sourrounding of the moduleaction-navigation (NOT THE MODULE LIST!)
<moduleactionnavi_main>%%rows%%</moduleactionnavi_main>

One row representing one action
Possible: %%name%%, %%link%%, %%href%%
<moduleactionnavi_row>%%link%%</moduleactionnavi_row>

Spacer, used to seperate logical groups
<moduleactionnavi_spacer>&nbsp;|&nbsp;</moduleactionnavi_spacer>

---------------------------------------------------------------------------------------------------------
-- ERROR HANDLING ---------------------------------------------------------------------------------------

<error_container>
    <table align="center"><tr><td>
        <table class="warnbox">
          <tr>
            <td>%%errorintro%%<br /><br />
            %%errorrows%%</td>
          </tr>
        </table>
</td></tr></table>
</error_container>

<error_row>
    &middot; %%field_errortext%% <br />
</error_row>

---------------------------------------------------------------------------------------------------------
-- PATH NAVIGATION --------------------------------------------------------------------------------------

The following sections are used to display the path-navigations, e.g. used by the navigation module

<path_container>
<div style="padding-bottom: 5px;">%%pathnavi%%</div>
</path_container>

<path_entry>
%%pathlink%%&nbsp;&gt;&nbsp;
</path_entry>

---------------------------------------------------------------------------------------------------------
--- PREFORMATTED ----------------------------------------------------------------------------------------

Used to print pre-formatted text, e.g. log-file contents
<preformatted>
    <pre class="preText">%%pretext%%</pre>
</preformatted>

---------------------------------------------------------------------------------------------------------
--- PORTALEDITOR ----------------------------------------------------------------------------------------

The following section is the toolbar of the portaleditor, displayed at top of the page.
The following placeholders are provided by the system:
pe_status_page, pe_status_status, pe_status_autor, pe_status_time
pe_status_page_val, pe_status_status_val, pe_status_autor_val, pe_status_time_val
pe_iconbar, pe_disable
<pe_toolbar>
    <div id="pe_classicskin">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr style="font-family: verdana, arial; font-size: 10px; white-space: nowrap;">
                <td rowspan="2" style="font-family: verdana, arial; font-size: 20px; font-weight: bold; font-style: italic; ">
                    <img src="_skinwebpath_/pe_logo.jpg" />
                </td>
                <td rowspan="2" style="text-align: center; width:100%; padding-top: 7px;" >
                    %%pe_iconbar%%
                </td>
                <td align="right" valign="bottom">%%pe_status_page%%</td>
                <td valign="bottom">%%pe_status_page_val%%</td>
                <td align="right" valign="bottom">&nbsp;&nbsp;&nbsp;%%pe_status_time%%</td>
                <td valign="bottom">%%pe_status_time_val%%</td>
                <td rowspan="2" valign="middle" style="padding-top: 7px;">&nbsp;&nbsp;%%pe_disable%%</td>
            </tr>
            <tr style="font-family: verdana, arial; font-size: 10px; white-space: nowrap;">
                <td align="right" valign="top">%%pe_status_status%%</td>
                <td valign="top">%%pe_status_status_val%%</td>
                <td align="right" valign="top">%%pe_status_autor%%</td>
                <td valign="top">%%pe_status_autor_val%%</td>
            </tr>
        </table>
    </div>
</pe_toolbar>

<pe_actionToolbar>
<div id="container_%%systemid%%" class="peContainerOut" onmouseover="portalEditorHover('%%systemid%%')" onmouseout="portalEditorOut('%%systemid%%')">
    <div id="menu_%%systemid%%" class="menuOut">
        <div class="actions">
            %%actionlinks%%
        </div>
    </div>
    %%content%%
</div>
</pe_actionToolbar>

Possible placeholders: %%link_complete%%, %%name%%, %%href%%
<pe_actionToolbar_link>
%%link_complete%%
</pe_actionToolbar_link>

---------------------------------------------------------------------------------------------------------
--- LANGUAGES -------------------------------------------------------------------------------------------

A single button, represents one language. Put together in the language-switch
<language_switch_button>
    <a href="#" onclick="%%onclickHandler%%" class="languageButton">%%languageName%%</a>
</language_switch_button>

A button for the active language
<language_switch_button_active>
    <a href="#" onclick="%%onclickHandler%%" class="languageButtonActive">%%languageName%%</a>
</language_switch_button_active>

The language switch sourrounds the buttons
<language_switch>
<div class="languageswitch">&nbsp;&nbsp;%%languagebuttons%%</div>
</language_switch>

---------------------------------------------------------------------------------------------------------
-- QUICK HELP -------------------------------------------------------------------------------------------

<quickhelp>
    <script type="text/javascript">
        function showQuickHelp() {
            document.getElementById('quickhelp').style.display='block';
            document.getElementById('quickhelp').style.position='absolute';
            document.getElementById('quickhelp').style.left=(screen.availWidth/2 - 200)+'px';
            document.getElementById('quickhelp').style.top='150px';
            document.getElementById('quickhelp').style.zIndex='21';
        }

        function hideQuickHelp() {
            document.getElementById('quickhelp').style.display='none';
        }

        //Register mover for the help-layer
		document.onmousemove = checkMousePosition;
		document.onmouseup = objMover.unsetMousePressed;
    </script>
    <div id="quickhelp" style=" width: 400px; display: none; background: #ffffff; border: 1px solid #000099;"
           onmousedown="objMover.setMousePressed(this)" onmouseup="objMover.unsetMousePressed()" >
        <div style="height: 20px;"><table width="100%"><tr><td align="left" class="modulheadkurz">%%title%%</td></tr></table></div>
        <div style="height: 20px;"><table width="100%"><tr><td align="right"><a href="#" onclick="hideQuickHelp();">[X]</a></td></tr></table></div>
        <div style="/* height: 220px; */padding: 5px;  text-align: left; margin-right: 20px;">%%text%%</div>
        <div style="height: 100%; width: 380px; position: absolute; top: 0px;"></div>

    </div>
</quickhelp>

<quickhelp_button>
    <a href="#" onclick="showQuickHelp();">%%text%%</a>
</quickhelp_button>

---------------------------------------------------------------------------------------------------------
-- PAGEVIEW ---------------------------------------------------------------------------------------------

<pageview_body>
<div align="center" style="margin-top: 10px;">%%nrOfElementsText%% %%nrOfElements%% | %%linkBackward%% %%pageList%% %%linkForward%%</div>
</pageview_body>

<pageview_link_forward>
<a href="%%href%%">%%linkText%%&gt;&gt;</a>
</pageview_link_forward>

<pageview_link_backward>
<a href="%%href%%">&lt;&lt;%%linkText%%</a>
</pageview_link_backward>

<pageview_page_list>
%%pageListItems%%
</pageview_page_list>

<pageview_list_item>
<a href="%%href%%" >[ %%pageNr%% ]</a>&nbsp;
</pageview_list_item>

<pageview_list_item_active>
<b><a href="%%href%%" >[ %%pageNr%% ]</a></b>&nbsp;
</pageview_list_item_active>

---------------------------------------------------------------------------------------------------------
-- WIDGETS / DASHBOAORD  --------------------------------------------------------------------------------

<adminwidget_widget>
<div class="adminwidget" style="font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 11px;">
	<div class="adminwidgetHeader">
		<div class="adminwidgetHeaderTitle">%%widget_name%%</div>
		<div class="adminwidgetHeaderActions">%%widget_edit%% %%widget_delete%%</div>
		<div style="clear: both;"></div>
	</div>
	<div class="adminwidgetContent">%%widget_content%%</div>
</div>
</adminwidget_widget>

<dashboard_column_header>
	<script type="text/javascript">
    	kajonaAjaxHelper.loadDragNDropBase();
    	if(arrayListIds == null)
            var arrayListIds = new Array("%%column_id%%");
        else
            arrayListIds[(arrayListIds.length +0)] = "%%column_id%%";
    
        kajonaAjaxHelper.addFileToLoad("admin/scripts/dragdrophelper_li.js");
	</script>
	
	<ul id="%%column_id%%">
</dashboard_column_header>

<dashboard_column_footer>
	</ul>
</dashboard_column_footer>

<dashboard_encloser>
	<li id="%%entryid%%">%%content%%</li>
</dashboard_encloser>

<adminwidget_text>
<div>%%text%%</div>
</adminwidget_text>

<adminwidget_separator>
<table cellpadding="0" cellspacing="0" style="width: 100%;">
	<tr>
  		<td class="divider">&nbsp;</td>
	</tr>
</table>
</adminwidget_separator>