<?php

require_once 'dbconfig.php';

if($user->is_loggedin()!="")
{
	$user->redirect('home.php');
}

if(isset($_POST['btn-signup']))
{
	
	$fname = trim($_POST['txt_fname']);
	$lname = trim($_POST['txt_lname']);
	//Previous code
	$uname = trim($_POST['txt_uname']);
	$umail = trim($_POST['txt_umail']);
	$upass = trim($_POST['txt_upass']);	
	//New
	$address = trim($_POST['txt_address']);
	
	if($uname=="")	{
		$error[] = "provide username !";	
	}
	else if($umail=="")	{
		$error[] = "provide email id !";	
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Please enter a valid email address !';
	}
	else if($upass=="")	{
		$error[] = "provide password !";
	}
	else if(strlen($upass) < 6){
		$error[] = "Password must be atleast 6 characters";	
	}
	else
	{
		try
		{
			
			$stmt = $DB_con->prepare("SELECT username,emailid FROM users WHERE username=:uname OR emailid=:umail");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['username']==$uname) {
				$error[] = "sorry username already taken !";
			}
			else if($row['emailid']==$umail) {
				$error[] = "sorry email id already taken !";
			}
			else
			{
				if($user->register($uname,$fname,$lname,$upass,$umail,$address,$joined))	{
					
					$user->redirect('sign-up.php?joined');
				}
			}
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
<title>Sign up : cleartuts</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css"  />
</head>
<body>
<div class="container">
    	<div class="form-container">
        <form method="post">
            <h2>Sign up.</h2><hr />
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
			else if(isset($_GET['joined']))
			{
				 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='index.php'>login</a> here
                 </div>
                 <?php
			}
			?>
            
			<div class="form-group">
            <input type="text" class="form-control" name="txt_fname" placeholder="Enter First Name" value="<?php if(isset($error)){echo $fname;}?>" />
            </div>
			<div class="form-group">
            <input type="text" class="form-control" name="txt_lname" placeholder="Enter Last Name" value="<?php if(isset($error)){echo $lname;}?>" />
            </div>
			
			<!-- Previous Fields -->
			<div class="form-group">
            <input type="text" class="form-control" name="txt_uname" placeholder="Enter Username" value="<?php if(isset($error)){echo $uname;}?>" />
            </div>
            <div class="form-group">
            <input type="text" class="form-control" name="txt_umail" placeholder="Enter E-Mail ID" value="<?php if(isset($error)){echo $umail;}?>" />
            </div>
            <div class="form-group">
            	<input type="password" class="form-control" name="txt_upass" placeholder="Enter Password" />
            </div>
			<div class="form-group">
            <input type="text" class="form-control" name="txt_address" placeholder="Enter Address" value="<?php if(isset($error)){echo $address;}?>" />
            </div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn-signup">
                	<i class="glyphicon glyphicon-open-file"></i>&nbsp;SIGN UP
                </button>
            </div>
            <br />
            <label>have an account ! <a href="index.php">Sign In</a></label>
        </form>
       </div>
</div>

</body>
</html>