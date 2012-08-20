<?php	if (!defined('APPLICATION')) exit();
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
?>
<div class="CronJobsPlugin">
	<div class="PluginHeader">
		<?php include('cronjobs_admin_header.php'); ?>
	</div>
	<div class="PluginContent">
		<?php
			echo $this->Form->Open();
			echo $this->Form->Errors();
		?>
		<fieldset>
			<legend>
				<h3><?php echo T('Cron Jobs Execution Counters'); ?></h3>
				<p><?php
					echo Wrap(sprintf(T('The Execution Counters indicate how many times the Cron process has been triggered ' .
															'in the pase Minute, Hour and Day. If you attempt to trigger the Cron process and it ' .
															'fails, please make sure that the Counters haven\'t exceeded the maximum amount of ' .
															'executions allowed in %s page.'),
														Anchor(T('Settings'), CRON_SETTINGS_URL)),
										'p');
					echo Wrap(T('If you wish to reset the counters without changing the configuration (e.g. for '.
											'testing purposes), you can do so by clicking on <strong>Reset Counters</strong> button below.'),
										'p');

				?></p>
			</legend>
			<ul>
				<li><?php
					echo sprintf(T('Date/Time of last Cron Run: %s'), date('Y-m-d H:i:s', intval(C('Plugin.CronJobs.LastRun'))));
				?></li>
				<li><?php
					echo sprintf(T('Minute Calls: %d'), C('Plugin.CronJobs.MinuteRuns'));
				?></li>
				<li><?php
					echo sprintf(T('Hour Calls: %d'), C('Plugin.CronJobs.HourRuns'));
				?></li>
				<li><?php
					echo sprintf(T('Day Calls: %d'), C('Plugin.CronJobs.DayRuns'));
				?></li>
			</ul>
		</fieldset>
		<?php
			echo $this->Form->Close('Reset Counters');
		?>
	</div>
</div>
