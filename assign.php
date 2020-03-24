<?php 
session_start();
if(!isset($_SESSION["client_id"])){ // if "user" not set,
	session_destroy();
	header('Location: liblogin.php');   // go to login page
	exit;
}
//$id = $_GET['id']; // for MyAssignments
$sessionid = $_SESSION['client_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
	

		<div class="row">
				<p>
					<h3>Assignments</h3> <br>


					<a href="client.php" class="btn btn-info">Clients</a>
					<a href="material.php" class="btn btn-info">Books</a>
					<?php if($_SESSION['client_title']=='Administrator')
					echo '<a href="assignCreate.php" class="btn btn-dark">Add Assignment</a>'
					?>
					

                </p>
		</div>
		
		<div class="row">

			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Book Type</th>
						<th>author</th>
						<th>Type</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					include 'libDatabase.php';
					//include 'functions.php';
					$pdo = Database::connect();
					
					$sql = "SELECT * FROM assign 
						LEFT JOIN client ON client.client_id = assign.assign_client_id 
						LEFT JOIN material ON material.id = assign.assign_material_id
						ORDER BY name ASC;";


					foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>'. $row['name'] . '</td>';
						echo '<td>'. $row['author'] . '</td>';
						echo '<td>'. $row['type'] . '</td>';
						echo '<td>'. $row['fName'] . '</td>';
						echo '<td>'. $row['lName'] . '</td>';
						
						echo '<td width=250>';
						# use $row[0] because there are 3 fields called "id"
						echo '<a class="btn" href="assignRead.php?id='.$row[0].'">Details</a>';
						if($_SESSION['client_title']=='Administrator')
						echo '<a class="btn btn-danger" href="assignDelete.php?id='.$row[0].'">Delete</a>';
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