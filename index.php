<?php

   $link = mysqli_connect(******);

   $login = -1;
   $signup = -1;
   $name = "";
   $id = 0;
   $items = -1;
   $item = Array();
   $itemid = Array();
   $put = -1;
   $images = Array();
   $iname = Array();
   $iid = Array();
   $oname = "";
   $itype = "";
   $isubtype = "";
   $price = "";
   $sentMail = -1;
   $location = "";
   $address = "";
   $mobile = "";
   $email = "";
   $idno = "";
   $idpic = "";
   $locker = Array();
   $rent = Array();
   $sell = Array();
   $give = Array();
   $rentiid = Array();
   $selliid = Array();
   $giveiid = Array();
   $number = 0;
   $duration = 0;
   $charity = "";
   $forgot = 0;
   $prices = Array();
   $firstName = "";
   $verified = 0;
   $count = Array();
   $counts = "";
   $description = "";
   $rentprice = Array();
   $sellprice = Array();

    if($_GET['log'] == 1) { //log = 1 is for login


        $query = "SELECT * FROM `users` WHERE `email`='".mysqli_real_escape_string($link, $_GET['email'])."'"; //Email passed from the app

        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result)==0) {

            $login = 0; //Account does not exist

        } else {

            $row = mysqli_fetch_array($result);

            if(hash('sha512',$_GET['pass']) == $row['password']) {
                
                if($row['block'] == 1) {
                    $login = 3;
                } else {
                    $login = 1; // Login successful

                    $name = $row['name'];

                    $id = $row['id'];

                    $firstName = explode(" ", $name)[0];
                }

            } else {

                $login = 2; //Password is wrong

            }

        }
            
    } else if($_GET['log'] == 2) { //Log = 2 is for signup

        $query = "SELECT * FROM `users` WHERE email = '".$_GET['email']."' OR mobile = '".$_GET['mobile']."'"; //Get email and mobile from the app

        if(mysqli_num_rows(mysqli_query($link, $query))>0) {

            $signup = 0; //Account alredy exists with either email or mobile or both

        } else { //Put data into database
            date_default_timezone_set('Asia/Kolkata');
            $query = "INSERT INTO `users`(`name`, `location`, `address`, `mobile`, `email`, `password`, `time`) VALUES('".mysqli_real_escape_string($link, $_GET['name'])."'
            , '".mysqli_real_escape_string($link, $_GET['location'])."', '".mysqli_real_escape_string($link, $_GET['address'])."'
            , '".mysqli_real_escape_string($link, $_GET['mobile'])."', '".mysqli_real_escape_string($link, $_GET['email'])."'
            , '". mysqli_real_escape_string($link, hash('sha512',$_GET['password']))."', '".date('d-m-Y H:i:s')."')"; 

            mysqli_query($link, $query);
            
            //Get variables that are taken from the app are: name, location, address, mobile, email, password
            
            //Email verification send
            $to = $_GET['email'];
            $subject = "Email Verification";
            $message = '
Thanks for signing up!
Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.

------------------------
Username: '.$_GET['email'].'
Password: '.$_GET['password'].'
------------------------

Please click this link to activate your account:
http://vastukosh-com.stackstaging.com/mail/verify?email='.$_GET['email'].'

This is a system generated mail. Do not reply. 
            ';
            $headers = 'From:no-reply@vastukosh.com' . "\r\n"; 
            mail($to, $subject, $message, $headers); 

            $signup = 1; // Sign up successful

        }

    }

    if($_GET['items'] == 1) { //To get the item id and item name for displaying in rent-sell dropdown

        $query = "SELECT * FROM `items` WHERE id='".mysqli_real_escape_string($link, $_GET['id'])."' AND `count` > 0 AND `status` = 1";

        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result) == 0) {

            $items = 0;

        } else {

            $items = 1;

            while($row = mysqli_fetch_array($result)) {

                array_push($itemid,$row['iid']);
                array_push($item,$row['iname']);
                array_push($count,$row['count']);

            }

        }
        
        //Get variables are iid and iname

    }

    if($_GET['put'] == 1) { // To put an item on rent or sale
        
        if($_GET['type'] == 1 && $put == -1) {
            
            $query1 = "SELECT * FROM `items` WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            
            $row = mysqli_fetch_array(mysqli_query($link, $query1));
            
            $count = $row['count'] - $_GET['count'];
            $ad_count = $row['ad_count'];
            if($ad_count != "") {
                $ad_count = $row['ad_count']."++".mysqli_real_escape_string($link, $_GET['count']);
                $ad_descr = $row['ad_descr']."++".mysqli_real_escape_string($link, $_GET['descr']);
                $rent = $row['rent'] + 1;
                $duration = $row['duration']."++".mysqli_real_escape_string($link, $_GET['duration']);
                $price = $row['price']."++".mysqli_real_escape_string($link, $_GET['price']);
            } else {
                $ad_count = mysqli_real_escape_string($link, $_GET['count']);
                $ad_descr = mysqli_real_escape_string($link, $_GET['descr']);
                $rent = 1;
                $duration = mysqli_real_escape_string($link, $_GET['duration']);
                $price = mysqli_real_escape_string($link, $_GET['price']);
            }
            
            $query1 = "UPDATE `items` SET `rent` = '".$rent."', `duration` = '".$duration."', `price` = '".$price."', `ad_count` = '".$ad_count."', `ad_descr` = '".$ad_descr."', `count` = '".$count."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";

            if(mysqli_query($link, $query1)) {
                
                $put = 2; //Item is put on rent successfully
                
            }

        }

        if($_GET['type'] == 2 && $put == -1) {
            
            $query1 = "SELECT * FROM `items` WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            
            $row = mysqli_fetch_array(mysqli_query($link, $query1));
            
            $count = $row['count'] - $_GET['count'];
            $ad_count = $row['ad_count'];
            if($ad_count != "") {
                $ad_count = $row['ad_count']."++".mysqli_real_escape_string($link, $_GET['count']);
                $ad_descr = $row['ad_descr']."++".mysqli_real_escape_string($link, $_GET['descr']);
                $sell = $row['sell'] + 1;
                $duration = $row['duration']."++"."0";
                $price = $row['price']."++".mysqli_real_escape_string($link, $_GET['price']);
            } else {
                $ad_count = mysqli_real_escape_string($link, $_GET['count']);
                $ad_descr = mysqli_real_escape_string($link, $_GET['descr']);
                $sell = 1;
                $duration = "0";
                $price = mysqli_real_escape_string($link, $_GET['price']);
            }
            
            $query1 = "UPDATE `items` SET `sell` = '".$sell."', `duration` = '".$duration."', `price` = '".$price."', `ad_count` = '".$ad_count."', `ad_descr` = '".$ad_descr."', `count` = '".$count."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";

            if(mysqli_query($link, $query1)) {
                
                $put = 2; //Item is put on sale successfully
                
            }

        }

    }

    if($_GET['images'] == 1) { //images=1 means that the page is showing items on sale

        $query = "SELECT * FROM `items` WHERE `sell` > 0";

        if($result = mysqli_query($link, $query)) {

            while($row = mysqli_fetch_array($result)) {
                
                $image = "http://vastukosh-com.stackstaging.com/img/items/".$row['iimage']; 
                
                if($row['rent'] > 0 || $row['sell'] > 1) {
                    $prices_local = explode("++", $row['price']);
                    $duration_local = explode("++", $row['duration']);
                    for($i=0;$i<sizeof($prices_local);$i++) {
                        if($duration_local[$i] == "0") {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        }
                    }
                } else {
                    array_push($images, $image);
                    array_push($iname, $row['iname']);
                    array_push($iid, $row['iid']);
                    array_push($prices, $row['price']);
                }
            }

        }

    } else if($_GET['images'] == 2) { //images=2 means that the page is showing items on rent

        $query = "SELECT * FROM `items` WHERE `rent` > 0";

        if($result = mysqli_query($link, $query)) {

            while($row = mysqli_fetch_array($result)) {

                $image = "http://vastukosh-com.stackstaging.com/img/items/".$row['iimage']; 
                
                if($row['sell'] > 0 || $row['rent'] > 1) {
                    $prices_local = explode("++", $row['price']);
                    $duration_local = explode("++", $row['duration']);
                    for($i=0;$i<sizeof($prices_local);$i++) {
                        if($duration_local[$i] != "0") {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        }
                    }
                } else {
                    array_push($images, $image);
                    array_push($iname, $row['iname']);
                    array_push($iid, $row['iid']);
                    array_push($prices, $row['price']);
                }

            }

        }

    }

    if($_GET['search'] == 1) { //search=1 means that the page is showing search results for items on sale
        
        $search = mysqli_real_escape_string($link, $_GET['sname']);
        
        $query = "SELECT * FROM `items` WHERE `sell` > 0 AND `iname` LIKE '%$search%'";
        
        if($result = mysqli_query($link, $query)) {
            
            while($row = mysqli_fetch_array($result)) {
                
                $image = "http://vastukosh-com.stackstaging.com/img/items/".$row['iimage']; 
                
                if($row['rent'] > 0 || $row['sell'] > 1) {
                    $prices_local = explode("++", $row['price']);
                    $duration_local = explode("++", $row['duration']);
                    for($i=0;$i<sizeof($prices_local);$i++) {
                        if($duration_local[$i] == "0") {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        }
                    }
                } else {
                    array_push($images, $image);
                    array_push($iname, $row['iname']);
                    array_push($iid, $row['iid']);
                    array_push($prices, $row['price']);
                }
                
            }
            
        }
        
    } else if($_GET['search'] == 2) { //search=2 means that the page is showing search results for items on rent
        
        $search = mysqli_real_escape_string($link, $_GET['sname']);
        
        $query = "SELECT * FROM `items` WHERE `rent` > 0 AND `iname` LIKE '%$search%'";
        
        if($result = mysqli_query($link, $query)) {
            
            while($row = mysqli_fetch_array($result)) {
                
                $image = "http://vastukosh-com.stackstaging.com/img/items/".$row['iimage']; 
                
                if($row['sell'] > 0 || $row['rent'] > 1) {
                    $prices_local = explode("++", $row['price']);
                    $duration_local = explode("++", $row['duration']);
                    for($i=0;$i<sizeof($prices_local);$i++) {
                        if($duration_local[$i] != "0") {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        }
                    }
                } else {
                    array_push($images, $image);
                    array_push($iname, $row['iname']);
                    array_push($iid, $row['iid']);
                    array_push($prices, $row['price']);
                }
                
            }
            
        }
        
    } 

    if($_GET['filter'] == 1) { //filter=1 means that only type filter is applied without search
        
        $query = "SELECT * FROM `items` WHERE `itype` = '".mysqli_real_escape_string($link, $_GET['itype'])."' AND `sell` > 0";
        
        if($_GET['select'] == 2) {
            
            $query = "SELECT * FROM `items` WHERE `itype` = '".mysqli_real_escape_string($link, $_GET['itype'])."' AND `rent` > 0";
            
        }
        
        if($result = mysqli_query($link, $query)) {
            
            while($row = mysqli_fetch_array($result)) {
                
                $image = "http://vastukosh-com.stackstaging.com/img/items/".$row['iimage'];
                
                if($_GET['select'] == 1) {
                    $condition = $row['sell'] > 1 || $row['rent'] > 0;
                } else if($_GET['select'] == 2) {
                    $condition = $row['rent'] > 1 || $row['sell'] > 0;
                }

                if($condition) {
                    $prices_local = explode("++", $row['price']);
                    $duration_local = explode("++", $row['duration']);
                    for($i=0;$i<sizeof($prices_local);$i++) {
                        if($duration_local[$i] == "0" && $_GET['select'] == 1) {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        } else if($duration_local[$i] != "0" && $_GET['select'] == 2) {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        }
                    }
                } else {
                    array_push($images, $image);
                    array_push($iname, $row['iname']);
                    array_push($iid, $row['iid']);
                    array_push($prices, $row['price']);
                }
                
            }
            
        }
        
    } else if($_GET['filter'] == 2) { //filter=2 means that both type and subtype filters are applied without search
        
        $query = "SELECT * FROM `items` WHERE `itype` = '".mysqli_real_escape_string($link, $_GET['itype'])."' AND `isubtype` = '".mysqli_real_escape_string($link, $_GET['isubtype'])."' AND `sell` > 0";
        
        if($_GET['select'] == 2) {
            
            $query = "SELECT * FROM `items` WHERE `itype` = '".mysqli_real_escape_string($link, $_GET['itype'])."' AND `isubtype` = '".mysqli_real_escape_string($link, $_GET['isubtype'])."' AND `rent` > 0";
            
        }
        
        if($result = mysqli_query($link, $query)) {
            
            while($row = mysqli_fetch_array($result)) {
                
                $image = "http://vastukosh-com.stackstaging.com/img/items/".$row['iimage'];

                if($_GET['select'] == 1) {
                    $condition = $row['sell'] > 1 || $row['rent'] > 0;
                } else if($_GET['select'] == 2) {
                    $condition = $row['rent'] > 1 || $row['sell'] > 0;
                }

                if($condition) {
                    $prices_local = explode("++", $row['price']);
                    $duration_local = explode("++", $row['duration']);
                    for($i=0;$i<sizeof($prices_local);$i++) {
                        if($duration_local[$i] == "0" && $_GET['select'] == 1) {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        } else if($duration_local[$i] != "0" && $_GET['select'] == 2) {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        }
                    }
                } else {
                    array_push($images, $image);
                    array_push($iname, $row['iname']);
                    array_push($iid, $row['iid']);
                    array_push($prices, $row['price']);
                }
                
            }
            
        }
        
    } else if($_GET['filter'] == 3) { //filter=3 means that only type filter is applied with search
        
        $search = mysqli_real_escape_string($link, $_GET['sname']);
        
        $query = "SELECT * FROM `items` WHERE `sell` = 1 AND `itype` = '".mysqli_real_escape_string($link, $_GET['itype'])."' AND `iname` LIKE '%$search%'";
        
        if($_GET['select'] == 2) {
            
            $query = "SELECT * FROM `items` WHERE `rent` = 1 AND `itype` = '".mysqli_real_escape_string($link, $_GET['itype'])."' AND `iname` LIKE '%$search%'";
            
        }
        
        if($result = mysqli_query($link, $query)) {
            
            while($row = mysqli_fetch_array($result)) {
                
                $image = "http://vastukosh-com.stackstaging.com/img/items/".$row['iimage'];

                if($_GET['select'] == 1) {
                    $condition = $row['sell'] > 1 || $row['rent'] > 0;
                } else if($_GET['select'] == 2) {
                    $condition = $row['rent'] > 1 || $row['sell'] > 0;
                }

                if($condition) {
                    $prices_local = explode("++", $row['price']);
                    $duration_local = explode("++", $row['duration']);
                    for($i=0;$i<sizeof($prices_local);$i++) {
                        if($duration_local[$i] == "0" && $_GET['select'] == 1) {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        } else if($duration_local[$i] != "0" && $_GET['select'] == 2) {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        }
                    }
                } else {
                    array_push($images, $image);
                    array_push($iname, $row['iname']);
                    array_push($iid, $row['iid']);
                    array_push($prices, $row['price']);
                }
                
            }
            
        }
        
    } else if($_GET['filter'] == 4) { //filter=4 means that both type and subtype filters are applied with search
        
        $search = mysqli_real_escape_string($link, $_GET['sname']);
        
        $query = "SELECT * FROM `items` WHERE `sell` = 1 AND `itype` = '".mysqli_real_escape_string($link, $_GET['itype'])."' AND `isubtype` = '".mysqli_real_escape_string($link, $_GET['isubtype'])."' AND `iname` LIKE '%$search%'";
        
        if($_GET['select'] == 2) {
            
            $query = "SELECT * FROM `items` WHERE `rent` = 1 AND `itype` = '".mysqli_real_escape_string($link, $_GET['itype'])."' AND `iname` LIKE '%$search%'";
            
        } 
        
        if($result = mysqli_query($link, $query)) {
            
            while($row = mysqli_fetch_array($result)) {
                
                $image = "http://vastukosh-com.stackstaging.com/img/items/".$row['iimage'];

                if($_GET['select'] == 1) {
                    $condition = $row['sell'] > 1 || $row['rent'] > 0;
                } else if($_GET['select'] == 2) {
                    $condition = $row['rent'] > 1 || $row['sell'] > 0;
                }

                if($condition) {
                    $prices_local = explode("++", $row['price']);
                    $duration_local = explode("++", $row['duration']);
                    for($i=0;$i<sizeof($prices_local);$i++) {
                        if($duration_local[$i] == "0" && $_GET['select'] == 1) {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        } else if($duration_local[$i] != "0" && $_GET['select'] == 2) {
                            array_push($images, $image);
                            array_push($iname, $row['iname']);
                            array_push($iid, $row['iid']);
                            array_push($prices, $prices_local[$i]);
                        }
                    }
                } else {
                    array_push($images, $image);
                    array_push($iname, $row['iname']);
                    array_push($iid, $row['iid']);
                    array_push($prices, $row['price']);
                }
                
            }
            
        }
        
    }

    if($_GET['details'] == 1) { //Print details for item that is put on sale

        $query = "SELECT * FROM `items` WHERE `sell` > 0 AND `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";

        $row = mysqli_fetch_array(mysqli_query($link, $query));
        
        if($row['sell'] > 1 || $row['rent'] > 0) {
            $price = explode("++", $row['price']);
            $pos = -1;
            for($i=0;$i<sizeof($price);$i++) {
                if("Rs.".$price[$i] == $_GET['price']) {
                    $pos = $i;
                }
            }
            $count_arr = explode("++", $row['ad_count']);
            $description_arr = explode("++", $row['ad_descr']);
            $oname = $row['cname'];
            $itype = $row['itype'];
            $isubtype = $row['isubtype'];
            $counts = $count_arr[$pos];
            $description = $description_arr[$pos];
            $id = $row['id'];
        } else {
            $oname = $row['cname'];
            $itype = $row['itype'];
            $isubtype = $row['isubtype'];
            $counts = $row['ad_count'];
            $description = $row['ad_descr'];
            $id = $row['id'];
        }

    } else if($_GET['details'] == 2) { //Print details for item that is put on rent

        $query = "SELECT * FROM `items` WHERE `rent` > 0 AND `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
        
        $row = mysqli_fetch_array(mysqli_query($link, $query));

        if($row['rent'] > 1 || $row['sell'] > 0) {
            $price = explode("++", $row['price']);
            $pos = -1;
            for($i=0;$i<sizeof($price);$i++) {
                if("Rs.".$price[$i] == $_GET['price']) {
                    $pos = $i;
                }
            }
            $count_arr = explode("++", $row['ad_count']);
            $description_arr = explode("++", $row['ad_descr']);
            $duration_arr = explode("++", $row['duration']);
            $oname = $row['cname'];
            $itype = $row['itype'];
            $isubtype = $row['isubtype'];
            $counts = $count_arr[$pos];
            $description = $description_arr[$pos];
            $duration = $duration_arr[$pos];
            $id = $row['id'];
        } else {
            $oname = $row['cname'];
            $itype = $row['itype'];
            $isubtype = $row['isubtype'];
            $counts = $row['ad_count'];
            $description = $row['ad_descr'];
            $row['id'];
        }

    }

    if($_GET['interested'] == 1) { //Person is interested in either renting or buying the product
        
        $query = "SELECT `email` FROM `users` WHERE `id` = '".mysqli_real_escape_string($link, $_GET['id'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $cmail = $row['email'];

        $query = "SELECT * FROM `items` WHERE iid='".mysqli_real_escape_string($link, $_GET['iid'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $query = "SELECT `email` FROM `users` WHERE id='".mysqli_real_escape_string($link, $row['id'])."'";
        $row1 = mysqli_fetch_array(mysqli_query($link, $query));
        $to = $row1['email'];
        $subject = 'Someone has shown interest in your product.';
        $type = "";
        if($_GET['type'] == 1) {
            $type = "buy";
        } else if($_GET['type'] == 2) {
            $type = "rent";
        }
        $message = '
Dear Customer, 

'.$_GET['name'].' has shown interest to '.$type.' your product. 

-------------------------------
Product Name: '.$row['iname'].'
Item id: '.$_GET['iid'].'    
Price Offering: Rs.'.$_GET['oprice'].'
Expected Price: '.$_GET['price'].'
-------------------------------

If you want to accept the proposal then click the following link:- 

http://vastukosh-com.stackstaging.com/mail/confirm?iid='.$_GET['iid'].'&cmail='.$cmail.'&price='.$_GET['oprice'].'&stat='.$_GET['type'].'&omail='.$to.'

This is a system generated mail. Please do not reply. 
        ';
        $headers = 'From:no-reply@vastukosh.com' . "\r\n"; 
        if(mail($to, $subject, $message, $headers)) {
            $sentMail = 1; //Mail is sent to the owner successfully
        }

    }

    if($_GET['resend'] == 1) { //Resend verification mail

        $query = "SELECT `email` FROM `users` WHERE id='".mysqli_real_escape_string($link, $_GET['id'])."'";

        $row = mysqli_fetch_array(mysqli_query($link, $query));

        $to = $row['email'];
        $subject = "Email Verification";
        $message = '
Thanks for signing up!
Your account has been created, you can login with your credentials after you have activated your account by pressing the url below.

Please click this link to activate your account:
http://vastukosh-com.stackstaging.com/mail/verify?email='.$to.'

This is a system generated mail. Do not reply. 
        ';
        $headers = 'From:no-reply@vastukosh.com' . "\r\n"; 
        if(mail($to, $subject, $message, $headers)) {

            $sentMail = 1; //Verification mail sent successfully

        }

    }

    if($_GET['give'] == 1) { //Items that are in your locker which can be donated

        $query = "SELECT * FROM `items` WHERE id = '".mysqli_real_escape_string($link, $_GET['id'])."' AND `count` > 0 AND `status` = 1";

        if($result = mysqli_query($link, $query)) {

            while($row = mysqli_fetch_array($result)) {

                array_push($iid, $row['iid']);
                array_push($iname, $row['iname']);
                array_push($count, $row['count']);

            }

        }

    } else if($_GET['give'] == 2) { //Put the item on give list and remove it from item list

        $query = "SELECT * FROM `items` WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";

        $row = mysqli_fetch_array(mysqli_query($link, $query));
        
        date_default_timezone_set('Asia/Kolkata');
        $query = "INSERT INTO `give`(`iid`, `id`, `oname`, `iname`, `itype`, `isubtype`, `count`, `iimage`, `charity`, `time`) VALUES('".mysqli_real_escape_string($link, $_GET['iid'])."', '".mysqli_real_escape_string($link, $row['id'])."'
        , '".mysqli_real_escape_string($link, $row['cname'])."', '".mysqli_real_escape_string($link, $row['iname'])."', '".mysqli_real_escape_string($link, $row['itype'])."', '".mysqli_real_escape_string($link, $row['isubtype'])."', '".mysqli_real_escape_string($link, $_GET['count'])."', '".mysqli_real_escape_string($link, $row['iimage'])."', '".mysqli_real_escape_string($link, $_GET['charity'])."', '".date('d-m-Y H:i:s')."')";
        
        mysqli_query($link, $query);
        
        $count = $row['count'] - $_GET['count'];
        
        if($count == 0) {
            $query = "DELETE FROM `items` WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
        } else {
            $query = "UPDATE `items` SET `count` = '".$count."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
        }

        if(mysqli_query($link, $query)) { 
            
            $sentMail = 1; //Item is put on give list and deleted from item list successfully  
            
        }

    }

    if($_GET['profile'] == 1) { //It means that the items of the profile are to be taken from the database

        $query = "SELECT * FROM `users` WHERE `id` =  '".mysqli_real_escape_string($link, $_GET['id'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $name = $row['name'];
        $location = $row['location'];
        $address = $row['address'];
        $mobile = $row['mobile'];
        $email = $row['email'];
        $idno = $row['idno'];
        $idpic = $row['idpic'];

        $query = "SELECT * FROM `items` WHERE `id` = '".mysqli_real_escape_string($link, $_GET['id'])."'";
        
        if($result = mysqli_query($link, $query)) {

            while($row = mysqli_fetch_array($result)) {
                
                if($row['ad_count'] != "") {
                    $duration = explode("++", $row['duration']);
                    $price_local = explode("++", $row['price']);
                    for($i=0;$i<sizeof($duration);$i++) {
                        if($duration[$i] != 0) {
                            array_push($rent, $row['iname']);
                            array_push($rentiid, $row['iid']);
                            array_push($rentprice, $price_local[$i]);
                        } else {
                            array_push($sell, $row['iname']);
                            array_push($selliid, $row['iid']);
                            array_push($sellprice, $price_local[$i]);
                        }
                    }
                }
                
                if($row['count'] > 0) {
                    array_push($locker, $row['iname']);
                    array_push($iid, $row['iid']);
                }

            }

        }

        $query = "SELECT * FROM `give` WHERE `id` = '".mysqli_real_escape_string($link, $_GET['id'])."'";

        if($result = mysqli_query($link, $query)) {
            
            while($row = mysqli_fetch_array($result)) {

                array_push($give, $row['iname']);
                array_push($giveiid, $row['iid']);

            }

        }

    } else if($_GET['profile'] == 2) { //Show the items that are either on rent or sell

        $query = "SELECT * FROM `items` WHERE iid = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $oname = $row['cname'];
        $itype = $row['itype'];
        $isubtype = $row['isubtype'];
        if($row['ad_count'] != "") {
            $price_local = explode("++", $row['price']);
            $duration_local = explode("++", $row['duration']);
            $count_local = explode("++", $row['ad_count']);
            $desc_local = explode("++", $row['ad_descr']);
            for($i=0;$i<sizeof($price_local);$i++) {
                if($price_local[$i] == $_GET['price']) {
                    $counts = $count_local[$i];
                    $description = $desc_local[$i];
                    if($duration_local[$i] != 0) {
                        $duration = $duration_local[$i];
                    }
                }
            }
        } 
        $image = $row['iimage'];

    } else if($_GET['profile'] == 3) { //Show the items that are donated

        $query = "SELECT * FROM `give` WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $oname = $row['oname'];
        $itype = $row['itype'];
        $isubtype = $row['isubtype'];
        $image = $row['iimage'];
        $charity = $row['charity'];
        $counts = $row['count'];
    } else if($_GET['profile'] == 4) {
        
        $query = "SELECT * FROM `items` WHERE iid = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $oname = $row['cname'];
        $itype = $row['itype'];
        $isubtype = $row['isubtype'];
        $counts = $row['count'];
        $description = $row['descr'];
        $image = $row['iimage'];
    }

    if($_GET['getImage'] == 1) { //To set the name of picture ourselves and not take the random name given by the user

        $query = "SELECT `iimage` FROM `items` ORDER BY `iid` DESC LIMIT 1";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $imageName = $row['iimage'];
        $imageName = explode("image",$imageName);
        $number = explode(".",$imageName[1]);
        $number = (int)$number[0];
        $number++;

    }

    if($_GET['locker'] == 1) { //Put items on locker
        
        date_default_timezone_set('Asia/Kolkata');
        $query = "INSERT INTO `items`(`id`, `cname`, `iname`, `itype`, `isubtype`, `count`, `descr`, `time`) VALUES('".mysqli_real_escape_string($link, $_GET['id'])."', '".
        mysqli_real_escape_string($link, $_GET['name'])."', '".mysqli_real_escape_string($link, $_GET['iname'])."', '".mysqli_real_escape_string($link, $_GET['type'])."'
        , '".mysqli_real_escape_string($link, $_GET['subtype'])."', '".mysqli_real_escape_string($link, $_GET['count'])."', '".mysqli_real_escape_string($link, $_GET['descr'])."', '".date('d-m-Y H:i:s')."')";

        if(mysqli_query($link, $query)) {
            $sentMail = 1; //Item put on locker successfully
        } else {
            echo mysqli_error($link);
        }

    }

    if($_GET['forgot'] == 1) { //User requested change in password. 
        
        $query = "SELECT * FROM `users` WHERE `email` = '".mysqli_real_escape_string($link, $_GET['email'])."'";
        if(mysqli_num_rows(mysqli_query($link, $query)) > 0) {
            $to = $_GET['email'];
            $subject = 'Reset your password.';
            $message = '
Dear Customer, 

Please click on the link to reset your password:- 

http://vastukosh-com.stackstaging.com/mail/forgot?email='.$_GET['email'].'

This is a system generated mail. Please do not reply. 
        ';
            $headers = 'From:no-reply@vastukosh.com' . "\r\n"; 
            if(mail($to, $subject, $message, $headers)) {
                $forgot = 1; //Mail is sent to the owner successfully
            }
        } else {
            $forgot = 2; //Account does not exist
        }
        
    }

    if($_GET['checkVerify'] == 1) {
        $query = "SELECT `status`, `idno` FROM `users` WHERE `email` = '".mysqli_real_escape_string($link, $_GET['email'])."'";
        
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        if($row['status'] == 1 && $row['idno'] != "") {
            $verified = 1;
        } else {
            $verified = 2;
        }
    }

    if($_GET['countReturn'] == 1) {
        $query = "SELECT `count` FROM `items` WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $counts = $row['count'];
    }

    if($_GET['return'] == 1) {
        $query = "SELECT * FROM `items` WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
        $row = mysqli_fetch_array(mysqli_query($link,$query));
        $id = $row['id'];
        $count = $row['count'] - $_GET['count'];
        $query = "UPDATE `items` SET `count` = '".$count."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
        mysqli_query($link, $query);
        $query = "SELECT * FROM `users` WHERE `id` = '".$id."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $to = "koshvastu@gmail.com";
        $subject = 'Request for item return.';
        $message = '
'.$_GET['iid'].', count: '.$_GET['count'].' number(s) to be returned to '.$row['name'].' residing at '.$row['address'].', '.$row['location'].'
    ';
        $headers = 'From:no-reply@vastukosh.com' . "\r\n"; 
        if(mail($to, $subject, $message, $headers)) {
            $sentMail = 1; //Mail is sent to the owner successfully
        }
    }

    if($_GET['edit'] == 1) {
        $sentMail = 0;
        if($_GET['name'] != "") {
            $query = "UPDATE `users` SET `name` = '".mysqli_real_escape_string($link, $_GET['name'])."' WHERE `id` =
            '".mysqli_real_escape_string($link, $_GET['id'])."'";
             if(mysqli_query($link, $query)) {
                 $sentMail = 1;
             } else {
                 $sentMail = 0;
             }
            
        }
        if($_GET['address'] != "") {           
            $query = "UPDATE `users` SET `address` = '".mysqli_real_escape_string($link, $_GET['address'])."' WHERE `id` = '".mysqli_real_escape_string."'";
            if(mysqli_query($link, $query)) {
                 $sentMail = 1;
             } else {
                 $sentMail = 0;
             }
        }
        if($_GET['location'] != "") {
            $query = "UPDATE `users` SET `location` = '".mysqli_real_escape_string($link, $_GET['location'])."' WHERE `id` = '".mysqli_real_escape_string($link, $_GET['id'])."'";
            if(mysqli_query($link, $query)) {
                 $sentMail = 1;
             } else {
                 $sentMail = 0;
             }
        }
        if($_GET['mobile'] != "") {
            $query = "UPDATE `users` SET `mobile` = '".mysqli_real_escape_string($link, $_GET['mobile'])."' WHERE `id` = '".mysqli_real_escape_string($link, $_GET['id'])."'";
            if(mysqli_query($link, $query)) {
                 $sentMail = 1;
             } else {
                 $sentMail = 0;
             }
        }
    }

    if($_GET['pass'] == 1) {
        $sentMail = 0;
        $query = "SELECT `password` FROM `users` WHERE `id` = '".mysqli_real_escape_string($link, $_GET['id'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        if(hash('sha512',$_GET['opassword']) != $row['password']) {
            $sentMail = 2;
        } else {
            $query = "UPDATE `users` SET `password` = '".mysqli_real_escape_string($link, hash('sha512',$_GET['password']))."' WHERE `id` =
            '".mysqli_real_escape_string($link, $_GET['id'])."'";
            if(mysqli_query($link, $query)) {
                $sentMail = 1;
            } else {
                $sentMail = 0;
            }
        }
    } 

    if($_GET['deal'] == 1) {
        $query = "SELECT `address` FROM `users` WHERE `email` = '".mysqli_real_escape_string($link, $_GET['email'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        $to = "koshvastu@gmail.com";
        $subject = 'Request for delivery.';
        $message = '
Delivery is requested for item id: '.$_GET['iid'].'.The item is to be delivered on the following address:-
'.$row['address'].'
Price for the item is '.$_GET['price'].'
    ';
        $headers = 'From:no-reply@vastukosh.com' . "\r\n"; 
        if(mail($to, $subject, $message, $headers)) {
            $sentMail = 1; //Mail is sent to the us successfully
        }
    }

    if($_GET['blockCheck'] == 1) {
        $query = "SELECT `block` FROM `users` WHERE `id` = '".mysqli_real_escape_string($link, $_GET['id'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        if($row['block'] == 1) {
            $sentMail = 1;
        } else {
            $sentMail = 0;
        }
    }

    $results = Array("login" => $login, "signup" => $signup, "name" => $name, "id" => $id, "items" => $items, "item" => $item, "itemid" => $itemid, "put" => $put,
    "images" => $images, "iname" => $iname, "iid" => $iid, "oname" => $oname, "itype" => $itype, "isubtype" => $isubtype, "price" => $price, "image" => $image,
    "sent" => $sentMail, "location" => $location, "address" => $address, "mobile" => $mobile, "email" => $email, "idno" => $idno, "idpic" => $idpic, 
    "locker" => $locker, "rent" => $rent,"sell" => $sell, "give" => $give, "rentiid" => $rentiid, "selliid" => $selliid, "giveiid" => $giveiid, "imgNumber" => $number, "duration" => $duration, "forgot" => $forgot, "prices" => $prices, "first" => $firstName, "verified" => $verified, "charity" => $charity, "count" => $count, "counts" => $counts, "descr" => $description, "rentprice" => $rentprice, "sellprice" => $sellprice);

    header("Content-Type: application/json");
    echo json_encode($results);

?>
