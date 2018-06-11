<?php

include "functions.php";

header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding( 'UTF-8' );

$Type = $_REQUEST["Type"];
$RecID = $_REQUEST["id"];
$Filter = $_POST["filter"];
$hideFinished = $_POST["hidefinished"];

if (strlen($_POST["hidefinished"])>0){
  setcookie("hidefinished", $hideFinished, time() + (86400 * 30), "/");   //30 days
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="jquery-3.3.1.js" ></script>
<script src="func.js" ></script>
<LINK REL="StyleSheet" HREF="style.css" TYPE="text/css">
<title>Admin</title>
</head>

<body>
  <div>Admin</div>
  <a href="admin.php">Lista</a>
  <a href="admin.php?Type=new">Új</a>
  <a href="logout.php">Kilépés</a>
 
<?
// LIST: default *****************************************  
if (strlen($Type)==0){
  $stmt;
  
?>  
  <form method="post" target="_self">
    <input type="text" name="filter" placeholder="Keresés..." /><input type="submit" value="Keresés" />
  </form>
  <form method="post" target="_self">
    <input type="hidden" name="hidefinished" value="Y" /><input type="submit" value="Elvégzettek elrejtése" />
  </form>
  <form method="post" target="_self">
    <input type="hidden" name="hidefinished" value="N" /><input type="submit" value="Elvégzettek megjelenítése" />
  </form>
  
  <table>
    <tr>
      <td>Azonosító</td>
      <td>Feladat</td>
      <td>Felelős</td>
      <td>Időpont</td>
      <td>Elvégezve?</td>
      <td>Elvégzés ideje</td>
      <td>Leírás</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>  
  <?
  if (strlen($Filter)>0 && $Filter!="*"){
    $Filter = "%" . $Filter . "%";
    
    if(!isset($_COOKIE["hidefinished"]) || $hideFinished=="N"){
      $stmt = mysqli_prepare($con,"SELECT ToDoID,ToDoName,UserID,CreatedAt,FinishedFlag,FinishedAt,Description FROM ToDo WHERE ToDoName LIKE ? OR Description LIKE ? ORDER BY FinishedFlag,FinishedAt DESC,CreatedAt DESC");          
    }else if ($_COOKIE["hidefinished"]=="Y" || $hideFinished=="Y"){  
      $stmt = mysqli_prepare($con,"SELECT ToDoID,ToDoName,UserID,CreatedAt,FinishedFlag,FinishedAt,Description FROM ToDo WHERE ( ToDoName LIKE ? OR Description LIKE ? ) AND IFNULL(FinishedFlag,0)<>1 ORDER BY FinishedFlag,FinishedAt DESC,CreatedAt DESC");    
    }else{
      $stmt = mysqli_prepare($con,"SELECT ToDoID,ToDoName,UserID,CreatedAt,FinishedFlag,FinishedAt,Description FROM ToDo WHERE ToDoName LIKE ? OR Description LIKE ? ORDER BY FinishedFlag,FinishedAt DESC,CreatedAt DESC");                
    }
    
    mysqli_stmt_bind_param($stmt, "ss", $Filter, $Filter);
  }else{
    if(!isset($_COOKIE["hidefinished"]) || $hideFinished=="N"){
      $stmt = mysqli_prepare($con,"SELECT ToDoID,ToDoName,UserID,CreatedAt,FinishedFlag,FinishedAt,Description FROM ToDo ORDER BY FinishedFlag,FinishedAt DESC,CreatedAt DESC");
    }else if ($_COOKIE["hidefinished"]=="Y" || $hideFinished=="Y"){
      $stmt = mysqli_prepare($con,"SELECT ToDoID,ToDoName,UserID,CreatedAt,FinishedFlag,FinishedAt,Description FROM ToDo WHERE IFNULL(FinishedFlag,0)<>1 ORDER BY FinishedFlag,FinishedAt DESC,CreatedAt DESC");
    }else{
      $stmt = mysqli_prepare($con,"SELECT ToDoID,ToDoName,UserID,CreatedAt,FinishedFlag,FinishedAt,Description FROM ToDo ORDER BY FinishedFlag,FinishedAt DESC,CreatedAt DESC");
    }    
  }
  
  
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  mysqli_stmt_bind_result($stmt, $todoID, $todoName, $userID, $createdAt, $finishedFlag, $finishedAt, $desc);
  
  $rowsNum = mysqli_stmt_num_rows($stmt);
  if ($rowsNum==0){
  ?>
    <tr>
      <td colspan="10" >Jelenleg nincsenek feladatok!</td>
    </tr>
  <?
  }else{
    while(mysqli_stmt_fetch($stmt)) {
  ?>
    <tr>
      <td>
        <?=$todoID?>
      </td>
      <td>
        <?=htmlspecialchars($todoName)?>
      </td>
      <td>
        <?
          if (strlen($userID)>0){
            $SQLresult2 = mysqli_query($con,"SELECT UserID,UserName FROM User WHERE UserID=" . intval($userID));
            while($row2 = mysqli_fetch_assoc($SQLresult2)) {
              echo htmlspecialchars($row2['UserName']);
            }
          }
        ?>
      </td>
      <td>
        <?
          $datetime1 = $createdAt;
          $datetime2 = date('Y-m-d H:i:s');
          $diff = abs(strtotime($datetime2) - strtotime($datetime1));
          $days = floor($diff / (3600*24));
          $hours = floor(($diff - $days * (3600 * 24)) / 3600);
          echo $days . " nap és " . $hours . " óra";
        ?>
      </td>
      <td>
        <?
          if ($finishedFlag=="1"){
            echo "igen";
          }else{
            echo "nem";
          }
        ?>
      </td>
      <td>
        <?
          if ($finishedAt!="0000-00-00 00:00:00"){
            echo $finishedAt;
          }
        ?>
      </td>
      <td>
        <?=htmlspecialchars($desc)?>
      </td>
      <td>
        <button onclick="submitFinished(<?=$todoID?>)" >Elvégezve</button>
      </td>
      <td>
        <button onclick="window.location.href='admin.php?Type=modify&id=<?=$todoID?>'" >Szerkeszt</button>
      </td>
      <td>
        <button onclick="submitDelete(<?=$todoID?>)" >Töröl</button>
      </td>
    </tr>
  <?    
    }
  }
  ?>
  </table>
<?
  mysqli_close($con);
}
?> 

<?
// NEW **************************************************************  
if ($Type=="new"){
  $UserOption = "<option value='0' >--</option>";
  $SQLresult = mysqli_query($con,"SELECT UserID,UserName FROM User");
  while($row = mysqli_fetch_assoc($SQLresult)) {
    $UserOption = $UserOption . "<option value='" . $row['UserID'] . "' >" . htmlspecialchars($row['UserName']) . "</option>";
  }  
?>
  <form id="form1">
    <table>
      <tr><td colspan="2" >Új feladat</td></tr>
      <tr><td>Feladat:</td><td><input name="todoName" type="text" /></td></tr>
      <tr><td>Felelős felhasználó:</td><td><select name="todoUser" type="text"><?=$UserOption?></select></td></tr> 
      <tr><td>Elvégzett:</td><td><input name="todoFinishedFlag" type="checkbox" value="Y" /></td></tr>
      <tr><td>Leírás:</td><td><textarea name="todoDesc" cols="30" rows="4" ></textarea></td></tr>      
    </table>
    
    <input name="Type" type="hidden" value="new" />
  </form>
  <button onclick="submitNew()">Mentés</button>  
<?
  mysqli_close($con);
}
?>   

<?
// MODIFY **************************************************************  
if ($Type=="modify"){
  if (strlen($RecID)==0){
    echo "Hiba! Nem megfelelő paraméter!";
    exit;    
  }else if (!is_numeric($RecID)){
    echo "Hiba! Nem megfelelő paraméter!";
    exit;
  }
  
  $UserOption = "<option value='0' >--</option>";
  
  $SQLresult = mysqli_query($con,"SELECT * FROM ToDo WHERE ToDoID=" . intval($RecID));
  while($row = mysqli_fetch_assoc($SQLresult)) {
  
?>
  <form id="form1">
    <table>
      <tr><td colspan="2" >Feladat módosítása</td></tr>
      <tr><td>Feladat:</td><td><input name="todoName" type="text" value="<?=htmlspecialchars($row['ToDoName'])?>" /></td></tr>
<?
    $SQLresult2 = mysqli_query($con,"SELECT UserID,UserName FROM User");
    while($row2 = mysqli_fetch_assoc($SQLresult2)) {
      $UserOption = $UserOption . "<option value='" . $row2['UserID'] . "' ";
      
      if ($row['UserID']==$row2['UserID']){
        $UserOption = $UserOption . " selected ";
      }
      
      $UserOption = $UserOption . " >" . htmlspecialchars($row2['UserName']) . "</option>";
    }  
?>      
      <tr><td>Felelős felhasználó:</td><td><select name="todoUser" type="text"><?=$UserOption?></select></td></tr> 

<?
    $CheckFlag = "";
    if ($row['FinishedFlag']=="1") { $CheckFlag = "checked"; }
?>      
      <tr><td>Elvégzett:</td><td><input name="todoFinishedFlag" type="checkbox" value="Y" <?=$CheckFlag?> /></td></tr>
      
      <tr><td>Leírás:</td><td><textarea name="todoDesc" cols="30" rows="4" ><?=htmlspecialchars($row['Description'])?></textarea></td></tr>      
    </table>
    
    <input name="Type" type="hidden" value="modify" />
    <input name="id" type="hidden" value="<?=$row['ToDoID']?>" />
  </form>
  <button onclick="submitModify()">Mentés</button>  
<?
  }
  mysqli_close($con);
}
?>   

</body>
</html>
