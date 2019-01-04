<?php

    $link = mysqli_connect(******);

//Fetching owner mobile using his/her id
    $query = "SELECT `id` FROM `items` WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
    $row = mysqli_fetch_array(mysqli_query($link, $query));
    $id = $row['id'];
    $query = "SELECT `mobile` FROM `users` WHERE `id` = '$id'";
    $row1 = mysqli_fetch_array(mysqli_query($link, $query));

//Fetching user mobile using his/her id
    $query = "SELECT `mobile`,`id` FROM `users` WHERE `email` = '".mysqli_real_escape_string($link, $_GET['cmail'])."'";
    $row2 = mysqli_fetch_array(mysqli_query($link, $query));
    if($_GET['stat']==1) {
        $query = "SELECT * FROM `items` WHERE iid='".mysqli_real_escape_string($link, $_GET['iid'])."' AND `sell` = 1";
        $row = mysqli_fetch_assoc(mysqli_query($link, $query));
    } else if($_GET['stat']==0){
        $query = "SELECT * FROM `items` WHERE iid='".mysqli_real_escape_string($link, $_GET['iid'])."' AND `rent` = 1";
        $row = mysqli_fetch_assoc(mysqli_query($link, $query));
    }

//Sending details of owner to user
    $to = $_GET['cmail'];
    $type = "";
    if($_GET['stat'] == 1) {
        $type = "buy";
    } else {
        $type = "rent";
    }
    $subject = "Contact details for interested ad";
    $message = '
Dear Customer, 

The owner has shown you interest for a product you wished to '.$type.'

Details of the product:
Item Name: '.$row['iname'].'
Item Type: '.$row['itype'].'
Item Subtype: '.$row['isubtype'].'
Price Quoted: '.$_GET['price'].'
Owner Email: '.$_GET['omail'].'
Owner Mobile: '.$row1['mobile'].'


This is a system generated mail. Please do not reply. 
    ';
    $headers = 'From:noreply@vastukosh.com' . "\r\n"; 
    if(mail($to, $subject, $message, $headers)) {
        echo "<script> alert('Mail sent successfully!'); </script>";
    }

//Sending details of user to owner
    $to = $_GET['omail'];
    $type = "";
    if($_GET['stat'] == 1) {
        $type = "buying";
    } else {
        $type = "renting";
    }
    $subject = "Contact details for interested ad";
    $message = '
Dear Customer, 

Here are the details of the user who showed interest in '.$type.' your product:-

Details of the product:
Item Name: '.$row['iname'].'
Item Type: '.$row['itype'].'
Item Subtype: '.$row['isubtype'].'
Price Quoted: '.$_GET['price'].'
User Email: '.$_GET['cmail'].'
User Mobile: '.$row2['mobile'].'

When deal is done just paste this email into the detail page of item in your profile.

This is a system generated mail. Please do not reply. 
    ';
    $headers = 'From:noreply@vastukosh.com' . "\r\n"; 
    if(mail($to, $subject, $message, $headers)) {
        echo "<script> alert('Mail sent to you!'); </script>";
    }
?>
