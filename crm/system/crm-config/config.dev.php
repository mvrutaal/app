<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Development config overrides & db credentials
 *
 * Our database credentials and any environment-specific overrides
 *
 * @package    Deviarte-CRM
 * @author     Victor Gutierrez, Deviarte <hello@deviarte.com>
 */

$env_db['hostname'] = 'localhost';
$env_db['username'] = 'root';
$env_db['password'] = '';
$env_db['database'] = 'deviarte_crm';

$env_config['base_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/Deviarte-CRM/';
$env_config['owner_short'] = 'FEFFIK';
$env_config['owner_long'] = 'FEFFIK';

/* End of file config.dev.php */
/* Location: ./config/config.dev.php */