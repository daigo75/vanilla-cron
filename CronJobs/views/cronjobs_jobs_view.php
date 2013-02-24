<?php	if (!defined('APPLICATION')) exit();
/**
{licence}
*/

// Indicates how many columns there are in the table that shows data from the
// Cron Jobs History. It's mainly used to set the "colspan" attributes of
// single-valued table rows, such as Title, or the "No Results Found" message.
define('JOBS_TABLE_COLUMNS', 2);

// The following HTML will be displayed when the DataSet is empty.
$OutputForEmptyDataSet = Wrap(T('No Jobs configured.'),
															'td',
															array('colspan' => JOBS_TABLE_COLUMNS,
																		'class' => 'NoResultsFound',)
															);
?>
<div class="CronJobsPlugin">
	<div class="PluginHeader">
		<?php include('cronjobs_admin_header.php'); ?>
	</div>
	<div class="PluginContent">
		<?php
			// TODO Implement form to filter view (e.g. show only Active/Inactive Jobs).

			echo $this->Form->Open();
			echo $this->Form->Errors();
			//
		?>
	</div>
	<div id="CronJobs">
		<?php
			$CronJobsDataSet = $this->Data['CronJobsDataSet'];
		?>
		<table id="CronJobsList" class="display">
			<thead>
				<tr>
					<th colspan="<?php echo JOBS_TABLE_COLUMNS ?>" class="Title"><?php echo T('Registered Cron Jobs'); ?></th>
				</tr>
				<tr>
					<th class="Plugin"><?php echo T('Plugin'); ?></th>
					<th class="Enable"><?php echo T('Enable'); ?></th>
				</tr>
			</thead>
			<tfoot>
			</tfoot>
			<tbody>
				<?php
					// If DataSet is empty, just print a message.
					if(empty($CronJobsDataSet)) {
						echo $OutputForEmptyDataSet;
					}

					// Output the details of each row in the DataSet
					foreach($CronJobsDataSet as $Object) {
						echo "<tr>\n";
						// Output name of the Class that Registered the Job
						$ClassName = get_class($Object);
						echo Wrap($ClassName, 'td', array('class' => 'Plugin',));
						echo Wrap($this->Form->CheckBox($ClassName, '', array('value' => 1,
																																	'checked' => 'checked',)),
											'td',
											array('class'=> 'Enable',)
											);
						echo "</tr>\n";
					}
				?>
			 </tbody>
		</table>
		<div>
			<?php
				 echo $this->Form->Close('Save');
			?>
		</div>
	</div>
</div>
