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
$session =& JFactory::getSession();
$mystyles = array(
        'turquoise', 'red', 'orange', 'blue'
);
$default_style = 'turquoise';

//*
if ( isset($_GET['change_css']) && !empty($_GET['change_css']) ) {
        // check if style is valid
        if( in_array( $_GET['change_css'], $mystyles ) ){

                $_SESSION['frontend_css'] = $_GET['change_css'];
                $_SESSION['frontend_changed_css'] = true;
                $valid_styles = $_GET['change_css'];

        }else {
                // else set to default style
                $valid_styles = $default_style;
        }

} else {
        // second case, checkes for admin changes or front-end changes

        if ( isset($_SESSION['frontend_changed_css']) && in_array( $_SESSION['frontend_css'], $mystyles ) ){

                $valid_styles = $_SESSION['frontend_css'];

        }else if( isset( $_SESSION['admin_change'] ) ){

                $valid_styles = $this->params->get("default_color");

        }else {
                $valid_styles = $default_color;
        }
}
//*/
$css_file = in_array( $valid_styles, $mystyles ) ? $valid_styles : $default_style;
/**
 * Font switcher
 */
$fonts = array(
        1=>'9px',
        2=>'10px',
        3=>'12px',
        4=>'14px',
        5=>'16px'
);
$default_font = $this->params->get('default_font');
if( isset( $_GET['change_font'] ) && !empty( $_GET['change_font'] ) ){

        $font_size = isset( $_SESSION['frontend_font'] ) && isset( $_SESSION['frontend_changed_font'] ) ? $_SESSION['frontend_font'] : $default_font;

        switch ( $_GET['change_font'] ){
                case 'increase':
                        $font_size+=1;
                        if( $font_size > 5 )
                                $font_size = 5;
                break;

                case 'decrease':
                        $font_size-=1;
                        if ($font_size < 1)
                                $font_size = 1;
                break;
        }
        $_SESSION['frontend_font'] = $font_size;
        $valid_font = $font_size;
        $_SESSION['frontend_changed_font'] = true;

}else {

        if ( isset( $_SESSION['frontend_changed_font'] ) ){

                $valid_font = $_SESSION['frontend_font'];

        }else if ( isset( $_SESSION['admin_change'] ) ) {

                $valid_font = $this->params->get('default_font');

        }else{

                $valid_font = $default_font;

        }
}
$font_key = array_key_exists( $valid_font, $fonts ) ? $valid_font : $default_font;
$css_font = $fonts[$font_key];

// MENU
$mymenu = array(
        1, 2, 3, 4, 5
);
$default_menu = $this->params->get('default_menu_style');

//*
if ( isset($_GET['change_menu']) && !empty($_GET['change_menu']) ) {
        // check if style is valid
        if( in_array( $_GET['change_menu'], $mymenu ) ){

                $_SESSION['frontend_menu'] = $_GET['change_menu'];
                $_SESSION['frontend_changed_menu'] = true;
                $valid_menu = $_GET['change_menu'];

        }else {
                // else set to default style
                $valid_menu = $default_style;
        }

} else {
        // second case, checkes for admin changes or front-end changes

        if ( isset($_SESSION['frontend_changed_menu']) && in_array( $_SESSION['frontend_menu'], $mymenu ) ){

                $valid_menu = $_SESSION['frontend_menu'];

        }else if( isset( $_SESSION['admin_change'] ) ){

                $valid_menu = $this->params->get("default_menu_style");

        }else {
                $valid_menu = $default_menu;
        }
}
//*/
$default_menu_style = in_array( $valid_menu, $mymenu ) ? $valid_menu : $default_menu;

        //echo $valid_menu;
// LAYOUT
$mylayouts = array(
        1, 2, 3
);
$default_layout = $this->params->get('site_layout');

//*
if ( isset($_GET['change_layout']) && !empty($_GET['change_layout']) ) {
        // check if style is valid
        if( in_array( $_GET['change_layout'], $mylayouts ) ){

                $_SESSION['frontend_layout'] = $_GET['change_layout'];
                $_SESSION['frontend_changed_layout'] = true;
                $valid_layout = $_GET['change_layout'];

        }else {
                // else set to default style
                $valid_layout = $default_layout;
        }

} else {
        // second case, checkes for admin changes or front-end changes

        if ( isset($_SESSION['frontend_changed_layout']) && in_array( $_SESSION['frontend_layout'], $mylayouts ) ){

                $valid_layout = $_SESSION['frontend_layout'];

        }else if( isset( $_SESSION['admin_change'] ) ){

                $valid_layout = $this->params->get("site_layout");

        }else {
                $valid_layout = $default_layout;
        }
}
//*/
$site_layout = in_array( $valid_layout, $mylayouts ) ? $valid_layout : $default_layout;


//MOBILE
$mymobile = array(
        1 => 'yjsg_mobile.php',
        2 => 'yjsg_main.php'
);
$default_mobile = $this->params->get('site_mobile');
if ( isset( $_GET['change_mobile'] ) && !empty( $_GET['change_mobile'] ) ){

        // check if style is valid
        if( array_key_exists( $_GET['change_mobile'], $mymobile ) ){

                $_SESSION['frontend_mobile'] = $_GET['change_mobile'];
                $_SESSION['frontend_changed_mobile'] = true;
                $valid_mobile = $_GET['change_mobile'];

        }else {
                // else set to default style
                $valid_mobile = $default_mobile;
        }

}else{

        // second case, checkes for admin changes or front-end changes
        if ( isset($_SESSION['frontend_changed_mobile']) && array_key_exists( $_SESSION['frontend_mobile'], $mymobile ) ){

                $valid_mobile = $_SESSION['frontend_mobile'];

        }else if( isset( $_SESSION['admin_change'] ) ){

                $valid_mobile =  $this->params->get('site_mobile');

        }else {
                $valid_mobile = 2;
        }

}
if( empty( $valid_mobile ) )
        $valid_mobile = 2;
$site_mobile = $mymobile[$valid_mobile];

// DIRECTION
$mydirection = array(
        1, 2
);
$default_direction = $this->params->get('text_direction');

//*
if ( isset($_GET['change_direction']) && !empty($_GET['change_direction']) ) {
        // check if style is valid
        if( in_array( $_GET['change_direction'], $mydirection ) ){

                $_SESSION['frontend_direction'] = $_GET['change_direction'];
                $_SESSION['frontend_changed_direction'] = true;
                $valid_direction = $_GET['change_direction'];

        }else {
                // else set to default style
                $valid_direction = $default_direction;
        }

} else {
        // second case, checkes for admin changes or front-end changes

        if ( isset($_SESSION['frontend_changed_direction']) && in_array( $_SESSION['frontend_direction'], $mydirection ) ){

                $valid_direction = $_SESSION['frontend_direction'];

        }else if( isset( $_SESSION['admin_change'] ) ){

                $valid_direction = $this->params->get("text_direction");

        }else {
                $valid_direction = $default_direction;
        }
}
//*/
$text_direction = in_array( $valid_direction, $mydirection ) ? $valid_direction : $default_direction;
?>