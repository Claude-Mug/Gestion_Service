<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Services"; // Nom de votre base de données

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau d'Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Panneau d'administration</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Basculer la navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="bi bi-person nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addAdminModal">Ajouter Admin</a>
                    </li>
                    <li class="nav-item ms-5">
                        <a class="nav-link bi bi-person" href="profil.php" target="mainFrame">Profil</a>
                    </li>
                    <li class="nav-item ms-5">
                        <a class="nav-link bi bi-house" href="index.php">Accueil</a>
                    </li>
                    <div class="container w-auto mt-1">
                        <form class="d-flex" onsubmit="return handleSearch(event)">
                            <input class="form-control me-2" type="search" name="query" placeholder="Rechercher" aria-label="Search" required>
                            <button class="btn btn-outline-primary btn-sm" type="submit">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                        </form>
                    </div>

                    <script>
                        function handleSearch(event) {
                            event.preventDefault(); // Empêche l'envoi normal du formulaire
                            const query = document.querySelector('input[name="query"]').value;
                            const url = 'recherche2.php?query=' + encodeURIComponent(query); // Crée l'URL
                            window.location.href = url; // Redirige vers la page de résultats
                        }
                    </script>
                    
                    <li class="nav-item dropdown ms-5">
                        <a class="bi bi-gear nav-link dropdown-toggle" href="#" id="parametresDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Paramètres
                        </a>
                        <ul class="dropdown-menu bg-danger-subtle" aria-labelledby="parametresDropdown">
                            <li><a class="dropdown-item" href="adminpass.php" target="mainFrame">Modifier le mot de passe</a></li>
                            <li><a class="dropdown-item" href="Themes.php" target="mainFrame">Configurer les thèmes</a></li>
                            <li><a class="dropdown-item" href="notifications.php" target="mainFrame">Notifications</a></li>
                            <li><a class="dropdown-item" href="Statistique.php" target="mainFrame">Statistiques de paiement</a></li>
                            <li><a class="dropdown-item" href="historique_con.php" target="mainFrame">Afficher l'historique des connexions</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</head>

<body>
    <style>
        body {
            padding-top: 65px; /* Espace pour la barre de navigation */
            margin-right: auto;
        }

        .sidebar {
            height: calc(100vh - 65px); /* Hauteur de la barre latérale */
            position: fixed;
            top: 70px; /* Ajustez selon la hauteur de l'en-tête */
            bottom: 0;
            width: 19%; /* Largeur de la barre latérale */
            overflow-y: auto;
            z-index: 1; /* Assure que la barre latérale est au-dessus du contenu */
        }

        .main-content {
            margin-left: 19%; /* Marge pour la barre latérale */
            padding-top: 10px; /* Ajustez selon la hauteur de l'en-tête */
            height: calc(100vh - 65px); /* Hauteur ajustée */
            width: 81%; /* Largeur ajustée pour la barre latérale */
        }

        .additional-info {
            margin-top: 10px; /* Espace réduit pour un affichage plus proche */
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        #mainFrame {
            width: 100%; /* Occupe toute la largeur disponible */
            height: calc(100vh - 150px); /* Hauteur ajustée */
            border: none;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <!-- Barre latérale -->
            <div class="sidebar bg-info-subtle m-1">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active" onclick="loadContent('tableau.php')">Tableau de messageries</a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="loadContent('statistiques.php')">Statistiques générales</a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="loadContent('revenus.php')">Revenus et Analyses</a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="loadContent('LirePayement.php')">Liste paiements</a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="loadContent('historique.php')">Historiques commande</a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="loadContent('notifications.php')">Notifications globales</a>

                    <div class="mt-3">
                        <h4>Services</h4>
                        <div class="d-flex flex-column">
                            <a href="reservations.php" target="mainFrame" class="btn btn-primary mb-2">Reservations</a>
                            <a href="Prix.php" target="mainFrame" class="btn btn-outline-info mb-2">Statut des commandes</a>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h4>Prestataires</h4>
                        <div class="d-flex flex-column">
                            <a href="prestatairBase.php" target="mainFrame" class="btn btn-success mb-2">Gérer les prestataires</a>
                            <a href="prestatairBase.php" target="mainFrame" class="btn btn-primary mb-2">Ajouter un prestataire</a>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h4>Clients</h4>
                        <div class="d-flex flex-column">
                            <a href="GestionClient.php" target="mainFrame" class="btn btn-success mb-2">Gérer les clients</a>
                            <a href="GestionClient.php" target="mainFrame" class="btn btn-primary mb-2">Ajouter un client</a>
                        </div>
                        <br><br><br>
                    </div>
                </div>
            </div>
            
            <!-- Contenu principal -->
            <div class="col-md-12 main-content">
                <h2 class="text-center text-info">Bienvenue dans le panneau d'administration</h2>
                <!-- Informations supplémentaires directement sous le titre -->
                <div class="additional-info" id="additionalInfo">
                    <h5>Informations Supplémentaires</h5>
                    <p>Cette section contient des informations utiles pour la gestion des services, prestataires et clients.</p>
                </div>
                <iframe name="mainFrame" id="mainFrame" src="Admin0.php" style="width: 100%; height: calc(100vh - 150px); border: none;"></iframe>
            </div> 
        </div>
    </div>

    <!-- Modal pour Ajouter un Admin -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdminModalLabel">Ajouter un Administrateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="ajouter_admin.php" method="POST">
                    <div class="mb-3">
                        <label for="adminNom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="adminNom" name="adminNom" required>
                    </div>
                    <div class="mb-3">
                        <label for="adminPrenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="adminPrenom" name="adminPrenom" required>
                    </div>
                    <div class="mb-3">
                        <label for="adminEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="adminEmail" name="adminEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="adminPassword" class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                            <span class="input-group-text" onclick="togglePasswordVisibility()">👁️</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="adminRole" class="form-label">Rôle</label>
                        <select class="form-select" id="adminRole" name="adminRole" required>
                            <option value="manager">Manager</option>
                            <option value="editor">Éditeur</option>
                            <option value="viewer">Visionneur</option>
                            <option value="service_reparation">Service Réparation</option>
                            <option value="services_domicile">Services Domicile</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Créer Admin</button>
                    <a href="ajouter_admin.php" class="btn btn-outline-secondary">Voir admin</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('adminPassword');
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
    }
</script>

    <script>
        function loadContent(content) {
            const mainFrame = document.getElementById("mainFrame");
            const additionalInfo = document.getElementById("additionalInfo");

            // Masquer les informations supplémentaires
            additionalInfo.style.display = "none";

            // Charger le nouveau contenu dans l'iframe
            mainFrame.src = content;
        }

        // Masquer les informations supplémentaires au chargement initial
        document.addEventListener("DOMContentLoaded", function() {
            const additionalInfo = document.getElementById("additionalInfo");
            additionalInfo.style.display = "none";
        });
    </script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>