<?php if (!defined('APPLICATION')) exit();
/**
 {licence}
*/

require('lib/cronjobs.defines.php');
require('lib/cronjobs.validation.php');
// Load Plugin's Autoloader
require_once(CRON_PLUGIN_PATH . '/vendor/autoload.php');

// Define the plugin:
$PluginInfo['CronJobs'] = array(
	'Name' => 'Cron Jobs',
	'Description' => 'Allows other plugins to register actions that will be executed on a scheduled basis.',
	'Version' => '14.11.19b',
	'RequiredApplications' => array('Vanilla' => '>=2.0.10'),
	'RequiredTheme' => FALSE,
  'RequiredPlugins' => array('Logger' => '13.02.01'),
	'HasLocale' => FALSE,
	'SettingsUrl' => '/plugin/cronjobs',
	'SettingsPermission' => 'Garden.Settings.Manage',
	'Author' => 'Diego Zanella',
	'AuthorEmail' => 'diego@pathtoenlightenment.net',
	'AuthorUrl' => 'http://dev.pathtoenlightenment.net',
	'RegisterPermissions' => array('Plugins.CronJobs.Manage',),
);

class CronJobsPlugin extends Gdn_Plugin {
	// @var array Contains a list of registered Jobs.
	// @see CronJobListModel
	protected $CronJobsList;

	// @var Contains an instance of CronJobsHistoryModel
	protected $CronJobsHistoryModel;

	/**
	 * Returns the instance of CronJobsHistoryModel used by the plugin. If an
	 * instance is not set, the function creates a new one and returns it.
	 *
	 * @return An instance of CronJobsHistoryModel.
	 */
	protected function GetCronJobsHistoryModel() {
		if(empty($this->CronJobsHistoryModel)) {
			$this->CronJobsHistoryModel = new CronJobsHistoryModel();
		}

		return $this->CronJobsHistoryModel;
	}

	/**
	 * Set Validation Rules related to Configuration Model.
	 *
	 * @param Gdn_Validation $Validation The Validation that is (or will be)
	 * associated to the Configuration Model.
	 *
	 * @return void
	 */
	protected function _SetConfigModelValidationRules(Gdn_Validation $Validation) {
		$Validation->AddRule('PositiveInteger', 'function:ValidatePositiveInteger');
		$Validation->AddRule('IPAddressList', 'function:ValidateIPAddressList');

		// Validation rules for Allowed Anonymous IP List
		$Validation->ApplyRule('Plugin.CronJobs.AllowedIPAddresses', 'Required', T('Please specify at least one Allowed IP Address.'));
		$Validation->ApplyRule('Plugin.CronJobs.AllowedIPAddresses', 'IPAddressList', T('One or more IP Addresses are not valid. ' .
																																										'Please specify each address in IPv4 or IPv6 format and separate them with a semicolon.'));

		// Validation rules for Throttling Values
		$Validation->ApplyRule('Plugin.CronJobs.MaxRunsPerMinute', 'Required', T('Please specify a value for Maximum Runs per Minute.'));
		$Validation->ApplyRule('Plugin.CronJobs.MaxRunsPerMinute', 'PositiveInteger', T('Maximum Runs per Minute must be a positive integer.'));
		$Validation->ApplyRule('Plugin.CronJobs.MaxRunsPerHour', 'Required', T('Please specify a value for Maximum Runs per Hour.'));
		$Validation->ApplyRule('Plugin.CronJobs.MaxRunsPerHour', 'PositiveInteger', T('Maximum Runs per Hour must be a positive integer.'));
		$Validation->ApplyRule('Plugin.CronJobs.MaxRunsPerDay', 'Required', T('Please specify a value for Maximum Runs per Day.'));
		$Validation->ApplyRule('Plugin.CronJobs.MaxRunsPerDay', 'PositiveInteger', T('Maximum Runs per Day must be a positive integer.'));
	}

	/**
	 * Set Validation Rules that apply when User is requesting Cron Jobs History
	 * data for a period of time.
	 *
	 * @param Gdn_Validation $Validation The Validation object to which the rules
	 * will be added.
	 *
	 * @return void
	 */
	protected function _SetCronJobsHistoryValidationRules(Gdn_Validation $Validation) {
		// Validation rules for DateFrom field
		$Validation->ApplyRule('DateFrom', 'Required', T('Start Date is required.'));
		$Validation->ApplyRule('DateFrom', 'Date', T('Start Date must be a valid date, in "YYYY-MM-DD" format.'));

		// Validation rules for DateTo field
		$Validation->ApplyRule('DateTo', 'Required', T('Start Date is required.'));
		$Validation->ApplyRule('DateTo', 'Date', T('Start Date must be a valid date, in "YYYY-MM-DD" format.'));
	}

