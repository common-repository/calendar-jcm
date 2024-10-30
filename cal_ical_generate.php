<?		
$filename_list = '/home/mysite/public_html/calendar/calendars/my_blog.ics';
$content_list = "";
$content_list = "BEGIN:VCALENDAR\n";
$content_list .= "VERSION:2.0\n";
$content_list .= "X-WR-CALNAME:My Great Site\n";
$content_list .= "PRODID:-//Apple Computer\, Inc//iCal 1.5//EN\n";
$content_list .= "X-WR-RELCALID:218A623C-C9F9-11D8-A1D4-003065E55000\n";
$content_list .= "X-WR-TIMEZONE:US/Eastern\n";
$content_list .= "CALSCALE:GREGORIAN\n";
$content_list .= "METHOD:PUBLISH\n";
$content_list .= "BEGIN:VTIMEZONE\n";
$content_list .= "TZID:US/Eastern\n";
$content_list .= "LAST-MODIFIED:20040629T182125Z\n";
$content_list .= "BEGIN:DAYLIGHT\n";
$content_list .= "DTSTART:20040404T070000\n";
$content_list .= "TZOFFSETTO:-0400\n";
$content_list .= "TZOFFSETFROM:+0000\n";
$content_list .= "TZNAME:EDT\n";
$content_list .= "END:DAYLIGHT\n";
$content_list .= "BEGIN:STANDARD\n";
$content_list .= "DTSTART:20041031T020000\n";
$content_list .= "TZOFFSETTO:-0500\n";
$content_list .= "TZOFFSETFROM:-0400\n";
$content_list .= "TZNAME:EST\n";
$content_list .= "END:STANDARD\n";
$content_list .= "END:VTIMEZONE\n";
		
