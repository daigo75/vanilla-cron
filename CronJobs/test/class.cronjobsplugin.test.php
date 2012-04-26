<?php
/*
Copyright 2012 Diego Zanella IT Services
This file is part of Cron Plugin for Vanilla Forums.

Cron Plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Cron Plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Cron Plugin.  If not, see <http://www.gnu.org/licenses/>.

Contact Diego Zanella at diego [at] pathtoenlightenment [dot] net
*/

// 1. Define the constants we need to get going.
define('APPLICATION', 'Vanilla');
define('DS', '/');

/*
	File Bootstrap.php, required to initialize Vanilla, is located in the root
	directory of Vanilla installation, which is three level up from current
	location.
*/
//define('PATH_ROOT', dirname(__FILE__) . '../../../');

/* IMPORTANT: Read if you're using a Symlink to point to plugin's directory!
 Comment the previous Define('PATH_ROOT') and uncomment the line below if you're
 using a Symlink to point to plugin's directory. Command dirname() resolves
 Symlinks, therefore, the resulting path for PATH_ROOT would be the physical
 location of the plugin. In such case, simply write the whole absolute path of
 your Vanilla Installation.

 Note: there are methods to deal with the issues generated by Symlinks, but, for
 testing, simply indicating an absolute path is simpler and faster.
*/

define('PATH_ROOT', 'C:\XAMPP\htdocs\vanilla');

var_dump(__FILE__);

// 2. Include the bootstrap to configure the framework.
require_once(PATH_ROOT . '/bootstrap.php');

class CronJobsPluginTests extends PHPUnit_Framework_TestCase {
	protected $CronJobsPlugin;

	/**
	 * Called when a method raises an unexpected exception, it builds an error
	 * message containing the details of such exception.
	 *
	 * @param MethodName The name of the method that generated the exception.
	 * @param e The Exception generated by the method.
	 *
	 * @return A string containing the details of the Exception generated by the
	 * method, or an error message if the arguments are not formally valid.
	 */
	protected function BuildExceptionMessage($MethodName, $e) {
		// Validate MethodName, it must be a string.
		if(!is_string($MethodName)) {
			return sprintf(T("Method %s->BuildExceptionMessage() called improperly. " .
											 "Parameter \$MethodName was expected to be a string, while it's a %s instead."),
											 get_class($this),
											 get_class($MethodName));
		}

		// This method should only be called within a "catch" section and should
		// receive an Exception. If not, it will handle the case gracefully to
		// inform the User that is has been misused.
		if(!($e instanceof Exception)) {
			return sprintf(T("Method %s->BuildExceptionMessage() called improperly. " .
											 "Parameter \$e was expected to be an Exception, while it's a %s instead.\n" .
											 "Backtracke: %s"),
											 get_class($this),
											 get_class($e),
											 print_r(debug_backtrace(), TRUE));
		}
		return sprintf(T("Method %s raised an unexpected Exception.\n Exception Details: %s.\nTrace: %s"),
									 $MethodName,
									 $e->__toString(),
									 $e->getTraceAsString());
	}

	/**
	 * Test Suite initialization.
	 */
	protected function setUp() {
		$this->CronJobsPlugin = new CronJobsPlugin();
	}

	/**
	 * Test Suite finalization and cleanup.
	 */
	protected function tearDown() {
		$this->CronJobsPlugin = null;
	}

	/**
	 * Sample test. Verify that internal Plugin variable has been initialized.
	 */
	public function testPluginSet() {
		$this->assertNotNull($this->CronJobsPlugin, T('Plugin has not been instantiated.'));
	}

	/**
	 * Attempt to register to the Cron Jobs list a null object.
	 *
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionCode CRON_ERR_NOT_AN_OBJECT
	 */
	public function testRegisterCronJob_NullObject() {
		$InvalidObject = null;
		$this->CronJobsPlugin->RegisterCronJob($InvalidObject);
	}

	/**
	 * Attempt to register to the Cron Jobs list an object which doesn't
	 * implement the required Cron() method.
	 *
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionCode CRON_ERR_CRON_METHOD_UNDEFINED
	 */
	public function testRegisterCronJob_CronMethodUndefined() {
		$InvalidObject = new stdClass();
		$this->CronJobsPlugin->RegisterCronJob($InvalidObject);
	}

	/**
	 * Attempt to register to the Cron Jobs list an object which implements
	 * the required Cron() as expected. For the purpose of this test, the
	 * internal CronJobsPlugin is used, as it already implements the required
	 * method. It wouldn't make much sense in real life to register the same
	 * object multiple times, however RegisterCronJob method uses object's
	 * class as a key, preventing duplicate registration.
	 *
	 */
	public function testRegisterCronJob_ExpectedObject() {
		$this->assertTrue($this->CronJobsPlugin->RegisterCronJob($this->CronJobsPlugin),
											T('Failed to register plugin for Cron.'));
	}

	/**
	 * Tests the execution of registered Cron Jobs.
	 */
	public function testExecuteJobs() {
		// TODO Test execution Cron Jobs by passing a single object. Expect a single result in the list.
	}
}