	/**
	 * This function is called when a request for Cron execution is received. It
	 * checks that the request is legitimate, based on several criteria.
	 *
	 * @param Sender The controller who is dispatching the Request.
	 *
	 * @return True if the request is authorized, False otherwise.
	 */
	protected function _IsRequestAuthorized($Sender) {
		$CronKey = (C('Plugin.CronJobs.CronKey') != '') ? C('Plugin.CronJobs.CronKey') : null;

		// Cron Key is optional, but it must match if it was set in configuration.
		if(isset($CronKey) &&
			 ($Sender->Request->Get(CRON_ARG_CRONKEY) != $CronKey)) {
			$Sender->SetData('CronExecResult', CRON_ERR_INVALID_CRONKEY);
			return false;
		}

		// The request must come from an authorized IP Address, unless the IP Address
		// list is empty
		$ValidIPAddresses = explode(';', C('Plugin.CronJobs.AllowedIPAddresses'));
		if(!empty($ValidIPAddresses)) {
			if(!ValidateIPAddressInList(RemoteIP(), $ValidIPAddresses)) {
				$Sender->SetData('CronExecResult', CRON_ERR_IPNOTALLOWED);

				return false;
			}
		}

		return true;
	}

	protected function _CheckExecutionLimits($Sender) {
		$LastRun = C('Plugin.CronJobs.LastRun');
		$Now = mktime();

		// Make sure that the Day Run Limit has not been exceeded
		if(date('Y-m-d', $Now) != date('Y-m-d', $LastRun)) {
			// If current Day is different from the Day of last execution, reset the
			// corresponding counter.
			SaveToConfig('Plugin.CronJobs.DayRuns', 0);
		}
		else {
			if(C('Plugin.CronJobs.DayRuns') >= intval(C('Plugin.CronJobs.MaxRunsPerDay'))) {
				$Sender->SetData('CronExecResult', CRON_ERR_DAYRUNEXCEEDED);
				return false;
			}
		}

		// Make sure that the Hour Run Limit has not been exceeded
		if(date('Y-m-d H', $Now) != date('Y-m-d H', $LastRun)) {
			// If current Hour is different from the Hour of last execution, reset the
			// corresponding counter.
			SaveToConfig('Plugin.CronJobs.HourRuns', 0);
		}
		else {
			if(C('Plugin.CronJobs.HourRuns') >= intval(C('Plugin.CronJobs.MaxRunsPerHour'))) {
				$Sender->SetData('CronExecResult', CRON_ERR_HOURRUNEXCEEDED);
				return false;
			}
		}

		// Make sure that the Minute Run Limit has not been exceeded
		if(date('Y-m-d H:i', $Now) != date('Y-m-d H:i', $LastRun)) {
			// If current Minute is different from the Minute of last execution, reset the
			// corresponding counter.
			SaveToConfig('Plugin.CronJobs.MinuteRuns', 0);
		}
		else {
			if(C('Plugin.CronJobs.MinuteRuns') >= intval(C('Plugin.CronJobs.MaxRunsPerMinute'))) {
				$Sender->SetData('CronExecResult', CRON_ERR_MINUTERUNEXCEEDED);
				return false;
			}
		}

		return true;
	}

	/**
	 * Reset all the counters used to throttle Cron Executions.
	 *
	 * @param Form The form which will be used to save the new values.
	 *
	 * @return void.
	 */
	protected function _ResetCronExecutionCounters($Form) {
		// TODO Move Cron Execution Counters to a database table
		SaveToConfig('Plugin.CronJobs.DayRuns', 0);
		SaveToConfig('Plugin.CronJobs.HourRuns', 0);
		SaveToConfig('Plugin.CronJobs.MinuteRuns', 0);
	}

	/**
	* Plugin constructor
	*
	* This fires once per page load, during execution of bootstrap.php. It is a decent place to perform
	* one-time-per-page setup of the plugin object. Be careful not to put anything too strenuous in here
	* as it runs every page load and could slow down your forum.
	*/
	public function __construct() {
		parent::__construct();

	}

