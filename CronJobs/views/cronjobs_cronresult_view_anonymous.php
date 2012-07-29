<?php	if (!defined('APPLICATION')) exit();
/**
 * Copyright 2012 Diego Zanella
 * This file is part of CronJobs Plugin for Vanilla Forums.
 *
 * Plugin is free software: you can redistribute it and/or
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

// This page will be rendered when an anonymous User calls plugin's Cron
// method successfully. It just displays "OK", as an anonymous User shouldn't
// be given too much information.
echo T('OK');
?>
