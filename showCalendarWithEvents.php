<?php
  define("ADAY", (60*60*24));
  if ((!isset($_POST['month'])) || (!isset($_POST['year']))) {
      $nowArray = getdate();
      $month = $nowArray['mon'];
      $year = $nowArray['year'];
 } else {
     $month = $_POST['month'];
     $year = $_POST['year'];
 }

 $start = mktime (12, 0, 0, $month, 1, $year);
 $firstDayArray = getdate($start);
 ?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
   <title><?php echo "Calendar: ".$firstDayArray['month']." ".$firstDayArray['year']; ?></title>
   <style type="text/css">
   *{
     font-family: sans-serif;
   }
    table {
border: 1px solid black;
border-collapse: collapse;
   }    
 th {
border: 1px solid black;
padding: 6px;
font-weight: bold;
background: #ccc;
 }
  td {
border: 1px solid black;
padding: 6px;
vertical-align: top;
width: 100px;
}
.select_date{
     display:flex;
     flex-direction:column;
     padding: 10px 10px;
     margin: 10px 10px;
}
.select_date>select{
    padding: 10px;
    border-radius: 4px;
    font-family: sans-serif;
    margin: 3px 3px;
}
.select_date>button{
    font-family: sans-serif;
    margin: 3px 3px;
    padding: 10px 10px;
    background:#0a83ff;
    color:white;
    border:0px ;
    border-radius: 4px;
    transition: transform .3s;
}
.select_date>button:hover{
     transform:scale(1.01);
}

   </style>
 </head>
 <body>
   <h1>Select a Month/Year Combination</h1>
   <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <div class="select_date">
     <select name="month">
<?php
 $months = Array("January", "February", "March", "April", "May",  "June", "July",
"August", "September", "October", "November", "December");
for ($x=1; $x <= count($months); $x++) {
echo"<option value=\"$x\"";
if ($x == $month) {
echo " selected";
}
echo ">".$months[$x-1]."</option>";
}
?>
     </select>
     <select name="year">
     <?php
     for ($x=1990; $x<=2025; $x++) {
echo "<option";
     if ($x == $year) {
echo " selected";
     }
     echo ">$x</option>";
         }
    ?>
     </select>
          <button type="submit" name="submit" value="submit">Go!</button>
     </div>
   </form>
     <br>
     <?php
     $days = Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
     echo "<table><tr>\n";
     foreach ($days as $day) {
echo "<th>".$day."</th>\n";
     }
     for ($count=0; $count < (6*7); $count++) {
$dayArray = getdate($start);
if (($count % 7) == 0) {
if ($dayArray['mon'] != $month) {
break;
} else {
echo "</tr><tr>\n";
}
}
if ($count < $firstDayArray['wday'] || $dayArray['mon'] != $month) {
echo "<td>&nbsp;</td>\n";
} else {
$event_title = "";
 $mysqli = mysqli_connect("localhost", "testuser", "calender_simple", "testuser");
$chkEvent_sql = "SELECT event_title FROM calendar_events WHERE month(event_start) = '".$month."' AND dayofmonth(event_start) = '".$dayArray['mday']."' AND year(event_start) = '".$year."' ORDER BY event_start";
$chkEvent_res = mysqli_query($mysqli, $chkEvent_sql)or die(mysqli_error($mysqli));
if (mysqli_num_rows($chkEvent_res) > 0) {
while ($ev = mysqli_fetch_array($chkEvent_res)) {
$event_title .= stripslashes($ev['event_title'])."<br>";
}
} else {
$event_title = "";
}
echo "<td><a href=\"javascript:eventWindow('addEvents.php?m=".$month.
 "&amp;d=".$dayArray['mday']."&amp;y=$year');\">".$dayArray['mday']."</a>
<br>".$event_title."</td>\n";
unset($event_title);
$start += ADAY;
}
}
     echo "</tr></table>";
     //close connection to MySQL
     mysqli_close($mysqli);
     ?>

   <script type="text/javascript">
   function eventWindow(url) {
event_popupWin = window.open(url, 'event', 'resizable=yes,scrollbars=yes, toolbar=no,width=400,height=400');
event_popupWin.opener = self;
   }
   </script>
 </body>
 </html>