<?php
// titre 
$pageTitle = "Administration";

// fonct 
include_once 'includes/functions.php';
include_once 'includes/session.php';

// user admin ou pas
requireAdmin();

// charge les users 
$users = loadData('users.json');

// variables cm dans le cours
$success = '';
$error = '';

// search 
$searchKeyword = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchKeyword = trim($_GET['search']);
    
    if (!empty($searchKeyword)) {
        // filtre en fonction de ce que l'user recherche
        $filteredUsers = array();
        foreach ($users as $user) {
            if (stripos($user['nom'], $searchKeyword) !== false ||
                stripos($user['prenom'], $searchKeyword) !== false ||
                stripos($user['email'], $searchKeyword) !== false) {
                $filteredUsers[] = $user;
            }
        }
        $users = $filteredUsers;
    }
}

// a finir pr java 
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    
    if ($userId > 0) {
        // en theorie c pr modif un user pas admin
        $success = "Action effectuée avec succès.";
    } else {
        $error = "Utilisateur invalide.";
    }
}

// basique pagination (a edit si jamais )
$usersPerPage = 10;
$totalUsers = count($users);
$totalPages = ceil($totalUsers / $usersPerPage);
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start = ($currentPage - 1) * $usersPerPage;
$paginatedUsers = array_slice($users, $start, $usersPerPage);

// header
include_once 'includes/header.php';
?>

<div class="container">
    <?php if ($success): ?>
        <div class="success-message">
            <p><?php echo $success; ?></p>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error-message">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <div class="profile-section">
        <h2><i class="fas fa-tachometer-alt"></i> Tableau de bord</h2>
        <div class="dashboard-grid">
            <div class="stat-card stat-card-green">
                <i class="fas fa-users fa-2x"></i>
                <h3><?php echo count($users); ?></h3>
                <p>Utilisateurs</p>
            </div>
            <div class="stat-card stat-card-blue">
                <i class="fas fa-plane-departure fa-2x"></i>
                <h3><?php echo count(loadData('trips.json')); ?></h3>
                <p>Voyages actifs</p>
            </div>
            <div class="stat-card stat-card-orange">
                <i class="fas fa-shopping-cart fa-2x"></i>
                <h3><?php echo count(loadData('bookings.json')); ?></h3>
                <p>Réservations</p>
            </div>
            <div class="stat-card stat-card-red">
                <i class="fas fa-comment fa-2x"></i>
                <h3>0</h3>
                <p>Nouveaux messages</p>
            </div>
        </div>
    </div>
    
    <h2 class="mt-5"><i class="fas fa-users"></i> Gestion des utilisateurs</h2>
    
    <div class="form-container form-search">
        <form method="GET" action="admin.php">
            <div class="search-bar">
                <div class="search-input">
                    <input type="text" name="search" placeholder="Rechercher un utilisateur..." style="width: 100%;" value="<?php echo $searchKeyword; ?>">
                </div>
                <button type="submit"><i class="fas fa-search"></i> Rechercher</button>
                <a href="admin.php"><button type="button" class="btn-add"><i class="fas fa-user-plus"></i> Ajouter</button></a>
            </div>
        </form>
    </div>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Date d'inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paginatedUsers as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['prenom'] . ' ' . $user['nom']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                    <span class="user-status status-<?php echo $user['status']; ?>">
                        <?php echo ucfirst($user['status']); ?>
                    </span>
                </td>
                <td><?php echo $user['date_inscription']; ?></td>
                <td>
                    <a href="admin.php?action=edit&user_id=<?php echo $user['id']; ?>"><button class="btn-edit"><i class="fas fa-edit"></i></button></a>
                    
                    <?php if ($user['status'] === 'banned'): ?>
                        <a href="admin.php?action=activate&user_id=<?php echo $user['id']; ?>"><button class="btn-activate"><i class="fas fa-check"></i></button></a>
                    <?php else: ?>
                        <a href="admin.php?action=ban&user_id=<?php echo $user['id']; ?>"><button class="btn-ban"><i class="fas fa-ban"></i></button></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="admin.php?page=<?php echo $currentPage - 1; ?><?php echo !empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : ''; ?>">
                <button class="pagination-btn"><i class="fas fa-angle-left"></i></button>
            </a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="admin.php?page=<?php echo $i; ?><?php echo !empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : ''; ?>">
                <button class="pagination-btn<?php echo $i === $currentPage ? '-active' : ''; ?>"><?php echo $i; ?></button>
            </a>
        <?php endfor; ?>
        
        <?php if ($currentPage < $totalPages): ?>
            <a href="admin.php?page=<?php echo $currentPage + 1; ?><?php echo !empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : ''; ?>">
                <button class="pagination-btn"><i class="fas fa-angle-right"></i></button>
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php
// foooooter 
include_once 'includes/footer.php';
?>