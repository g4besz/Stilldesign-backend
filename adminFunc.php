<?php

include "functions.php";

header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding( 'UTF-8' );

$Type = $_POST["Type"];
$TodoName = $_POST["todoName"];
$TodoUser = $_POST["todoUser"];
$TodoDesc = $_POST["todoDesc"];
$TodoFinishedFlag = $_POST["todoFinishedFlag"];
$TodoFinishedAt = "";
$RecID = $_POST["id"];

//check parameters ***************************
if ($Type=="new" || $Type=="modify"){
  if (strlen($TodoName)==0){
    ExitFunc("Nincs kitöltve a 'Feladat' mező!");
  }

  if (strlen($TodoName)>50){
    ExitFunc("A 'Feladat' mező értéke túl hosszú!");
  }

  if (strlen($TodoUser)==0){
    ExitFunc("Nincs kitöltve a 'Felelős felhasználó' mező!");
  }else if (!is_numeric($TodoUser)){
    ExitFunc("A 'Felelős felhasználó' mező értéke nem megfelelő!");
  }else{
    $SQLresult = mysqli_query($con,"SELECT UserID FROM User WHERE UserID=" . intval($TodoUser));
    if (mysqli_num_rows($SQLresult)==0) {
      ExitFunc("A 'Felelős felhasználó' mező értéke nem megfelelő!");
    }
  }

  if (strlen($TodoDesc)>255){
    ExitFunc("A 'Leírás' mező értéke túl hosszú!");
  }
}

if ($Type=="delete" || $Type=="finished"){
  if (strlen($RecID)==0){
    ExitFunc("Nem megfelelő paraméter!");
  }else if (!is_numeric($RecID)){
    ExitFunc("Nem megfelelő paraméter!");
  }
}

//NEW ****************************************
if ($Type=="new"){
  if ($TodoFinishedFlag=="Y"){
    $TodoFinishedFlag = "1";
    $TodoFinishedAt = date("Y-m-d H:i:s");
  }else{
    $TodoFinishedFlag = "0";
  }
  
  $stmt = mysqli_prepare($con,"INSERT INTO ToDo (ToDoName,UserID,Description,FinishedFlag,FinishedAt,CreatedAt) VALUES (?,?,?,?,?,NOW())");
  mysqli_stmt_bind_param($stmt, "sisis", $TodoName, $TodoUser, $TodoDesc, $TodoFinishedFlag, $TodoFinishedAt);  
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  mysqli_close($con);
  
  echo "NEW OK";
  exit;
}

//MODIFY ****************************************
if ($Type=="modify"){
  if ($TodoFinishedFlag=="Y"){
    $TodoFinishedFlag = "1";
    $TodoFinishedAt = date("Y-m-d H:i:s");
  }else{
    $TodoFinishedFlag = "0";
  }
  
  $stmt = mysqli_prepare($con,"UPDATE ToDo SET ToDoName=?,UserID=?,Description=?,FinishedFlag=?,FinishedAt=? WHERE ToDoID=?");
  mysqli_stmt_bind_param($stmt, "sisisi", $TodoName, $TodoUser, $TodoDesc, $TodoFinishedFlag, $TodoFinishedAt, $RecID);  
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  mysqli_close($con);
  
  echo "MOD OK";
  exit;
}

//FINISHED ****************************************
if ($Type=="finished"){
  $stmt = mysqli_prepare($con,"UPDATE ToDo SET FinishedFlag=1,FinishedAt=NOW() WHERE ToDoID=?");
  mysqli_stmt_bind_param($stmt, "i", $RecID);  
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  mysqli_close($con);
  
  echo "FINISHED OK";
  exit;
}

//DELETE ****************************************
if ($Type=="delete"){
  $stmt = mysqli_prepare($con,"DELETE FROM ToDo WHERE ToDoID=?");
  mysqli_stmt_bind_param($stmt, "i", $RecID);  
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  mysqli_close($con);
  
  echo "DEL OK";
  exit;
}

//Error handle ****************************************
function ExitFunc($mess){
  echo "Hiba! " . $mess;
  exit;  
}

?>