<?php
// funct 
include_once 'includes/functions.php';
include_once 'includes/session.php';

// deco
logoutUser();

// pr la page daccueil
header("Location: index.php");
exit();
?>