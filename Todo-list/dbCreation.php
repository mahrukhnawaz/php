<?php
    $conn = new mysqli("localhost","root","","todo");
    if($conn->connect_error)
    {
        die("Connection Failed! ".$conn->connect_error);
    }

    // creating users tables
    $sql = "CREATE TABLE users (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        fname VARCHAR(50) NOT NULL,
        lname VARCHAR(50) NOT NULL,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        pass VARCHAR(50) NOT NULL
    )";

    if ($conn->query($sql) === TRUE) 
    {
        echo "Table users created successfully!!";
    } 
    else 
    {
        echo "Error creating table: " . $conn->error;
    }

    // creating user tables
    $sql = "CREATE TABLE todos(
        user_id INT,
        todo_id INT NOT NULL AUTO_INCREMENT,
        task VARCHAR(500) NOT NULL,
        isdone BOOLEAN,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        PRIMARY KEY(`todo_id`)
    )";
    if ($conn->query($sql) === TRUE) 
    {
        echo "Table todos created successfully!!";
    } 
    else 
    {
        echo "Error creating table: " . $conn->error;
    }

    $conn->close();
?>