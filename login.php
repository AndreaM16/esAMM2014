<?php
$username = "lelliGiancarlo";
$password = "anatra176";
$host = "localhost";
$db_name = "amm14_lelliGiancarlo";

if (isset($_POST['username']) and isset($_POST['pass']))
{
    $username = $_POST['username'];
    $password = $_POST['pass'];
    
    // Create connection
    $connection=mysqli_connect($host,$username,$password,$db_name,3306);
    if (!$connection){
        die("Database Connection Failed" . mysql_error());
    }
    
    $query = "SELECT * FROM `users` WHERE username='$username' and password='$password'";
    $result = mysql_query($query) or die(mysql_error());
    $count = mysql_num_rows($result);
    mysqli_close($connection);
    
    if ($count == 1)
    {
        $data = mysql_fetch_row($result);
        session_start();
        $_SESSION['id'] = $data[0];
        session_write_close();
        header("Location: homepage.php"); 
    }
    else
    {
        echo("Invalid Login Credentials.\n");
    }
}
else
{
    echo("Error fetching POST params\n");
}