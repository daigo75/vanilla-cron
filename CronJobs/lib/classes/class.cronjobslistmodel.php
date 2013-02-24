<?php if (!defined('APPLICATION')) exit();
/**
 * Copyright 2012 Diego Zanella
 * This file is part of CronJobs Plugin for Vanilla Forums.
 *
 * CronJobs Plugin is free software: you can redistribute it and/or
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

/**
 * Cron Jobs List Model
 *
 * @package CronPlugin
 */

/**
 * Allows to read/write Cron Jobs List table, which contains a list of the Cron
 * Jobs registered by Plugins and configured in the system.
 */
class CronJobsListModel extends Gdn_Model {
	/**
	 * An associative array of Objects registered for Cron. For each entry, the
	 * Key is Object's Class, the Value is the Object instance itself.
	 */
	protected $CronJobs = array();

	/**
	 * Set Validation Rules that apply when saving a new row in Cron Jobs History.
	 *
	 * @return void
	 */
	protected function _SetCronJobsListValidationRules() {
		// Set additional Validation Rules here. Please note that formal validation
		// is done automatically by base Model Class, by retrieving Schema
		// Information.
	}

	/**
	 * Defines the related database table name.
	 */
	public function __construct() {
		parent::__construct('CronJobsList');

		$this->_SetCronJobsListValidationRules();
	}

	/**
	 * Modifies standard Gdn_Model->Get to use CronJobsListQuery.
	 *
	 * @return An array of the classes who registerd a Cron job.
	 */
	public function Get() {
		return $this->CronJobs;
	}

	/*
	 * Adds an object to the list of the objects registered for Cron Jobs.
	 *
	 * @param $Object An Object which should be registered for Cron execution.
	 * Such Object must implement a Cron() method.
	 *
	 * @return True if Object was registered successfully, False otherwise.
	 */
	public function Add(&$Object) {
		// Null Objects can't be registered for obvious reasons, and should never
		// be passed to this function (hence the Exception).
		if(!is_object($Object)) {
			throw new InvalidArgumentException(T('Parameter $Object must be an Object.'), CRON_ERR_NOT_AN_OBJECT);
			return false;
		}

		// Throw an exception if an Object which doesn't have a public Cron method
		// attempts to register itself for Cron.
		if(!ObjectImplementsCron($Object)) {
			throw new InvalidArgumentException(T('Parameter $Object doesn\'t have a public Cron() method.'), CRON_ERR_CRON_METHOD_UNDEFINED);
			return false;
		}

		// Object's class is used for reference.
		$Class = get_class($Object);

		// Register the object using its class as a key. This will prevent duplicate
		// registrations.
		$this->CronJobs[get_class($Object)] = &$Object;
		return true;
	}
}
