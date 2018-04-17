<?php

$target_dir = "../img/items";
if(!file_exists($target_dir))
{
mkdir($target_dir, 0777, true);
}

$target_dir = $target_dir . "/" .$_POST['image'];

if (file_put_contents($target_dir, base64_decode($image))) 
{
echo json_encode([
"Message" => "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.",
"Status" => "OK"
]);

} else {

echo json_encode([
"Message" => "Sorry, there was an error uploading your file.",
"Status" => "Error"
]);

}
?>