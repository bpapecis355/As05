<?php 

session_start();
if(!isset($_SESSION["client_id"])){ // if "user" not set,
	session_destroy();
	header('Location: liblogin.php');     // go to login page
	exit;
}

require 'libDatabase.php';
//require 'functions.php';

if ( !empty($_POST)) { // if not first time through

	// initialize user input validation variables
	$nameError = null;
	$authorError = null;
	$typeError = null;
	
	// initialize $_POST variables
	$name = $_POST['name'];
	$author = $_POST['author'];
	$type = $_POST['type'];
	
	// validate user input
	$valid = true;
	if (empty($name)) {
		$nameError = 'Please enter Date';
		$valid = false;
	}
	if (empty($author)) {
		$authorError = 'Please enter Time';
		$valid = false;
	} 		
	if (empty($type)) {
		$typeError = 'Please enter Location';
		$valid = false;
	}		
	

	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO material (name, author, type) values(?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($name,$author,$type));
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
		<?php 
			//gets logo
			//functions::logoDisplay();
		?>	
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Add New Material (book,PDF,video etc.)</h3>
			</div>
	
			<form class="form-horizontal" action="materialCreate.php" method="post">
			
				<div class="control-group <?php echo !empty($nameError)?'error':'';?>">
					<label class="control-label">Name</label>
					<div class="controls">
						<input name="name" type="text"  placeholder="Material Name" value="<?php echo !empty($name)?$name:'';?>">
						<?php if (!empty($nameError)): ?>
							<span class="help-inline"><?php echo $nameError;?></span>
						<?php endif; ?>
					</div>
				</div>
			  
				
				<div class="control-group <?php echo !empty($authorError)?'error':'';?>">
					<label class="control-label">author</label>
					<div class="controls">
						<input name="author" type="text" placeholder="author" value="<?php echo !empty($author)?$author:'';?>">
						<?php if (!empty($authorError)): ?>
							<span class="help-inline"><?php echo $authorError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($typeError)?'error':'';?>">
					<label class="control-label">Type</label>
					<div class="controls">
						<input name="type" type="text" placeholder="Type" value="<?php echo !empty($type)?$type:'';?>">
						<?php if (!empty($typeError)): ?>
							<span class="help-inline"><?php echo $typeError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Create</button>
					<a class="btn" href="material.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- div: class="container" -->
				
    </div> <!-- div: class="container" -->
	
</body>
</html>