$events = $wpdb->get_results("SELECT * FROM calendarCG");
if($events) {
	$bgc = "#ffffff";
	foreach($events as $events) {
		//$type_name = $events->type_name;
		$uid_gen = "$events->uid";
		$date_start = "$events->date_start";
		$date_end = "$events->date_end";
		$time_start = "$events->time_start";
		$time_end = "$events->time_end";
		$title = "$events->title";
		$description = "$events->description";
		$event_type = "$events->event_type";
		$maxReservations = "$events->maxReservations";
		$moreContent = "$events->moreContent";
		
		$description = ereg_replace("\r", "", $description);
		$description = ereg_replace("\n", "\\n", $description);
		
		//*********************************************
		// parse dates/times for display
			
		$arr_date_start = explode("-",$date_start);
		$syear = $arr_date_start[0];
		$smonth = $arr_date_start[1];
		$sday = $arr_date_start[2];
		$link_date = $syear . $smonth . $sday;
			 
		$arr_date_end = explode("-",$date_end);
		$eyear = $arr_date_end[0];
		$emonth = $arr_date_end[1];
		$eday = $arr_date_end[2];	
		
		$arr_time_start = explode(":",$time_start);
		$shour = $arr_time_start[0];
		$sminute = $arr_time_start[1];
		
		$arr_time_end = explode(":",$time_end);
		$ehour = $arr_time_end[0];
		$eminute = $arr_time_end[1];
		
		if( $date_end=="0000-00-00" ){
			//$date_end = "$date_start";
		} 
		
		$duration_event = $ehour - $shour;
		//$duration_event_min = $eminute - $sminute;
		if($duration_event<0){
			$duration_event = 1;
		}
			
		$str_start = "$date_start"; 
		$timestamp_start = strtotime($str_start);
		$start_display_date = date('Ymd', $timestamp_start);
		$start_display = $start_display_date . "T" . $shour . $sminute;

		$str_end = "$date_end"; 
		$timestamp_end = strtotime($str_end);
		$end_display_date = date('Ymd', $timestamp_end);
		$end_display = $end_display_date . "T" . $ehour . $eminute;

		$TZID = ($syear . $smonth . $sday . "T" . $shour . $sminute . "00" );
		//print($shour);
			
		$content_list .= "BEGIN:VEVENT\n";
		if($shour=="00"&&$ehour=="00"){ // is all day
			$content_list .= "DTSTART;VALUE=DATE:" . $start_display_date . "\n";
			$content_list .= "DTEND;VALUE=DATE:" . $start_display_date+1 . "\n";
		} else {
			$content_list .= "DTSTART;TZID=US/Eastern:" . $TZID . "\n";
		}
		$content_list .= "SUMMARY:$title\n";
		$content_list .= "UID:218A5324-C9F9-11D8-A1D4-003065E55000$uid_gen\n";
		$content_list .= "SEQUENCE:1\n";
		$content_list .= "URL;VALUE=URI:\n";
		$content_list .= "DTSTAMP:20040629T182117Z\n";
		$content_list .= "DESCRIPTION:$description\n";
		
		// HANDLE RECURRING EVENTS
		if($event_type==1){ // WEEKLY
			if($date_end=="0000-00-00"){
				$content_list .= "RRULE:FREQ=WEEKLY;INTERVAL=1;BYWEEKDAY=" . $sday . "\n";
			} else {
				$content_list .= "RRULE:FREQ=WEEKLY;INTERVAL=1;UNTIL=" . $end_display_date . "\n";
			}
		}
		if($event_type==2){ // BIWEEKLY
			if($date_end=="0000-00-00"){
				$content_list .= "RRULE:FREQ=WEEKLY;INTERVAL=2;BYWEEKDAY=" . $sday . "\n";
			} else {
				$content_list .= "RRULE:FREQ=WEEKLY;INTERVAL=2;UNTIL=" . $end_display . ";BYWEEKDAY=" . $sday . "\n";
			}
		}
		if($event_type==3){ // MONTHLY
			if($date_end=="0000-00-00"){
				$content_list .= "RRULE:FREQ=MONTHLY;INTERVAL=1;BYMONTHDAY=" . $sday . "\n";
			} else {
				$content_list .= "RRULE:FREQ=MONTHLY;INTERVAL=1;UNTIL=" . $end_display . ";BYMONTHDAY=" . $sday . "\n";
			}
		}
		if($event_type==4){ // YEARLY
			if($date_end=="0000-00-00"){
				$content_list .= "RRULE:FREQ=YEARLY;INTERVAL=1;BYYEARDAY=" . $sday . "\n";
			} else {
				$content_list .= "RRULE:FREQ=YEARLY;INTERVAL=1;UNTIL=" . $end_display . ";BYYEARDAY=" . $sday . "\n";
			}
		}
		if($event_type==5){ // CUSTOM
			$maxReservations = $maxReservations + 1;
			if($maxReservations>4) $maxReservations = "-1";
			if($moreContent==0) $moreContent = "SU";
			if($moreContent==1) $moreContent = "MO";
			if($moreContent==2) $moreContent = "TU";
			if($moreContent==3) $moreContent = "WE";
			if($moreContent==4) $moreContent = "TH";
			if($moreContent==5) $moreContent = "FR";
			if($moreContent==6) $moreContent = "SA";
			if($date_end=="0000-00-00"){
				$content_list .= "RRULE:FREQ=MONTHLY;INTERVAL=1;BYDAY=" . $maxReservations . $moreContent . "\n";
			} else {
				$content_list .= "RRULE:FREQ=MONTHLY;INTERVAL=1;UNTIL=" . $end_display . ";BYDAY=" . $maxReservations . $moreContent . "\n";
			}
		}
		$content_list .= "DURATION:PT" . $duration_event . "H\n";
		$content_list .= "END:VEVENT\n";
	}
}
$content_list .= "END:VCALENDAR\n";
	
// ==============================
// WRITE FILE
// ==============================

// let's make sure the file exists and is writable
if (is_writable($filename_list)) {
	if (!$handle = fopen($filename_list, 'w')) {
		echo "Cannot open file ($filename_list)";
		exit;
    }
     // Write $content_list to our opened file.
     if (fwrite($handle, $content_list) === FALSE) {
       	echo "Cannot write to file ($filename_list)";
        exit;
    }
    //echo "Success, wrote to file ($filename_list)";
	fclose($handle);                 
 } else {
    //echo "The file $filename_list is not writable";
 }
?>