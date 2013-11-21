// JavaScript for Cron Jobs plugin settings page
jQuery(document).ready(function($) {
	// Enable user name autocomplete on selected inputs
	$('.User.Autocomplete').livequery(function() {
		$(this).autocomplete(
			gdn.url('/dashboard/user/autocomplete/'),
			{
				minChars: 2,
				multiple: false,
				selectFirst: true
			}
		);
	});
});
