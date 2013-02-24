<?php	if (!defined('APPLICATION')) exit();
/**
{licence}
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
