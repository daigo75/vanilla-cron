<?php	if (!defined('APPLICATION')) exit();
/**
 * Copyright 2012 Diego Zanella
 * This file is part of CronJobs Plugin for Vanilla Forums.
 *
 * CronJobs Plugin is free software: you can redistribute it and/or
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


$ExecutionResultMessages = array(CRON_EXEC_SUCCESS => T('Cron ran successfully.'),
																 CRON_ERR_INVALID_CRONKEY => T('Received Cron Key was not valid.'),
																 CRON_ERR_IPNOTALLOWED => T('Request came from an unauthorized IP Address.'),
																 CRON_ERR_DAYRUNEXCEEDED => sprintf(T('Maximum amount of Day Runs exceeded. Please go to <a href="%s">Cron Plugin Settings</a> to change the threshold and/or reset the counters.'), CRON_SETTINGS_URL),
																 CRON_ERR_HOURRUNEXCEEDED => sprintf(T('Maximum amount of Hour Runs exceeded. Please go to <a href="%s">Cron Plugin Settings</a> to change the threshold and/or reset the counters.'), CRON_SETTINGS_URL),
																 CRON_ERR_MINUTERUNEXCEEDED => sprintf(T('Maximum amount of Minute Runs exceeded. Please go to <a href="%s">Cron Plugin Settings</a> to change the threshold and/or reset the counters.'), CRON_SETTINGS_URL),
																 );
?>
<div >
	<div class="PluginHeader">
			<h1><?php echo T($this->Data['Title']); ?></h1>
	</div>
	<div id="CronResult" class="PluginContent">
		<div>
			<div>
				<h2><?php
					echo Wrap(T('Cron Execution Result'), 'h2');
				?></h2>
			</div>
			<?php
				// TODO Show more information about the Cron Run.
				echo Wrap($ExecutionResultMessages[$this->Data['CronExecResult']], 'p');
				echo Wrap(sprintf(T('Please go to %s for more details'), Anchor(T('Cron Jobs History Page'), CRON_HISTORY_URL)), 'p');
			?>
		</div>
	</div>
</div>
