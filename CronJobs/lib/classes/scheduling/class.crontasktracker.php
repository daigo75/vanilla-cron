<?php namespace Cron;
if (!defined('APPLICATION')) exit();

class TaskTracker {
	protected $CronTaskID;

	protected function TaskRootConfigKey() {
		return 'Cron.Task.' . $this->CronTaskID;
	}

	protected function TaskConfigKey($SubKey) {
		return $this->TaskRootConfigKey() . '.' . $SubKey;
	}

	public function __construct($CronTaskID) {
		$this->CronTaskID = $CronTaskID;
	}

	public function RunInterval($Interval = null) {
		$ConfigKey = $this->TaskConfigKey('RunInterval');
		$Result = C($ConfigKey, $Interval);

		if(is_numeric($Interval)) {
			SaveToConfig($ConfigKey, $Interval);
		}
		return $Result;

		$UpdateInterval = C('Plugin.TopContributors.UserTitleUpdateInterval', TOPCONTRIBUTORS_DEF_TITLES_UPDATE_INTERVAL);
		// Retrieves the date and time of when the processing of User Titles ran last
		$LastUserTitlesProcessRun = C('Plugin.TopContributors.LastUserTitlesProcessRun', 0);

	}

	public function LastRun() {
		$ConfigKey = $this->TaskConfigKey('LastRun');
	}

	public function ShouldRun() {
		throw new Exception(T('Not implemented. Descendant classes must implement this method.');
	}
}