	/**
	* Base_Render_Before Event Hook
	*
	* This is a common hook that fires for all controllers (Base), on the Render method (Render), just
	* before execution of that method (Before). It is a good place to put UI stuff like CSS and Javascript
	* inclusions. Note that all the Controller logic has already been run at this point.
	*
	* @param $Sender Sending controller instance
	*/
	public function Base_Render_Before($Sender) {
		$Sender->AddCssFile('cronjobs.css', 'plugins/CronJobs/css');
	}

	public function PluginController_CronJobs_Create($Sender) {
		// Initialize Model for Cron Jobs List
		$this->CronJobsList = new CronJobsListModel();

		/*
		* If you build your views properly, this will be used as the <title> for your page, and for the header
		* in the dashboard. Something like this works well: <h1><?php echo T($this->Data['Title']); ?></h1>
		*/
		$Sender->Title('Cron Jobs');
		$Sender->AddSideMenu('plugin/cronjobs');

		// Prepare form for sub-pages
		$Sender->Form = new Gdn_Form();

		// Fire the Event that allows plugins to Register their Cron Jobs
		$this->FireEvent('CronJobRegister');

		// Forward the call to the appropriate method.
		$this->Dispatch($Sender, $Sender->RequestArgs);
	}

	/**
	 * Default Controller. It opens Plugin's Settings Page.
	 */
	public function Controller_Index($Sender) {
		// Prevent non-admins from accessing this page
		$Sender->Permission('Vanilla.Settings.Manage');

		$Sender->AddJsFile('jquery.autocomplete.js');
		$Sender->AddJsFile('settings.js', 'plugins/CronJobs/js');

		$Sender->SetData('PluginDescription', $this->GetPluginKey('Description'));
		$Sender->SetData('CurrentPath', CRON_SETTINGS_URL);

		$Validation = new Gdn_Validation();
		$this->_SetConfigModelValidationRules($Validation);

		$ConfigurationModel = new Gdn_ConfigurationModel($Validation);
		$ConfigurationModel->SetField(array(
			'Plugin.CronJobs.AllowedIPAddresses' => CRON_DEFAULT_ALLOWEDIPADDRESSES,
			'Plugin.CronJobs.MaxRunsPerMinute' => CRON_DEFAULT_MAXRUNSPERMINUTE,
			'Plugin.CronJobs.MaxRunsPerHour' => CRON_DEFAULT_MAXRUNSPERHOUR,
			'Plugin.CronJobs.MaxRunsPerDay' => CRON_DEFAULT_MAXRUNSPERDAY,
			'Plugin.CronJobs.LastRun' => CRON_DEFAULT_LASTRUN,
			'Plugin.CronJobs.MinuteRuns' => CRON_DEFAULT_MINUTERUNS,

			// TODO Move Cron Execution Counters to a database table
			'Plugin.CronJobs.HourRuns' => CRON_DEFAULT_HOURRUNS,
			'Plugin.CronJobs.DayRuns' => CRON_DEFAULT_DAYRUNS,
			'Plugin.CronJobs.CronKey' => CRON_DEFAULT_CRONKEY,
			'Plugin.CronJobs.CronUser' => '',
		));

		// Set the model on the form.
		$Sender->Form->SetModel($ConfigurationModel);

		// If seeing the form for the first time...
		if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
			// Apply the config settings to the form.
			$Sender->Form->SetData($ConfigurationModel->Data);
		}
		else {
			$Saved = $Sender->Form->Save();

			if ($Saved) {
				$Sender->InformMessage(T('Your changes have been saved.'));
			}
		}

