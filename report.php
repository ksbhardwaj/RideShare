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
if(isset($_POST['btn_report'])) {
$successful = "Successful";
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
   <li><a href='update.php'>Update Profile</a></li>
   <li class='active'><a href='report.php'>Report to Admin</a></li>
</ul>
</div>
<div class="content">

welcome : <?php print($userRow['username']); ?>
<div class="form-container">
<form id="form_report" method="post">
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
<h2>Report to Admin</h2><hr />
<div class="form-group">
            	<input type="text" class="form-control" name="txt_report" placeholder="Enter Ride id" required />
            </div>
			*Get the Ride id from E-mail sent to you
<div class="form-group">
<textarea rows="4" cols="70" placeholder="Comments...">
</textarea> 
            </div>
<div class="clearfix"></div><hr />
<div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn_report">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;Report
                </button>
            </div>
			</form>
</div>
</div>
</body>
</html>