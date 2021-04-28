<?php
if (!isset($_COOKIE['account'])) {
    header("LOCATION:login.php");
    return;
}

try {
    $config = parse_ini_file("db.ini");
    $dbh = new PDO($config['dsn'], $config['username'],$config['password']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach ($dbh->query("select * from courses") as $course) {
        $testDuplicate = $dbh->prepare("select * from takes where account=:account and id=:id");
        $testDuplicate->execute(array('account' => $_COOKIE['account'], 'id' => $course[0]));
        if ($testDuplicate->rowCount() == 0 && isset($_POST[''.$course[0].''])) {
            $statement = $dbh->prepare("insert into takes values(:account, :id, false, 0)");
            $statement->execute(array('account' => $_COOKIE['account'], 'id' => $course[0]));
        } 
    }
    header("LOCATION:student.php");
} catch (PDOException $e) {
    print "Error!" . $e -> getMessage()."<br/>";
    die();
}

?>