		// GetView() looks for files inside plugins/PluginFolderName/views/ and returns their full path. Useful!
		$Sender->Render($this->GetView('cronjobs_settings_view.php'));
	}

	/**
	 * Displays a page showing all Cron Jobs registered by plugins.
	 */
	public function Controller_Jobs($Sender) {
		// Prevent Users without proper permissions from accessing this page.
		$Sender->Permission('Plugins.CronJobs.Manage');
		$Sender->SetData('CurrentPath', CRON_REGISTERED_JOBS_URL);

		$Validation = new Gdn_Validation();
		//$this->_SetConfigModelValidationRules($Validation);

		// Set the model on the form.
		//$Sender->Form->SetModel($ConfigurationModel);

		// If seeing the form for the first time...
		if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
			// Apply the config settings to the form.
			//$Sender->Form->SetData($ConfigurationModel->Data);
		}
		else {
			//var_dump($Sender->Form->FormValues());

			// TODO Handle saving of some settings (e.g. Enable/Disable Job) on the page
			//$Saved = $Sender->Form->Save();
			//
			//if ($Saved) {
			//	$Sender->StatusMessage = T('Your changes have been saved.');
			//}
		}

		$Sender->SetData('CronJobsDataSet', $this->CronJobsList->Get());

		$Sender->Render($this->GetView('cronjobs_jobs_view.php'));
	}

	/**
	 * Executes all the Registered Cron Jobs. This method can be called manually,
	 * by simply accessing the URL (e.g. http://myserver/vanilla/plugin/cron), or
	 * through a "wget" scheduled to run using Linux Cron or Windows Scheduler.
	 */
	public function Controller_Cron($Sender) {
		// Check that request is legitimate and that Cron execution limits
		// have not been exceeded.
		if($this->_IsRequestAuthorized($Sender) &&
			 $this->_CheckExecutionLimits($Sender)) {
			$Sender->SetData('CurrentPath', CRON_EXEC_URL);

			// Save time of execution, which will be used for throttling.
			SaveToConfig('Plugin.CronJobs.LastRun', mktime());

			// TODO Move Cron Execution Counters to a database table
			// Increment counters
			SaveToConfig('Plugin.CronJobs.MinuteRuns', intval(C('Plugin.CronJobs.MinuteRuns')) + 1);
			SaveToConfig('Plugin.CronJobs.HourRuns', intval(C('Plugin.CronJobs.HourRuns')) + 1);
			SaveToConfig('Plugin.CronJobs.DayRuns', intval(C('Plugin.CronJobs.DayRuns')) + 1);

			$this->_ProcessCronJobs();
			$Sender->SetData('CronExecResult', CRON_EXEC_SUCCESS);
		}

		// If User is logged in and has proper permission, it returns a properly
		// formatted page with some details about the Cron run.
		$Session = Gdn::Session();
		if ($Session->IsValid() && $Session->CheckPermission('Plugins.CronJobs.Manage')) {
			$Sender->Render($this->GetView('cronjobs_cronresult_view.php'));
			return;
		}


		// If User is not logged in, or doesn't have proper permissions, it just
		// returns a page with a simple message (OK is successful, Forbidden is not
		// allowed, or if request has been throttled.
		if($Sender->Data['CronExecResult'] == CRON_EXEC_SUCCESS) {
			// Prepare the feedback page.
			$Sender->MasterView = 'none';
			$Sender->Render($this->GetView('cronjobs_cronresult_view_anonymous.php'));
		}
		else {
			throw new Exception(T('Unauthorized'), 401);
		}
	}

	/**
	 * Shows a page that allows to view the History of Cron Jobs Executions in
	 * a period of time.
	 */
	public function Controller_History($Sender) {
		// Prevent Users without proper permissions from accessing this page.
		$Sender->Permission('Plugins.CronJobs.Manage');

		$Sender->SetData('CurrentPath', CRON_HISTORY_URL);

		// If seeing the form for the first time...
		if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
			// Just show the form with the default values

			// Default DateFrom is today
			$Sender->Form->SetFormValue('DateFrom', date('Y-m-d'));
			// Default DateTo is today
			$Sender->Form->SetFormValue('DateTo', date('Y-m-d'));
		}
		else {
			// Else, validate submitted data.
			$Validation = new Gdn_Validation();

			$this->_SetCronJobsHistoryValidationRules($Validation);

			$FormValues = $Sender->Form->FormValues();

			$Validation->Validate($FormValues);
			$Sender->Form->SetValidationResults($Validation->Results());

			if(!$Sender->Form->ErrorCount()){
				$DateFrom = $Sender->Form->GetFormValue('DateFrom');
				$DateTo = $Sender->Form->GetFormValue('DateTo');

				$CronJobsHistoryDataSet = $this->GetCronJobsHistoryModel()->Get($DateFrom, $DateTo)->Result();

				$Sender->SetData('CronJobsHistoryDataSet', $CronJobsHistoryDataSet);
			}
		}

		// GetView() looks for files inside plugins/PluginFolderName/views/ and returns their full path. Useful!
		$Sender->Render($this->GetView('cronjobs_history_view.php'));
	}

	/**
	 * Shows a page that displays the Status of Cron Jobs Throttling Counters and
	 * allows to reset them.
	 */
	public function Controller_Status($Sender) {
		// Prevent Users without proper permissions from accessing this page.
		$Sender->Permission('Plugins.CronJobs.Manage');
		$Sender->SetData('CurrentPath', CRON_STATUS_URL);

		// If seeing the form for the first time...
		if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
			// Just show the form with the default values
		}
		else {
			// No validation is needed on submit, the only possible action is
			// "Reset Counters"

			// Reset Cron Execution Counters
			$this->_ResetCronExecutionCounters($Sender->Form);

			$Sender->InformMessage(T('Counters have been reset.'));
		}

		// GetView() looks for files inside plugins/PluginFolderName/views/ and returns their full path. Useful!
		$Sender->Render($this->GetView('cronjobs_status_view.php'));
	}

	/**
	* Add a link to the dashboard menu
	*
	* By grabbing a reference to the current SideMenu object we gain access to its methods, allowing us
	* to add a menu link to the newly created /plugin/CronJobs method.
	*
	* @param $Sender Sending controller instance
	*/
	public function Base_GetAppSettingsMenuItems_Handler($Sender) {
		$Menu = $Sender->EventArguments['SideMenu'];
		$Menu->AddLink('Add-ons', 'Cron Jobs', 'plugin/cronjobs', 'Garden.Settings.Manage');
	}

	/**
	 * This function is called by 3rd party plugins which want to register a Cron
	 * Job. They will have to implement a method called Cron(), which will be
	 * called by this plugin. Note that it's not possible to register more than
	 * one Cron method per Class.
	 *
	 * @param $Object The instance of the Object that wants to be registered for
	 * Cron.
	 *
	 * @Return True if registration was successful, False otherwise.
	 */
	public function RegisterCronJob($Object) {
		return $this->CronJobsList->Add($Object);
	}

	/**
	 * Processes the list of Cron Jobs, executing them one by one.
	 */
	protected function _ProcessCronJobs() {
		// Run Cron jobs as the user specified in the configuration
		if(!Gdn::Session()->IsValid()) {
			$CronStartedSession = true;
			$CronUser = C('Plugin.CronJobs.CronUser');
			$CronUserID = GetValue('UserID', Gdn::UserModel()->GetByUsername($CronUser));

			if(!empty($CronUserID)) {
				Gdn::Session()->Start($CronUserID);
			}
		}

		foreach ($this->CronJobsList->Get() as $Class => $Object) {
			// This check uses a global Validation function. A Validator is not
			// used here as it would be overkill.
			// NOTE: this is actually a second check, just to be on the safe side.
			// The presence of a public Cron() method is also verified when Objects
			// register to the Cron List.
			if(!ObjectImplementsCron($Object)) {
				// TODO Use Logger to log the fact that an Object registered for Cron doesn't have a Cron method.
				// If Object doesn't implement Cron(), just ignore it.
				continue;
			}

			// Method _RunCronJob returns the details of the Job execution as an
			// instance of CronJobExecutionDataModel. This information could be used for
			// debugging and other purposes.
			$this->_RunCronJob($Object);
		}

		// If a session was started for Cron purposes, terminate it
		if(!empty($CronStartedSession)) {
			Gdn::Session()->End();
		}
	}

	/**
	 * Executes the Cron() method of the object passed as a parameter.
	 *
	 * @param $Object The Object implementing the Cron() method to be called.
	 *
	 * @return TRUE if Object's Cron ran successfully, FALSE otherwise.
	 */
	protected function _RunCronJob($Object) {
		$CronExecData = new CronJobExecutionDataModel(get_class($Object), date('Y-m-d H:i:s'));
		$this->FireEvent('BeforeCronJobExecute');

		try {
			$JobResult = call_user_func(array($Object, 'Cron'));

			$CronExecData->SetResultData(CRON_EXEC_SUCCESS, T('Success'));
		}
		catch (Exception $e) {
			// Catch any unhandled error and save its details to allow logging it to
			// Cron Job Execution History.
			$CronExecData->SetResultData($e->getCode(), sprintf(T('Failure. Exception details: %s.'),
																													$e->getMessage()));
		}
		$CronExecData->SetFinishedAt(date('Y-m-d H:i:s'));

		$this->EventArguments['CronExecData'] = &$CronExecData;
		$this->FireEvent('AfterCronJobExecute');

		return $CronExecData;
	}

	/**
	 * Handler for event CronJobsPlugin_BeforeCronJobExecute. Used for debugging
	 * purposes only.
	 */
	public function CronJobsPlugin_BeforeCronJobExecute_Handler($Sender){
	}

	/**
	 * Handler for event CronJobsPlugin_AfterCronJobExecute. Used for logging
	 * execution results and for debugging purposes.
	 */
	public function CronJobsPlugin_AfterCronJobExecute_Handler($Sender){
		$CronExecData = $Sender->EventArguments['CronExecData'];

		$InsertResult = $this->GetCronJobsHistoryModel()->Insert($CronExecData);
	}

	/**
	* Plugin setup
	*
	* This method is fired only once, immediately after the plugin has been enabled in the /plugins/ screen,
	* and is a great place to perform one-time setup tasks, such as database structure changes,
	* addition/modification of config file settings, filesystem changes, etc.
	*/
	public function Setup() {
		// Set up the plugin's default values
		SaveToConfig('Plugin.CronJobs.AllowedIPAddresses', CRON_DEFAULT_ALLOWEDIPADDRESSES);
		SaveToConfig('Plugin.CronJobs.MaxRunsPerMinute', CRON_DEFAULT_MAXRUNSPERMINUTE);
		SaveToConfig('Plugin.CronJobs.MaxRunsPerHour', CRON_DEFAULT_MAXRUNSPERHOUR);
		SaveToConfig('Plugin.CronJobs.MaxRunsPerDay', CRON_DEFAULT_MAXRUNSPERDAY);
		SaveToConfig('Plugin.CronJobs.LastRun', CRON_DEFAULT_LASTRUN);
		SaveToConfig('Plugin.CronJobs.MinuteRuns', CRON_DEFAULT_MINUTERUNS);
		SaveToConfig('Plugin.CronJobs.HourRuns', CRON_DEFAULT_HOURRUNS);
		SaveToConfig('Plugin.CronJobs.DayRuns', CRON_DEFAULT_DAYRUNS);
		SaveToConfig('Plugin.CronJobs.CronKey', CRON_DEFAULT_CRONKEY);

		// Create Route to redirect calls to /discussions to /listdiscussions
		Gdn::Router()->SetRoute(CRON_EXEC_ROUTE_REGEX,
														CRON_EXEC_URL . '/?$1',
														'Internal');

		// Create Database Objects needed by the Plugin
		require('install/cronjobs.schema.php');
		CronJobsSchema::Install();
	}

	/**
	 * Cleanup operations to be performend when the Plugin is disabled, but not
	 * permanently removed.
	 */
	public function OnDisable() {
		// Remove the Routes created by the Plugin.
		Gdn::Router()->DeleteRoute(CRON_EXEC_ROUTE_REGEX);
	}

	/**
	* Plugin cleanup
	*
	* This method is fired only once, when the plugin is removed, and is a great place to
	* perform cleanup tasks such as deletion of unsued files and folders.
	*/
	public function CleanUp() {
		// Remove Plugin's configuration parameters
		RemoveFromConfig('Plugin.CronJobs.AllowedIPAddresses');
		RemoveFromConfig('Plugin.CronJobs.MaxRunsPerMinute');
		RemoveFromConfig('Plugin.CronJobs.MaxRunsPerHour');
		RemoveFromConfig('Plugin.CronJobs.MaxRunsPerDay');
		RemoveFromConfig('Plugin.CronJobs.LastRun');
		RemoveFromConfig('Plugin.CronJobs.MinuteRuns');
		RemoveFromConfig('Plugin.CronJobs.HourRuns');
		RemoveFromConfig('Plugin.CronJobs.DayRuns');
		RemoveFromConfig('Plugin.CronJobs.CronKey');

		// Drop Database Objects created by the Plugin
		require('install/cronjobs.schema.php');
		CronJobsSchema::Uninstall();
	}

	/**
	 * Implements Cron method, which will be run automaticall by Cron Plugin. This
	 * will allow the plugin to register itself for Cron execution and perform
	 * tasks on a regular basis.
	 */
	public function Cron() {
		// TODO Add regular maintenance tasks.
	}

	/**
	 * Register plugin for Cron Jobs.
	 */
	public function CronJobsPlugin_CronJobRegister_Handler($Sender){
		$Sender->RegisterCronJob($this);
	}
}
