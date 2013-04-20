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
header("Content-type: text/css"); ?>
<?php
$template_path = dirname( dirname( $_SERVER['REQUEST_URI'] ) );
?>
html .png,
div .png,
div.arrow,.yjsglogo img,.logo,.bannergroup img,.menus_image_holder img,.menu img,.mainlevel img, .yjsquare_in img{
azimuth: expression(
this.pngSet?this.pngSet=true:(this.nodeName == "IMG" && this.src.toLowerCase().indexOf('.png')>-1?(this.runtimeStyle.backgroundImage = "none",
this.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + this.src + "', sizingMethod='image')",
this.src = "<?php echo $template_path; ?>/images/blank.gif"):(this.origBg = this.origBg? this.origBg :this.currentStyle.backgroundImage.toString().replace('url("','').replace('")',''),
this.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + this.origBg + "', sizingMethod='crop')",
this.runtimeStyle.backgroundImage = "none")),this.pngSet=true
);
}
/* MENU CONDS*/
.horiznav a {float:left;width:auto;}
.horiznav li:hover,.horiznav li.sfHover,.horiznav,.horiznav li.sfHoverHas {_position:relative;z-index:1000;}
.horiznav li ul {top:0px;_position:absolute;margin:0px 0 0 0px;}
.horiznav ul li{overflow:visible;}
/* DROPDOWN*/
.top_menu ul.subul_main{
display:inline-block;
margin:0;
padding:10px!important;
}

.horiznav ul ul a{
margin-right:-3px!important;
}
/*.horiznav li ul ul {_margin-left:95%!important;}*/
/* END MENU CONDS*/

.readon{
width:90px;
}
.yjround .content {
 zoom:1;
 _overflow-y:hidden;
}
.yjround .t {
 _height:1600px; /* arbitrary long height, IE 6 */
}

.itemImageBlock .clr{
display:none;
}
div.itemToolbar{
padding:5px 0 0 0;
height:21px;
}
div.subCategory{
padding:5px 0 0 8px;
}
div.k2ItemsBlock ul li img.moduleItemAuthorAvatar {
display:block;
position:absolute;
right:10px;
}
.moduleItemImage img{
display:block;
float:right;
position:relative;
}
.k2ItemsBlock ul li{
border-top:4px solid #DEDECC !important;
}
#quick_cap{
overflow:visible!important;
margin:-1px auto -33px auto!important;
}
#quick_link{
 left:0;
}
#yjsg1 .yjsgxhtml{
	background:url(../images/top_separator.gif) no-repeat right center!important;
}
#yjsg1 #top5{
	background:none!important;
}
.YJSlide_slide .YJSlide_intro{
bottom:-24px!important;
}
.yjsquare_yjnfu1  a.itemtitle {
	margin:0 22px 5px 0!important;
}
#centerbottom{
	padding:0!important;
}
#centerbottom_r{
	padding:0!important;
}
.bottom_left, .bottom_right{
margin-top:-5px;
}
.bottom_left{
margin-bottom:10px;
}
.top_left, .top_right{
margin-bottom:-5px;
}
.top_left{
margin-top:10px;
}
.top_right{
margin-top:0!important;
}
.roundins{
height:1%;
}
#yjsg5 .yjsgxhtml,#yjsg3 .yjsgxhtml{
	background:url(../images/separator_ie6.gif) no-repeat right center!important;
}
.yjsquare_yjnfu3 .itemdetails{
	padding:18px 0!important;
}
a.mainlevel_yjvmenu,.menu_yjvmenu a{
 background:url(../images/sidemenu_ie6.gif) no-repeat right top;
}
.menu_yjvmenu a:hover,.menu_yjvmenu li#current a,a.mainlevel_yjvmenu:hover,a#active_menu_yjvmenu{
 background:url(../images/sidemenu_ie6.gif) no-repeat right bottom;
}
a.mainlevel_yjvmenu,a.mainlevel_yjvmenu:hover,ul.menu_yjvmenu a,ul.menu_yjvmenu a:hover,.menu_yjvmenu li,a#active_menu_yjvmenu,a#active_menu_yjvmenu:hover{
 padding-right:15px;
}
a.mainlevel_yjvmenu:hover{
 background-position:right top;
}
#yv_slider .yv_slideitems_in{
	margin:0px!important;
}
#ie6Warning{display:none; position:absolute; width:100%; height:180px; text-align:center; background:#FFC url(<?php echo $template_path; ?>/images/typ/noie.png) no-repeat left center; font-size:18px; left:0px; top:-160px;     z-index:99999}
#ie6Warning .browsers{height:68px; width:390px; margin:0 auto; padding:5px 0 0 0; text-align:center; overflow:hidden}
#ie6Warning #fox, 
#ie6Warning #chrome, 
#ie6Warning #ie8, 
#ie6Warning #opera, 
##ie6Warning #safari{height:68px; width:68px; float:left; line-height:999px; display:block; overflow:hidden; margin:0 10px 0 0; background-image:url(<?php echo $template_path; ?>/images/typ/browsers.jpg)}
#ie6Warning #chrome{background-position:272px top}
#ie6Warning #ie8{background-position:204px top}
#ie6Warning #safari{background-position:136px top}
#ie6Warning #opera{background-position:68px top}
#ie6Warning h1{font-size:22px; font-weight:normal; color:#121212; padding:0 30px; display:block; width:800px; margin:0 auto; text-align:center}
#ie6Warning h4{font-size:16px; font-weight:normal; color:#121212}
#closeIe6Alert{display:block; position:absolute;     z-index:99999; bottom:10px; right:10px; width:80px;     height:28px;     overflow:hidden;     background-image:url(<?php echo $template_path; ?>/images/typ/hide.jpg);  line-height:999px; text-align:center}
