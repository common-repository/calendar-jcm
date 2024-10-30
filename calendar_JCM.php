<?php
/*
Plugin Name: Calendar JCM
Plugin URI: http://lestercat.net/wp
Description: Provides a nice interface for managing a calendart and outputs an .ical file, perfect for using something like PHP iCalendar to display calendar data on your site. 
Version: 0.1
Author: John Murden
Author URI: http://lestercat.net
*/

### Function:  Administration Menu
load_plugin_textdomain('calendar_JCM', 'wp-content/plugins/calendar_JCM');

### Function:  Administration Menu
add_action('admin_menu', 'calendars_menu');
function calendars_menu() {
	if (function_exists('add_menu_page')) {
		add_menu_page(__('Calendar', 'calendar_JCM'), __('Calendar', 'calendar_JCM'), '10', 'calendar_JCM/calendar-manager.php');
	}
	if (function_exists('add_submenu_page')) {
		add_submenu_page('calendar_JCM/calendar-manager.php', __('Manage Calendar', 'calendar_JCM'), __('Manage Calendar', 'calendar_JCM'), '8', 'calendar_JCM/calendar-manager.php');
		add_submenu_page('calendar_JCM/calendar-manager.php', __('Events Archive', 'calendar_JCM'), __('Events Archive', 'calendar_JCM'), '8', 'calendar_JCM/calendar-archive.php');
	}
}
?>