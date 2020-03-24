<?php 

session_start();
if(!isset($_SESSION["client_id"])){ // if "user" not set,
	session_destroy();
	header('Location: liblogin.php');     // go to login page
	exit;
}
	
require 'libDatabase.php';

$client_id = $_GET['id'];

if ( !empty($_POST)) { // if $_POST filled then process the form

	# initialize/validate (same as file: fr_per_create.php)

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
	//$fileName = $_FILES['userfile']['name'];
	//$tmpName  = $_FILES['userfile']['tmp_name'];
	//$fileSize = $_FILES['userfile']['size'];
	//$fileType = $_FILES['userfile']['type'];
	//$content = file_get_contents($tmpName);

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

	if (empty($email)) {
		$emailError = 'Please enter valid Email Address (REQUIRED)';
		$valid = false;
	} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$emailError = 'Please enter a valid Email Address';
		$valid = false;
	}

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
		$titleError = 'Please enter valid client_title';
		$valid = false;
	}
	// restrict file types for upload
	
	if ($valid) { // if valid user input update the database
	
		if(1 > 0) { // if file was updated, update all fields
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE client  set fName = ?, lName = ?, email = ?, mobile = ?, password = ?, client_title = ? WHERE client_id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($fName, $lName, $email, $mobile, $password, $client_title, $client_id));
			Database::disconnect();
			header("Location: client.php");
		}
		else { // otherwise, update all fields EXCEPT file fields
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE client  set fName = ?, lName = ?, email = ?, mobile = ?, password = ?, client_title = ? WHERE client_id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($fName, $lName, $email, $mobile, $password, $client_title,  $client_id));
			Database::disconnect();
			header("Location: client.php");
		}
	}
} else { // if $_POST NOT filled then pre-populate the form
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM client where client_id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($client_id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	$fName = $data['fName'];
	$lName = $data['lName'];
	$email = $data['email'];
	$mobile = $data['mobile'];
	$password = $data['password'];
	$client_title =  $data['client_title'];
	Database::disconnect();
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
				//require 'functions.php';
				//Functions::logoDisplay2();
			?>
		
			<div class="row">
				<h3>Update Client Details</h3>
			</div>
	
			<form class="form-horizontal" action="clientUpdate.php?id=<?php echo $client_id?>" method="post" enctype="multipart/form-data">
			
				<!-- Form elements (same as file: fr_per_create.php) -->

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
						<input id="password" name="password" readonly type="text"  placeholder="Password" value="<?php echo !empty($password)?$password:'';?>">
						<?php if (!empty($passwordError)): ?>
							<span class="help-inline"><?php echo $passwordError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Title</label>
					<div class="controls">
						<select class="form-control" name="client_title">
							<?php 
							# editor is a volunteer only allow volunteer option
							if (0==strcmp($_SESSION['client_title'],'Client')) echo '<option selected value="Client" >Client</option>';
							else if($client_title==Client) echo 
							'<option selected value="Client" >Client</option><option selected value="Administrator" >Administrator</option>';
							
							else echo
							'<option value="Client">Client</option>';
							?>
						</select>
					</div>
				</div>
			  

			  
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Update</button>
					<a class="btn" href="client.php">Back</a>
				</div>
				
			</form>
			
				<!-- Display photo, if any --> 

				<div class='control-group col-md-6'>
					<div class="controls ">
					<?php 
					if ($data['filesize'] > 0) 
						echo '<br><img  height=20%; width=35%; src="data:image/jpeg;base64,' . 
							base64_encode( $data['filecontent'] ) . '" />'; 
					else 
						echo 'No photo on file.';
					?><!-- converts to base 64 due to the need to read the binary files code and display img -->
					</div>
				</div>
				
		</div><!-- end div: class="span10 offset1" -->
		
    </div> <!-- end div: class="container" -->
	
</body>
</html>