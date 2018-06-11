<?php  session_start(); 

$con;

// check session ******************
if (isset($_SESSION['SessID']) && !empty($_SESSION['SessID'])>0){
  $SessID = $_SESSION['SessID'];
  $User = $_SESSION['User'];
  $Sec = time() - $_SESSION['LastAction'];
  $SiteName = $_SERVER['SCRIPT_FILENAME'];
  
  if ($Sec>=900){     // 15 minutes timeout
    Logout();
  }else{
    $_SESSION['LastAction'] = time();
  }
  
  $con = DBConnect();
  
  if (substr($SiteName,1)!="admin.php" && substr($SiteName,1)!="adminFunc.php"){    
    header("Location:admin.php");
    exit();    
  }
    
}else if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])){
  $User = $_POST['username'];
  $PW = $_POST['password'];
  $OK = False;
  
  $con = DBConnect();
  $SQLresult = mysqli_query($con,"SELECT UserID,UserName,Password FROM User WHERE ActiveFlag=1");
  if (mysqli_num_rows($SQLresult)>0) {
    while($row = mysqli_fetch_assoc($SQLresult)) {    
      if (strtoupper($row['UserName'])==strtoupper($User) && $row['Password']==hash('sha256',$PW)){
        $SessID = hash('sha256',$row['UserID'] . date('Y-m-d H:i:s') . rand(1, 100000));
        $_SESSION['SessID'] = $SessID;
        $_SESSION['User'] = $User;
        $_SESSION['LastAction'] = time();
        
        mysqli_query("UPDATE User SET Session='" . $SessID . "' WHERE UserID=" . $row['UserID'],$con);
        mysqli_close($con); 
        $OK = True;
        
        break;
      }
    }
  }
    
  if ($OK){
        
    header("Location:admin.php");
    exit();
  }
  
}else if (substr($_SERVER['SCRIPT_FILENAME'],1)=="admin.php"){    //get admin without session
  Logout();
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

function Logout(){
  session_unset(); 
  session_destroy();
  
  header("Location:index.php");
  exit();
  
}

?>
