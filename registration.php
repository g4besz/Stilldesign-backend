<?php
ob_start();
session_start();

$mess;

if (strlen($_POST["captcha_code"])>0){
  if ($_SESSION["captcha"]==$_POST["captcha_code"]){
    if (strlen($_POST["username"])>=4 && strlen($_POST["username"])<=50 && strlen($_POST["password"])>=6 && strlen($_POST["password"])<=12){
      
      $con = DBConnect();
      $stmt = mysqli_prepare($con,"SELECT UserID FROM User WHERE UserName=?");    
      mysqli_stmt_bind_param($stmt, "s", $_POST["username"]);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      mysqli_stmt_bind_result($stmt, $userID);
      
      $rowsNum = mysqli_stmt_num_rows($stmt);
      if ($rowsNum>0){
        $mess = "Már létezik ilyen felhasználónév: " . $_POST["username"] . "!";
        mysqli_close($con);
      }else{      
      
        $stmt = mysqli_prepare($con,"INSERT INTO User (UserName,Password,CreatedAt,ActiveFlag) VALUES (?,?,NOW(),1)");
        mysqli_stmt_bind_param($stmt, "ss", $_POST["username"], hash('sha256',$_POST["password"]));  
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
            
        header('Content-Type: text/html; charset=UTF-8');
        mb_internal_encoding( 'UTF-8' );
        echo "Sikeres regisztráció! Most már bejelentkezhet: <a href='index.php'>Bejelentkezés</a>";
        exit;
      }
    }else{
      $mess = "A Felhasználónév minimum 4 és maximum 50 karakter lehet! A Jelszó minimum 6 és maximum 12 karakter lehet!";      
    }
  }else{
    $mess = "Nem jó érték a Kód mezőben!";
  }
}else if(isset($_POST["submit"])){
  
  if (strlen($_POST["captcha_code"])==0){
    $mess = "Töltse ki a Kód mezőt!"; 
  }

}

// functions ******************
function DBConnect() {
  $ini_array = parse_ini_file("set.ini");
  $server = $ini_array["server"];
  $user = $ini_array["user"];
  $PW = $ini_array["pw"];
  $DBase = $ini_array["dbase"];
  
  $con = mysqli_connect($server,$user,$PW,$DBase);
  if (!$con) {
    die("Hiba! Nem lehet kapcsolódni az adatbázishoz! Hibakód:" . $user . mysqli_connect_error());
  }	

  return $con;
}

//Start Site ***************************************
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding( 'UTF-8' );

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <LINK REL="StyleSheet" HREF="style.css" TYPE="text/css">
    <title>Login</title>
</head>
<body >
  <div>
    <form method="post" target="_self" >
        <div>Regisztráció:</div>
        <div style="color:red;"><?=$mess?></div>
        
        <div>
          Felhasználónév:<input type="text" name="username"  value="" />
        </div>
        <div>
          Jelszó:<input type="password" name="password"  value="" />
        </div>
        <div>
          <img src="captcha.php" alt="Biztonsági kód" title="Biztonsági kód" />
        </div>
        <div>
          Kód:<input type="text" name="captcha_code" value="" />
        </div>
        
        <div>
          <input type="submit" name="submit" value="Regisztráció" />
        </div>
        
    </form>
  </div>  
</body>
</html>
