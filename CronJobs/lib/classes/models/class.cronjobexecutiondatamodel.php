<?php if (!defined('APPLICATION')) exit();
/**
{licence}
*/

/**
 * Cron Jobs Execution Data Model
 *
 * @package CronPlugin
 */

/**
 * This class acts as a wrapper used to pass the information about each Cron Job
 * Execution to Event Handlers and to Cron Jobs History Model for saving it in
 * the Database. This allows to achieve a higher level of decoupling from
 * Vanilla's Core.
 */
class CronJobExecutionDataModel extends Gdn_Model {
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
