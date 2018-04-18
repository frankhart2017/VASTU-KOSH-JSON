<?php

    $link = mysqli_connect("shareddb-g.hosting.stackcp.net","vastukosh-32353f7f","password98@","vastukosh-32353f7f");

    if(isset($_POST['submit'])) {
        if($_POST['to'] != "" && $_POST['subject'] != "" && $_POST['message'] != "") {
            if(!filter_var($_POST['to'], FILTER_VALIDATE_EMAIL)) {
                echo "<script> alert('Invalid email address!'); </script>";
            } else {
                $query = "SELECT `id` FROM `users` WHERE `email` = '".mysqli_real_escape_string($link, $_POST['to'])."'";
                if(mysqli_num_rows(mysqli_query($link, $query)) == 0) {
                    echo "<script> alert('Account does not exist with this email!'); </script>";
                } else {
                    $to = $_POST['to'];
                    $subject = $_POST['subject'];
                    $message = $_POST['content'];
                    $headers = 'From:noreply@vastukosh.com' . "\r\n"; 
                    $headers .= "Content-type: text/html; charset=iso-8859-1". "\r\n";
                    if(mail($to, $subject, $message, $headers)) {
                        echo "<script> alert('Mail sent successfully!'); </script>";
                    } else {
                        echo "<script> alert('Oops, there was some error. Please come back later!'); </script>";
                    }
                }   
            }
        } else {
            echo "<script> alert('All fields are mandatory!'); </script>";
        }
    }

?>


<form method="post">
    <p><input type="text" name="to" placeholder="To"></p>
    <p><input type="text" name="subject" placeholder="Subject"></p>
    <p><textarea name="message" placeholder="Message in HTML format" rows="20" cols="60"></textarea></p>
    <p><input type="submit" name="submit"></p>
</form>