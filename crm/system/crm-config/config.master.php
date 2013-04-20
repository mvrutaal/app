<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * GilesParscale Master Config
 *
 * This is the master config file for our ExpressionEngine sites
 * The settings will contain database credentials and numerous "config overrides"
 * used throughout the site. This file is used as first point of configuration
 * but there are environment-specific files as well. The idea is that the environment
 * config files contain config overrides that are specific to a single environment.
 *
 * Some config settings are used in multiple (but not all) environments. You will
 * see the use of conditionals around the ENV constant in this file. This constant is
 * defined in ./config/config.env.php
 *
 * All config files are stored in the ./config/ directory and this master file is "required"
 * in system/expressionengine/config/config.php and system/expressionengine/config/database.php
 *
 * require $_SERVER['DOCUMENT_ROOT'] . '/../config/config.master.php';
 *
 * @package    Deviarte-CRM
 * @author     Victor Gutierrez, Deviarte <hello@deviarte.com>
 */


// Require our environment declatation file if it hasn't
// already been loaded in index.php or admin.php
if ( ! defined('ENV'))
{
	require 'config.env.php';
}


// Setup our initial arrays
$env_db = $env_config = $env_global = $master_global = array();


/**
 * Database override magic
 *
 * If this equates to TRUE then we're in the database.php file
 * We don't want these settings bothered with in our config.php file
 */
if (isset($db['deviarte-crm']))
{
	/**
	 * Load our environment-specific config file
	 * which contains our database credentials
	 *
	 * @see config/config.local.php
	 * @see config/config.dev.php
	 * @see config/config.stage.php
	 * @see config/config.prod.php
	 */
	require 'config.' . ENV . '.php';

	// Dynamically set the cache path (Shouldn't this be done by default? Who moves the cache path?)
	$env_db['cachedir'] = APPPATH . 'cache/db_cache/';

	// Merge our database setting arrays
	$db['deviarte-crm'] = array_merge($db['deviarte-crm'], $env_db);

	// No need to have this variable accessible for the rest of the app
	unset($env_db);
}
// End if (isset($db['expressionengine'])) {}



/**
 * Config override magic
 *
 * If this equates to TRUE then we're in the config.php file
 * We don't want these settings bothered with in our database.php file
 */
if (isset($config))
{

	/**
	 * Dynamic path settings
	 *
	 * Make it easy to run the site in multiple environments and not have to switch up
	 * path settings in the database after each migration
	 * As inspired by Matt Weinberg: http://eeinsider.com/articles/multi-server-setup-for-ee-2/
	 */
	$protocol                          = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
	$base_url                          = $protocol . $_SERVER['HTTP_HOST'];
	$base_path                         = $_SERVER['DOCUMENT_ROOT'];
	$system_folder                     = APPPATH . '../';
	$images_folder                     = 'images';
	$images_path                       = $base_path . '/' . $images_folder;
	$images_url                        = $base_url . '/' . $images_folder;

	$env_config['base_url']            = $base_url . '/';
	$env_config['site_url']            = $env_config['base_url'];

	/**
	 * Set debug to '2' if we're in dev mode, otherwise just '1'
	 *
	 * 0: no PHP/SQL errors shown
	 * 1: Errors shown to Super Admins
	 * 2: Errors shown to everyone
	 */
	$env_config['debug']                = (ENV_DEBUG) ? '2' : '1' ;



	/**
	 * Load our environment-specific config file
	 * May contain override values from similar above settings
	 *
	 * @see config/config.local.php
	 * @see config/config.dev.php
	 * @see config/config.stage.php
	 * @see config/config.prod.php
	 */
	require 'config.' . ENV . '.php';


	/**
	 * Merge arrays to form final datasets
	 *
	 * We've created our base config and global key->value stores
	 * We've also included the environment-specific arrays now
	 * Here we'll merge the arrays to create our final array dataset which
	 * respects "most recent data" first if any keys are duplicated
	 *
	 * This is how our environment settings are "king" over any defaults
	 */
	$config = array_merge($config, $env_config); // config setting arrays

}
// End if (isset($config)) {}


/* End of file config.master.php */
/* Location: ./config/config.master.php */