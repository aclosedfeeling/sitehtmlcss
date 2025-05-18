<?php
// charge les fichier (fin cm)
function loadData($filename) {
    $filePath = "data/" . $filename;
    if (file_exists($filePath)) {
        $jsonData = file_get_contents($filePath);
        return json_decode($jsonData, true);
    }
    return array();
}

// sauvegarde dans les fichier (fin du cm)
function saveData($filename, $data) {
    $filePath = "data/" . $filename;
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($filePath, $jsonData);
}

// est ce que l'user existe
function userExists($login, $email) {
    $users = loadData('users.json');
    foreach ($users as $user) {
        if ($user['login'] === $login || $user['email'] === $email) {
            return true;
        }
    }
    return false;
}

// auth user
function authenticateUser($login, $password) {
    $users = loadData('users.json');
    foreach ($users as $user) {
        if (($user['login'] === $login || $user['email'] === $login) && 
            $user['password'] === $password && 
            $user['status'] !== 'banned') {
            return $user;
        }
    }
    return null;
}

// obtiens les infos par l'id 
function getUserById($userId) {
    $users = loadData('users.json');
    foreach ($users as $user) {
        if ($user['id'] == $userId) {
            return $user;
        }
    }
    return null;
}

// mise a jour de la dernier connexion de l'user
function updateLastLogin($userId) {
    $users = loadData('users.json');
    foreach ($users as &$user) {
        if ($user['id'] == $userId) {
            $user['derniere_connexion'] = date('Y-m-d');
            break;
        }
    }
    saveData('users.json', $users);
}

// recuperer les voyages
function getTrips($limit = 0) {
    $trips = loadData('trips.json');
    if ($limit > 0 && count($trips) > $limit) {
        return array_slice($trips, 0, $limit);
    }
    return $trips;
}

// trouve un voyage par ID
function getTripById($tripId) {
    $trips = loadData('trips.json');
    foreach ($trips as $trip) {
        if ($trip['id'] == $tripId) {
            return $trip;
        }
    }
    return null;
}

// recherche de voyage
function searchTrips($keywords, $destination = '', $dateDepart = '', $duree = '', $budget = '', $type = '') {
    $trips = loadData('trips.json');
    $results = array();
    
    foreach ($trips as $trip) {
        // mots clés pr trouver plus vite
        if (!empty($keywords) && 
            (stripos($trip['titre'], $keywords) === false && 
             stripos($trip['description'], $keywords) === false)) {
            continue;
        }
        
        if (!empty($destination) && $trip['destination'] !== $destination) {
            continue;
        }
        
        if (!empty($dateDepart) && strtotime($trip['date_debut']) < strtotime($dateDepart)) {
            continue;
        }
        
        if (!empty($duree)) {
            $dureeRange = explode('-', $duree);
            if (count($dureeRange) == 2) {
                if ($trip['duree'] < $dureeRange[0] || $trip['duree'] > $dureeRange[1]) {
                    continue;
                }
            } elseif ($duree == 'Plus de 15 jours' && $trip['duree'] <= 15) {
                continue;
            }
        }
        
        if (!empty($budget) && $trip['prix_base'] > $budget) {
            continue;
        }
        
        if (!empty($type) && !in_array($type, $trip['type'])) {
            continue;
        }
        
        $results[] = $trip;
    }
    
    return $results;
}

// nv user
function addUser($userData) {
    $users = loadData('users.json');
    
    // prend le dernier id
    $maxId = 0;
    foreach ($users as $user) {
        if ($user['id'] > $maxId) {
            $maxId = $user['id'];
        }
    }
    
    // new user
    $newUser = array(
        'id' => $maxId + 1,
        'login' => $userData['login'],
        'password' => $userData['password'],
        'role' => 'user',
        'nom' => $userData['nom'],
        'prenom' => $userData['prenom'],
        'email' => $userData['email'],
        'telephone' => isset($userData['telephone']) ? $userData['telephone'] : '',
        'date_inscription' => date('Y-m-d'),
        'derniere_connexion' => date('Y-m-d'),
        'status' => 'standard'
    );
    
    $users[] = $newUser;
    saveData('users.json', $users);
    
    return $newUser;
}

// renregistre une reservation
function saveBooking($userId, $tripId, $optionsChoisies, $prixTotal, $nbPersonnes, $dateDepart, $dateRetour, $transactionId = '') {
    $bookings = loadData('bookings.json');
    
    // trouve le dernier id donc la dernier reserv 
    $maxId = 0;
    foreach ($bookings as $booking) {
        if ($booking['id'] > $maxId) {
            $maxId = $booking['id'];
        }
    }
    
    // crée une reservation
    $newBooking = array(
        'id' => $maxId + 1,
        'user_id' => $userId,
        'trip_id' => $tripId,
        'transaction_id' => $transactionId, // add de l'ID de transaction
        'date_reservation' => date('Y-m-d'),
        'prix_total' => $prixTotal,
        'nb_personnes' => $nbPersonnes,
        'date_depart' => $dateDepart,
        'date_retour' => $dateRetour,
        'statut' => 'payé',
        'options_choisies' => $optionsChoisies,
        'paiement' => array(
            'date' => date('Y-m-d'),
            'montant' => $prixTotal,
            'methode' => 'Carte Bancaire'
        )
    );
    
    $bookings[] = $newBooking;
    saveData('bookings.json', $bookings);
    
    return $newBooking;
}

// recup les reservations de l'user
function getUserBookings($userId) {
    $bookings = loadData('bookings.json');
    $userBookings = array();
    
    foreach ($bookings as $booking) {
        if ($booking['user_id'] == $userId) {
            $userBookings[] = $booking;
        }
    }
    
    return $userBookings;
}

// ici ça fait le calcul de tout les couts possible
function calculateTotalPrice($trip, $optionsChoisies, $nbPersonnes) {
    $totalPrice = $trip['prix_base'] * $nbPersonnes;
    
    foreach ($optionsChoisies as $etapeIndex => $options) {
        $etape = $trip['etapes'][$etapeIndex];
        
        // prix des options dhabitations
        foreach ($etape['options']['hebergement'] as $hebergement) {
            if ($hebergement['nom'] === $options['hebergement'] && !$hebergement['defaut']) {
                $totalPrice += $hebergement['prix'] * $nbPersonnes;
            }
        }
        
        // prix pour les options de restau
        foreach ($etape['options']['restauration'] as $restauration) {
            if ($restauration['nom'] === $options['restauration'] && !$restauration['defaut']) {
                $totalPrice += $restauration['prix'] * $nbPersonnes;
            }
        }
        
        // prix d activité
        if (isset($options['activites'])) {
            foreach ($options['activites'] as $activiteChoisie) {
                foreach ($etape['options']['activites'] as $activite) {
                    if ($activite['nom'] === $activiteChoisie && !$activite['defaut']) {
                        $totalPrice += $activite['prix'] * $nbPersonnes;
                    }
                }
            }
        }
        
        // prix si jamais ya un transport
        if (isset($options['transport']) && isset($etape['options']['transport'])) {
            foreach ($etape['options']['transport'] as $transport) {
                if ($transport['nom'] === $options['transport'] && !$transport['defaut']) {
                    $totalPrice += $transport['prix'];
                }
            }
        }
    }
    
    return $totalPrice;
}

function getBookingById($bookingId) {
    $bookings = loadData('bookings.json');
    foreach ($bookings as $booking) {
        if ($booking['id'] == $bookingId) {
            return $booking;
        }
    }
    return null;
}