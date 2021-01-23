<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <title>Sign Up | TODO</title>
<?php
    // User already logged in
    session_start();
    if(!empty($_SESSION['id']))
        header("location:indexPage.php");

    function signup_validations()
    {
        // Connection
        $conn = new mysqli("localhost","root","","todo");
        if($conn->connect_error)
        {
            die("Connection Failed! ".$conn->connect_error);
        }

        // fetch data from forms
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $pass = $_POST['password'];

        //validate fields
        if(empty($fname))
        {
            echo "<div class='alert alert-danger'> First name is Required </div>"; 
            return;
        }
        else if(empty($lname))
        {
            echo "<div class='alert alert-danger'> Last name is Required </div>";
            return;
        }
        else if(empty($username))
        {
            echo "<div class='alert alert-danger'> Username is Required </div>";
            return;
        }
        else if(empty($email))
        {
            echo "<div class='alert alert-danger'> Email is Required </div>";
            return;
        }
        else if(empty($pass))
        {
            echo "<div class='alert alert-danger'> Password is Required </div>";
            return;
        }

        // checking if user already exists
        $sql = "SELECT * FROM users WHERE email='$email' OR username='$username'";
        $queryResult = $conn->query($sql); 
        $result = $queryResult->fetch_assoc();
        if($result)
        {
            if($result['username'] === $username)
            {
                echo "<div class='alert alert-danger'> Username already taken. Please try something else. </div>";
                return;
            }
            else if($result['email'] === $email)
            {
                echo "<div class='alert alert-danger'> User already exist. Try loging in. </div>";
                return;
            }
        }
        // encrypt to hash password
        $password = md5($pass);

        // Creating user
        $sql = "INSERT INTO users (fname,lname,username,email,pass) VALUES ('$fname','$lname','$username','$email','$password')";
        if($conn->query($sql) == TRUE)
        {
            echo "<div class='alert alert-success'> Account has been created. </div>";  
            header("location:login.php");
        }
        else
	        echo $conn->error;


        $conn->close();
    }

    if(isset($_POST['signup']))
    {
        signup_validations();
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
<body style=" background: url(css/bg.jpg) no-repeat center center fixed; background-size: cover;">
    <!-- HEADER OF PAGE-->
    <header>
    <nav class="mynavbar col-md-12 col-sm-12 col-lg-12" id="mynav">
        <div class="custom-container">
            <div class="mynav-logo">
                <img src="css/logo.jpg" alt="Todo logo" id="navbar-logo" >
            </div>
            <div class="container-fluid "> 
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
    <div class="container-fluid col-md-5 col-sm-3 customMainDIV">
        <div class="container-fluid form-heading" >
            <h3>Sign Up</h3>
        </div>
        <form action="signup.php" method="POST">
            <div style="display:flex;">
                <div class="container-fluid">
                    <input type="text" name="fname" id="fname" placeholder="First Name">
                </div>
                <div class="container-fluid">
                    <input type="text" name="lname" id="lname" placeholder="Last Name">
                </div>
            </div>
            <div class="container-fluid">
                <input type="text" name="username" id="username" placeholder="Username">
            </div>
            <div class="container-fluid">
                <input type="email" name="email" id="email" placeholder="Email">
            </div>
            <div class="container-fluid">
                <input type="password" name="password" id="password" placeholder="Password">
            </div>
            <div class="container-fluid col-md-12">
                <input type="submit" name="signup" value="Sign Up" class="signupBTN">
            </div>
        </form>
        <form action="login.php">
        <div class="container-fluid">
        Already have one?
            <input type="submit" name="nn" value="Login" class="btn btn-primary">
        </div>
        </form>
    </div>
    <!-- End of content -->

    <!-- Footer -->
    <footer>
        <div class="container-fluid" style="background-color: rgb(27, 72, 90);margin-bottom:0px; padding:2%; color:lightgray;">
            <p>All rights are reserved. Site design / logo © 2021 Stack Exchange Inc; user contributions</p>
            <p>licensed under cc by-sa. rev 2021.1.21.38376</p>
        </div>
    </footer>
</body>
</html>