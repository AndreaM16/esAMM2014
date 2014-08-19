<?php
include 'config.php';
session_start();

if(isset($_GET['type']))
{
    $action = $_GET['type'];
    switch ($action)
    {
        case 0:
            // Carica news
            header('Content-Type: application/json');
            $result = LoadItems("SELECT date_posted,title,link FROM news ORDER BY date_posted DESC LIMIT 5");
            echo($result);
            break;
        case 1:
            header('Content-Type: application/json');
            if($_SESSION['is_admin'] == 1) {
                // Carica ordini admin
                $result = LoadItems("SELECT * FROM orders AS o, users AS u WHERE o.cliente_id=u.id");
            }
            else {
                // Carica ordini utente
                $id = $_SESSION['id'];
                $result = LoadItems("SELECT * FROM orders WHERE cliente_id=$id");
            }
            echo($result);
            break;
        case 2:
            // Carica utenti
            header('Content-Type: application/json');
            $result = LoadItems("SELECT * FROM users");
            echo($result);
            break;
        case 3:
            // Segna pagato
            $order_id = $_GET['id'];
            $output = PerformQuery("UPDATE `orders` SET status = 1 WHERE order_id=$order_id");
            if ($output) {
                header('Location: ../homepage.php');
            } else {
                header("HTTP/1.0 400 Bad Request");
            }
            break;
        case 4:
            // resetta pwd
            $user_id = $_GET['id'];
            $output = PerformQuery("UPDATE `users` SET pwd = 'temp' WHERE id=$user_id");
            if ($output) {
                header('Location: ../homepage.php');
            } else {
                header("HTTP/1.0 400 Bad Request");
            }
            break;
        case 5:
            // fai admin
            $user_id = $_GET['id'];
            $output = PerformQuery("UPDATE `users` SET is_admin = 1 WHERE id=$user_id");
            $_SESSION['is_admin'] = 1;
            if ($output) {
                header('Location: ../homepage.php');
            } else {
                header("HTTP/1.0 400 Bad Request");
            }
            break;
        case 6:
            // declass
            $user_id = $_GET['id'];
            $output = PerformQuery("UPDATE `users` SET is_admin = 0 WHERE id=$user_id");
            $_SESSION['is_admin'] = 0;
            if ($output) {
                header('Location: ../homepage.php');
            } else {
                header("HTTP/1.0 400 Bad Request");
            }
            break;
        case 7:
            // add news
            break;
        case 8:
            // add user
            break;
    }
}
else
{
    header("HTTP/1.0 400 Bad Request");
}

function LoadItems($stringQuery) {
    global $host;
    global $usernameDB;
    global $passwordDB;
    global $db_name;
    
    $connection=mysqli_connect($host,$usernameDB,$passwordDB);
    if (!$connection){
        die("Database Connection Failed\n" . mysql_error());
    }
    
    $selecting = mysqli_select_db($connection,$db_name);
    if (!$selecting){
        die("Database Selection Failed\n" . mysql_error());
    }

    $result = mysqli_query($connection,$stringQuery) or die(mysql_error());
    mysqli_close($connection);
    
    $output = array();
    while($row = mysqli_fetch_assoc($result)){
        $output[] = $row;
    }
    
    $json = json_encode($output);
    return $json;
}

function PerformQuery($queryString) {
    global $host;
    global $usernameDB;
    global $passwordDB;
    global $db_name;
    
    $connection=mysqli_connect($host,$usernameDB,$passwordDB);
    if (!$connection){
        die("Database Connection Failed\n" . mysql_error());
    }
    
    $selecting = mysqli_select_db($connection,$db_name);
    if (!$selecting){
        die("Database Selection Failed\n" . mysql_error());
    }

    $result = mysqli_query($connection,$queryString) or die(mysql_error());
    mysqli_close($connection);
    
    return $result;
}