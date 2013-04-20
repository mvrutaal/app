<?php
/*======================================================================*\
|| #################################################################### ||
|| # Youjoomla LLC - YJ- Licence Number 3850LE193
|| # Licensed to - Tanya Eldert
|| # ---------------------------------------------------------------- # ||
|| # Copyright (C) Since 2006 Youjoomla LLC. All Rights Reserved.       ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- THIS IS NOT FREE SOFTWARE ---------------- #      ||
|| # http://www.youjoomla.com | http://www.youjoomla.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/

	// no direct access
	defined('_JEXEC') or die('Restricted access');
	/**
	 * Image detection inside article. Searches in intro text and if not found, in full article text
	 *
	 * @param object $row
	 * @return string - image path
	 */
	function article_imageyouvers ($row)
	{
		$img = search_imageyouvers ( $row->introtext );
		if( $img ) return $img;
				
		$img = search_imageyouvers ( $row->fulltext );
		return $img;		
	}
	/**
	 * Searches for all images inside a text and returns the first one found
	 *
	 * @param string $text
	 * @return string
	 */
	function search_imageyouvers ( $text )
	{		
		preg_match_all("#\<img(.*)src\=\"(.*)\"#Ui", $text, $mathes);		
		return isset($mathes[2][0]) ? $mathes[2][0] : '';			
	}	
?>