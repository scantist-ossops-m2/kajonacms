<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" href="_skinwebpath_/styles.css?_system_browser_cachebuster_" type="text/css" />
	<script type="text/javascript" src="_webpath_/core/module_system/admin/scripts/yui/yuiloader-dom-event/yuiloader-dom-event.js?_system_browser_cachebuster_"></script>
    %%head%%
	<script type="text/javascript" src="_webpath_/core/module_system/admin/scripts/kajona.js?_system_browser_cachebuster_"></script>
	<title>Kajona³ admin [%%moduletitle%%]</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="robots" content="noindex, nofollow" />
	<meta name="generator" content="Kajona³, www.kajona.de" />
	<link rel="shortcut icon" href="_webpath_/favicon.ico" type="image/x-icon" />
	<script type="text/javascript">
	YAHOO.util.Event.onDOMReady(function() {
        new YAHOO.util.KeyListener(document, { keys:27 }, parent.KAJONA.admin.portaleditor.closeDialog).enable();
	});
	</script>
</head>
<body class="portalEditor">
<div id="contentBox">
    %%content%%
</div>

<div class="imgPreload">
    <img src="_skinwebpath_/loading.gif" alt="" title="" />
</div>

<div class="folderviewDialog" id="folderviewDialog">
    <div class="hd"><span id="folderviewDialog_title">BROWSER</span><div class="close"><a href="#" onclick="KAJONA.admin.folderview.dialog.hide(); KAJONA.admin.folderview.dialog.setContentRaw(''); return false;">X</a></div></div>
    <div class="bd" id="folderviewDialog_content">
        <!-- filled by js -->
    </div>
</div>

<script type="text/javascript">
    KAJONA.admin.loader.loadDialogBase(function() {
        KAJONA.admin.folderview.dialog = new KAJONA.admin.ModalDialog('folderviewDialog', 0, true, true);
    });
</script>

</body>
</html>