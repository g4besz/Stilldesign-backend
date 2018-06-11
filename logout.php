<?php  session_start(); ?>

<?php
session_unset(); 
session_destroy();

echo "<script>";
echo " window.location='index.php'";
echo "</script>";
exit();

?>