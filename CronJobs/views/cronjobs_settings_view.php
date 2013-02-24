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
				<h3><?php echo T('Security and Throttling'); ?></h3>
				<p>
					<?php
					echo T('In this section you can find some settings that will allow you to secure the access ' .
								 'to the Cron URL, and to limit the calls to it. This is useful to prevent abuse from ' .
								 'malicious users who could attempt to trigger Cron execution multiple times in a row ' .
								 'to perform a <a href="http://en.wikipedia.org/wiki/Denial-of-service_attack" title="Denial-of-service attack">DoS attack</a>.');
					?>
				</p>
			</legend>
			<ul>
				<li><?php
					echo $this->Form->Label(T('Allowed IP Addresses'), 'Plugin.CronJobs.AllowedIPAddresses');
					echo Wrap(sprintf(T('Specify which IP Addresses are allowed to run Cron by calling its <a href="%s">URL</a>. They can be specified as IPv4 or IPv6.'), CRON_EXEC_URL), 'p');
					echo $this->Form->TextBox('Plugin.CronJobs.AllowedIPAddresses');
				?></li>
				<li><?php
					echo $this->Form->Label(T('Cron Key'), 'Plugin.CronJobs.CronKey');
					echo Wrap(sprintf(T('If specified, this value will have to be passed as argument "ck" to Cron URL (i.e. %s/?ck=cron_key), or the request will be rejected.'), CRON_EXEC_URL), 'p');
					echo $this->Form->TextBox('Plugin.CronJobs.CronKey');
				?></li>
				<li><?php
					echo $this->Form->Label(T('Maximum Cron Runs per minute'), 'Plugin.CronJobs.MaxRunsPerMinute');
					echo Wrap(T('Specify how many times Cron can run in one minute.'), 'p');
					echo $this->Form->TextBox('Plugin.CronJobs.MaxRunsPerMinute');
				?></li>
				<li><?php
					echo $this->Form->Label(T('Maximum Cron Runs per Hour'), 'Plugin.CronJobs.MaxRunsPerHour');
					echo Wrap(T('Specify how many times Cron can run in one hour.'), 'p');
					echo $this->Form->TextBox('Plugin.CronJobs.MaxRunsPerHour');
				?></li>
				<li><?php
					echo $this->Form->Label(T('Maximum Cron Runs per Day'), 'Plugin.CronJobs.MaxRunsPerDay');
					echo Wrap(T('Specify how many times Cron can run in one day.'), 'p');
					echo $this->Form->TextBox('Plugin.CronJobs.MaxRunsPerDay');
				?></li>
			</ul>
		</fieldset>
		<?php
			 echo $this->Form->Close('Save');
		?>
	</div>
</div>
