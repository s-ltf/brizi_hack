<?php
session_start();

$BriziDir = "/home/pi/Brizi";

$normalized_Degree = 0;
$command=$_REQUEST["command"];

// store session data
if($command != 'MOTORS_OFF')
$_SESSION['lastCommand']=$command;


/*
 * MOTORS_ON
 * Does NOT adjust axis. Turns motors on and throttles forwards in the
 * directions the axis were pointed prior to running this command.
 */
if ($command == "MOTORS_ON")
{
  echo "Running command: " . $command;
  exec('gpio write 0 0');
  exec('gpio write 1 1');
  exec('gpio write 2 0');
  exec('gpio write 3 1');
  usleep(200000);
}

/*
 * MOTORS_OFF
 * Turns motors off.
 */
elseif ($command == "MOTORS_OFF")
{
    $lastCmd = $_SESSION['lastCommand'];
    if($lastCmd == 'GoUp' || $lastCmd == 'GoDown' || $lastCmd == 'GoRigth' || $lastCmd == 'GoLeft')
    {
        $_SESSION['lastCommand']='GoUp';
    }
    else{
        echo "Running command: " . $command;
          exec('gpio write 0 0');
          exec('gpio write 1 0');
          exec('gpio write 2 0');
          exec('gpio write 3 0');
          exec('gpio write 4 0');
          exec('gpio write 5 0');
    }
  usleep(200000);
}


/*
 * MOTORS_Reverse
 * Does NOT adjust axis. Turns motors on and throttles backwards in the
 * directions the axis were pointed prior to running this command.
 */
elseif ($command == "MOTORS_Reverse")
{
  echo "Running command: " . $command;
  exec('gpio write 0 1');
  exec('gpio write 1 0');
  exec('gpio write 2 1');
  exec('gpio write 3 0');
  usleep(200000);
}


/*
 * Right
 * Does NOT adjust axis. Spin right motor backwards and left motor forwards.
 */
elseif ($command == "Right")
{
  echo "Running command: " . $command;
  exec('gpio write 0 0');
  exec('gpio write 1 1');  
  exec('gpio write 2 1');//run in reverse
  exec('gpio write 3 0');
  exec('gpio write 4 1');
  exec('gpio write 5 0');
  usleep(200000);
}


/*
 * Left
 * Does NOT adjust axis. Spin right motor forwards and left motor backwards. 
 */
elseif ($command == "Left")
{
  echo "Running command: " . $command;
  exec('gpio write 0 1');//run in reverse
  exec('gpio write 1 0');
  exec('gpio write 2 0');
  exec('gpio write 3 1');
  exec('gpio write 4 0');
  exec('gpio write 5 1');
  usleep(200000);
}


/*
 * StopAll
 *
 */
elseif ($command == "StopAll")
{
    echo "Running command: " . $command;
	$normalized_Degree = map(0, -90, 90, 5, 30);
	  $cmd = "./servo " . intval($normalized_Degree);
	  echo $cmd;
	  exec($cmd, $arrACL);
	  
      exec('gpio write 0 0');
      exec('gpio write 1 0');
      exec('gpio write 2 0');
      exec('gpio write 3 0');
      exec('gpio write 4 0');
      exec('gpio write 5 0');
}


/*
 * GoUp
 * Adjust axis to point up, then throttle forwards (essentially GoUp)
 */
elseif ($command == "GoUp")
{
  echo "Running command: " . $command;
  $normalized_Degree = map(0, -90, 90, 5, 30);
  $cmd = "./servo " . intval($normalized_Degree);
  echo $cmd;
  exec($cmd, $arrACL);
  echo "Running command: Motor On";
  exec('gpio write 0 0');
  exec('gpio write 1 1');
  exec('gpio write 2 0');
  exec('gpio write 3 1');
}


/*
 * GoDown
 * Adjust axis to point down, then throttle forwards (essentially GoDown)
 */
elseif ($command == "GoDown")
{
  echo "Running command: " . $command;
  $normalized_Degree = map(0, -90, 90, 5, 30);
  $cmd = "./servo " . intval($normalized_Degree);
  echo $cmd;
  exec($cmd, $arrACL);
  echo "Running command: MOTORS_Reverse";
  exec('gpio write 0 1');
  exec('gpio write 1 0');
  exec('gpio write 2 1');
  exec('gpio write 3 0');
}

/*
 * GoRight
 * This is a more optimized version of Right, because we adjust the axis at the start.
 * Adjust axis to point parallel to the ground, then spin right motor backwards
 * and left motor forwards (essentially GoRight).
 */
