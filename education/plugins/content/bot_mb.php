<?php
/*----------------------------------------------------------------------
#Multi Media Box - 2.0
/*======================================================================*\
|| #################################################################### ||
|| # Copyright ©2006-2009 Youjoomla LLC. All Rights Reserved.           ||
|| # ----------------     JOOMLA TEMPLATES CLUB      ----------- #      ||
|| # @license http://www.gnu.org/copyleft/gpl.html GNU/GPL            # ||
|| #################################################################### ||
\*======================================================================*/
if( ! defined( '_JEXEC' ) ) {
	die( 'Direct Access to ' . basename( __FILE__ ) . ' is not allowed.' ) ;
}
jimport("joomla.application.application");

$mainframe->registerEvent( 'onBeforeDisplayContent', 'pop_box_media');

global $mainframe;
$mosConfig_absolute_path	= JPATH_ROOT;
$mosConfig_live_site 		= JURI :: base();

$document = JFactory::getDocument();
JHTML::_('behavior.mootools'); 
$document->addStyleSheet(JURI::base() . 'plugins/content/bot_mb/css/yjmmbox.css');
$document->addScript(JURI::base() . 'plugins/content/bot_mb/js/yjmmbox.js');

$linktag_boot2="";
$linktag_boot2.= "<!--[if lte IE 6]>
<link href=\"".$mosConfig_live_site."plugins/content/bot_mb/css/ie6fixes.css\" rel=\"stylesheet\" type=\"text/css\" />
<script type=\"text/javascript\" src=\"".$mosConfig_live_site."plugins/content/bot_mb/js/ie6minmax.js\"></script>
<![endif]-->
\n";
JApplication::addCustomHeadTag($linktag_boot2);

function find_part($a,$s)
{
  for ($i=0;$i<count($a);$i++)
  {
    if (substr_count($a[$i],$s)>0)
    {
      return($i);
    }
  }
  return(-1);
}

function pop_box_media(&$row, &$params, $page = 0)
{
	global $mainframe;
	$mosConfig_absolute_path	= JPATH_ROOT;
	$mosConfig_live_site 		= JURI :: base();
	$database					=& JFactory::getDBO();

	$plugin 	=& JPluginHelper::getPlugin('content', 'bot_mb');
	$params_all = new JParameter( $plugin->params );
	$params2	= $params_all->_registry['_default']['data'];

    $image_folder=$params2->image_folder;


    $class_name="popbox";
    $regex = '/\{mbox:(.*?)}/i';

	preg_match_all($regex,$row->text,$matches);
	for($x=0; $x<count($matches[0]); $x++)
	{
		$parts = explode("|",$matches[1][$x]);
        $href=$parts[0];

        if (find_part($parts,"title=")!=-1)
        {
          $t=explode("title=",$parts[find_part($parts,"title=")]);
          $title=$t[1];
        }else
        {
          $title="Empty title";
        }

        if (find_part($parts,"group=")!=-1)
        {
          $t=explode("group=",$parts[find_part($parts,"group=")]);
          $group='rel="'.$t[1].'"';
        }else
        {
          $group='';
        }
// ADD width 
        if (find_part($parts,"width=")!=-1)
        {
          $t=explode("width=",$parts[find_part($parts,"width=")]);
          $img_width=$t[1];
        }else
        {
          $img_width="";
        }
		
		//////
		
		
		// ADD height 
        if (find_part($parts,"height=")!=-1)
        {
          $t=explode("height=",$parts[find_part($parts,"height=")]);
          $img_height=$t[1];
        }else
        {
          $img_height="";
        }
		
		//////
		
		// ADD CAPTION 
        if (find_part($parts,"caption=")!=-1)
        {
          $t=explode("caption=",$parts[find_part($parts,"caption=")]);
          $caption=$t[1];
		  $show_caption="<span class=\"bot_caption\" style=\"width:".$img_width."px;height:20px;\" >".$caption."</span>";
        }else
        {
          $caption="";
		  $show_caption="";
        }	
		//////
		
				// ADD THUMB 
        if (find_part($parts,"thumb=")!=-1)
        {
          $t=explode("thumb=",$parts[find_part($parts,"thumb=")]);
          $thumb=$t[1];

        }else
        {
          $thumb="".$href."";

        }	

        if (find_part($parts,"txt=")!=-1)
        {
          $t=explode("txt=",$parts[find_part($parts,"txt=")]);
          $txt=$t[1];
        }else
        {
          $txt="Link";
        }

		// ADD THUMB FOR VIDEO
		 if (find_part($parts,"vthumb=")!=-1)
        {
          $t=explode("vthumb=",$parts[find_part($parts,"vthumb=")]);
          $vthumb="<img class=\"bot_thumbv\" src=\"".$mosConfig_live_site."".$image_folder."/".$thumb."\" width=\"".$img_width."\" height=\"".$img_height."\" border=\"0\" alt=\"".$title."\"/>\n";      $vclass_name="class=\"popboxv\"";
        }else
        {
          $vthumb="Missing Image";
        }
		
		          if (find_part($parts,"vthumb=")!=-1)
        {
		$linkis=$vthumb;
		}else if (find_part($parts,"txt=")!=-1){
		$linkis=$txt;
		$vclass_name="";
		}
		
		///////////////
        $fp=explode('.',$href);

		if (isset($fp[1])&&(($fp[1]=="jpg")||($fp[1]=="gif")||($fp[1]=="png")||($fp[1]=="jpeg") ||($fp[1]=="JPG")))
        {
          $replace="<a  href=\"".$mosConfig_live_site."".$image_folder."/".$href."\" title=\"".$title."\" class=\"".$class_name."\" ".$group.">";
		  
		if (find_part($parts,"thumbnone=")!=-1){
          $t=explode("thumbnone=",$parts[find_part($parts,"thumbnone=")]);
         $replace.="";
        }else{
		 $replace.="<img class=\"bot_thumb\" src=\"".$mosConfig_live_site."".$image_folder."/".$thumb."\" width=\"".$img_width."\" height=\"".$img_height."\" border=\"0\" alt=\"".$title."\"/>\n";
		  }
          $replace.="".$show_caption."</a>\n";
        }else{
        $replace="<a href=\"".$href."\" title=\"".$title."\" ".$vclass_name."  rel=\"mediabox[".$parts[1]." ".$parts[2]."]\">".$linkis."".$show_caption."</a>\n";
        }
        $row->text=str_replace($matches[0][$x],$replace,$row->text);
	}
}

?>
