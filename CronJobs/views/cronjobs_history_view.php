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
				<h3><?php echo T('Date Range'); ?></h3>
				<p>
					<?php
					echo T('In this section you can view a History of the Cron Jobs executions, with the' .
								 'result of each job.');
					?>
				</p>
			</legend>
			<div class="FilterMenu">
				<ul>
					 <li><?php
							echo $this->Form->Label(T('Start Date'), 'DateFrom');
							echo $this->Form->Date('DateFrom');
					 ?></li>
					 <li><?php
							echo $this->Form->Label(T('End Date'), 'DateTo');
							echo $this->Form->Date('DateTo');
					 ?></li>
					 <li><?php
							//echo $this->Form->Label(T('Results per Page'), 'ResultsPerPage');
							//echo $this->Form->Textbox('ResultsPerPage');
							echo $this->Form->Hidden('ResultsPerPage', array('value' => CRON_DEFAULT_HISTORYJOBSPERPAGE,));
					 ?></li>
				</ul>
			</div>
		</fieldset>
		<?php
			echo $this->Form->Close('Refresh');
		?>
		<div class="Results">
			<?php
				$CronJobsHistoryDataSet = GetValue('CronJobsHistoryDataSet', $this->Data, null);

				include('cronjobs_history_details.php');
			?>
		</div>
	</div>
</div>
