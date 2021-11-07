<!DOCTYPE html>
   <html>
   <head>
     <title>Show/Add Events</title>
     <style type="text/css">
     *{
      font-family: sans-serif;
     }
     .btn{
      background:#0a83ff;
      color:white;
      border:0px ;
      border-radius: 4px;
      transition: transform .3s;
      padding:3px;
      width:100%;
      text-align:center;
}
   .btn:hover{
     transform:scale(1.01);
}
     </style>
   </head>
   <body>
     <h1>Show/Add Events</h1>
     <?php
     $mysqli = mysqli_connect("localhost", "testuser", "calender_simple", "testuser");
    //add any new event
        if ($_POST) {
//create database-safe strings
$safe_m = $mysqli->real_escape_string( $_POST['m']);
$safe_d = $mysqli->real_escape_string( $_POST['d']);
$safe_y = $mysqli->real_escape_string( $_POST['y']);
$safe_event_title = $mysqli->real_escape_string($_POST['event_title']);
$safe_event_shortdesc = $mysqli->real_escape_string($_POST['event_shortdesc']);
$safe_event_time_hh = $mysqli->real_escape_string($_POST['event_time_hh']);
$safe_event_time_mm = $mysqli->real_escape_string($_POST['event_time_mm']);
$event_date = $safe_y."-".$safe_m."-".$safe_d."-".$safe_event_time_hh.":".$safe_event_time_mm.":00";
$insEvent_sql = "INSERT INTO calendar_events (event_title,
event_shortdesc, event_start) VALUES('".$safe_event_title."', '".$safe_event_shortdesc."','".$event_date."')";
$insEvent_res = mysqli_query($mysqli, $insEvent_sql)or die(mysqli_error($mysqli));
  } else {
//create database-safe strings
$safe_m = $mysqli->real_escape_string( $_GET['m']);
$safe_d = $mysqli->real_escape_string( $_GET['d']);
$safe_y = $mysqli->real_escape_string( $_GET['y']);
    }
  //show events for this day
    $getEvent_sql = "SELECT event_title, event_shortdesc,date_format(event_start, '%l:%i %p') as fmt_date FROM calendar_events WHERE month(event_start) ='".$safe_m."' AND dayofmonth(event_start) ='".$safe_d."' AND year(event_start) ='".$safe_y."' ORDER BY event_start";
    $getEvent_res = mysqli_query($mysqli, $getEvent_sql)
or die(mysqli_error($mysqli));
    if (mysqli_num_rows($getEvent_res) > 0) {
$event_txt = "<ul>";
while ($ev = @mysqli_fetch_array($getEvent_res)) {
$event_title = stripslashes($ev['event_title']);
$event_shortdesc = stripslashes($ev['event_shortdesc']);
$fmt_date = $ev['fmt_date'];
$event_txt .= "<li><strong>".$fmt_date."</strong>:
".$event_title."<br>".$event_shortdesc."</li>";
}
$event_txt .= "</ul>";
mysqli_free_result($getEvent_res);
   } else {
$event_txt = "";
}    // close connection to MySQL
    mysqli_close($mysqli);
    if ($event_txt != "") {
echo "<p><strong>Today's Events:</strong></p>$event_txt<hr>";
    }
  // show form for adding an event
    echo <<<END_OF_TEXT
  <form method="post" action="$_SERVER[PHP_SELF]">
  <p><strong>Would you like to add an event?</strong><br>
  Complete the form below and press the submit button to add the event and refresh this window.</p>
  <p><label for="event_title">Event Title:</label><br>
  <input type="text" id="event_title" name="event_title" size="25" maxlength="25"></p>
  <p><label for="event_shortdesc">Event Description:</label><br>
  <input type="text" id="event_shortdesc" name="event_shortdesc" size="25" maxlength="255"></p>
  <fieldset>
  <legend>Event Time (hh:mm):</legend>
  <select name="event_time_hh">
  END_OF_TEXT;
    for ($x=1; $x <= 24; $x++) {
       echo "<option value=\"$x\">$x</option>";
    }
       echo <<<END_OF_TEXT
 </select> 
 <select name="event_time_mm">
 <option value="00">00</option>
 <option value="15">15</option>
 <option value="30">30</option>
 <option value="45">45</option>
 </select>
 </fieldset>
 <input type="hidden" name="m" value="$safe_m">
 <input type="hidden" name="d" value="$safe_d">
 <input type="hidden" name="y" value="$safe_y">
 <button type="submit" name="submit" value="submit" class="btn">Add Event</button>
 </form>
 END_OF_TEXT;
   ?>
 </body>
 </html>