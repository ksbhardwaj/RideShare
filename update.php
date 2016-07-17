<?php
require_once 'dbconfig.php';
if(!$user->is_loggedin())
{
	$user->redirect('index.php');
}
$user_id = $_SESSION['user_session'];
$stmt = $DB_con->prepare("SELECT * FROM users WHERE id=:user_id");
$stmt->execute(array(":user_id"=>$user_id));
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
if(isset($_POST['btn_update']))
{
	$current_password = $_POST['txt_current_password'];
	$new_password = $_POST['txt_new_password'];
	$confirm_password = $_POST['txt_confirm_password'];
	if ($new_password!=$confirm_password) {
		$error = "'Confirm password' and 'Password' do not match.";
	} else if(!password_verify($current_password, $userRow['password'])){
		$error = "Password is incorrect   !";
	}
	else{
		try {	
		$stmt = $DB_con->prepare("UPDATE users
									SET password=?
										WHERE id=?");					  	
			$stmt->execute(array($current_password,$user_id));	
			$error = "Sucessful";
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
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
   <li class = 'active'><a href='update.php'>Update Profile</a></li>
   <li><a href='report.php'>Report to Admin</a></li>
</ul>
</div>
<div class="content">
welcome : <?php print($userRow['username']); ?>
<br><br><br>
	<div class="form-container">
        <form id="form_availability" method="post">
            <h2>Update Profile</h2><hr />
			
			<?php
			if(isset($error))
			{
					 ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?> !
                     </div>
                     <?php
			}
			?>
            <div class="form-group">
            	<input type="password" class="form-control" name="txt_current_password" placeholder="Current Password" required/>
            </div>
            <div class="form-group">
            	<input type="password" class="form-control" name="txt_new_password" placeholder="New Password" required />
            </div>
			<div class="form-group">
            	<input type="password" class="form-control" name="txt_confirm_password" placeholder="Confirm Password" required />
            </div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" name="btn_update" class="btn btn-block btn-primary">
                	<i class="glyphicon glyphicon-log-in"></i>&nbsp;Update
                </button>
            </div>
		
	</div>
</body>
</html>