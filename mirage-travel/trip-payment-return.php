<?php
// start  la session et inclure les fonctions
include_once 'includes/functions.php';
include_once 'includes/session.php';

// verif si l'utilisateur est connecté
requireLogin();

// verif que tous les paramètres sont présents
if (!isset($_GET['transaction'], $_GET['montant'], $_GET['vendeur'], $_GET['status'], $_GET['control'], $_GET['user_id'])) {
    header("Location: index.php");
    exit();
}

// recup les paramètres
$transaction = $_GET['transaction'];
$montant = $_GET['montant'];
$vendeur = $_GET['vendeur'];
$status = $_GET['status'];
$control = $_GET['control'];
$userId = intval($_GET['user_id']);

// verif que l'utilisateur est correct
if ($userId != $_SESSION['user_id']) {
    header("Location: index.php");
    exit();
}

// verif l'intégrité des données
require_once('getapikey.php');
$api_key = getAPIKey($vendeur);
$calculatedControl = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $status . "#");

if ($calculatedControl !== $control) {
    // erreur d'intégrité, rediriger vers une page d'erreur
    header("Location: payment-error.php?error=integrity");
    exit();
}

// verif le statut du paiement
if ($status === 'accepted') {
    // payement accepter , donc :
    
    // recup les informations de customisation de voyage
    if (isset($_SESSION['trip_customize'])) {
        $tripId = $_SESSION['trip_customize']['trip_id'];
        $chosenOptions = $_SESSION['trip_customize']['options'];
        $nbPersonnes = $_SESSION['trip_customize']['nb_personnes'];
        
        // recup le voyage
        $trip = getTripById($tripId);
        
        if ($trip) {
            // save la réservation
            $booking = saveBooking(
                $userId,
                $tripId,
                $chosenOptions,
                $montant,
                $nbPersonnes,
                $trip['date_debut'],
                $trip['date_fin'],
                $transaction // add l'ID de transaction
            );
            
            // clean la session
            unset($_SESSION['trip_customize']);
            
            // redirect vers la page de succese
            header("Location: payment-success.php?booking_id=" . $booking['id']);
            exit();
        }
    }
    
    // si on a besoin de ça, c'est qu'il y a un problème avec les données
    header("Location: payment-error.php?error=data");
    exit();
} else {
    // Paiem    ent refusé, rediriger vers la page d'erreur
    header("Location: payment-error.php?error=payment");
    exit();
}
?>