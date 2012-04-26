<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2012 Diego Zanella IT Services
This file is part of Cron Plugin for Vanilla Forums.

Cron Plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Cron Plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Cron Plugin.  If not, see <http://www.gnu.org/licenses/>.

Contact Diego Zanella at diego [at] pathtoenlightenment [dot] net
*/

	/**
	 * This template is a snippet that has to be loaded by parent views, such as
	 * cronjobhistory_view.php. It expects variable $CronJobsHistoryDataSet
	 * to be already populated, and, if it is, it outputs a table containing the
	 * Cron Jobs History within a date range.
	 *
	 * This snippet has been extracted to a separate file to allow reusing it in
	 * different places (e.g. in Views which allow Users to choose different
	 * parameters, or in a Widget on the front end).
	 */

	// No point in proceeding if there's not even a DataSet to begin with.
	if(!isset($CronJobsHistoryDataSet)) { return; }

	// Indicates how many columns there are in the table that shows data from the
	// Cron Jobs History. It's mainly used to set the "colspan" attributes of
	// single-valued table rows, such as Title, or the "No Results Found" message.
	define('HISTORY_TABLE_COLUMNS', 5);

	// The following HTML will be displayed when the DataSet is empty.
	$OutputForEmptyDataSet = Wrap(T('No results found. Please try using different parameters.'),
																'td',
																array('colspan' => HISTORY_TABLE_COLUMNS,
																			'class' => 'NoResultsFound',)
																);
?>
	<table id="CronJobsHistoryDetails" class="display">
		<thead>
			<tr>
				<th class="ClassName"><?php echo T('Class/Plugin'); ?></th>
				<th class="StartedAt"><?php echo T('Job started at'); ?></th>
				<th class="FinishedAt"><?php echo T('Job finished at'); ?></th>
				<th class="Result"><?php echo T('Result'); ?></th>
				<th class="ResultMessage"><?php echo T('Message'); ?></th>
			</tr>
		</thead>
		<tfoot>
		</tfoot>
		<tbody>
			<?php
				// If DataSet is empty, just print a message.
				if(empty($CronJobsHistoryDataSet)) {
					echo $OutputForEmptyDataSet;
				}

				// TODO Implement Pager.
				// Output the details of each row in the DataSet
				foreach($CronJobsHistoryDataSet as $CronHistoryEntry) {
					echo "<tr>\n";
					// Output Class Name
					echo Wrap(Gdn_Format::Text($CronHistoryEntry->ClassName), 'td', array('class' => 'ClassName',));
					// Output Date/Time when Job Started
					echo Wrap($CronHistoryEntry->StartedAt, 'td', array('class' => 'StartedAt',));
					// Output Date/Time when Job Finished
					echo Wrap($CronHistoryEntry->FinishedAt, 'td', array('class' => 'FinishedAt',));
					// Output Job Result
					echo Wrap($CronHistoryEntry->Result, 'td', array('class' => 'Result',));
					// Output Job Result Message
					echo Wrap(Gdn_Format::Html($CronHistoryEntry->ResultMessage), 'td', array('class' => 'ResultMessage',));
					echo "</tr>\n";
				}
			?>
		 </tbody>
	</table>
