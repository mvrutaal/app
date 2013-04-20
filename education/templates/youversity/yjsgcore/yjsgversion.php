<?php
/*======================================================================*\
|| #################################################################### ||
|| # Package - Joomla Template based on YJSimpleGrid Framework          ||
|| # Copyright (C) 2010  Youjoomla LLC. All Rights Reserved.            ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla LLC                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/
	define( '_JEXEC', 1 );
	define( 'DS', DIRECTORY_SEPARATOR );
	
	$parts = explode( DS, dirname(__FILE__) );
	$template = $parts[ count($parts)-2 ];
	
	
	$t_path = DS.'templates'.DS.$template.DS.'yjsgcore';	
	define('JPATH_BASE', str_replace($t_path ,'',dirname(__FILE__)) );	
	
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	
	$mainframe =& JFactory::getApplication('site');
	$mainframe->initialise();
	jimport( 'joomla.filesystem.file' );
	
	// CURRENT YJSG VERSION
	$your_yjsgversion ='1.0.11';
	
	$yjsg_version = @JFile::read('http://www.youjoomla.com/yjsgversion/current_yjsgversion.txt');
?>
<body style="background:#000; font-family:Arial, Helvetica, sans-serif; padding:30px 0 0 0;">
<div class="yjsgversion" style="color:#fafcbe;text-align:center;font-weight:bold;height:80px;line-height:20px;">
<?php if ($yjsg_version && ini_get('allow_url_fopen') == '1' ) { ?>
		Current YJSG Version is <?php echo $yjsg_version ?><br />
	<?php if ($your_yjsgversion !== $yjsg_version){ ?>
		Your YJSG Version is <span style="color:red;"><?php echo $your_yjsgversion ?>!</span><br />
	 	<a href="http://www.youjoomla.com/joomla_support/yougrids/" style="color:green;" target="_blank">Visit forums for manual update</a>
		<br />
		or download and reinstall your template.
	<?php }else{ ?>
		<span style="color:#4db905;">Your YJSG Version is up to date</span>
	<?php } ?>
<?php // case allow_url_fopen is off ?>
<?php }elseif (ini_get('allow_url_fopen') !== '1' ) { ?>
			allow_url_fopen is not enabled on your server.<br />
			Click <a href="http://www.youjoomla.com/yjsgversion/yjsgversion.html" style="color:red;"  target="_blank">here</a> to compare 									            YJSG Version Directly.
<?php }else{ ?>
			<span style="color:#fff;">Unable to check your version at this time.Please try again later.</span>
<?php } ?>
</div>
</body>