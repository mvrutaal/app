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
// mainbody grid widths
defined( '_JEXEC' ) or die( 'Restricted index access' );
if ( $left  &&  $right && $inset ) {	
	$leftblock   = $leftcolumn.$widthdefined;
	$midblock    = $maincolumn.$widthdefined;
	$rightblock  = $rightcolumn.$widthdefined;
	$insetblock  = $insetcolumn.$widthdefined;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif ( $left && $right) {
    $insetdevide = $insetcolumn /2;
	$leftblock   = $leftcolumn+$insetdevide.$widthdefined;
	$midblock    = $maincolumn.$widthdefined;
	$rightblock  = $rightcolumn+$insetdevide.$widthdefined;
	$insettop    = 100 - $midblock.$widthdefined;
	
	
}elseif ( $left && $inset) {
	$leftblock   = $leftcolumn.$widthdefined;
	$midblock    = $maincolumn+$insetcolumn.$widthdefined;
	$insetblock  = $insetcolumn.$widthdefined;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif ( $right && $inset) {
	$rightblock  = $rightcolumn.$widthdefined;
	$midblock    = $maincolumn+$insetcolumn.$widthdefined;
	$insetblock  = $insetcolumn.$widthdefined;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif ( $left) {
	$midblock   = $maincolumn + $rightcolumn + $insetcolumn.$widthdefined;
	$leftblock  = $leftcolumn.$widthdefined ;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif ( $right) {
	$midblock    = $maincolumn + $leftcolumn + $insetcolumn.$widthdefined;
	$rightblock  = $rightcolumn.$widthdefined ;
	$insettop    = 100 - $midblock.$widthdefined;

}elseif ( $inset) {
	$midblock    = $maincolumn + $rightcolumn + $leftcolumn.$widthdefined;
	$insetblock  = $insetcolumn.$widthdefined ;
	$insettop    = 100 - $midblock.$widthdefined;

} else {
    $midblock    = $leftcolumn + $rightcolumn + $maincolumn + $insetcolumn.$widthdefined;
	$insettop    ='0'.$widthdefined;
	}
?>