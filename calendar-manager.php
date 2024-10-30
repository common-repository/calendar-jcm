<?php
### Add/Edit/Delete

$ACTION = $_POST['ACTION'];

$shour = $_POST['shour'];
$sminute = $_POST['sminute'];
$syear = $_POST['syear'];
$smonth = $_POST['smonth'];
$sday = $_POST['sday'];

$ehour = $_POST['ehour'];
$eminute = $_POST['eminute'];
$eyear = $_POST['eyear'];
$emonth = $_POST['emonth'];
$eday = $_POST['eday'];

$title = $_POST['title'];
$description = $_POST['description'];
$link = $_POST['link'];
$moreContent = $_POST['moreContent']; // reused, should be renamed
$maxReservations = $_POST['maxReservations'];  // reused, should be renamed
$event_type = $_POST['event_type'];

$uid = $_POST['uid'];

function check_for_select($first, $second){
	if($first==$second){
		echo(" SELECTED");
	}
}

function monthname($month_number){
	if($month_number==1) echo(">Jan");
	if($month_number==2) echo(">Feb");
	if($month_number==3) echo(">Mar");
	if($month_number==4) echo(">Apr");
	if($month_number==5) echo(">May");
	if($month_number==6) echo(">Jun");
	if($month_number==7) echo(">Jul");
	if($month_number==8) echo(">Aug");
	if($month_number==9) echo(">Sep");
	if($month_number==10) echo(">Oct");
	if($month_number==11) echo(">Nov");
	if($month_number==12) echo(">Dec");
}

function check_for_checked(){

}

$date_start = $syear . "-" . $smonth . "-" . $sday;
$date_end = $eyear . "-" . $emonth . "-" . $eday;
$time_start = $shour . ":" . $sminute . ":00";
$time_end = $ehour . ":" . $eminute . ":00";
	
if($ACTION == "ADDNEW"){
	$add_event = $wpdb->query("INSERT INTO calendarCG VALUES ('', '$date_start', '$date_end', '$time_start', '$time_end', '$title', '$description', '$cost', '$link', '$link_display', '$moreContent','$maxReservations','$event_type')");
	if(!$add_event) {
		$msg="There was an error saving the event.";
	} else {
		$uid = mysql_insert_id();
		$msg="The event has been saved.<br>Make changes below or <a href='admin.php?page=calendar_JCM/calendar-manager.php'>click here to add a new event</a>.<br>";
		$ACTION = "MOD";
		include("cal_ical_generate.php");
	}	
}

if($ACTION == "UPDATE"){
	srand((double)microtime()*1000000); 
	$link = rand(1,50000);
	$edit_classified = $wpdb->query("UPDATE calendarCG SET date_start='$date_start', date_end='$date_end', time_start='$time_start', time_end='$time_end', title='$title', description='$description', event_type='$event_type', maxReservations='$maxReservations', moreContent='$moreContent', link='$link' WHERE uid = '$uid'");
	if(!$edit_classified) {
		$msg="There was an error saving the event.";
		$ACTION = "MOD";
	} else {
		$msg="The event has been saved.<br>Make changes below or <a href='admin.php?page=calendar_JCM/calendar-manager.php'>click here to add a new event</a>.<br>";
		$ACTION = "MOD";
		include("cal_ical_generate.php");
	}		
}	


if($ACTION == "MOD"){	
	$categories = $wpdb->get_results("SELECT * FROM calendarCG WHERE uid = '$uid'");
	if($categories) {
		foreach($categories as $categories) {
			//$type_name = $categories->type_name;
			//$type_uid = $categories->type_uid;
			$uid = "$categories->uid";
			$date_start = "$categories->date_start";
			$date_end = "$categories->date_end";
			$time_start = "$categories->time_start";
			$time_end = "$categories->time_end";
			$title = "$categories->title";
			$description = "$categories->description";
			$event_type = "$categories->event_type";
			$moreContent = "$categories->moreContent";
			$maxReservations = "$categories->maxReservations";
		}
		$modifycontent = "yes";
	}
}

