<?php
session_start();
session_unset();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login - HouseLedger</title>
        <link rel="stylesheet" href="style.css">
        <!--link rel="stylesheet" href="css/master.css">
        <link rel="stylesheet" href="css/login.css" -->
    </head>

    <body>
        <div class="container">

<!-- NAV -->            
            <header>


                <!--?php include 'header.php'; //Header? -->
            </header>

<!-- PAGE CONTENT -->
        <div class="loginForm">

            <h1 class="title">Welcome Back!</h1>
            <h2 class="subtitle">Sign in to your account to continue.</h2>
            
            <div class="content-flexbox1">
                <!-- a href=""><button type="reset" class="signInButton"><span>Sign in</span></button></a -->
                
                <!--a href=""><button type="reset" class="signUpButton"><span>Sign up</span></button></a-->
            </div>

            <div class="login-container">
                <form name="login" id="login" method="post" action="login.php">
                <input type="text" id="username" name="username" placeholder="Username"/>
                <input type="password" id="password" name="password" placeholder="Password"/>
                <a><button type="submit" id="submit" name="submit" class="submitButton"><span>Sign in</span></button></a>
                </form>
            </div>
            <?php 
                if(isset($_POST['submit'])){
                    
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    try{
                        include 'db_connect.php';
                        $conn = OpenCon();
                        $sql = $conn->query("SELECT Users.user_id FROM users WHERE users.user_name='$username' AND users.user_password='$password';");
                        foreach($sql as $row){
                            $_SESSION["UserSession"] = $row["user_id"]; 
                            header("Location: index.php");
                        }
                        echo 'Username or Password were incorrect, please try again.';
                    }   
                    catch (mysqli_sql_Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }               
                }
                

            ?>
        </div>
        </div> <!-- Container End -->

<!-- FOOTER -->

    </body>

</html>