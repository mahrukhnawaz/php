<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <script src="file.js" type="text/javascript"></script>    
    <title>My Todo | TODO</title>

<?php
    session_start();
    function db ()
    {
        static $conn;
        $conn = new mysqli("localhost","root","","todo");
        if($conn->connect_error)
        {
            die("Connection Failed! ".$conn->connect_error);
        }
        return $conn;    
    }
    if(empty($_SESSION['id']))
        header("location:login.php");
        
    //from there

    //new record
    function newTask(){
        $conn = db();

        if(empty($_POST['task'])){
            return;
        }
        $user_id = $_SESSION['id'];
        $user_task = $_POST['task'];
        $sql = "INSERT INTO todos (user_id,task,isdone) VALUES ('$user_id','$user_task',0)";
        if($conn->query($sql) == TRUE)
        {
            echo "<div class='alert alert-success'> New Task Added! </div>";  
            header("location:indexPage.php");
        }
        else
            echo $conn->error;
        
        $conn->close();

    }

    if(isset($_POST['addrecord']))
    {
        newTask();
    }
    // Complete Task Update 
    function markAsDone($taskid)
    {
        $conn = db();
        $sql = "UPDATE todos SET isDone = 1 WHERE todo_id = ".$taskid."";
        if($conn->query($sql) === TRUE)
        {
            header("location: indexPage.php");
        }
        else
            $conn->error;
    }
    if(isset($_GET['done']))
    {
        markAsDone($_GET['done']);
    }
    // Edit
    function taskChange($tid)
        {
            $conn = db();
            $uid = $_SESSION['id'];

            $sql = "SELECT * FROM todos WHERE user_id = $uid AND todo_id = $tid";
            $result = $conn->query($sql);
            $res = $result->fetch_assoc();
            echo "<div class='container-fluid'>";
            echo "<form action='indexPage.php' method='POST'>";
            echo "<input type='hidden' name='id' value= ".$res['todo_id'].">";
            echo "<input type='text' name='edittask' value= ".$res['task'].">";
            echo "<input type='submit' value='Save' name='updaterecord'>";
            echo "</form>";
            echo "</div>";
            // echo '<script type="text/javascript"> console.log("YES!!!");  designInput(); </script>';
            $conn->close();
        }
    if(isset($_GET['tid']))
    {
        taskChange($_GET['tid']);
    }
    function edit($task,$id)
    {
        $conn = db();
        $sql = "UPDATE todos SET task= '$task' WHERE todo_id = $id";
        if($conn->query($sql) === TRUE)
        {
            header("location: indexPage.php");
        }
        else 
            echo $conn->error;

        $conn->close();
    }
    if(isset($_POST['updaterecord']) && !empty($_POST['edittask']))
    {
        edit($_POST['edittask'],$_POST['id']);
    }

    //Delete
    function delete($tid)
    {
        $conn = db();
        $sql = "DELETE FROM todos WHERE todo_id = $tid";
        if($conn->query($sql) === TRUE)
        {
            echo "<div class='alert alert-success'> Task Deleted. </div>";
            header("location: indexPage.php");
        }
        else
            $conn->error;
    }
    if(isset($_GET['tiid']))
    {
        delete($_GET['tiid']);
    }
    //logout
    function logout()
    {
        session_destroy();
        header("location: login.php");
    }
    if(isset($_POST['logout']))
    {
        logout();
    }
?>
</head>
<body style="background: url(css/bg2.jpg) no-repeat center center fixed; background-size: cover;">
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
                <a><form action="indexPage.php" method="POST">
                    <input type="submit" value="Logout" name="logout" id="logoutBTN">
                </form></a>
                <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                    <i class="fa fa-bars" style="color:black;"></i>
                </a>
            </ul>
            <div class="userName">
                <p>Hello, <?php echo $_SESSION['fname']; ?></p>
            </div>
        </div>
    </nav>
    </header>
    <!-- Content of Page -->
    <div class="container-fluid title-div">
        <h1 style="font-size: 6rem;" class="main-text">My Todo's</h1>
    </div>
    <div class="container-fluid">
        <?php
            $conn = db();
        
            // Extrect and print sessioned user data
            $sql = "SELECT * FROM todos WHERE user_id = '".$_SESSION['id']."'";
            $result = $conn->query($sql);
            echo "<table border=1>";
            echo "<thead>";
            echo "<tr>";
                echo "<th>Tasks</th>";
                echo "<th>Operations</th>";
            echo "</tr>";
            echo "</thead>";
            if(mysqli_num_rows($result) > 0)
            {
                while($res = $result->fetch_assoc())
                {
                    echo "<tr>";
                    if($res['isdone']){
                        echo "<td class='completed'> ".$res['task']." </td>";
                        echo "<td  class='btncompleted'>
                        <a href='indexPage.php?tiid=".$res['todo_id']."' class='delete'>Delete</a> 
                        </td>";
                    }
                    else{
                        echo "<td class=''> ".$res['task']."</td>";
                        echo "<td class=''> 
                                <a href='indexPage.php?tid=".$res['todo_id']."' class='edit'>Edit</a> 
                                <a href='indexPage.php?tiid=".$res['todo_id']."' class='delete'>Delete </a>
                                <a href='indexPage.php?done=".$res['todo_id']."' class='done'>Done </a> 
                            </td>";
                    }
                    echo "</tr>";
                }
                
            }
            else{
                echo "<tr >";
                echo "<td colspan=2 class='sub-text'> No Tasks yet!! </td>";
                echo "</tr>";
            }
            echo "</table>";
        ?>
    </div>
    
    <div class="container-fluid col-md-5 col-sm-12 customTaskDIV">
        <form action="indexPage.php" method="POST">
            <input type="text" name="task" id="" placeholder="Add Task" required>
            <div class="container-fluid col-md-5 col-sm-12 TaskSubmitDIV">
                <input type="submit" value="Add" name="addrecord" class="btn btn-primary">
            </div>
        </form>
    </div>
    <!-- End of content -->

    <!-- Footer -->
    <footer>
        <div class="container-fluiud " id="custom-footer" style="background-color: rgb(27, 72, 90);margin:0px; padding:2%; color:lightgray;">
            <p>All rights are reserved. Site design / logo © 2021 Stack Exchange Inc; user contributions</p>
            <p>licensed under cc by-sa. rev 2021.1.21.38376</p>
        </div>
    </footer>
</body>
</html>
