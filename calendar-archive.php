<?php
### Display Form to Add/Edit

?>
<div class="wrap">
<h2>Events Archive</h2>
			
<?
if($msg!=""){
	print($msg);
}

print("<div align='center'><table width='900' style='margin-left:12px;'>");
print("<tr><td valign='top' width='450'>");
	
print("<h3>Events Archive</h3>");
	
		### List Current 
		$today=date('Y-m-d');
		print("\n<table width='480' cellpadding='3' cellspacing='1' border='0'>\n");
		//WHERE DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= date_col;
		$categories = $wpdb->get_results("SELECT * FROM calendarCG WHERE ( (event_type=0 AND date_start<='$today' ) OR ((event_type>0 AND (date_end!='0000-00-00' AND date_end<='$today') ) ) ) ORDER BY event_type, date_start DESC, time_start, title ASC");
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
						$event_type_desc = "Single Events";
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
						if($date_end!="0000-00-00"){
							$date_end = date("Mj-Y", $date_end);
							print(" (end $date_end)");
						}
					} else if($event_type==3){ // monthly - show date
						$date_start = strtotime($date_start);
						$date_start = date('jS', $date_start);
						$date_end = date("Mj-Y", $date_end);
						print("$date_start");
						if($date_end!="0000-00-00"){
							$date_end = date("Mj-Y", $date_end);
							print(" (end $date_end)");
						}
					} else if($event_type==2){ // biweekly - show day
						$date_start = strtotime($date_start);
						$date_start = date('D', $date_start);
						print("$date_start");
						if($date_end!="0000-00-00"){
							$date_end = strtotime($date_end);
							$date_end = date("M j, Y", $date_end);
							print(" (end $date_end)");
						}
					} else if($event_type==1){ // Weekly - show day
						$date_start = strtotime($date_start);
						$date_start = date('D', $date_start);
						print("$date_start");
						if($date_end!="0000-00-00"){
							$date_end = strtotime($date_end);
							$date_end = date("M j, Y", $date_end);
							print(" (end $date_end)");
						}
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
