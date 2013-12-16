<?php namespace Cron;
if (!defined('APPLICATION')) exit();

class TaskTracker {
	const EPOCH = 0;

	// @var string The task ID.
	protected $CronTaskID;

	/**
	 * Retrieves the root configuration key for the task, based on its ID.
	 *
	 * @return string
	 */
	protected function TaskRootConfigKey() {
		return 'Cron.Task.' . $this->CronTaskID;
	}

	/**
	 * Retrieves the configuration key for a specific task parameter.
	 *
	 * @return string
	 */
	protected function TaskConfigKey($SubKey) {
		return $this->TaskRootConfigKey() . '.' . $SubKey;
	}

	/**
	 * Class constructor.
	 *
	 * @param string CronTaskID The task ID.
	 */
	public function __construct($CronTaskID) {
		$this->CronTaskID = $CronTaskID;
	}

	/**
	 * Returns the time interval to respect between one task execution and the
	 * next one.
	 *
	 * @param int Interval If specified, it replaces the existing interval.
	 * @return int
	 */
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

	/**
	 * Returns the Unix time stamp of the last task execution.
	 *
	 * @return int
	 */
	public function LastRun() {
		$ConfigKey = $this->TaskConfigKey('LastRun');
		return C($ConfigKey, self::EPOCH);
	}

	/**
	 * Determines if the task should run. This is a placeholder method and it must
	 * be implemented by descendant classes.
	 *
	 * @return bool
	 */
	public function ShouldRun() {
		throw new Exception(T('Not implemented. Descendant classes must implement this method.'));
	}
}
