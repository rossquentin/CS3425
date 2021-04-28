<?php

if (!isset($_COOKIE['account'])) {
	header("LOCATION:login.php");
	return;
}
?>


<html>

<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>
	<nav class="navbar navbar-dark bg-dark navbar-expand-lg fixed-top">
		<div class="container">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="student.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="register.php">Register</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="logout.php">Logout</a>
				</li>
			</ul>
		</div>
	</nav>
	<div class="container" style="padding-top:75">
		<div class='col'>
		<h3>Course List</h3>
		<p>Click the checkbox and click register to register for a course</p>
		</div>
		<?php
		try {
			$config = parse_ini_file("db.ini");
			$dbh = new PDO($config['dsn'], $config['username'], $config['password']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			echo "<form action='registerAccount.php' method='POST'>";
			echo "<table class='table'>";
			echo "<thead class='table-borderless'>";
			echo    "<tr>";
			echo    "<th></th>";
			echo    "<th class='w-25'> ID </th>";
			echo    "<th class='w-50'> Title </th>";
			echo    "<th class='w-25'> Credits </th>";
			echo    "</tr>";
			echo "</thead>";
			echo "<tbody>";
			foreach ($dbh->query("select * from courses") as $course) {
				echo "<tr>";
				echo "<td style='text-align:center'><input type='checkbox' name='".$course[0]."'/></td>";
				echo "<td> ".$course[0]."</td>";
				echo "<td> ".$course[1]."</td>";
				echo "<td> ".$course[2]."</td>";
				echo "</tr>";
			}
			echo "</tbody>";
			echo "</table>";
			echo "<input class='btn btn-dark' type='submit' name='Submit' value='Register'/>";
			echo "</form>";
		} catch (PDOException $e) {
			print "Error!" . $e->getMessage() . "<br/>";
			die();
		}
		?>
	</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>