elseif ($command == "GoRight")
{
  echo "Running command: " . $command;
  $normalized_Degree = map(-45, -90, 90, 5, 30);
  $cmd = "./servo " . intval($normalized_Degree);
  echo $cmd;
  exec($cmd, $arrACL);
  echo "Running command: MOTORS_Right";
  exec('gpio write 0 0');
  exec('gpio write 1 1');  
  exec('gpio write 2 1');//run in reverse
  exec('gpio write 3 0');
  exec('gpio write 4 1');
  exec('gpio write 5 0');
}


/*
 * GoLeft
 * This is a more optimized version of Left, because we adjust the axis at the start.
 * Adjust axis to point parallel to the ground, then spin right motor forwards
 * and left motor backwards (essentially GoLeft).
 */
elseif ($command == "GoLeft")
{
  echo "Running command: " . $command;
  $normalized_Degree = map(-45, -90, 90, 5, 30);
  $cmd = "./servo " . intval($normalized_Degree);
  echo $cmd;
  exec($cmd, $arrACL);
  echo "Running command: MOTORS_Left";
  exec('gpio write 0 1');//run in reverse
  exec('gpio write 1 0');
  exec('gpio write 2 0');
  exec('gpio write 3 1');
  exec('gpio write 4 0');
  exec('gpio write 5 1');
}


/*
 * StillPhoto
 * Can only run if not live-streaming. Turns the camera on, and takes 1 image.
 * Expect to see the image in the /home/pi/Brizi/images/ directory.
 */
elseif ($command == "StillPhoto")
{
  include 'FTP_Helper.php';
  echo "Running command: " . $command;
  $cmd = $BriziDir . "/stream/./stopstream.sh";
  exec($cmd, $arrACL);
   
  $num = GetLastImageNumber();
  $num++;
  $cmd = "raspistill -o " . $BriziDir . "/images/image" . $num . ".jpg -w 1920 -h 1080 -n -q 100 -vs -gc -t 500";
  echo $cmd;
  exec($cmd, $arrACL);
  print_r($arrACL);
  $cmd2 = "cp -p " . $BriziDir . "/images/image" . $num . ".jpg " . $BriziDir . "/gallery/ki_galleries/images";
  exec($cmd2, $arrACL);
  $cmd2 = "cp -p " . $BriziDir . "/images/image" . $num . ".jpg " . $BriziDir . "/ftpGallery";
  exec($cmd2, $arrACL);
  exec("php ftpbrizi.php > /dev/null &");

  //print_r($arrACL);
  //Ftp_Send("/home/pi/dev/images/image" . $num . ".jpg");
  //usleep(200000);
}


/*
 * TimeLapse
 * Can only run if not live-streaming. Turns the camera on, and takes 4 images 
 * within 2 seconds.
 * Expect to see the images in the /home/pi/Brizi/images/ directory. 
 */
elseif ($command == "TimeLapse")
{
  echo "Running command: " . $command;
  $cmd = $BriziDir . "/stream/./stopstream.sh";
   exec($cmd, $arrACL);
   
  $num = GetLastImageNumber();
  $num++;
  $cmd = "raspistill -o " . $BriziDir . "/images/image" . $num . "_%d.jpg -w 1920 -h 1080 -n -q 100 -vs -gc -tl 500 -t 2000";
  echo $cmd;
  exec($cmd, $arrACL);
  usleep(500000);
  $cmd2 = "cp -p " . $BriziDir . "/images/image" . $num . "*.jpg " . $BriziDir . "/gallery/ki_galleries/images";
  exec($cmd2, $arrACL);
  
  $cmd2 = "cp -p " . $BriziDir . "/images/image" . $num . "*.jpg " . $BriziDir . "/ftpGallery";
  exec($cmd2, $arrACL);
  exec("php ftpbrizi.php > /dev/null &");
  //print_r($arrACL);
  //usleep(200000);
}


/*
 * StartRecordingVideo
 * Starts taking a video (inifite length), and places the video in the
 * /home/pi/Brizi/videos/ directory. To stop the video recording, call the
 * 'StopRecordingVideo' function.
 */
elseif ($command == "StartRecordingVideo")
{
  echo "Running command: " . $command;
  /*$outPut = shell_exec("ls " . $BriziDir . "/videos/");
  $videos = explode("\n", $outPut);
  print_r($videos);
  $video_num = count($videos);*/
  $cmd = "screen -d -m " . $BriziDir . "/videos/./takeVideo.sh";
  //$cmd = "raspivid -w 1920 -h 1080 -awb auto -ex antishake -mm backlit -vs -t 99999999 -o " . $BriziDir . "/videos/video" . $video_num . ".h264";
  echo $cmd;
  exec($cmd, $arrACL);
  usleep(200000);
}

