<?php
 ob_start();
 session_start();

include "functions.php";

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
        <div>Login:</div>
        
        <label for="UserName" >Felhasználónév:</label><input type="text" id="UserName" name="username" /></p>
        <label for="Password" >Jelszó:</label><input type="password" id="Password" name="password" /></p>
        
        <button type="submit" name="login" >Belépés</button>
        
    </form>
  </div> 
  <div>
     <button onclick="window.location.href='registration.php'" >Regisztráció</button>
  </div>
</body>
</html>
