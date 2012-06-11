<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2012 Diego Zanella IT Services
This file is part of Cron Plugin for Vanilla Forums.

Cron Plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Cron Plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Cron Plugin.  If not, see <http://www.gnu.org/licenses/>.

Contact Diego Zanella at diego [at] pathtoenlightenment [dot] net
*/

require('plugin.schema.php');

class CronJobsSchema extends PluginSchema {
	protected $Database;
	protected $Construct;

	/**
	 * Drops and recreates an Index on the specified Table. If Index doesn't exist,
	 * it simply creates it.
	 *
	 * @param TableName The name of the table where the index will be (re)created.
	 * Name must not contain database prefix, it will be added automatically.
	 * @param IndexName The name of the index to (re)create.
	 * @param Fields Array containing the names of the fields to be added to the
	 * Index. Clauses such as ASC or DESC can be specified for each entry (e.g.
	 * array('MyField ASC', 'MyOtherField DESC')).
	 *
	 * @return void
	 */
	protected function CreateIndex($TableName, $IndexName, $Fields) {
		$Px = $this->Database->DatabasePrefix;

		// Attempt to drop index. If Index doesn't exist, an exception will be
		// raised, but it can just be hidden.
		$Sql = "ALTER TABLE `{$Px}$TableName`\n" .
					"DROP INDEX `$IndexName`;\n";
		try {
			$this->Construct->Query($Sql);
		}
		catch(Exception $e) {
			// Nothing to be done.
		}

		// Recreate the Index with the new fields
		$Sql = "ALTER TABLE `{$Px}$TableName`\n" .
					"ADD INDEX `$IndexName` (" . implode(', ', $Fields) . ");\n";
		$this->Construct->Query($Sql);
	}

	/**
	 * Create the table which will store the History of Cron Executions.
	 */
	public function create_cronjobshistory_table() {
		$Px = $this->Database->DatabasePrefix;
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
	public function create_cronjobslist_table() {
		$Px = $this->Database->DatabasePrefix;
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
