<?php	if (!defined('APPLICATION')) exit();
/**
{licence}
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
