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
$k = 0;
for( $i = $YJ_starting_key; $i < $YJ_max_modules + $YJ_starting_key; $i++ ){	
	
	$mod_name = $YJ_module_prefix.$i;
	if ( $this->countModules( $mod_name ) && array_key_exists($k, $grid_widths) ){
		
		$width = $grid_widths[$k];
		if( is_numeric( $width ) && $width > 0 )
			$YJ_modules[$i] = $width;	
		
	}	
	$k++;	
}
$total_size = array_sum( $YJ_modules );
if( $total_size < 100 && $YJ_modules ){
	
	$remaining_size = 100 - $total_size;
	foreach ( $YJ_modules as $k=>$module ){
		
		$percent = $module / $total_size;
		$YJ_modules[$k] = number_format( $module + $remaining_size * $percent, 2);
		
	}	
}

$check_size = array_sum( $YJ_modules );
if( $check_size > 100 && $YJ_modules ){
	$ratio = ($check_size-100)/100;
	if( $ratio > 1 ){
		foreach ( $YJ_modules as $k=>$m ){
			$YJ_modules[$k] = $m/$ratio;			
		}	
		$check_size = array_sum( $YJ_modules );		
	}
	
	$plus_size = ($check_size - 100) / count( $YJ_modules );
	foreach ( $YJ_modules as $k=>$m ){
		$final_size = $m - $plus_size;
		if( $final_size < 1 ){
			unset( $YJ_modules[$k] );
			continue;
		}	
		$YJ_modules[$k] = $final_size;
	}
}
?>