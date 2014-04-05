<?php

header('Content-type: application/json');

/* Enable error checks */
ini_set('display errors', 1);
error_reporting(E_ALL);

if(isset($_POST['func'])) {
  if($_POST['func'] == 'caseVote') {
    $selected_location = $_POST['location'];
    $selected_location = trim(strip_tags($selected_location));
    $table = $_POST['table'];
    $table = trim(strip_tags($table));
    castVote($table, $selected_location);
    $voting_results = calculateVotes($table);
    echo json_encode($voting_results);
  } elseif($_POST['func'] == 'checkVoteResults') {
    $table = $_POST['table'];
    $table = trim(strip_tags($table));
    $voting_results = calculateVotes($table);
    echo json_encode($voting_results);
  } elseif($_POST['func'] == 'unique') {
    $table = $_POST['table'];
    $table = trim(strip_tags($table));
    $unique_locations = getUniqueLocations($table);
    echo json_encode($unique_locations);
  } elseif($_POST['func'] == 'resetTableVoteValues') {
    $table = $_POST['table'];
    $table = trim(strip_tags($table));
    $returnValue = resetTableVoteValues($table);
    echo json_encode($returnValue);
  } elseif($_POST['func'] == 'returnImages') {
    $returnValue = returnImages();
    echo json_encode($returnValue);
  }  
}

function castVote($table, $location) {
  // Database Related 
  $user="brizi";
  $password="10YongeSt";
  $database = "voting";

  $connection = mysqli_connect("localhost", $user, $password, $database);

  // Check connection
  if (mysqli_connect_errno())
  {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  // $query="INSERT INTO $table (Location) VALUES ('$location')";
  $query="UPDATE $table SET Count=Count+1 WHERE Location=('$location')";

  if (!mysqli_query($connection, $query))
  {
    die('Error: ' . mysqli_error($connection));
  }

  mysqli_close($connection);
}

function calculateVotes($table) {
  $voting_results = array();
  $vote_categories = array(
    "Salesforce Booth",
    "Workshop Section",
    "Speaker Stage",
    "Entrance",);

  $vote_categories = getUniqueLocations($table);

  // Database Related 
  $user="brizi";
  $password="10YongeSt";
  $database = "voting";

  $connection = mysqli_connect("localhost", $user, $password, $database);

  // Check connection
  if (mysqli_connect_errno())
  {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  foreach ($vote_categories as $vote_category)
  {
    $query = "SELECT * FROM $table WHERE Location = '$vote_category'";
    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
    $row = mysqli_fetch_row($result);
    $voting_results[$vote_category] = $row[2];
  }

  mysqli_close($connection);
  return $voting_results;
}

function createTable($table_name, $locations_array) {
  // Database Related 
  $user="brizi";
  $password="10YongeSt";
  $database = "voting";

  $query = "CREATE TABLE ($table_name) (
    ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Location_Name VARCHAR(30) NOT NULL
  );";

// CREATE TABLE ($table_name) LIKE voting_template;
// Then add rows....
}

function resetTableVoteValues($table_name) {
  // Database Related 
  $user="brizi";
  $password="10YongeSt";
  $database = "voting";

  $connection = mysqli_connect("localhost", $user, $password, $database);
  $query = "UPDATE ($table_name) SET Count=0";
  // $query = "SELECT * FROM ($table_name)";

  // Check connection
  if (mysqli_connect_errno())
  {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $result = mysqli_query($connection, $query) or die(mysqli_error($connection));  

  mysqli_close($connection);
  return $result;
}

function getUniqueLocations($table) {
  // Database Related 
  $user="brizi";
  $password="10YongeSt";
  $database = "voting";

  $locations = array();

  $connection = mysqli_connect("localhost", $user, $password, $database);

  // Check connection
  if (mysqli_connect_errno())
  {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  $query = "SELECT DISTINCT(Location) FROM ($table)";

  $result1 = mysqli_query($connection, $query) or die(mysqli_error($connection));
  while ($row = $result1->fetch_assoc()) {
    // do what you need.
    $locations[] = $row['Location'];
  }

  // DOESN'T WORK ON CPANEL PHP
  // $result2 = mysqli_fetch_all($result1, MYSQLI_ASSOC);

  // foreach ($result2 as $result_obj) {
  //   $locations[] = $result_obj['Location'];
  // }

  mysqli_close($connection);
  return $locations;
}

function returnImages($dirname="./images_ftp") {
  $pattern="/(\.jpg$)|(\.png$)|(\.jpeg$)/i"; //valid image extensions
  $files = array();
  $curimage=0;
  if($handle = opendir($dirname)) {
    while(false !== ($file = readdir($handle))){
      if (preg_match($pattern, $file)){
        $files[] = $file;
        $curimage++;
      }
    }
    closedir($handle);
  }
  return($files);
}

?>
