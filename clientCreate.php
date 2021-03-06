<?php 


 session_start();

	
require 'libDatabase.php';

if ( !empty($_POST)) { // if not first time through

	// initialize user input validation variables
	$fNameError = null;
	$lNameError = null;
	$emailError = null;
	$mobileError = null;
	$passwordError = null;
	$titleError = null;
	$pictureError = null; // not used
	
	// initialize $_POST variables
	$fName = $_POST['fName'];
	$lName = $_POST['lName'];
	$email = $_POST['email'];
	$mobile = $_POST['mobile'];
	$password = $_POST['password'];
	$passwordhash = MD5($password);
	$client_title =  $_POST['client_title'];
	//$picture = $_POST['picture']; // not used
	
	// initialize $_FILES variables
	$fileName = $_FILES['userfile']['name'];
	$tmpName  = $_FILES['userfile']['tmp_name'];
	$fileSize = $_FILES['userfile']['size'];
	$fileType = $_FILES['userfile']['type'];
	$content = file_get_contents($tmpName);

	// validate user input
	$valid = true;
	if (empty($fName)) {
		$fNameError = 'Please enter First Name';
		$valid = false;
	}
	if (empty($lName)) {
		$lNameError = 'Please enter Last Name';
		$valid = false;
	}
	// do not allow 2 records with same email address!
	if (empty($email)) {
		$emailError = 'Please enter valid Email Address (REQUIRED)';
		$valid = false;
	} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$emailError = 'Please enter a valid Email Address';
		$valid = false;
	}

	$pdo = Database::connect();
	$sql = "SELECT * FROM client";
	foreach($pdo->query($sql) as $row) {

		if($email == $row['email']) {
			$emailError = 'Email has already been registered!';
			$valid = false;
		}
	}
	Database::disconnect();
	
	// email must contain only lower case letters
	if (strcmp(strtolower($email),$email)!=0) {
		$emailError = 'email address can contain only lower case letters';
		$valid = false;
	}
	
	if (empty($mobile)) {
		$mobileError = 'Please enter Mobile Number (or "none")';
		$valid = false;
	}
	if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $mobile)) {
		$mobileError = 'Please write Mobile Number in form 000-000-0000';
		$valid = false;
	}
	if (empty($password)) {
		$passwordError = 'Please enter valid Password';
		$valid = false;
	}
	if (empty($client_title)) {
		$titleError = 'Please enter valid Title';
		$valid = false;
	}
	// restrict file types for upload
	$types = array('image/jpeg','image/gif','image/png');
	if(1 > 0) {
		if(in_array($_FILES['userfile']['type'], $types)) {
		}
		else {
			$filename = null;
			$filetype = null;
			$filesize = null;
			$filecontent = null;
			$pictureError = 'improper file type';
			$valid=false;
			
		}
	}
	// insert data
	if ($valid) 
	{
		$pdo = Database::connect();
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO client (fName,lName,email,mobile,password,client_title,
		filename,filesize,filetype,filecontent) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($fName,$lName,$email,$mobile,$passwordhash,$client_title,
		$fileName,$fileSize,$fileType,$content));
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM client WHERE email = ? AND password = ? LIMIT 1";
		$q = $pdo->prepare($sql);
		$q->execute(array($email,$passwordhash));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		$_SESSION['client_id'] = $data['client_id'];
		$_SESSION['client_title'] = $data['client_title'];
		
		Database::disconnect();
		header("Location: material.php");
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body>
    <div class="container">

		<div class="span10 offset1">
			<?php
				/*require 'functions.php';
				Functions::logoDisplay2();*/
			?>
			<div class="row">
				<h3>Add New Volunteer</h3>
			</div>
	
			<form class="form-horizontal" action="clientCreate.php" method="post" enctype="multipart/form-data">

				<div class="control-group <?php echo !empty($fNameError)?'error':'';?>">
					<label class="control-label">First Name</label>
					<div class="controls">
						<input name="fName" type="text"  placeholder="First Name" value="<?php echo !empty($fName)?$fName:'';?>">
						<?php if (!empty($fNameError)): ?>
							<span class="help-inline"><?php echo $fNameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($lNameError)?'error':'';?>">
					<label class="control-label">Last Name</label>
					<div class="controls">
						<input name="lName" type="text"  placeholder="Last Name" value="<?php echo !empty($lName)?$lName:'';?>">
						<?php if (!empty($lNameError)): ?>
							<span class="help-inline"><?php echo $lNameError;?></span>
						<?php endif; ?>
					</div>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($emailError)?'error':'';?>">
					<label class="control-label">Email</label>
					<div class="controls">
						<input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
						<?php if (!empty($emailError)): ?>
							<span class="help-inline"><?php echo $emailError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($mobileError)?'error':'';?>">
					<label class="control-label">Mobile Number</label>
					<div class="controls">
						<input name="mobile" type="text"  placeholder="Mobile Phone Number" value="<?php echo !empty($mobile)?$mobile:'';?>">
						<?php if (!empty($mobileError)): ?>
							<span class="help-inline"><?php echo $mobileError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
					<label class="control-label">Password</label>
					<div class="controls">
						<input id="password" name="password" type="password"  placeholder="password" value="<?php echo !empty($password)?$password:'';?>">
						<?php if (!empty($passwordError)): ?>
							<span class="help-inline"><?php echo $passwordError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Title</label>
					<div class="controls">
						<select class="form-control" name="client_title">
							<option value="Volunteer" selected>Client</option>
							<!-- <option value="Administrator" >Administrator</option>-->
						</select>
					</div>
				</div>
			  
				<div class="control-group <?php echo !empty($pictureError)?'error':'';?>">
					<label class="control-label">Picture</label>
					<div class="controls">
						<input type="hidden" name="MAX_FILE_SIZE" value="16000000">
						<input name="userfile" type="file" id="userfile">
						
					</div>
				</div>
			  
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
					<a class="btn" href="material.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
  </body>
</html>