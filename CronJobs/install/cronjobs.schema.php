<?php if (!defined('APPLICATION')) exit();
/**
{licence}
*/

require('plugin.schema.php');

class CronJobsSchema extends PluginSchema {
	/**
	 * Create the table which will store the History of Cron Executions.
	 */
	protected function create_cronjobshistory_table() {
		Gdn::Structure()
			->Table('CronJobsHistory')
			->PrimaryKey('CronJobsHistoryID')
			->Column('ClassName', 'varchar(100)', FALSE, 'index')
			->Column('StartedAt', 'datetime', FALSE)
			->Column('FinishedAt', 'datetime', FALSE)
			->Column('Result', 'int')
			->Column('ResultMessage', 'varchar(3000)')
			->Column('DateInserted', 'datetime', FALSE, 'index')
			// The User ID can be null if the Cron Execution is called by an automated
			// process, such as "wget", which doesn't log in first.
			->Column('InsertUserID', 'int', TRUE)
			->Set(FALSE, FALSE);

		// Create multi-column Indexes via SQL (not supported by Vanilla's 2.0 Database Class)
		// Create Index for TimeFrame
		$this->CreateIndex('CronJobsHistory', 'IX_TimeFrame', array('StartedAt',
																																'FinishedAt',));
	}

	/**
	 * Create the table which will store a list of configured Cron Jobs.
	 */
	protected function create_cronjobslist_table() {
		Gdn::Structure()
			->Table('CronJobsList')
			->PrimaryKey('CronJobsListID')
			->Column('ClassName', 'varchar(100)', FALSE, 'unique')
			->Column('Enabled', 'uint', FALSE, 'index')
			->Column('DateInserted', 'datetime', FALSE, 'index')
			->Column('InsertUserID', 'int')
			->Column('DateUpdated', 'datetime', FALSE, 'index')
			->Column('UpdateUserID', 'int')
			->Set(FALSE, FALSE);
	}

	/**
	 * Create all the Database Objects in the appropriate order.
	 */
	protected function CreateObjects() {
		$this->create_cronjobshistory_table();
		$this->create_cronjobslist_table();
	}

	/**
	 * Delete the Database Objects.
	 */
	protected function DropObjects() {
		$this->DropTable('CronJobsHistory');
		$this->DropTable('CronJobsList');
	}
}
