<?php

    $link = mysqli_connect("shareddb-g.hosting.stackcp.net","vastukosh-32353f7f","password98@","vastukosh-32353f7f");

    $query = "UPDATE `users` SET `status` = 1 WHERE `email` = '".mysqli_real_escape_string($link, $_GET['email'])."'";

    if(mysqli_query($link, $query)) {
        echo "<script> alert('Email address verified successfully. Update adhaar details and your are good to go!); </script>";
    }

    if(isset($_POST['submit'])) {
        $errors = "";
        if($_POST['idno'] != "" && $_FILES['idpic']['name'] != "") {
            $file_name = $_FILES['idpic']['name'];
            $file_tmp =$_FILES['idpic']['tmp_name'];
            $file_ext=strtolower(end(explode('.',$_FILES['idpic']['name'])));

            $extensions= array("jpeg","jpg","png");

            if(in_array($file_ext,$extensions)=== false){
                $errors .= "Extension not allowed, please choose a JPEG or PNG file!\\n";
            }
            
            if(strlen($_POST['idno']) != 12) {
                $errors .= "Invalid adhaar number length!\\n";
            }

            if(empty($errors)==true){
                if(move_uploaded_file($file_tmp,"../img/id/".$file_name)) {
                    $query = "UPDATE `users` SET `idno` = '".mysqli_real_escape_string($link, $_POST['idno'])."', `idpic` = '".mysqli_real_escape_string($link, $file_name)."' WHERE `email` = '".mysqli_real_escape_string($link, $_GET['email'])."'";
                    if(mysqli_query($link, $query)) {
                        echo "<script> alert('Data updated successfully!'); </script>";
                    }
                } 
                
            } else {
                echo "<script> alert('$errors'); </script>";
            }
        } else {
            echo "<script> alert('Complete the form!'); </script>";
        }
    }

?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<form method="post" enctype="multipart/form-data">
    <p><input type="number" maxlength="12" name="idno" placeholder="Adhaar number"></p>
    <p><label for="idpic">Upload adhaar image:</label>
    <input type="file" accept="image/*" id="idpic" name="idpic"></p>
    <p><input type="submit" name="submit" value="Update"></p>
</form>