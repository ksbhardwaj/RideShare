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

//delete availability
if(isset($_POST['btn_delete'])) {
	$txt_delete_availability = trim($_POST['txt_delete_availability']);
	// database query
	try
		{
			$stmt = $DB_con->prepare("SELECT * FROM ride_history WHERE ride_id=?");
			$stmt->execute(array($txt_delete_availability));
			if($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
				$ride_user_id = $row['user_id'];
				$ride_date = $row['date'];
				// invalid ride id entered
				if($ride_user_id!=$user_id){
					$error = "Invalid Ride id";
				} else {
						$now1 = date("Y-m-d");
						$now2 = date('Y-m-d', strtotime($now1 . ' +2 day'));
						// can only delete if more than 2 days available
						if($ride_date<$now2){
						$error = "You can't delete availability if days remaining are less than 2 ..!";
						} else {
							$stmt11 = $DB_con->prepare("DELETE FROM ride_history where ride_id=?");
							if($stmt11->execute(array($txt_delete_availability)))
							{
								$stmt111 = $DB_con->prepare("DELETE FROM confirmed_rides where ride_id=?");
							if($stmt111->execute(array($txt_delete_availability))){
							$successful = "Availability deleted successfully;";}
							//TODO: send E-Mail
							} else {$error = "Sorry! Some Error occured ..!";}
						}
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
<link rel="stylesheet" href="styless.css">
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="script.js"></script>
<title>welcome - <?php print($userRow['emailid']); ?></title>
</head>

<body>

<div class="header">
	<div class="left">
    	<label><a href="#">Ride-Share</a></label>
    </div>
    <div class="right">
    	<label><a href="logout.php?logout=true"><i class="glyphicon glyphicon-log-out"></i> logout</a></label>
    </div>
</div>
<div id='cssmenu'>
<ul>
   <li><a href='home.php'>Home</a></li>
   <li><a href='availability.php'>Specify Availability</a></li>
   <li class = 'active'><a href='edit-availability.php'>Delete Availability</a></li>
</ul>
</div>
<div class="content">
welcome : <?php print($userRow['username']); ?>

<div class="form-container">

<form id="form_delete" method="post">
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
<h2>Delete Availability</h2><hr />
<div class="form-group">
            	<input type="text" class="form-control" name="txt_delete_availability" placeholder="Enter Ride id" required />
            </div>
			*Get the Ride id from list below
<div class="clearfix"></div><hr />
<div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn_delete">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;Delete
                </button>
            </div>
			</form>
</div>

</div>
<br>
<?php
	$now = date("Y-m-d");
	$query_search = "SELECT * FROM ride_history where date>? and user_id=?";
    $PDA = $DB_con->prepare($query_search);  
    $PDA -> execute(array($now,$user_id));
    echo '<div id="wrapper">
	<div id="header">Your specified rides</div>
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
?>
</div>
</body>
</html>