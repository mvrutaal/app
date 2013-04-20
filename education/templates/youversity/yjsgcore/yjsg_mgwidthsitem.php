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
defined( '_JEXEC' ) or die( 'Restricted index access' );
if ( $left  &&  $right && $inset ) {
	
	$leftblock   = $leftcolumn_itmid .$widthdefined_itmid;
	$midblock    = $maincolumn_itmid .$widthdefined_itmid;
	$rightblock  = $rightcolumn_itmid .$widthdefined_itmid;
	$insetblock  = $insetcolumn_itmid .$widthdefined_itmid;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif ( $left && $right) {
	$leftblock   = $leftcolumn_itmid .$widthdefined_itmid;
	$midblock    = $maincolumn_itmid +$insetcolumn_itmid.$widthdefined_itmid;
	$rightblock  = $rightcolumn_itmid .$widthdefined_itmid;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif ( $left && $inset) {
	$leftblock   = $leftcolumn_itmid .$widthdefined_itmid;
	$midblock    = $maincolumn_itmid +$insetcolumn_itmid .$widthdefined_itmid;
	$insetblock  = $insetcolumn_itmid .$widthdefined_itmid;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif ( $right && $inset) {
	$rightblock  = $rightcolumn_itmid.$widthdefined_itmid;
	$midblock    = $maincolumn_itmid+$insetcolumn_itmid.$widthdefined_itmid;
	$insetblock  = $insetcolumn_itmid.$widthdefined_itmid;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif ( $left) {
	$midblock   = $maincolumn_itmid + $rightcolumn_itmid + $insetcolumn_itmid.$widthdefined_itmid;
	$leftblock  = $leftcolumn_itmid.$widthdefined_itmid;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif ( $right) {
	$midblock    = $maincolumn_itmid + $leftcolumn_itmid + $insetcolumn_itmid.$widthdefined_itmid;
	$rightblock  = $rightcolumn_itmid.$widthdefined_itmid;
	$insettop    = 100 - $midblock.$widthdefined;

}elseif ( $inset) {
	$midblock    = $maincolumn_itmid + $rightcolumn_itmid + $leftcolumn_itmid.$widthdefined_itmid;
	$insetblock  = $insetcolumn_itmid.$widthdefined_itmid ;
	$insettop    = 100 - $midblock.$widthdefined;

} else {
    $midblock = $leftcolumn_itmid + $rightcolumn_itmid + $maincolumn_itmid + $insetcolumn_itmid.$widthdefined_itmid;
	$insettop    ='0'.$widthdefined;
}
// divide among yourself if component is off
if ( $left  &&  $right && $inset && $turn_component_off == 1 ) {
	
	$devide =$midblock /3;
	$leftblock   = $leftcolumn_itmid+$devide .$widthdefined_itmid;
	$midblock    = '0'.$widthdefined_itmid;
	$rightblock  = $rightcolumn_itmid+$devide .$widthdefined_itmid;
	$insetblock  = $insetcolumn_itmid+$devide .$widthdefined_itmid;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif  ( $left && $right && $turn_component_off == 1 ) {
	
	$devide =$midblock /2;
	$midblock    = '0'.$widthdefined_itmid;
	$leftblock   = $leftcolumn_itmid+$devide .$widthdefined_itmid;
	$rightblock  = $rightcolumn_itmid+$devide .$widthdefined_itmid;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif ( $right && $inset && $turn_component_off == 1 ) {
	
	$devide =$midblock /2;
	$midblock    = '0'.$widthdefined_itmid;
	$rightblock  = $rightcolumn_itmid+$devide .$widthdefined_itmid;
	$insetblock  = $insetcolumn_itmid+$devide .$widthdefined_itmid;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif  ( $left && $inset && $turn_component_off == 1 ) {
	
	$devide =$midblock /2;
	$midblock    = '0'.$widthdefined_itmid;
	$leftblock   = $leftcolumn_itmid+$devide .$widthdefined_itmid;
	$insetblock  = $insetcolumn_itmid+$devide .$widthdefined_itmid;
	$insettop    = 100 - $midblock.$widthdefined;
	
}elseif ( $left && $turn_component_off == 1 ) {

	$midblock    = '0'.$widthdefined_itmid;
	$leftblock  = '100'.$widthdefined;
	$insettop    = '100'.$widthdefined;
}elseif ( $right && $turn_component_off == 1 ) {

	$midblock    = '0'.$widthdefined_itmid;
	$rightblock  = '100'.$widthdefined;
	$insettop    = '100'.$widthdefined;
}elseif ( $inset && $turn_component_off == 1 ) {

	$midblock    = '0'.$widthdefined_itmid;
	$insetblock  = '100'.$widthdefined;
	$insettop    = '100'.$widthdefined;
}
?>