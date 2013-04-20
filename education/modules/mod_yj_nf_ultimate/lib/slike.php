<?php

/*----------------------------------------------------------------------
#Youjoomla Images
# ----------------------------------------------------------------------
# Copyright (C) Since 2007 You Joomla. All Rights Reserved.
# Designed by: You Joomla
# License: Copyright Youjoomla.com
# Website: http://www.youjoomla.com
------------------------------------------------------------------------*/

	// no direct access
	defined('_JEXEC') or die('Restricted access');
	/**
	 * Image detection inside article. Searches in intro text and if not found, in full article text
	 *
	 * @param object $row
	 * @return string - image path
	 */
	function yjnfu_image ($row)
	{
		$img = search_yjnfu_image( $row->introtext );
		if( $img ) return $img;
				
		$img = search_yjnfu_image( $row->fulltext );
		return $img;		
	}
	/**
	 * Searches for all images inside a text and returns the first one found
	 *
	 * @param string $text
	 * @return string
	 */
	function search_yjnfu_image( $text )
	{		
		preg_match_all("#\<img(.*)src\=\"(.*)\"#Ui", $text, $mathes);		
		return isset($mathes[2][0]) ? $mathes[2][0] : '';			
	}	
?>