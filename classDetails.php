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
                    <a class="nav-link" href="instructor.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container" style="padding-top:50">
        <br>
        <div class="row">

        <?php
        echo "<div class='col'>";
        echo '<h3 class="my-auto"> Class Overview <a class="float-right" href="surveyResults.php?id='.$_GET['id'].'">Evaluation Results</a></h3>';
        echo "</div>";
            
            try {
                $config = parse_ini_file("db.ini");
                $dbh = new PDO($config['dsn'], $config['username'],$config['password']);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $taking = $dbh->prepare("select account, eval_status from takes where id=:id");
                $taking->execute(array('id' => $_GET['id']));

                echo "<table class='table table-hover'>";
                echo "<thead class='table-borderless'>";
                echo    "<tr>";
                echo    "<th scope='col' style='width: 20%'> Account </th> ";
                echo    "<th scope='col'> Name </th>";
                echo    "<th scope='col' style='width: 20%'> Evaluation Status </th>";
                echo    "</tr>";
                echo "</thead>";
                echo "<tbody>";
                if ($taking->rowCount() > 0) {
                    foreach ($taking->fetchAll(PDO::FETCH_ASSOC) as $student) {
                        $name = $dbh->prepare("select name from students where account=:account");
                        $name->execute(array('account' => $student['account']));
                        echo "<tr>";
                        echo "<td>".$student['account']."</td>";
                        echo "<td>".$name->fetchColumn(0)."</td>";
                        if ($student['eval_status'] == 0) {
                            echo "<td> Incomplete </td>";
                        } else {
                            echo "<td> Complete </td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
                else {
                    echo "<tr>";
                    echo "<td> NO DATA </td>";
                    echo "<td> NO DATA </td>";
                    echo "</tr>";
                    echo "</tbody>";
                    echo "</table>";
                }
                
            } catch (PDOException $e) {
                print "Error!" . $e->getMessage()."<br/>";
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