/*
 * StopRecordingVideo
 * Must be run after 'StartRecordingVideo' has been called. It will stop the
 * infinite recording, and then you can finally view the video recorded at
 * /home/pi/Brizi/videos/.
 */
elseif ($command == "StopRecordingVideo")
{
  $cmd = $BriziDir . "/stream/./stopstream.sh";
   exec($cmd, $arrACL);
   print_r($arrACL);
}


/*
 * VineVideo
 * Records a 6 second video, and places it in the /home/pi/Brizi/videos/ directory.
 */
elseif ($command == "VineVideo")
{
  echo "Running command: " . $command;
  $outPut = shell_exec("ls " . $BriziDir . "/videos/");
  $videos = explode("\n", $outPut);
  print_r($videos);
  $video_num = count($videos);
  $cmd = "raspivid -t 6000 -w 320 -h 240 -o " . $BriziDir . "/videos/video" . $video_num . ".h264" . "; " . "MP4Box -fps 30 -add " . $BriziDir . "/videos/video" . $video_num . ".h264  " . $BriziDir . "/videosmp4/video" . $video_num . ".mp4";
  echo $cmd;
  exec($cmd, $arrACL);
  print_r($arrACL);
  usleep(200000);
}


/*
 * StartVideoStream
 * Starts the live-video streaming, where you can view the stream straight on
 * any browser or media player that is configured correctly. Come talk with us
 * when you want to get this setup.
 */
elseif ($command == "StartVideoStream")
{
   //$outPut = shell_exec("uv4l --driver raspicam --auto-video_nr --width 640 --height 480 --encoding jpeg");
   //$cmd = "/opt/vc/bin/raspivid -o - -w 320 -h 240 -t 10000000 -b 500000 –s | avconv -f h264 -i pipe:0 -s 320×240 -r 30 -pix_fmt yuv420p -r 30 -f flv rtmp://1.17420200.fme.ustream.tv/ustreamVideo/17420200/xZaPJATeGDXpWU4FzpbFrAaJPaJChVag";
   $cmd = "screen -d -m " . $BriziDir . "/stream/./streamWowza.sh";
   exec($cmd, $arrACL);
   print_r($arrACL);
}


/*
 * StopVideoStream
 * Must be run after 'StartVideoStream' has been called. This function will
 * in turn stop the live-video streaming.
 */
elseif ($command == "StopVideoStream")
{
	$cmd = $BriziDir . "/stream/./stopstream.sh";
   exec($cmd, $arrACL);
   print_r($arrACL);
}

elseif (strpbrk($command, 'Servo ') != FALSE)
{
  //$Degree = substr($command, -3); 
 $Degree = filter_var($command, FILTER_SANITIZE_NUMBER_INT);
 
  if(is_numeric($Degree))
  {
    $_SESSION['angle']= $Degree; //save last servo angle 
    echo "Running command: " . $command;
    $normalized_Degree = map($Degree, -90, 90, 5, 30);
    $cmd = "./servo " . intval($normalized_Degree);
    echo $cmd;
    exec($cmd, $arrACL);
  }

  else
  {
     echo $Degree . " is Not Numeric" . "&#10";
     echo "command: ".$command ;
  }
    
  usleep(200000);
}

else
{
  echo "No command recognized! ";
}

echo "&#10";

function map($value, $fromLow, $fromHigh, $toLow, $toHigh) {
    $fromRange = $fromHigh - $fromLow;
    $toRange = $toHigh - $toLow;
    $scaleFactor = $toRange / $fromRange;

    // Re-zero the value within the from range
    $tmpValue = $value - $fromLow;
    // Rescale the value to the to range
    $tmpValue *= $scaleFactor;
    // Re-zero back to the to range
    return $tmpValue + $toLow;
}
function GetLastImageNumber() {
  $num =0;
    foreach(glob("images/{*.gif,*.jpg,*.png,*.jpeg,*.bmp}", GLOB_BRACE) as $image){
      //echo "<br />image: " . $image;
      $str = explode('_', $image);
      //print_r($str);
      $temp = filter_var($str[0], FILTER_SANITIZE_NUMBER_INT);
      //echo "<br />temp: " . $temp . "<br />";
      if($temp > $num)
          $num = $temp;
  }
  return $num;
}

?>