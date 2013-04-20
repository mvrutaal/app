<?php defined('_JEXEC') or die('Restricted access');
class YJSGparams{
   static public function YJSGparam(){
      $app       = & JFactory::getApplication();
      $YJSGcont  = null;
      $YJSGini   = JPATH_THEMES.DS.$app->getTemplate().DS.'params.ini';
      jimport('joomla.filesystem.file');
      if (JFile::exists($YJSGini)) {
         $YJSGcont = JFile::read($YJSGini);
      } else {
         $YJSGcont = null;
      }
      return new JParameter($YJSGcont);      
   }
}
?>