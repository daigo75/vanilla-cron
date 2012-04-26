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
 * Validation functions for Cron Plugin Plugin.
 *
 * @package CronPlugin
 */

// TODO Export all these functions to an independent plugin, to allow other plugins to use them.

if(!function_exists('ValidateIPAddress')){
	/**
	 * Validates a string representing an IP Address.
	 */
	function ValidateIPAddress($Value, $Field, $FormPostedValues){
		return filter_var($Value, FILTER_VALIDATE_IP);
	}
}

if(!function_exists('ValidateIPAddressList')){
	/**
	 * Validates a string representing a list IP Addresses. The addresses are
	 * expected to be separated by a semicolon.
	 */
	function ValidateIPAddressList($Value, $Field, $FormPostedValues){
		$IPAddresses = split(';', $Value);

		foreach($IPAddresses as $IPAddress) {
			// No need to continue if even just one IP Address is wrong. Let the
			// User review the data he entered.
			if(!ValidateIPAddress($IPAddress)) {
				return false;
			}
		}
		return true;
	}
}

if(!function_exists('ValidateIPAddressInList')){
	/**
	 * Checks if an IP Address exists in a list of IP Addresses.
	 *
	 * @param IPAddress The IP Address to verify.
	 * @param IPAddressList The List against which the verification will be made.
	 *
	 * @return True if the IP Address exists in the List, False otherwise.
	 */
	function ValidateIPAddressInList($IPAddress, $IPAddressList){
		foreach($IPAddressList as $ValidIPAddress) {
			if(ip2long($IPAddress) == ip2long($ValidIPAddress)) {
				return true;
			}
		}
		return false;
	}
}

if(!function_exists('ObjectImplementsCron')){
	/**
	 * Checks if an Object implements Cron() method.
	 *
	 * @param Object The object which should be implementing a Cron() method.
	 *
	 * @return True if Object has a Cron method, False otherwise.
	 */
	function ObjectImplementsCron(&$Object){
		return method_exists($Object, 'Cron');
	}
}
