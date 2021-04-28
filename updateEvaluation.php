<?php
if (!isset($_COOKIE['account'])) {
    header("LOCATION:login.php");
    return;
}

try {
    $config = parse_ini_file("db.ini");
    $dbh = new PDO($config['dsn'], $config['username'],$config['password']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $numQuestions = $dbh->query("select q_no from questions")->rowCount();

    for ($i=1; $i <= $numQuestions; $i++) {
        $testDuplicate = $dbh->prepare("select * from evaluates where account=:account and id=:id and q_no=:q_no");
        $testDuplicate->execute(array('account' => $_COOKIE['account'], 'id' => $_POST['id'], 'q_no' => $i));
        if ($testDuplicate->rowCount() > 0) {
            $remove = $dbh->prepare("delete from evaluates where q_no=:q_no and account=:account and id=:id");
            $remove->execute(array('q_no' => $i, 'account' => $_COOKIE['account'], 'id' => $_POST['id']));
        } 
        $statement = $dbh->prepare("insert into evaluates values(:account, :id, :q_no, :response)");
        $statement->execute(array(':account' => $_COOKIE['account'], 'id' => $_POST['id'], 'q_no' => $i, 'response' => $_POST['q_no_'.$i]));
    }
    $statement = $dbh->prepare("update takes set eval_status=true, eval_time_taken=now() where account=:account and id=:id");
    $statement->execute(array('account' => $_COOKIE['account'], 'id' => $_POST['id']));
    header("LOCATION:student.php");
} catch (PDOException $e) {
    print "Error!" . $e -> getMessage()."<br/>";
    die();
}

?>