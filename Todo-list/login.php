<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <title>Login | TODO</title>

<?php
    session_start();
    if(!empty($_SESSION['id']))
        header("location:indexPage.php");
        
    function login_validations()
    {
        // Connection
        $conn = new mysqli("localhost","root","","todo");
        if($conn->connect_error)
        {
            die("Connection Failed! ".$conn->connect_error);
        }
        
        // fetch data from forms
        $username = $_POST['username'];
        $pass = $_POST['password'];

        //validate fields
        if(empty($username))
        {
            echo "<div class='alert alert-danger'> Username is Required </div>";
            return;
        }
        else if(empty($pass))
        {
            echo "<div class='alert alert-danger'> Password is Required </div>";
            return;
        }

        // retriving data
        $sql = "SELECT * FROM users WHERE username='$username'";
        $queryResult = $conn->query($sql); 
        $result = $queryResult->fetch_assoc();
        if($result)
        {
            if($result['username'] === $username && $result['pass'] === md5($pass))
            {
                echo "<div class='alert alert-success'> loged In. </div>";

                // creating user session
                session_start();
                $_SESSION['id'] = $result['id'];
                $_SESSION['fname'] = $result['fname'];
                $_SESSION['username'] = $result['username'];
                header("location:indexPage.php");
            }
            if($result['username'] != $username || $result['pass'] != md5($pass))
            {
                echo "<div class='alert alert-danger'> Invalid username or password. </div>";
                return;
            }
        }
        else{
            echo "<div class='alert alert-danger'> Invalid username or password. </div>";
            return;
        }

        $conn->close();
    }

    if(isset($_POST['login']))
    {
        login_validations();
    }
?>
<script>
function myFunction() 
    {
        var x = document.getElementById("mynav");
        if (x.className === "mynavbar col-md-12 col-sm-12 col-lg-12") {
            x.className += " responsive";
        }
    }
</script>
</head>
<body style="background: url(css/bg1.jpg) no-repeat center center fixed; background-size: cover;">
    <!-- HEADER OF PAGE-->
    <header>
    <nav class="mynavbar col-md-12 col-sm-12 col-lg-12" id="mynav">
        <div class="custom-container">
            <div class="mynav-logo">
                <img src="css/logo.jpg" alt="Todo logo" id="navbar-logo" >
            </div>
            <div class="container-fluid"> 
                <h1 class="main-text">TODO</h1> 
            </div>
            <ul class="mynav-elements">
                <a href="indexPage.php"><li>Home</li></a>
                <a href="login.php"><li>Login</li></a>
                <a href="signup.php"><li>Sign Up</li></a>
                <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                    <i class="fa fa-bars" style="color:black;"></i>
                </a>
            </ul>
        </div>
    </nav>
    </header>
    <!-- Content of Page -->
<div class="container-fluid col-md-3 col-sm-3 customMainDIV">
    <div class="container-fluid form-heading">
        <h2>Login</h2>
    </div>
        <form action="login.php" method="POST">
            <div class="container-fluid">
                <input type="text" name="username" id="username" placeholder="username">
            </div>
            <div class="container-fluid">
                <input type="password" name="password" id="password" placeholder="Password">
            </div>
            <div class="container-fluid">
                <input type="submit" name="login" value="Login" class="signupBTN">
            </div>
        </form>
        <form action="signup.php">
        <div class="container-fluid">
        <p>Don't have an account?</p>
            <input type="submit" name="nn" value="Sign Up" class="btn btn-primary">
        </div>
        </form>
    </div>
    <!-- End of content -->

    <!-- Footer -->
    <footer>
        <div class="container-fluid " id="custom-footer" style="background-color: rgb(27, 72, 90);margin-bottom:0px; padding:2%; color:lightgray;">
            <p>All rights are reserved. Site design / logo © 2021 Stack Exchange Inc; user contributions</p>
            <p>licensed under cc by-sa. rev 2021.1.21.38376</p>
        </div>
    </footer>
</body>
</html>