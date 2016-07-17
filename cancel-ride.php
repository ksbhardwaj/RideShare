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

//Cancel Ride
if(isset($_POST['btn_cancel'])) {
	$txt_cancel_ride = trim($_POST['txt_cancel_ride']);
	// database query
	try
		{
			$stmt = $DB_con->prepare("SELECT * FROM confirmed_rides WHERE confirmation_id=?");
			$stmt->execute(array($txt_cancel_ride));
			if($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
				$ride_taker_id = $row['ride_taker_id'];
				$ride_id = $row['ride_id'];
				// invalid ride id entered
				if($ride_taker_id!=$user_id){
					$error = "Invalid id";
				} else {
						
						$stmt12 = $DB_con->prepare("SELECT * FROM ride_history WHERE ride_id=?");
						$stmt12->execute(array($ride_id));
						$row12=$stmt12->fetch(PDO::FETCH_ASSOC);
						$ride_date = $row12['date'];
						
						$now1 = date("Y-m-d");
						$now2 = date('Y-m-d', strtotime($now1 . ' +2 day'));
						// can only delete if more than 2 days available
						if($ride_date<$now2){
						$error = "You can't cancel ride if days remaining are less than 1 ..!";
						} else {
							$stmt11 = $DB_con->prepare("DELETE FROM confirmed_rides where confirmation_id=?");
							if($stmt11->execute(array($txt_cancel_ride)))
							{
								$stmt111 = $DB_con->prepare("UPDATE ride_history SET available_rides = available_rides + 1 where ride_id = ?");
							if($stmt111->execute(array($ride_id))){
							$successful = "Ride canceled successfully;";}
							//TODO: send E-Mail
							} else {$error = "Sorry! Some Error occured ..!";}
						}
				}
			}else{ $error = "Invalid id ..!";}
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
      <li><a href='list.php'>Ride Search</a></li>
   <li><a href='confirm-ride.php'>Book Ride</a></li>
   <li class='active'><a href='cancel-ride.php'>Cancel Ride</a></li>
</ul>
</div>
<div class="content">
welcome : <?php print($userRow['username']); ?>

<div class="form-container">
<form id="form_cancel" method="post">
<?php
if(isset($error))
			{
					 ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?> !
                     </div>
                     <?php
			}
if(isset($successful))
			{
					 ?>
                     <div class="alert alert-success">
                        <i class="glyphicon glyphicon-check"></i> &nbsp; <?php echo $successful; ?>
                     </div>
                     <?php
			}

			?>

<h2>Cancel Ride</h2><hr />
<div class="form-group">
            	<input type="text" class="form-control" name="txt_cancel_ride" placeholder="Enter Ride id" required />
            </div>
			*Get the id from list below
<div class="clearfix"></div><hr />
<div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn_cancel">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;Cancel
                </button>
            </div>
			</form>
</div>
<br>
<?php
	$now = date("Y-m-d");
	$query_search = "select * from ride_history inner join confirmed_rides on ride_history.ride_id=confirmed_rides.ride_id where confirmed_rides.ride_taker_id=?";
    $PDA = $DB_con->prepare($query_search);  
    $PDA -> execute(array($user_id));
    echo '<div id="wrapper">
	<div id="header">Your Booked Rides</div>
	<div id="content">
	<div id="content-left">Id</div>
	<div id="content-main">From</div>
	<div id="content-main">To</div>
	<div id="content-main">Date</div>
	<div id="content-right">Time</div>
	<div id="content-right">&nbsp;</div>';
	

while($row = $PDA -> fetch(PDO::FETCH_ASSOC))
{
echo'<div id="content-left">' . $row['confirmation_id'] . '</div>';
echo'<div id="content-main">' . $row['location_from'] . '</div>';
echo'<div id="content-main">' . $row['location_to'] . '</div>';
echo'<div id="content-main">' . $row['date'] . '</div>';
echo'<div id="content-right">' . $row['time_hours'] . ':'. $row['time_minutes'] . ':' . $row['time_period'] . '</div>';
echo'<div id="content-main">&nbsp;</div>';
}

echo '</div>
	<div id="bottom"></div>
</div>'; 
?>
</div>
</body>
</html>