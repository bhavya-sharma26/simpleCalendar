<!DOCTYPE html>
   <html lang="en">
   <head>
     <title>My Calendar</title>
     <style type="text/css">
     *{
        font-family: sans-serif;
     }
     table {
border: 1px solid black;
border-collapse: collapse;
margin-top: 1rem;
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
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="functions.js"></script>
 </head>
 <body>
   <h1>Select a Month/Year Combination</h1>
   <form id="datePicker" class="select_date"></form>
   <div id="myCal"></div>

   <script type="text/javascript">
   $().ready(function(){
     // build the picker form
     buildDateForm();
     calendar();

    $("#submit").click(function() {
var newMonth = $('#month').val();
var newYear = $('#year').val();
var newDate = new Date(newYear, newMonth, 1);
calendar(newDate);
return false;
});
    });
    </script>
 </body>
 </html>