<?php
include_once 'dbconfig.php';
if(!$user->is_loggedin())
{
	$user->redirect('index.php');
}
$user_id = $_SESSION['user_session'];
$stmt = $DB_con->prepare("SELECT * FROM users WHERE id=:user_id");
$stmt->execute(array(":user_id"=>$user_id));
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css"  />
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="styless.css">
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="script.js"></script>
<title>welcome - <?php print($userRow['emailid']); ?></title>
</head>

<body>

<div class="header">
	<div class="left">
    	<label><a href="#">UWindsor Ride-Share</a></label>
    </div>
    <div class="right">
    	<label><a href="logout.php?logout=true"><i class="glyphicon glyphicon-log-out"></i> logout</a></label>
    </div>
</div>
<div id='cssmenu'>
<ul>
   <li><a href='home.php'>Home</a></li>
      <li class='active'><a href='list.php'>Ride Search</a></li>
   <li><a href='confirm-ride.php'>Book Ride</a></li>
   <li><a href='cancel-ride.php'>Cancel Ride</a></li>
</ul>
</div>
<div class="content">
welcome : <?php print($userRow['username']); ?>

<div class="form-container">
<form id="form_search" method="post">
<h2>Search by date.</h2><hr />
<?php
	  require('calendar/tc_calendar.php');
	  $myCalendar = new tc_calendar("date5", true, false);
	  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("calendar/");
	  $myCalendar->setYearInterval(2015, 2017);
	  $myCalendar->dateAllow('2015-11-11', '2017-01-01');
	  $myCalendar->writeScript();
	  ?>
	  <div class="clearfix"></div><hr />
<div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn_search">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;Search
                </button>
            </div>
			</form>
</div>
<br>
<?php
if(isset($_POST['btn_search'])) {
	$date_ride = $_POST['date5'];
	$query_search = "SELECT * FROM ride_history where date=? and user_id!=? and available_rides>0";
    $PDA = $DB_con->prepare($query_search);  
    $PDA -> execute(array($date_ride,$user_id));
    echo '<div id="wrapper">
	<div id="header">Search by Date: '.$date_ride.'</div>
	<div id="content">
	<div id="content-left">Ride Id</div>
	<div id="content-main">From</div>
	<div id="content-main">To</div>
	<div id="content-main">Date</div>
	<div id="content-main">Time</div>
	<div id="content-right">Available Rides</div>';
	

while($row = $PDA -> fetch(PDO::FETCH_ASSOC))
{
echo'<div id="content-left">' . $row['ride_id'] . '</div>';
echo'<div id="content-main">' . $row['location_from'] . '</div>';
echo'<div id="content-main">' . $row['location_to'] . '</div>';
echo'<div id="content-main">' . $row['date'] . '</div>';
echo'<div id="content-main">' . $row['time_hours'] . ':'. $row['time_minutes'] . ':' . $row['time_period'] . '</div>';
echo'<div id="content-right">' . $row['available_rides'] . '</div>';
}

echo '</div>
	<div id="bottom"></div>
</div>'; 
}
?>
<br>
<div id="wrapper">
	<div id="header">All available rides</div>
	<div id="content">
	<div id="content-left">Ride Id</div>
	<div id="content-main">From</div>
	<div id="content-main">To</div>
	<div id="content-main">Date</div>
	<div id="content-main">Time</div>
	<div id="content-right">Available Rides</div>
        <?php 
        $query = "SELECT * FROM ride_history where date>? and user_id!=? and available_rides>0";       
        $records_per_page=10;
        $newquery = $paginate->paging($query,$records_per_page);
        $paginate->dataview($newquery,$user_id);
        $paginate->paginglink($query,$records_per_page,$user_id);  
        ?>
</div>
	<div id="bottom"></div>
</div>
</div>
</body>
</html>