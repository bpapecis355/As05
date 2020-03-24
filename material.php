<?php

session_start();
if(!isset($_SESSION["client_id"])){ // if "user" not set,
	session_destroy();
	header('Location: liblogin.php');     // go to login page
	exit;
}
$sessionid = $_SESSION['client_id'];
//include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body style="background-color: white !important";>
    <div class="container">
		  <?php 
			//gets logo
			//functions::logoDisplay2();
		?>
		<div class="row">
			<h3>All Material</h3>
		</div>
		
		<div class="row">
			<p>
				<?php if($_SESSION['client_title']=='Administrator')
					echo '<a href="materialCreate.php" class="btn btn-primary">Add Material</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['client_title']=='Administrator')
					echo '<a href="client.php">Clients</a> &nbsp;';
				?>

			
				<a href="assign.php?id=<?php echo $sessionid; ?>">All Rentals</a>&nbsp;
					<?php if($_SESSION['client_title']=='Administrator') 
					echo '<a class="btn btn-success" href="assignCreate.php">New Rental</a>'; ?>
					
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Name</th>
						<th>author</th>
						<th>Type</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
			<?php 
						include 'libDatabase.php';
						$pdo = Database::connect();
						//$sql = 'SELECT `material`.*, SUM(case when rent_id ='. $_SESSION['id'] .' then 1 else 0 end) AS sumAssigns, COUNT(`rental`.rent_material_id) AS countAssigns FROM `material` LEFT OUTER JOIN `rental` ON (`material`.id=`material`.material_id) GROUP BY `material`.id';
						
					$sql = 'SELECT * FROM material ';
						 foreach ($pdo->query($sql) as $row) {
							echo '<tr>';

							echo '<td>'. ($row['name']) . '</td>';
							echo '<td>'. ($row['author']) . '</td>';
							echo '<td>'. ($row['type']) . '</td>';

							echo '<td>';
							echo '<a class="btn" href="materialRead.php?id='.$row['id'].'">Details</a> &nbsp;';
							
							if ($_SESSION['client_title']=='Administrator' )
								echo '<a class="btn btn-success" href="materialUpdate.php?id='.$row['id'].'">Update</a>&nbsp;';
							if ($_SESSION['client_title']=='Administrator')
								echo '<a class="btn btn-danger" href="materialDelete.php?id='.$row['id'].'">Delete</a>';


							//if($row['sumAssigns']==1) 
								//echo " &nbsp;&nbsp;Me";
							echo '</td>';
							echo '</tr>';
						} 
						Database::disconnect();
					?>
				</tbody>
			</table>
    	</div>
	
    </div> <!-- end div: class="container" -->
	
  </body>
  
</html>