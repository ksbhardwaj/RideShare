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
   <li class='active'><a href='home.php'>Home</a></li>
   <!--
   <li><a href='availability.php'>Specify Availability</a></li>
   <li><a href='edit-availability.php'>Edit Availability</a></li>
   -->
   <li><a href='list.php'>Ride Search</a></li>
   <li><a href='confirm-ride.php'>Book Ride</a></li>
   <li><a href='cancel-ride.php'>Cancel Ride</a></li>
</ul>
</div>
<div class="content">
welcome : <?php print($userRow['username']); ?>
<?php
			if(isset($_GET['submit_availability']))
			{
				$alert = "Availability successfully submitted   !";
			 	?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $alert; ?>
                     </div>
                <?php
			}
?>

</div>
</body>
</html>