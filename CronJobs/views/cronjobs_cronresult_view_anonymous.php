<?php	if (!defined('APPLICATION')) exit();
/**
{licence}
*/

// This page will be rendered when an anonymous User calls plugin's Cron
// method successfully. It just displays "OK", as an anonymous User shouldn't
// be given too much information.
echo T('OK');
?>
