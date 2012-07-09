<?php	if (!defined('APPLICATION')) exit();
/*
Copyright 2012 Diego Zanella IT Services
This file is part of Cron Plugin for Vanilla Forums.

Cron Plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Cron Plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Cron Plugin.  If not, see <http://www.gnu.org/licenses/>.

Contact Diego Zanella at diego [at] pathtoenlightenment [dot] net
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
					echo $this->Form->Label(T('Results per Page'), 'ResultsPerPage');
					echo $this->Form->Textbox('ResultsPerPage');
			 ?></li>
		</ul>
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
