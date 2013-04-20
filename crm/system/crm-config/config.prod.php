<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Development config overrides & db credentials
 *
 * Our database credentials and any environment-specific overrides
 *
 * @package    DeviarteCRM
 * @author     Victor Gutierrez, Deviarte <hello@deviarte.com>
 */

$env_db['hostname'] = 'localhost';
$env_db['username'] = 'feffik_crmdb';
$env_db['password'] = 'feffik.an';
$env_db['database'] = 'feffik_crm';

/*$env_db['hostname'] = 'localhost';
$env_db['username'] = 'feffik_crmtemp';
$env_db['password'] = 'Temp123$';
$env_db['database'] = 'feffik_crmtemp';*/


$env_config['base_url'] = 'http://crm.feffik.com/';
$env_config['owner_short'] = 'FEFFIK';
$env_config['owner_long'] = 'FEFFIK';

/* End of file config.dev.php */
/* Location: ./config/config.dev.php */