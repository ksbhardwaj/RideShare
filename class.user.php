<?php
class USER
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	public function register($uname,$fname,$lname,$upass,$umail,$address,$joined)
	{
		try
		{
			$joined = date('Y-m-d H:i:s');
			$new_password = password_hash($upass, PASSWORD_DEFAULT);
			
			$stmt = $this->db->prepare("INSERT INTO users(username,firstname,lastname,password,emailid,address,joined) 
		                                               VALUES(:uname, :fname, :lname, :upass, :umail, :address, :joined)");
												  
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":fname", $fname);
			$stmt->bindparam(":lname", $lname);
			$stmt->bindparam(":upass", $new_password);										  
			$stmt->bindparam(":umail", $umail);
			$stmt->bindparam(":address", $address);
			$stmt->bindparam(":joined", $joined);
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function login($uname,$umail,$upass)
	{
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM users WHERE username=:uname OR emailid=:umail LIMIT 1");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0)
			{
				if(password_verify($upass, $userRow['password']))
				{
					$_SESSION['user_session'] = $userRow['id'];
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function submit_availability($user_id, $list_from, $list_to, $date5, $txt_time_hour, $txt_time_minute,$list_period,$txt_ride)
	{
		try
		{
			$flag = 0;
			$stmt = $this->db->prepare("INSERT INTO ride_history(user_id,location_from,location_to,date,time_hours,time_minutes,time_period,available_rides,flag) 
		                                 VALUES(:user_id, :list_from, :list_to, :date5, :txt_time_hour, :txt_time_minute,:list_period,:txt_ride, :flag)");
												  
			$stmt->bindparam(":user_id", $user_id);
			$stmt->bindparam(":list_from", $list_from);
			$stmt->bindparam(":list_to", $list_to);
			$stmt->bindparam(":date5", $date5);										  
			$stmt->bindparam(":txt_time_hour", $txt_time_hour);
			$stmt->bindparam(":txt_time_minute", $txt_time_minute);
			$stmt->bindparam(":list_period", $list_period);
			$stmt->bindparam(":txt_ride", $txt_ride);
			$stmt->bindparam(":flag", $flag);
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
}
?>