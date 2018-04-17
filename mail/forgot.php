<?php

    $link = mysqli_connect("shareddb-g.hosting.stackcp.net","vastukosh-32353f7f","password98@","vastukosh-32353f7f");

    if(isset($_POST['submit'])) {
        if($_POST['pass'] != "" && $_POST['cpass'] != "") {
            if($_POST['pass'] != $_POST['cpass']) {
                echo "<script> alert('Passwords do not match!'); </script>";
            } else if(strlen($_POST['pass']) < 8) {
                echo "<script> alert('Password cannot be less than 8 characters!'); </script>";
            } else {
                $query = "UPDATE `users` SET `password` = '".mysqli_real_escape_string($link, hash('sha512', $_POST['pass']))."' WHERE `email` = '".mysqli_real_escape_string($link, $_GET['email'])."'";
                if(mysqli_query($link, $query)) {
                    echo "<script> alert('Password updated successfully!'); </script>";
                }
            }
        } else {
            echo "<script> alert('Complete the form!'); </script>";
        }
    }

?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<form method="post">
    <p><input type="password" name="pass" placeholder="Enter new password"></p>
    <p><input type="password" name="cpass" placeholder="Confirm new password"></p>
    <p><input type="submit" value="Change password" name="submit"></p>
</form>