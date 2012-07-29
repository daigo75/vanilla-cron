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
