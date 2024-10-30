This is freely and openly released, but you use at your own risk. I encourage you to contact me with any fixes or issues or suggestions: John Murden at jmurden@lestercat.net.

----------------------------------------------


=== Plugin Name ===
Contributors: John Murden
Tags: calendar, ical
Requires at least: 2.2
Tested up to: 2.3
Stable tag: .1

This plugin outputs an .ical file, perfect for using something like PHP iCalendar to display calendar data on your site. 


== Installation ==

1) In cal_ical_generate.php, change the location/name of the .ics file to match the location of where the file needs to be/

2) Make sure that the .ics file at that location is writable.

3) In cal_ical_generate.php, change the X-WR-CALNAME on the 5th line of code below to reflect the name of your site.

4) Add this table to the database:

	CREATE TABLE calendarCG (
	  uid int(25) NOT NULL auto_increment,
	  date_start date default NULL,
	  date_end date default NULL,
	  time_start time default NULL,
	  time_end time default NULL,
	  title varchar(255) default NULL,
	  description longtext,
	  cost varchar(100) default NULL,
	  link varchar(255) default NULL,
	  link_display varchar(255) default NULL,
	  moreContent longtext,
	  maxReservations int(5) default NULL,
	  event_type int(11) default '0',
	  PRIMARY KEY  (uid),
	  UNIQUE KEY uid (uid),
	  KEY uid_2 (uid,date_start),
	  KEY maxReservations (maxReservations),
	  KEY event_type (event_type)
	);

5) Use something like http://phpicalendar.net/ to display the calendar or to convert the cal to RSS.