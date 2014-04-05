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
    array_push($fileNameArray, '.'.$fileName);
  }
  return($fileNameArray);
}


/* WEB
-------------------------------------------------- */

if(isset($_POST['func'])) {
  if($_POST['func'] == 'returnImages') {
    echo json_encode(returnImages($_POST['directory']));
  }
  if ($_POST['func'] == 'deleteImage') {
    echo json_encode(deleteImage($_POST['imgName'], $_POST['directory']));
  }  
  if($_POST['func'] == 'takeSnapshot') {
    echo json_encode(takeSnapshot());
  }
}

function returnImages($dirname) {
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
function deleteImage($imageName, $dirname="./images_snaps/") {
  unlink($dirname . $imageName);
  return returnImages($dirname);
}

function takeSnapshot() {
  echo "Starting ffmpeg...\n\n";
  echo shell_exec('./scripts/snap_ffmpeg.sh');
  echo "Done.\n";

  return "Returning from PHP func call.";
}

?>
