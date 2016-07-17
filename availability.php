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

//get list of locations
$sql = "SELECT DISTINCT * FROM location";
$query = $DB_con->prepare($sql);
$query->execute();
$option = null;
$result = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $id = $row['city'];
    $option.='<option value="'.$id.'">'.$id.'</option>';
}
// submit availability
if(isset($_POST['btn_availability'])) {
	$txt_time_hour = trim($_POST['txt_time_hour']);
	$txt_time_minute = trim($_POST['txt_time_minute']);
	$txt_ride = trim($_POST['txt_ride']);
	$list_from = $_POST['list_from'];
	$list_to = $_POST['list_to'];
	$list_period = $_POST['list_period'];
	$date5 = $_POST['date5'];
	
	// database query
	try
		{
			
			$stmt = $DB_con->prepare("SELECT * FROM ride_history WHERE user_id=:user_id");
			$stmt->execute(array(':user_id'=>$user_id));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['date']==$date5) {
				$error[] = "You have already given availability for ". $date5 ." !";
			}
			else
			{
				if($user->submit_availability($user_id, $list_from, $list_to, $date5, $txt_time_hour, $txt_time_minute,$list_period,$txt_ride))	{
					
					$user->redirect('ridegiver.php?submit_availability');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css"  />
<link rel="stylesheet" href="styles.css">
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
   <li class = 'active'><a href='availability.php'>Specify Availability</a></li>
   <li><a href='edit-availability.php'>Delete Availability</a></li>
</ul>
</div>
<div class="content">
welcome : <?php print($userRow['username']); ?>
<br><br><br>
	<div class="form-container">
        <form id="form_availability" method="post">
            <h2>Specify Availability</h2><hr />
			
			<?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
				}
			}
			?>
		<label>Choose Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><br><label> From:</label>
		<select name="list_from" form="form_availability">
		<option disabled>Select City</option>
		<?php echo $option;?>
		</select><br>
		<br><label>To:&nbsp;&nbsp;&nbsp;</label>
		&nbsp
		<select name="list_to" form="form_availability">
		<option disabled>Select City</option>
		<?php echo $option;?>
		</select><br>
	
	<label for="date5">Select Date:</a>
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
		<div class="form-group">
		<label>Specify Time:</label>
        <input type="text" class="form-control" name="txt_time_hour" maxlength="2" style="width:50px;" value="<?php if(isset($error)){echo $txt_time_hour;}?>" required/>
        <input type="text" class="form-control" name="txt_time_minute" maxlength="2" style="width:50px;" value="<?php if(isset($error)){echo $txt_time_minute;}?>" required/>
		<select name="list_period" form="form_availability">
		<option>AM</option>
		<option>PM</option>
		</select>
		<div class="form-group">
		<label>Number of Rides Available</label>
        <input type="text" class="form-control" name="txt_ride" maxlength="2" style="width:50px;" value="<?php if(isset($error)){echo $txt_ride;}?>" required/>
		</div>
		<hr />
		  <div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn_availability">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;Submit
                </button>
            </div>
		</div>
		
</div>
</body>
</html>