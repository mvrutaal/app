<?php

/**
 * Environment Declaration
 *
 * This switch statement sets our environment. The environment is used primarily
 * in our custom config file setup. It is also used, however, in the front-end
 * index.php file and the back-end admin.php file to set the debug mode
 *
 * @package    Deviarte-CRM
 * @author     Victor Gutierrez, Deviarte <hello@deviarte.com>
 */

if ( ! defined('ENV'))
{
	switch ($_SERVER['HTTP_HOST']) {

		case 'dev.localsteals.com' :
			define('ENV', 'dev');
			define('ENV_FULL', 'Development');
			define('ENV_DEBUG', TRUE);
		break;

		default :
			define('ENV', 'prod');
			define('ENV_FULL', 'Production');
			define('ENV_DEBUG', FALSE);
		break;
	}
}

/* End of file config.env.php */
/* Location: ./config/config.env.php */