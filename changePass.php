<?php
$message = "";
$success = false;
try{
    $config = parse_ini_file("db.ini");
    $dbh = new PDO($config['dsn'], $config['username'],$config['password']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_POST["loginType"])) {
        $loginType = $_POST["loginType"];
        if ($loginType == "student") {
            $statement = $dbh->prepare("select * from students where account=:account");
            
        } else if ($loginType == "instructor")  {
            $statement = $dbh->prepare("select * from instructors where account=:account");
        }

        $statement->execute(array('account' => $_POST['account']));
        $password1 = $_POST['password'];
        $password2 = $_POST['passwordRetype'];
        if ($statement->rowCount() > 0) {
            if (strlen($password1) < 7) {
                $message = "Password needs to be at least 7 characters long";
            } else if (strcmp($password1, $password2) != 0) {
                $message = "Passwords must match";
            } else {
                if ($loginType == "student") {
                    $statement = $dbh->prepare("update students set password=SHA2(:password,256) where account=:account") ;
                    $result = $statement->execute(array(':password' => $_POST['password'], ':account' => $_POST['account']));
                } else if ($loginType == "instructor") {
                    $statement = $dbh->prepare("update instructors set password=SHA2(:password,256) where account=:account");
                    $result = $statement->execute(array(':password' => $_POST['password'], ':account' => $_POST['account']));            
                }
                $message = "Password successfully changed";
                $success = true;
            }
        }
        else {
             $message = "Invalid account";
        }
    } else {
        $message = "Please select an account type";
    }
} catch (PDOException $e) {
    print "Error!" . $e -> getMessage()."<br/>";
    die();
}
?>


<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body>
    <div class=container>
        <h1>Change password</h1>
        <br>
        <form action="changePass.php" method="POST">
            <div class="row">
                <div class="col w-50">
                <label for="account" size="30">Account name:</label>
                <input class="form-control" type="text" name="account" placeholder="Account name"/>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <label for="password" size="30">Password:</label>
                    <input class="form-control" type="password" name="password" placeholder="Password"/>
                    
                </div>
                <div class="col">
                    <label for="password" size="30">Re-type Password:</label>
                    <input class="form-control" type="password" name="passwordRetype" placeholder="Re-type password"/>
                </div>
            </div>
            <br>
            <?php
            if ($success == true) {
                echo '<p style="color:green">'.$message.'</p>';
            } else {
                echo '<p style="color:red">'.$message.'</p>';
            }
            ?>
            <label for="loginType">Account type:</label>
            <br>
            <input type="radio" name="loginType" value="student"> Student</input>
            <br>
            <input type="radio" name="loginType" value="instructor"> Instructor</input>
            
            <br>
            <br>
            <input class="btn btn-dark" type="submit" name="Submit" value="Change Password"/>
            <br>            
        </form>
        
        <a href="login.php">Login</a>
        
    </div>
    
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>