<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Helper files
| 4. Custom config files
| 5. Language files
| 6. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packges
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/

$autoload['packages'] = array(APPPATH.'third_party');
$autoload['libraries'] = array();
$autoload['helper'] = array();
$autoload['config'] = array();
$autoload['language'] = array();
$autoload['model'] = array();


$autoload['libraries'][] = 'database';
$autoload['libraries'][] = 'session';
$autoload['libraries'][] = 'ion_auth';
$autoload['libraries'][] = 'acl';
$autoload['helper'][] = 'url';
$autoload['helper'][] = 'form';
$autoload['model'][] = 'global_model';

/* End of file autoload.php */
/* Location: ./application/config/autoload.php */