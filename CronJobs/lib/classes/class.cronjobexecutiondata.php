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
 * Cron Jobs Execution Data Model
 *
 * @package CronJobs
 */

/**
 * Cron Jobs Execution Data management.
 *
 * @package CronJobs
 */


/**
 * This class acts as a wrapper used to pass the information about each Cron Job
 * Execution to Event Handlers and to Cron Jobs History Model for saving it in
 * the Database. This allows to achieve a higher level of decoupling from
 * Vanilla's Core.
 */
class CronJobExecutionData extends Gdn_Model {
	protected $ClassName;
	protected $StartedAt;
	protected $FinishedAt;
	protected $Result;
	protected $ResultMessage;

	public function GetResultMessage() {
		return (string)$this->ResultMessage;
	}

	public function SetResultMessage($Value) {
		$this->ResultMessage = $Value;
	}

	public function GetResult() {
		return (int)$this->Result;
	}

	public function SetResult($Value) {
		$this->Result = $Value;
	}

	public function GetFinishedAt() {
		return (string)$this->FinishedAt;
	}

	public function SetFinishedAt($Value) {
		$this->FinishedAt = $Value;
	}

	public function GetStartedAt() {
		return (string)$this->StartedAt;
	}

	public function SetStartedAt($Value) {
		$this->StartedAt = $Value;
	}

	public function GetClassName() {
		return (string)$this->ClassName;
	}

	public function SetClassName($Value) {
		$this->ClassName = $Value;
	}

	public function SetResultData($Result, $ResultMessage) {
		$this->Result = $Result;
		$this->ResultMessage = $ResultMessage;
	}

	/**
	 * Returns the data in a format compatible with Gdn_Model Insert() function.
	 *
	 * @return Cron Job Execution Data as an associative array.
	 *
	 * @version 1.0.0
	 */
	public function GetData() {
		return array('ClassName' => $this->GetClassName(),
								 'StartedAt' => $this->GetStartedAt(),
								 'FinishedAt' => $this->GetFinishedAt(),
								 'Result' => $this->GetResult(),
								 'ResultMessage' => $this->GetResultMessage(),
								);
	}

	public function __construct($ClassName, $StartedAt) {
		parent::__construct();

		$this->SetClassName($ClassName);
		$this->SetStartedAt($StartedAt);
	}
}
