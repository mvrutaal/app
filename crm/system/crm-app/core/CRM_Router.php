<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	Author: Wilson Santos
*/
class CRM_Router extends CI_Router
{

   function _validate_request($segments)
    {
		$segment_count = count($segments);
		if($segment_count == 0) return $segments;
		
		// Is the controller in a sub-folder?
        if (is_dir(APPPATH.'controllers/'.$segments[0]))
        {
            // @edit: Support multi-level sub-folders
            $dir = '';
            do
            {
                if (strlen($dir) > 0)
                {
                    $dir .= '/';
                }
                $dir .= $segments[0];
                $segments = array_slice($segments, 1);
            } while (count($segments) > 0 && is_dir(APPPATH.'controllers/'.$dir.'/'.$segments[0]));
            // Set the directory and remove it from the segment array
            $this->set_directory($dir);
            // @edit: END

            // @edit: If no controller found, use 'default_controller' as defined in 'config/routes.php'
            if (count($segments) > 0 && ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
            {
                array_unshift($segments, $this->default_controller);
            }
            // @edit: END

            if (count($segments) > 0)
            {
                // Does the requested controller exist in the sub-folder?
                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
                {
                    // show_404($this->fetch_directory().$segments[0]);
                    // @edit: Fix a "bug" where show_404 is called before all the core classes are loaded
                    $this->directory = '';
                    // @edit: END
                }
            }
            else
            {
                // Is the method being specified in the route?
                if (strpos($this->default_controller, '/') !== FALSE)
                {
                    $x = explode('/', $this->default_controller);

                    $this->set_class($x[0]);
                    $this->set_method($x[1]);
                }
                else
                {
                    $this->set_class($this->default_controller);
                    $this->set_method('index');
                }

                // Does the default controller exist in the sub-folder?
                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
                {
                    $this->directory = '';
                    return array();
                }

            }

            return $segments;
        }
		
		if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
        {
            return $segments;
        }
		
		if (!empty($this->routes['404_override']))
        {
            $x = explode('/', $this->routes['404_override']);

            $this->set_class($x[0]);
            $this->set_method(isset($x[1]) ? $x[1] : 'index');

            return $x;
        }
			
        show_404($segments[0]);
    }
	
	 function set_directory($dir)
    {
        $this->directory = str_replace(array('.'), '', $dir).'/'; // @edit: Preserve '/'
    }
}  