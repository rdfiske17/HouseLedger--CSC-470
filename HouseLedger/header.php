<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = -12;
$logged_in = false;
if (isset($_SESSION["UserSession"])){
    //echo $_SESSION["UserSession"];
    //echo "Session is set";
    $user_id = $_SESSION["UserSession"];
    //echo $user_id;
}
if ($user_id != -12){
    $logged_in = true;
}

echo '

<!DOCTYPE html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>

    <header>
        <div class="header-wrapper">
            <div class="logo-bar">
                <img src="img/icon.png"class="logo">
            </div>

            <div class="header-container">
                <nav>
                    <ul class="topNav">
                        <li><a href="index.php"><b>Home</b></a></li>';
                        if($logged_in == false){ echo'<li><a href="login.php"><b>Login</b></a></li> ' ;}
                        if($logged_in == true){ echo '<li><a  href="log_out.php"><b>Logout</b></a></li> ';}
                        if($logged_in == true){ echo '<li><a  href="view_receipts.php"><b>View Receipts</b></a></li> ' ;}
                        if($logged_in == true){ echo '<li><a  href="your_receipts.php"><b>Your Receipts</b></a></li> ' ;}
                        if($logged_in == true){ echo '<li><a  href="new_receipt.php"><b>Create New Receipt</b></a></li> ' ;}
                        if($logged_in == true){ echo '<li><a  href="view_balances.php"><b>View Balances</b></a></li> ' ;} echo '
                    </ul>
                </nav>
            </div>
        </div>
    </header>';
?>