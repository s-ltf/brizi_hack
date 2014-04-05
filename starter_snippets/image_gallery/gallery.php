<?php

header('Content-type: application/json');

/* Enable error checks */
ini_set('display errors', 1);
error_reporting(E_ALL);


/* MOBILE
-------------------------------------------------- */

if(isset($_GET['func'])) {
  echo json_encode(getImagesMobile());
}
function getImagesMobile() {
  $fileNameArray = array();
  foreach (glob('./images_ftp/*.*') as $fileName) {
    array_push($fileNameArray, $fileName);
  }
  return($fileNameArray);
}


/* WEB
-------------------------------------------------- */

if(isset($_POST['func'])) {
  if($_POST['func'] == 'returnImages') {
    $returnValue = returnImages();
    echo json_encode($returnValue);
  }
  if ($_POST['func'] == 'deleteImage') {    
    echo json_encode(deleteImage($_POST['imgName']));
  }  
  if($_POST['func'] == 'takeSnapshot') {
    $returnValue = takeSnapshot();
    echo json_encode($returnValue);
  }
}

function returnImages($dirname="./images") {
  $pattern="/(\.jpg$)|(\.png$)|(\.jpeg$)/i"; //valid image extensions
  $files = array();
  if($handle = opendir($dirname)) {
    while(false !== ($file = readdir($handle))){
      if (preg_match($pattern, $file)){
        $files[] = $file;
      }
    }
    closedir($handle);
  }
  return($files);
}
function deleteImage($imageName) {
  $dirname="./images/";
  unlink($dirname . $imageName);
  return returnImages();
}

function takeSnapshot() {
  echo "Starting ffmpeg...\n\n";
  echo shell_exec('./scripts/snap_ffmpeg.sh');
  echo "Done.\n";

  return "Returning from PHP func call.";
}

?>
