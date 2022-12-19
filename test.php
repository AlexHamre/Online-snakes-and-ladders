<?php
$currentPositions = array(8,6,1,1,1,1,1,1);
$currentPositions = implode(",",$currentPositions);

$servername = "localhost";
$username = "alex";
$password = "Alex1234";
$dbname = "stigespill";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "UPDATE test SET currentPositions= '$currentPositions' WHERE id=1";
$result = $conn->query($sql);

$sql = "SELECT id, currentPositions,currentPlayer FROM test where id = 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
echo  $row["currentPositions"];

echo "<br>";
echo  $row["currentPlayer"];

mysqli_close($conn);

?>