<?php if (!defined('APPLICATION')) exit();
/**
{licence}
*/


/**
 * Constants used by Cron Plugin.
 *
 * @package CronPlugin
 */

// Default Configuration Settings
define('CRON_DEFAULT_ALLOWEDIPADDRESSES', '127.0.0.1');
define('CRON_DEFAULT_MAXRUNSPERMINUTE', 2);
define('CRON_DEFAULT_MAXRUNSPERHOUR', 10);
define('CRON_DEFAULT_MAXRUNSPERDAY', 60);
define('CRON_DEFAULT_LASTRUN', 0);
define('CRON_DEFAULT_MINUTERUNS', 0);
define('CRON_DEFAULT_HOURRUNS', 0);
define('CRON_DEFAULT_DAYRUNS', 0);
define('CRON_DEFAULT_CRONKEY', '');
define('CRON_DEFAULT_HISTORYJOBSPERPAGE', 50);

// Paths
define('CRON_PLUGIN_PATH', PATH_PLUGINS . '/CronJobs');
define('CRON_PLUGIN_LIB_PATH', CRON_PLUGIN_PATH . '/lib');

// URLs
define('CRON_PLUGIN_BASE_URL', 'plugin/cronjobs');
define('CRON_SETTINGS_URL', CRON_PLUGIN_BASE_URL);
define('CRON_REGISTERED_JOBS_URL', CRON_PLUGIN_BASE_URL . '/jobs');
define('CRON_HISTORY_URL', CRON_PLUGIN_BASE_URL . '/history');
define('CRON_EXEC_URL', CRON_PLUGIN_BASE_URL . '/cron');
define('CRON_STATUS_URL', CRON_PLUGIN_BASE_URL . '/status');

// Regular Expression that will be used to create a shortcut route for Cron execution script
define('CRON_EXEC_ROUTE_REGEX', '^cron/?\??(.*)$');

// Return Codes
define('CRON_EXEC_SUCCESS', 0);
define('CRON_OK', 0);
define('CRON_ERR_NOT_AN_OBJECT', 1);
define('CRON_ERR_CRON_METHOD_UNDEFINED', 2);
define('CRON_ERR_INVALID_CRONKEY', 4011);
define('CRON_ERR_IPNOTALLOWED', 4012);
define('CRON_ERR_DAYRUNEXCEEDED', 4013);
define('CRON_ERR_HOURRUNEXCEEDED', 4014);
define('CRON_ERR_MINUTERUNEXCEEDED', 4015);

// Http Arguments
define('CRON_ARG_CRONKEY', 'ck');
