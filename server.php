<?php
// variables for mysql
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
// getting the position of the players from the database
$sql = "SELECT id, currentPositions FROM test where id = 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$currentPositions = explode(",", $row["currentPositions"]);


// variables
$playerAmount = 2;
$botAmount = 0;
$currentPlayer = 0;
$currentCheck = 0;
// default value would never be used if working correctly hence the "something is wrong"
$wintext = "something is wrong";

// gets current player from the DB
$sql = "SELECT id, currentPlayer FROM test where id = 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$currentPlayer = $row["currentPlayer"];

function diceroll(){
    // using global lets me use out of scope variables
    global $currentPositions;
    global $currentPlayer;
    global $playerAmount;
    global $botAmount;
    global $conn;
    
    $rand = rand(1,6); 
    $dieface = "dieface" . strval($rand);
    $currentPositions[$currentPlayer] += $rand;
    $currentPositions = implode(",",$currentPositions);
    $sql = "UPDATE test SET currentPositions= '$currentPositions' WHERE id=1";
    $result = mysqli_query($conn, $sql);
    
    $currentPlayer++;
    if ($currentPlayer == $playerAmount + $botAmount){
        $currentPlayer = 0;};

    $sql = "UPDATE test SET currentPlayer= '$currentPlayer' WHERE id=1";
    $result = $conn->query($sql);
    return $dieface;
};

// uses substr to cut off "server.php/" from the request
$url = substr($_SERVER['REQUEST_URI'],12);

if ($url == "currentPositions"){
    if ($currentPlayer >= $playerAmount){diceroll();}
    $looping = true;
    while ($looping){
    if ($currentCheck > $botAmount+$playerAmount){$looping = false;}

    // if you get over 100 you have to back
    if ($currentPositions[$currentCheck] > 100 ){
        $currentPositions[$currentCheck] = 100 - ($currentPositions[$currentCheck]-100);}
        // snakes
    elseif ($currentPositions[$currentCheck] == 98 ){$currentPositions[$currentCheck]= 78;}
    elseif ($currentPositions[$currentCheck] == 93 ){$currentPositions[$currentCheck]= 74;}
    elseif ($currentPositions[$currentCheck] == 86 ){$currentPositions[$currentCheck]= 24;}
    elseif ($currentPositions[$currentCheck] == 62 ){$currentPositions[$currentCheck]= 20;}
    elseif ($currentPositions[$currentCheck] == 16 ){$currentPositions[$currentCheck]= 6;}
    elseif ($currentPositions[$currentCheck] == 49 ){$currentPositions[$currentCheck]= 12;}
        // ladders
    elseif ($currentPositions[$currentCheck] == 4 ){$currentPositions[$currentCheck]= 14;}
    elseif ($currentPositions[$currentCheck] == 9 ){$currentPositions[$currentCheck]= 31;}
    elseif ($currentPositions[$currentCheck] == 21 ){$currentPositions[$currentCheck]= 43;}
    elseif ($currentPositions[$currentCheck] == 28 ){$currentPositions[$currentCheck]= 77;}
    elseif ($currentPositions[$currentCheck] == 37 ){$currentPositions[$currentCheck]= 57;}
    elseif ($currentPositions[$currentCheck] == 51 ){$currentPositions[$currentCheck]= 67;}
    elseif ($currentPositions[$currentCheck] == 71 ){$currentPositions[$currentCheck]= 91;}
    $currentCheck++;
    }
    $currentPositions = implode(",",$currentPositions);
        $sql = "UPDATE test SET currentPositions= '$currentPositions' WHERE id=1";
        $result = mysqli_query($conn, $sql);

    $currentPositions = explode(",",$currentPositions);
    echo json_encode($currentPositions);
}
// if the diceroll request is sent while it is a players turn, it calls the function
if ($url == "diceroll"){
    if ($currentPlayer < $playerAmount){
    echo  diceroll();
    }
}

// if the reset request is sent, it resets the game
if ($url == "reset"){
    $currentPositions = array(1,1,1,1,1,1,1,1);
    $currentPositions = implode(",",$currentPositions);
    $sql = "UPDATE test SET currentPositions= '$currentPositions' WHERE id=1";
    $result = mysqli_query($conn, $sql);

    $currentPlayer = 0;
    $sql = "UPDATE test SET currentPlayer= '$currentPlayer' WHERE id=1";
    $result = $conn->query($sql);
}

?>
