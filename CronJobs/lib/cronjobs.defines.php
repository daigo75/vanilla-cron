<?php if (!defined('APPLICATION')) exit();
/**
 * Copyright 2012 Diego Zanella
 * This file is part of CronJobs Plugin for Vanilla Forums.
 *
 * Plugin is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or (at your
 * option) any later version.
 * Plugin is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
 * Public License for more details.
 * You should have received a copy of the GNU General Public License along with
 * CronJobs Plugin. If not, see http://opensource.org/licenses/GPL-2.0.
 *
 * @package CronJobs Plugin
 * @author Diego Zanella <diego@pathtoenlightenment.net>
 * @copyright Copyright (c) 2011 Diego Zanella (http://dev.pathtoenlightenment.net)
 * @license http://opensource.org/licenses/GPL-2.0 GPL 2.0
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

// Paths
define('CRON_PLUGIN_PATH', PATH_PLUGINS . '/CronJobs');
define('CRON_PLUGIN_LIB_PATH', CRON_PLUGIN_PATH . '/lib');

// URLs
define('CRON_PLUGIN_BASE_URL', 'plugin/cronjobs');
define('CRON_SETTINGS_URL', CRON_PLUGIN_BASE_URL);
define('CRON_REGISTERED_JOBS_URL', CRON_PLUGIN_BASE_URL . '/jobs');
define('CRON_HISTORY_URL', CRON_PLUGIN_BASE_URL . '/history');
define('CRON_EXEC_URL', CRON_PLUGIN_BASE_URL . '/cron');

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