$arr_date_start = explode("-",$date_start);
$syear = $arr_date_start[0];
$smonth = $arr_date_start[1];
$sday = $arr_date_start[2];
	 
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


if($ACTION == "DELETE"){
	$delete_classified = $wpdb->query("DELETE FROM calendarCG WHERE uid = '$uid'");
	if(!$delete_classified) {
		$msg="There was an error deleting the event.";
	} else {
		$msg="The event has been deleted.";
		include("cal_ical_generate.php");
		$uid = "";
		$date_start = "";
		$date_end = "";
		$time_start = "";
		$time_end = "";
		$title = "";
		$description = "";
		$event_type = "";
		$moreContent = "";
		$maxReservations = "";
	}
}


### Display Form to Add/Edit

?>
<div class="wrap">
<h2>Calendar Manager</h2>
			
<?
if($msg!=""){
	print($msg);
}

print("<div align='center'><table width='900' style='margin-left:12px;'>");
print("<tr><td valign='top' width='450'>");
	
if($ACTION == "MOD"){
	print("<h3>Edit this Event</h3>");
} else {
	print("<h3>Add an Event</h3>");
}
	
?>
	<form action="admin.php?page=calendar_JCM/calendar-manager.php" method="post" name="calendar">
		<table>
			<tr>
				<td align='right'>Title:</td>
				<td colspan='2'><input type="text" name="title" value="<? print($title); ?>" size="35" style="width:280px;"></td>
			</tr>
			<tr>
				<td align='right' valign='top'>Description:</td>
				<td colspan='2'><textarea name="description" cols="30" rows="10" style="width:280px;height:80px;"><? print(trim($description)); ?></textarea></td>
			</tr>
			<tr>
				<td align='right'>Date:</td>
				<td nowrap  colspan='2'>
					<select name='smonth'>
						<?
						$today = getdate();
						$thismonth = $today['mon'];
						if( $smonth == "" ){
							$smonth = $thismonth;
						}
						for($i=1;$i<=12;$i++){
							print("<option value='$i' ");
							check_for_select($i,$smonth);
							print(monthname($i) . " ($i)</option>");
						}
						?>
					</select>/<select name='sday'>
						<?
						$thisday = $today['mday'];
						if( $sday == "" ){
							$sday = $thisday;
						}
						for($i=1;$i<=31;$i++){
							print("<option value='$i' ");
							check_for_select($i,$sday);
							print(">$i</option>");
						}
						?>
					</select>/<select name='syear'>
						<?
						// make a select option for every year between next year and 2007
						
						$thisyear = $today['year'];
						$nextyear = $thisyear + 1;
						if( $syear == "" ){
							$syear = $thisyear;
						}
						for($i=$nextyear;$i>=2007;$i--){
							print("<option value='$i' ");
							check_for_select($i,$syear);
							print(">$i</option>");
						}
						?>
					</select>
				</td>
			</tr>
			
			<script type="text/javascript" language="javascript">
				function check_repeat(index_to_check){
					if(index_to_check==0){
						document.getElementById('frequency').style.display = 'none';
						document.getElementById('end_date').style.display = 'none'; 
					} else if(index_to_check==5){
						document.getElementById('frequency').style.display = 'table-row'; 
						document.getElementById('end_date').style.display = 'table-row'; 
					} else {
						document.getElementById('frequency').style.display = 'none'; 
						document.getElementById('end_date').style.display = 'table-row'; 
					}
				}
			</script>
			<tr>
				<td align='right'>Repeat:</td>
				<td colspan='2'>
					<select name='event_type' onchange='check_repeat(this.options[this.selectedIndex].value);'>
						<option value='0' <? check_for_select($event_type,0); ?>>never</option>
						<option value='1' <? check_for_select($event_type,1); ?>>weekly</option>
						<option value='2' <? check_for_select($event_type,2); ?>>biweekly</option>
						<option value='3' <? check_for_select($event_type,3); ?>>monthly</option>
						<option value='4' <? check_for_select($event_type,4); ?>>yearly</option>
						<option value='5' <? check_for_select($event_type,5); ?>>custom</option>
					</select>
				</td>
			</tr>
			<tr style='display:none;' id="frequency">
				<td align='right' style='background:#F7F2DC'>Frequency:</td>
				<td colspan='2' style='background:#F7F2DC'>
					<select name='maxReservations'>
						<option value='0' <? check_for_select($maxReservations,0); ?>>1st</option>
						<option value='1' <? check_for_select($maxReservations,1); ?>>2nd</option>
						<option value='2' <? check_for_select($maxReservations,2); ?>>3rd</option>
						<option value='3' <? check_for_select($maxReservations,3); ?>>4th</option>
						<option value='4' <? check_for_select($maxReservations,4); ?>>last</option>
					</select>
					<select name='moreContent'>
						<option value='0' <? check_for_select($moreContent,0); ?>>Sun</option>
						<option value='1' <? check_for_select($moreContent,1); ?>>Mon</option>
						<option value='2' <? check_for_select($moreContent,2); ?>>Tue</option>
						<option value='3' <? check_for_select($moreContent,3); ?>>Wed</option>
						<option value='4' <? check_for_select($moreContent,4); ?>>Thr</option>
						<option value='5' <? check_for_select($moreContent,5); ?>>Fri</option>
						<option value='6' <? check_for_select($moreContent,6); ?>>Sat</option>
					</select>
				</td>
			</tr>
			<tr style='display:none;' id="end_date">
				<td align='right'  style='background:#F7F2DC'>End Date:</td>
				<td nowrap colspan='2'  style='background:#F7F2DC'>
					<select name='emonth'>
						<option value='00'></option>
						<?
						for($i=1;$i<=12;$i++){
							print("<option value='$i' ");
							check_for_select($i,$emonth);
							print(monthname($i) . " ($i)</option>");
						}
						?>
					</select>/<select name='eday'>
						<option value='00'></option>
						<?
						for($i=1;$i<=31;$i++){
							print("<option value='$i' ");
							check_for_select($i,$eday);
							print(">$i</option>");
						}
						?>
					</select>/<select name='eyear'>
						<option value='0000'></option>
						<?
						// make a select option for every year between next year and 2007
						$today = getdate();
						$thisyear = $today['year'];
						$nextyear = $thisyear + 1;
						for($i=$nextyear;$i>=2007;$i--){
							print("<option value='$i' ");
							check_for_select($i,$eyear);
							print(">$i</option>");
						}
						?>
					</select>
				</td>
			</tr>
			<? if($event_type>0){ ?>
				<script type="text/javascript" language="javascript">
					check_repeat(6);
				</script>		
			<? } ?>
			<? if($event_type==5){ ?>
				<script type="text/javascript" language="javascript">
					check_repeat(5);
				</script>		
			<? } ?>
			
			<script type="text/javascript" language="javascript">
				function check_time_minutes(which_check, selected_val){
					if(which_check==1){
						// do nice minutes
						minutes = document.calendar.sminute.options.selectedIndex;
						if(selected_val!=0&&minutes==0){
							document.calendar.sminute.options.selectedIndex=1;
						} else if(selected_val==0){
							document.calendar.sminute.options.selectedIndex=0;
							document.calendar.ehour.options.selectedIndex=0;
							document.calendar.eminute.options.selectedIndex=0;
						}
					}
					if(which_check==2){
						// do nice minutes
						minutes = document.calendar.eminute.options.selectedIndex;
						if(selected_val!=0&&minutes==0){
							document.calendar.eminute.options.selectedIndex=1;
						} else if(selected_val=="00"){
							document.calendar.eminute.options.selectedIndex=0;
						}
						// check for start time
						shour = document.calendar.shour.options.selectedIndex;
						if(shour==0){
							end_hour = document.calendar.ehour.options.selectedIndex;
							document.calendar.shour.options.selectedIndex = end_hour-1;
							check_time_minutes(1);
						}
					}
					// check start is before end
					end_hour = document.calendar.ehour.options.selectedIndex;
					if(end_hour!=0){
						start_hour = document.calendar.shour.options.selectedIndex;
						if(start_hour>end_hour){
							alert("You can't have an event that finished before it starts!");
							document.calendar.ehour.focus();
						}
					}
					
				}
			</script>	
			<tr>
				<td align='right'>Start Time:</td>
				<td>
					<select name='shour' onchange='check_time_minutes(1,this.selectedIndex);'>
						<option value='00'></option>
						<? 
							for($i=1;$i<25;$i++){
								print("<option value='$i'");
								if($i==$shour) print("SELECTED");
								if($i<13){
									$j = "$i AM";
								} else {
									$j = $i - 12;
									$j = "$j PM";
								}
								if($j=="12 AM"){ 
									$j="12 PM";
								} else {
									if($j=="12 PM") $j="12 AM";
								}
								print(">$j </option>");
							} 
						?>
					</select>:<select name='sminute'>
						<option value='01'></option>
						<option value='00' <? check_for_select('00',$sminute); ?>>00</option>
						<option value='15' <? check_for_select('15',$sminute); ?>>15</option>
						<option value='30' <? check_for_select('30',$sminute); ?>>30</option>
						<option value='45' <? check_for_select('45',$sminute); ?>>45</option>
					</select> 
				</td>
				<td rowspan='2' style='border-left:1px solid #eeeeee;font-size:11px;color:#cccccc;' width='180'>Leave the time blank to set the event as "all day".</td>
			</tr>
			<tr>
				<td align='right'>End Time:</td>
				<td>
					<select name='ehour' onchange='check_time_minutes(2,this.options[this.selectedIndex].value);'>
						<option value='00'></option>
						<? 
							for($i=1;$i<25;$i++){
								print("<option value='$i'");
								if($i==$ehour) print("SELECTED");
								if($i<13){
									$j = "$i AM";
								} else {
									$j = $i - 12;
									$j = "$j PM";
								}
								if($j=="12 AM"){ 
									$j="12 PM";
								} else {
									if($j=="12 PM") $j="12 AM";
								}
								print(">$j </option>");
							} 
						?>
					</select>:<select name='eminute'>
						<option value='01'></option>
						<option value='00' <? check_for_select('00',$eminute); ?>>00</option>
						<option value='15' <? check_for_select('15',$eminute); ?>>15</option>
						<option value='30' <? check_for_select('30',$eminute); ?>>30</option>
						<option value='45' <? check_for_select('45',$eminute); ?>>45</option>
					</select> 
				</td>
			</tr>
			<tr>
				<td colspan='3' bgcolor='lightblue' align='right'>
					<?
					if($modifycontent == "yes"){
						?>
						 <input type="hidden" name="ACTION" value="UPDATE">
						 <input type="hidden" name="uid" value="<? print($uid); ?>">
						 <input type="submit" name="btn1" value="Save">
					<? } else { ?>
						 <input type="hidden" name="ACTION" value="ADDNEW">
						 <input type="submit" name="btn1" value="Save">
					<? }?>
				</td>	
			</tr>
		</table>
	</form>
	</td>
	<td valign='top'>
		<?
		### List Current 
		$today=date('Y-m-d');
		print("<h3>Current/Future Events</h3>");
		print("\n<table width='480' cellpadding='3' cellspacing='1' border='0'>\n");
		//WHERE DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= date_col;
		$categories = $wpdb->get_results("SELECT * FROM calendarCG WHERE ( (event_type=0 AND date_start>='$today' ) OR ((event_type>0 AND date_end='0000-00-00')OR(event_type>0 AND date_end>'$today')) ) ORDER BY event_type, date_start, time_start, title ASC");
		//$categories = $wpdb->get_results("SELECT * FROM calendarCG ORDER BY event_type, date_start, time_start, title ASC");
		if($categories) {
			$bgc = "#ffffff";
			$event_type_prev = "X";
			foreach($categories as $categories) {
				$uid = "$categories->uid";
				$date_start = "$categories->date_start";
				$date_end = "$categories->date_end";
				$time_start = "$categories->time_start";
				$time_end = "$categories->time_end";
				$title = "$categories->title";
				$description = "$categories->description";
				$event_type = "$categories->event_type";
				
				$maxReservations = "$categories->maxReservations";
				if($maxReservations=="0") $maxReservations="1st";
				if($maxReservations=="1") $maxReservations="2nd";
				if($maxReservations=="2") $maxReservations="3rd";
				if($maxReservations=="3") $maxReservations="4th";
				if($maxReservations=="4") $maxReservations="last";
				
				$moreContent = "$categories->moreContent";
				if($moreContent==0) $moreContent="Sun";
				if($moreContent==1) $moreContent="Mon";
				if($moreContent==2) $moreContent="Tue";
				if($moreContent==3) $moreContent="Wed";
				if($moreContent==4) $moreContent="Thu";
				if($moreContent==5) $moreContent="Fri";
				if($moreContent==6) $moreContent="Sat";
								
				if($event_type_prev!=$event_type){
					if($event_type==0){
						$event_type_desc = "Upcoming Events";
					}
					if($event_type==1){
						$event_type_desc = "Repeated Weekly";
					}
					if($event_type==2){
						$event_type_desc = "Repeated Biweekly";
					}
					if($event_type==3){
						$event_type_desc = "Repeated Monthly";
					}
					if($event_type==4){
						$event_type_desc = "Repeated Yearly";
					}
					if($event_type==5){
						$event_type_desc = "Custom";
					}
					print("<tr bgcolor='lightblue'>\n");
						print("<td colspan='3'>$event_type_desc</td>");
					print("</tr>\n");
				}
				$event_type_prev = $event_type;
				print("<tr bgcolor='$bgc'>\n");
				print("<td style='border-width:1px 0px 0px 0px;border-color:#eeeeee; border-style:solid;font-size:11px;' nowrap>");
					if($event_type==5){ // custom - show pattern
						print("$maxReservations $moreContent");
					} else if($event_type==4){ // yearly - show month and day
						$date_start = strtotime($date_start);
						$date_start = date('M n', $date_start);
						print("$date_start");
					} else if($event_type==3){ // monthly - show date
						$date_start = strtotime($date_start);
						$date_start = date('jS', $date_start);
						print("$date_start");
					} else if($event_type==2){ // biweekly - show day
						$date_start = strtotime($date_start);
						$date_start = date('D', $date_start);
						print("$date_start");
					} else if($event_type==1){ // weekly - show day
						$date_start = strtotime($date_start);
						$date_start = date('D', $date_start);
						print("$date_start");
					} else {
						//print("$date_start");
						if($time_start!="00:01:00"){
							$date_start = strtotime("$date_start $time_start");
							$date_start = date("M j, Y, g:i a", $date_start);
						} else {
							$date_start = strtotime("$date_start");
							$date_start = date("M j, Y ", $date_start);
						}
						print("$date_start");
						//if($time_start!="") print(" $time_start");
					}
				print("</td>");
				print("<td style='border-width:1px 0px 0px 0px;border-color:#eeeeee; border-style:solid;font-size:11px;'>$title</td>");
				print("<td align='right' style='border-width:1px 0px 0px 0px;border-color:#eeeeee; border-style:solid;font-size:11px;' nowrap><a href='javascript:handle_calendar(\"MOD\", \"$uid\");'>edit</a> | <a href='javascript:handle_calendar(\"DELETE\", \"$uid\");'>delete</a></td>");
				print("</tr>\n");
				if($bgc == "#eeeeee"){
					$bgc = "#ffffff";
				} else {
					$bgc = "#eeeeee";
				}	
				
			}
			print("<tr><td colspan='3' style='border-width:1px 0px 0px 0px;border-color:#eeeeee; border-style:solid;font-size:11px;'>&nbsp;</td></tr>\n");
			print("</table>");
		}
		?>
			<script type="text/javascript" language="javascript">
				function handle_calendar(ACTION, uid){
					document.handle_calendar.ACTION.value=ACTION;
					document.handle_calendar.uid.value=uid;
					document.handle_calendar.submit();
				}
			</script>
			<form action='admin.php?page=calendar_JCM/calendar-manager.php' method='post' name='handle_calendar'>
				<input type='hidden' name='ACTION' value=''>
				<input type='hidden' name='uid' value=''>
			</form>
		</td>
	</tr>
</table>
</div>

</td>
	</tr>
</table>


</div>
</div>
