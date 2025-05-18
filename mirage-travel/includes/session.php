<?php
// demarre/cree la session
session_start();

// user deja co ou pas
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// user admin ? !
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// co l'user
function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_login'] = $user['login'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
    
    // maj derniere connexion
    updateLastLogin($user['id']);
}

// tte les fonctions ici pour deco l'user
function logoutUser() {
    // supp les variables créee pr la session 
    $_SESSION = array();
    
    // supp la session
    session_destroy();
}

// redirect si pas co
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// redirect si deja co 
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header("Location: index.php");
        exit();
    }
}

// redirect si user normal 
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: index.php");
        exit();
    }
}
?>