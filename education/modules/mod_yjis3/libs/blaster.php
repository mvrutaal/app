<?php
/*======================================================================*\
|| #################################################################### ||
|| # Youjoomla LLC - YJ- Licence Number 3850LE193
|| # Licensed to - Tanya Eldert
|| # ---------------------------------------------------------------- # ||
|| # Copyright (c) 2006-2009 Youjoomla LLC. All Rights Reserved.        ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- THIS IS NOT FREE SOFTWARE ---------------- #      ||
|| # http://www.youjoomla.com | http://www.youjoomla.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
$document->addScriptDeclaration('
		<!--//--><![CDATA[//><!--
		Mediabox.scanPage = function() {
		  var links = $$("a").filter(function(el) {
			return el.rel && el.rel.test(/^lightbox/i);
		  });
		  $$(links).mediabox({
			playerpath: \''.JURI::base().'modules/mod_yjis3/src/NonverBlaster.swf\',
			JWplayerpath:  \''.JURI::base().'modules/mod_yjis3/src/player.swf\',
			loop:false,
			useNB:true,
			overlayOpacity:0.7
			}, null, function(el) {
			var rel0 = this.rel.replace(/[[]|]/gi," ");
			var relsize = rel0.split(" ");
			return (this == el) || ((this.rel.length > 8) && el.rel.match(relsize[1]));
		  });
		};
		window.addEvent("domready", Mediabox.scanPage);
		//--><!]]>
');	
?>