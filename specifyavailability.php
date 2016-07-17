
<?php
	
	require_once 'core/init.php';
	$user = new User();
	
	//User Not Logged-in
	if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
	} else {
		
		//
		$hostname='localhost';
$username='root';
$password='';
    $db = new PDO("mysql:host=$hostname;dbname=rs",$username,$password);
		
		
$sql = "SELECT DISTINCT * FROM location";
$query = $db->prepare($sql);
$query->execute();
$option = null;
$result = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $id = $row['city'];
    $option.='<option value="'.$id.'">'.$id.'</option>';
}
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html idmmzcc-ext-docid="498481152">

    <head></head>
    <body marginwidth="0" marginheight="0" topmargin="0" leftmargin="0">
        
		<table width="100%" height="100%" cellspacing="0" cellpadding="3" border="0">
            <tbody>
                <tr>
                    <td align="center">
                        <div align="center">
                            <br></br>
                            <br></br>
                                    <table width="294" cellspacing="0" cellpadding="3" border="0" align="center">
                                        <tbody>
                                            <tr>
                                                <td width="288" align="center">
                                                    <h1>Specify Availability</h1>
                                                    <br></br>
                                                    <table width="247" cellspacing="0" cellpadding="3" border="0" align="center">
                                                        <tbody>
                                                            <tr>
                                                                <td></td>
                                                                <td>
																
		<form action="" method="">
		<label>Choose Location:</label><br><label> From</label>
		&nbsp
		<select>
		<option disabled>Select City</option>
		<?php echo $option;?>
		</select>
		&nbsp<br> <label>To:</label>
		&nbsp
		<select>
		<option disabled>Select City</option>
		<?php echo $option;?>
		</select>
		<br><label>Choose Date:</label><br>
		&nbsp
		<select>
		<option value="day">Day</option>
		<option value="1">1</option>
		<option value="2">2</option>
		</select>&nbsp
		<select>
		<option value="month">Month</option>
		<option value="jan">1</option>
		<option value="feb">2</option>
		</select>&nbsp
		<select>
		<option value="year">Year</option>
		<option value="x">2015</option>
		</select><br>
		
		
		<label>Select Time:</label><br>
		&nbsp
		<select>
		<option value="day">Hours</option>
		<option value="1">1</option>
		<option value="2">2</option>
		</select>&nbsp
		<select>
		<option value="month">Minutes</option>
		<option value="jan">1</option>
		<option value="feb">2</option>
		</select><br>
		<input type="submit" value="Submit">
		
		</form>
		                                                                </td>
                                                            </tr>
                                                            
                                                            <tr></tr>
                                                            <tr></tr>
                                                            <tr></tr>
                                                            <tr></tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
		
		
    </html>
	<?php
	}
	?>