<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Upload</title>

<?php
    $conn = mysqli_connect("localhost","root","","dbimg");
    if($conn->connect_error)
    {
        die("Connection failed: ". $conn->connect_error);
    }
    if(isset($_POST['submit']) && !empty($_FILES["file"]["name"]) && !empty($_FILES["img"]["name"]))
    {
        //file
        $targetDir = "fuploads/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath,PATHINFO_EXTENSION));
        //image
        $targetDir1 = "iuploads/";
        $imgName = basename($_FILES["img"]["name"]);
        $targetImgPath = $targetDir1 . $imgName;
        $imgType = strtolower(pathinfo($targetImgPath,PATHINFO_EXTENSION));

        $name = $_POST["name"];
        $email = $_POST["email"];
        $pass = $_POST["pass"];
        $phone = $_POST["phone"];

        $fileTypesAllowed = array('pdf','doc','docx','pptx');
        $imgTypesAllowed = array('jpg','png','jpeg','gif');
        if(in_array($fileType,$fileTypesAllowed))
        {
            if(in_array($imgType,$imgTypesAllowed))
            {
                if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath) && move_uploaded_file($_FILES["img"]["tmp_name"], $targetImgPath))
                {
                    $sql = "INSERT INTO data (name,email,pass,img,file,phoneNumber) VALUES ('".$name."','".$email."','".$pass."','".$imgName."','".$fileName."','".$phone."')";
                    
                    if($conn->query($sql))
                    {
                        echo "Files has been uploaded!!";
                        //display
                        $query = $conn->query("SELECT * FROM data ORDER BY id DESC");

                        if($query->num_rows > 0){
                            while($row = $query->fetch_assoc()){
                                $imageURL = 'iuploads/'.$row["img"];
                            
                                echo "<img src='".$imageURL."' alt='' height='300' width='300'/>";
                            }
                        } 
                    }else{
                        echo "File upload failed, please try again.";
                    }
            }
        }else{
            echo "Invalid Image Type!!";
        }
    }else{
        echo "Invalid File Type!!";
    }
}

?>
</head>
<body>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
    <div class="container-fluid">
        <input type="text" name="name" placeholder="Name"> <br>
    </div>
    <div class="container-fluid">
        <input type="text" name="email" placeholder="Email"><br>
    </div>
    <div class="container-fluid">
        <input type="text" name="pass" placeholder="Password"><br>
    </div>
    <div class="container-fluid">
        <input type="text" name="phone" placeholder="Phone Number"><br>
    </div>
    <div class="container-fluid">
        <input type="file" name="file"><br>
    </div>
    <div class="container-fluid">
        <input type="file" name="img"  accept="image/*"><br>
    </div>
    <div class="container-fluid">
        <input type="submit" name="submit" value="Submit">
    </div>
    </form>
</body>
</html>