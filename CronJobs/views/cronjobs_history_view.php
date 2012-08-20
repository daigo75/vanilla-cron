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
							$this->Form->Hidden('ResultsPerPage', array('value' => CRON_DEFAULT_HISTORYJOBSPERPAGE,));
					 ?></li>
				</ul>
			</div>
		</fieldset>
		<?php
			 echo $this->Form->Close('Refresh');
		?>
		<div class="Results">
			<?php
				$CronJobsHistoryDataSet = $this->Data['CronJobsHistoryDataSet'];

				if(isset($CronJobsHistoryDataSet)) {
					include('cronjobs_history_details.php');
				}
			?>
		</div>
	</div>
</div>
