<?php 
define('HOST', 'DBHost');
//mysqli_errno
define('USER', 'DBUser');
define('PASS', 'DBPass');
define('BASE', 'DBName');

$con = mysqli_connect(HOST, USER, PASS, BASE);
if(!$con){
    //printf('could not connect to mysql'.mysqli_errno()."\n");
    //echo 'an error occurred while registering your email, <a href="inscription.html">please try again</a>';
    echo "conn error";
}
// $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

// $server = $url["host"];
// $username = $url["user"];
// $password = $url["pass"];
// $db = substr($url["path"], 1);

// $con = mysqli_connect($server, $username, $password, $db);
// if(mysqli_connect_errno()){
//     //die('could not connect to mysql'.mysqli_connect_error()."\n");
//     echo 'an error occurred while registering your email, <a href="inscription.html">please try again</a>';
// }

?>