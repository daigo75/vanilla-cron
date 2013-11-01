<?php if (!defined('APPLICATION')) exit();
/**
{licence}
*/


/**
 * Validation functions for Cron Plugin.
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
		$IPAddresses = explode(';', $Value);

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

if (!function_exists('ValidatePositiveInteger')) {
		/**
		 * Check that a value is a positive Integer.
		 */
		function ValidatePositiveInteger($Value, $Field) {
			return ValidateInteger($Value, $Field) &&
						 ($Value > 0);
		}
}
