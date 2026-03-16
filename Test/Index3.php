<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Services";

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
    <title>Services à Domicile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            padding-top: 56px; /* Espace pour la navbar */    
        }
        
        #mainFrame {
            height: calc(100vh - 56px); /* Hauteur de l'iframe, moins la navbar */
            border: none; /* Pas de bordure */
            width: 100%; /* Pleine largeur */
            
        }
        .icon-color {
            color: #007bff; /* Couleur personnalisée pour les icônes */
        }
        #services {
            display: block; /* Afficher la section des services par défaut */
            margin-top: 20px; /* Espacement supérieur */
        }
        .dropdown-menu {
            display: none; /* Masquer les dropdowns par défaut */
            flex-wrap: wrap; /* Permet aux éléments de passer à la ligne si nécessaire */
            padding: 10px; /* Ajout de padding pour le dropdown */
            z-index: 1000; /* Assure que le dropdown est au-dessus des autres éléments */
            width: 50vw; /* Occuper toute la largeur de l'écran */
            left: 0; /* Aligner à gauche */
            position: absolute; /* Permet de le positionner correctement */
            background-color: rgba(158, 137, 97, 0.187); /* Arrière-plan */
            border: 4px solid #1f1919; /* Bordure grise */
            border-radius: 5px; /* Coins arrondis */
        }
        .dropdown-menu.show {
            display: flex; /* Affiche le dropdown lorsqu'il est ouvert */
        }
        .dropdown-item {
            margin-right: 10px; /* Espacement entre les éléments */
            padding: 8px 12px; /* Padding pour les éléments du dropdown */
            border-radius: 3px; /* Coins arrondis pour les éléments */
            transition: background-color 0.2s; /* Transition douce pour le hover */
        }
        .dropdown-item:hover {
            background-color: #707172; /* Arrière-plan au survol */
        }
        .dropdown-item:last-child {
            margin-right: 0; /* Supprime l'espacement après le dernier élément */
        }
        .info-section {
            background-color: #e5edf0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .info-section:hover {
            transform: scale(1.02);
        }
        .header-text {
            color: #0f0f0f;
            font-weight: bold;
        }
        .paragraph-text {
            color: #060606;
        }
    </style>
</head>
<body>

<!-- Entête Dynamique -->
<div class="container mt-3">
    <div id="dynamic-header" class="border border-info rounded p-1 d-flex justify-content-between align-items-center" style="background-color: #f8f9fa;">
        <div class="contact-info">
            <h5>Contactez-nous</h5>
            <ul class="list-inline">
                <li class="list-inline-item">
                    <i class="bi bi-telephone"></i> +257 76906021
                </li>
                <li class="list-inline-item">
                    <i class="bi bi-envelope"></a></i> claudemug4@gmail.com / mugishabruce@gmail.com
                </li>
            </ul>
        </div>
        <button class="nav-item"><a class="nav-link text-black" href="#" onclick="loadContent('Apropos.php');">A propos</a></button>
        <div class="social-media">
            <h5>Suivez-nous</h5>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="https://www.facebook.com/claude.sebir" target="_blank" class="text-primary" style="color: #3b5998;"><i class="bi bi-facebook"></i></a></li>
                <li class="list-inline-item"><a href="https://x.com/ClaudeMug98071" target="_blank" class="text-info" style="color: #1DA1F2;"><i class="bi bi-twitter"></i></a></li>
                <li class="list-inline-item"><a href="https://www.instagram.com/claude_mug/#" target="_blank" class="text-danger" style="color: #C13584;"><i class="bi bi-instagram"></i></a></li>
                <li class="list-inline-item"><a href="https://www.linkedin.com/in/claude-mugisha" target="_blank" class="text-info" style="color: #0077B5;"><i class="bi bi-linkedin"></i></a></li>
                <li class="list-inline-item"><a href="https://www.youtube.com/@ClaudeMug" target="_blank" class="text-danger" style="color: #FF0000;"><i class="bi bi-youtube"></i></a></li>
                <li class="list-inline-item"><a href="https://t.me/claudemug" target="_blank" class="text-info" style="color: #0088CC;"><i class="bi bi-telegram"></i></a></li>
                <li class="list-inline-item"><a href="https://api.whatsapp.com/send?phone=25769392200" target="_blank" class="text-success" style="color: #25D366;"><i class="bi bi-whatsapp"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Entête Statique -->
<nav class="navbar navbar-expand-lg navbar-light bg-success fixed-top">
    <div class="container">
        <a class="navbar-brand text-white" href="#">Services à Domicile</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-white" href="#" onclick="showHome();">Accueil</a></li>
                <li class="nav-item dropdown" id="servicesDropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="dropdownServices" role="button">
                        Nos Services à Domicile
                    </a>
                    <ul class="dropdown-menu bg-light" aria-labelledby="dropdownServices">
                        <li><a class="dropdown-item" href="#" onclick="loadContent('electromenagers.php');"><i class="bi bi-tools icon-color"></i> Systèmes Électroménagers</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('securite.php');"><i class="bi bi-shield-lock icon-color"></i> Sécurité à Domicile</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('demenagement.php');"><i class="bi bi-box icon-color"></i> Déménagement</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('enfants.php');"><i class="bi bi-person icon-color"></i> Garde d'Enfants</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('livraison.php');"><i class="bi bi-truck icon-color"></i> Livraison</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('evenements.php');"><i class="bi bi-clipboard icon-color"></i> Organisation d'Événements</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('coaching.php');"><i class="bi bi-people icon-color"></i> Coaching Personnel</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('conseil-financier.php');"><i class="bi bi-graph-up icon-color"></i> Conseil Financier</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('peinture.php');"><i class="bi bi-paint-bucket icon-color"></i> Peinture et Nettoyage</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('hygiene.php');"><i class="bi bi-thermometer icon-color"></i> Soins d’hygiène et infirmiers</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('jardinage.php');"><i class="bi bi-house-door icon-color"></i> Entretien Ménager et Jardinage</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown" id="repairDropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="dropdownRepair" role="button">
                        Réparation
                    </a>
                    <ul class="dropdown-menu bg-light" aria-labelledby="dropdownRepair">
                        <li><a class="dropdown-item" href="#" onclick="loadContent('electricite.php');"><i class="bi bi-lightbulb icon-color"></i> Électricité</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('electronique.php');"><i class="bi bi-laptop icon-color"></i> Réparation d'Appareils Éléctronique</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('plomberie.php');"><i class="bi bi-water icon-color"></i> Plomberie</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('mecanique.php');"><i class="bi bi-tools icon-color"></i> Mécanique</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('soudure.php');"><i class="bi bi-car-front icon-color"></i> Soudure</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('cameras.php');"><i class="bi bi-camera icon-color"></i> Réparation de Caméras</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('montres.php');"><i class="bi bi-watch icon-color"></i> Réparation de Montres et Accessoires</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('velos.php');"><i class="bi bi-bicycle icon-color"></i> Réparation de Vélo</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('climatisation.php');"><i class="bi bi-thermometer icon-color"></i> Réparation de Climatisation et Micro-ondes</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('mixeur-audio.php');"><i class="bi bi-soundwave icon-color"></i> Réparation de Systèmes Audio et Mixeurs</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('musique-instruments.php');"><i class="bi bi-screwdriver icon-color"></i> Réparation d'Instruments de Musique</a></li>
                    </ul>
                </li>

                 
                <div class="search-container d-flex align-items-center ms-auto">
    <button class="btn btn-success" id="search-button" onclick="toggleSearch()">
        <i class="bi bi-search"></i>
    </button>
    <div class="input-group" style="width: 0; transition: width 0.3s;" id="search-input-container">
        <input type="text" 
               class="form-control search-input" 
               id="search-input" 
               placeholder="Rechercher un service..."
               style="display: none; min-width: 200px;"
               onkeypress="if(event.key === 'Enter') executeSearch()">
        <button class="btn btn-primary" 
                id="execute-search-button" 
                style="display: none;"
                onclick="executeSearch()">
            <i class="bi bi-search"></i>
        </button>
    </div>
</div>

<div id="search-results" class="mt-3 position-absolute bg-white shadow rounded p-2" style="display:none; width: 400px; max-height: 500px; overflow-y: auto; z-index: 1000;"></div>

<script>
// Fonction pour basculer l'affichage de la recherche
function toggleSearch() {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('execute-search-button');
    const container = document.getElementById('search-input-container');
    
    if (searchInput.style.display === 'none') {
        // Afficher le champ de recherche avec une animation
        container.style.width = '250px';
        searchInput.style.display = 'block';
        searchButton.style.display = 'block';
        searchInput.focus();
    } else {
        // Masquer le champ de recherche avec une animation
        container.style.width = '0';
        setTimeout(() => {
            searchInput.style.display = 'none';
            searchButton.style.display = 'none';
            document.getElementById('search-results').style.display = 'none';
        }, 300);
    }
}

// Fonction pour exécuter la recherche
async function executeSearch() {
    const query = document.getElementById('search-input').value.trim();
    const resultsContainer = document.getElementById('search-results');
    
    // Validation minimale
    if (query.length < 2) {
        showSearchResults('<div class="alert alert-warning p-2">Veuillez entrer au moins 2 caractères</div>');
        return;
    }

    // Afficher le loader
    showSearchResults('<div class="text-center p-3"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div><p class="mt-2">Recherche en cours...</p></div>');

    try {
        const response = await fetch('recherche.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `search_query=${encodeURIComponent(query)}`
        });
        
        if (!response.ok) {
            throw new Error('Erreur serveur: ' + response.status);
        }
        
        const data = await response.json();
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        if (data.results && data.results.length > 0) {
            displayResults(data.results);
        } else {
            showSearchResults('<div class="alert alert-info p-2">Aucun résultat trouvé</div>');
        }
        
    } catch (error) {
        console.error("Erreur de recherche:", error);
        showSearchResults(`<div class="alert alert-danger p-2">Erreur: ${error.message}</div>`);
    }
}

// Afficher les résultats de recherche
function showSearchResults(content) {
    const container = document.getElementById('search-results');
    container.innerHTML = content;
    container.style.display = 'block';
    
    // Positionner les résultats sous le champ de recherche
    const input = document.getElementById('search-input');
    const rect = input.getBoundingClientRect();
    container.style.left = `${rect.left}px`;
    container.style.top = `${rect.bottom + window.scrollY + 5}px`;
}

// Formater les résultats
function displayResults(results) {
    let html = '<div class="list-group list-group-flush">';
    
    results.forEach(result => {
        html += `
            <a href="#" class="list-group-item list-group-item-action" onclick="selectSearchResult('${result.table}', ${result.id})">
                <div class="d-flex justify-content-between">
                    <h6 class="mb-1 text-primary">${result.table} #${result.id}</h6>
                    <small class="text-muted">${result.context}</small>
                </div>
                ${result.data.nom ? `<p class="mb-1"><strong>${result.data.nom}</strong></p>` : ''}
                ${result.data.description ? `<small class="text-muted">${result.data.description.substring(0, 100)}${result.data.description.length > 100 ? '...' : ''}</small>` : ''}
            </a>
        `;
    });
    
    html += '</div>';
    showSearchResults(html);
}

// Sélectionner un résultat
function selectSearchResult(table, id) {
    // Charger le contenu correspondant au résultat
    loadContent(`${table.toLowerCase()}.php?id=${id}`);
    
    // Fermer les résultats et réinitialiser la recherche
    document.getElementById('search-results').style.display = 'none';
    document.getElementById('search-input').value = '';
    toggleSearch();
}

// Fermer les résultats quand on clique ailleurs
document.addEventListener('click', function(e) {
    const resultsContainer = document.getElementById('search-results');
    const searchContainer = document.querySelector('.search-container');
    
    if (!searchContainer.contains(e.target)) {
        resultsContainer.style.display = 'none';
    }
});
</script>
                <li class="nav-item"><a class="nav-link text-white" href="#" onclick="loadContent('prestateur.php');">Devenir prestataire</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#" onclick="loadContent('faq.php');">FAQ</a></li>
                <li class="nav-item"><a class="btn btn-outline-dark text-white m-1" href="#" onclick="loadContent('mescommandes.php');">mes commandes</a></li>
                <button type="button" class="bi bi-person btn btn-outline-info text-white" data-bs-toggle="modal" data-bs-target="#loginModal">
                    Se connecter
                </button>
            </ul>
        </div>
    </div>
</nav>


<!-- Section des Services -->
<div class="container mt-5" id="services">
    <!-- Nouveaux Paragraphes -->
    <h1 class="text-center">Bienvenue chez Homme Services !</h1>   
    <div class="row">
        <!-- Image en haut de la page -->
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="images/home service.jpg" alt="Entretien Ménager et Jardinage" class="img-fluid rounded" style="width: 100%; height: 400px;">
        </div>
    </div>
</div>
        <div class="col-md-6">
            <div class="info-section">
                <h3 class="header-text">Pourquoi Choisir Homme Services ?</h3>
                <p class="paragraph-text">
                    Nous sommes fiers d'être les meilleurs dans ce que nous faisons. Notre expertise, notre attention aux détails et notre engagement envers la satisfaction client font de nous le choix idéal. Avec Homme Services, vous bénéficiez d'une qualité sans compromis et d'un savoir-faire reconnu.
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-section">
                <h3 class="header-text">Services en Ligne</h3>
                <p class="paragraph-text">
                    Grâce à la technologie, nous avons simplifié la manière dont vous accédez à nos services. Notre plateforme en ligne vous permet de réserver facilement des services, de gérer vos demandes et de suivre vos commandes en toute transparence. Nous avons pensé à vous, pour rendre votre expérience aussi agréable que possible.
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="info-section">
                <h3 class="header-text">Qualité et Savoir-Faire</h3>
                <p class="paragraph-text">
                    Chez Homme Services, la qualité est notre devise. Chaque membre de notre équipe est formé pour offrir un service de haute qualité, en utilisant les meilleures pratiques de l'industrie. Nous nous engageons à vous fournir des solutions adaptées à vos besoins, tout en garantissant un service amical et professionnel.
                </p>
            </div>
        </div>
    
            <div class="row">
                <div class="col-md-4">
                    <div class="info-section">
                        <h3 class="header-text">Nos Services de Qualité</h3>
                        <p class="paragraph-text">
                            Chez Homme Services, nous offrons une gamme complète de services adaptés à vos besoins. Que ce soit pour des travaux ménagers, de la sécurité à domicile, ou même des services d'assistance personnelle, nous avons ce qu'il vous faut.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-section">
                        <h3 class="header-text">Une Équipe Dévouée</h3>
                        <p class="paragraph-text">
                            Notre équipe est composée de professionnels qualifiés et passionnés, prêts à vous offrir un service exceptionnel. Votre satisfaction est notre priorité, et nous nous engageons à dépasser vos attentes.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-section">
                        <h3 class="header-text">Simplicité et Confiance</h3>
                        <p class="paragraph-text">
                            Laissez-nous gérer les détails pendant que vous profitez de votre temps. Avec un processus simple et transparent, nous vous garantissons une expérience sans stress et des résultats de qualité.
                        </p>
                    </div>
                </div>
            </div>
    </div>
    <h2 class="text-center text-info-emphasis text-decoration-underline">Découvrez nos services et laissez-nous vous aider à simplifier votre vie.</h2>

    <!-- Services à Domicile -->
    <h3 class="mt-5 text-primary-emphasis text-center">Services à Domicile</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-tools icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Systèmes Électroménagers</h5>
                    <p class="card-text">Installation et dépannage de divers appareils électroménagers.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('electromenagers.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-shield-lock icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Sécurité à Domicile</h5>
                    <p class="card-text">Services d'installation de systèmes de sécurité.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('securite.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-box icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Déménagement</h5>
                    <p class="card-text">Services de déménagement et transport.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('demenagement.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-person-fill icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Garde d'Enfants</h5>
                    <p class="card-text">Services de garde d'enfants à domicile.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('enfants.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-truck icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Livraison</h5>
                    <p class="card-text">Services de livraison à domicile.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('livraison.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-clipboard icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Organisation d'Événements</h5>
                    <p class="card-text">Services d'organisation pour divers événements.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('evenements.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-people icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Coaching Personnel</h5>
                    <p class="card-text">Services de coaching et de développement personnel.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('coaching.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-graph-up icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Conseil Financier</h5>
                    <p class="card-text">Services de conseil pour la gestion financière.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('conseil-financier.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-paint-bucket icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Peinture et Nettoyage</h5>
                    <p class="card-text">Services de peinture et de nettoyage.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('peinture.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-heart icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Soins d’hygiène et infirmiers</h5>
                    <p class="card-text">Services de soins d’hygiène et infirmiers à domicile.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('hygiene.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-house-door icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Entretien Ménager et Jardinage</h5>
                    <p class="card-text">Services d'entretien ménager et jardinage.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('jardinage.php');">En savoir plus</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Services de Réparation -->
    <h3 class="mt-5 text-primary-emphasis text-center">Services de Réparation</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-lightbulb-off-fill icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Électricité</h5>
                    <p class="card-text">Services de réparation et d'installation électrique.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('electricite.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-laptop icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Réparation d'Appareils Électroniques</h5>
                    <p class="card-text">Réparation de divers appareils électroniques.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('electronique.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-water icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Plomberie</h5>
                    <p class="card-text">Services de réparation de plomberie.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('plomberie.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-tools icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Mécanique</h5>
                    <p class="card-text">Réparation de véhicules mécaniques.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('mecanique.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-car-front icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Soudure</h5>
                    <p class="card-text">Services de soudage de plusier genre.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('soudure.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-camera icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Réparation de Caméras</h5>
                    <p class="card-text">Services de réparation pour caméras.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('cameras.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-watch icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Réparation de Montres et Accessoires</h5>
                    <p class="card-text">Réparation de montres et accessoires.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('montres.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-bicycle icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Réparation de Vélo</h5>
                    <p class="card-text">Services de réparation et entretien de vélos.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('velos.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-snow icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Réparation de Climatisation et Micro-ondes</h5>
                    <p class="card-text">Services de réparation pour climatiseurs et micro-ondes.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('climatisation.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-soundwave icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Réparation de Systèmes Audio et Mixeurs</h5>
                    <p class="card-text">Réparation de systèmes audio et équipements de mixage.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('mixeur.php');">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-music-note-beamed icon-color" style="font-size: 50px;"></i>
                    <h5 class="card-title">Réparation d'Instruments de Musique</h5>
                    <p class="card-text">Services de réparation d'instruments de musique.</p>
                    <a href="#" class="btn btn-outline-info text-success" onclick="loadContent('musique-instruments.php');">En savoir plus</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="info-section">
                <h3 class="header-text">Avis de Nos Clients</h3>
                <p class="paragraph-text">
                    Ne prenez pas seulement notre parole ! Lisez les avis de nos clients satisfaits qui témoignent de la qualité de nos services. Vos retours sont précieux et nous aident à nous améliorer.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-section">
                <h3 class="header-text">Contactez-Nous</h3>
                <p class="paragraph-text">
                    Vous avez des questions ? N'hésitez pas à nous contacter ! Notre équipe est là pour vous aider et répondre à toutes vos préoccupations. Ensemble, nous trouverons la solution qui vous convient.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-section">
                <h3 class="header-text">Commencez Dès Aujourd'hui</h3>
                <p class="paragraph-text">
                    Ne perdez pas de temps ! Commender nos services dès maintenant et faites le premier pas vers une vie plus facile.!
                </p>
            </div>
        </div>
    </div>
    
 <!-- Section des Avis -->
<div class="container mt-5" id="avis">
    <h2 class="text-center text-info-emphasis text-decoration-underline">Avis</h2>
    <div class="row" id="avis-container"></div>
</div>

<script>
    const services = [
        { name: "Systèmes Électroménagers" },
        { name: "Sécurité à Domicile" },
        { name: "Déménagement" },
        { name: "Garde d'Enfants" },
        { name: "Livraison" },
        { name: "Organisation d'Événements" },
        { name: "Coaching Personnel" },
        { name: "Conseil Financier" },
        { name: "Peinture et Nettoyage" },
        { name: "Soins d’hygiène et infirmiers" },
        { name: "Entretien Ménager et Jardinage" },
        { name: "Électricité" },
        { name: "Réparation d'Appareils Électroniques" },
        { name: "Plomberie" },
        { name: "Mécanique" },
        { name: "Réparation de Voitures" },
        { name: "Réparation de Caméras" },
        { name: "Réparation de Montres et Accessoires" },
        { name: "Réparation de Vélo" },
        { name: "Réparation de Climatisation et Micro-ondes" },
        { name: "Réparation de Systèmes Audio et Mixeurs" },
        { name: "Réparation d'Instruments de Musique" }
    ];

    // Fonction pour afficher tous les services et leurs avis
    function showAllServices() {
        const container = document.getElementById('avis-container');
        container.innerHTML = ''; // Clear previous reviews

        services.forEach(service => {
            const colDiv = document.createElement('div');
            colDiv.className = 'col-md-4';
            colDiv.innerHTML = `
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">${service.name}</h5>
                            <div class="stars" data-rating="0">
                                <span class="star" data-value="1">&#9733;</span>
                                <span class="star" data-value="2">&#9733;</span>
                                <span class="star" data-value="3">&#9733;</span>
                                <span class="star" data-value="4">&#9733;</span>
                                <span class="star" data-value="5">&#9733;</span>
                            </div>
                        </div>
                        <a href="avis.php" class="leave-review" data-service="${service.name}">Laisser un Avis</a>
                    </div>
                </div>
            `;
            container.appendChild(colDiv);

            // Add event listener for stars
            const starsContainer = colDiv.querySelector('.stars');
            starsContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('star')) {
                    const rating = e.target.getAttribute('data-value');
                    this.setAttribute('data-rating', rating);
                    updateStars(this, rating);
                }
            });
        });

        document.getElementById('avis').style.display = 'block'; // Show the avis section
    }

    function updateStars(container, rating) {
        const stars = container.querySelectorAll('.star');
        stars.forEach(star => {
            star.classList.remove('filled');
            if (star.getAttribute('data-value') <= rating) {
                star.classList.add('filled');
            }
        });
    }

    function loadPage(page) {
        // Cache la section des avis lorsque vous chargez une nouvelle page
        document.getElementById('avis').style.display = 'none';
        
        // Ici, vous pouvez ajouter la logique pour charger votre page
        console.log(`Chargement de la page: ${page}`);
        // Exemple : AJAX call ou autre méthode pour charger le contenu
    }

    // Appel initial pour afficher tous les services
    showAllServices(); // Affiche tous les services lors du chargement de la page.
</script>

<style>
    .stars {
        display: flex;
        justify-content: flex-start;
        cursor: pointer;
    }
    .star {
        font-size: 30px;
        color: lightgray; /* Couleur par défaut */
        transition: color 0.2s;
    }
    .star.filled {
        color: gold; /* Couleur des étoiles remplies */
    }
    .leave-review {
        margin-left: 10px;
        text-decoration: underline;
        color: blue;
    }
</style>
    


<!-- Iframe pour Charger le Contenu -->
<div class="container-fluid">
    <iframe id="mainFrame" src="" frameborder="0"></iframe>
    
</div>

<!-- Section des Avis -->
<?php
// ==== CONFIGURATION BASE DE DONNÉES ====
$host = 'localhost';
$dbname = 'Services';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// ==== TRAITEMENT DES AVIS ====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['note'])) {
        // Enregistrement de la note
        $stmt = $pdo->prepare("INSERT INTO avis (service_nom, service_categorie, note) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['service_nom'], $_POST['service_categorie'], (int)$_POST['note']]);
    } 
    elseif (isset($_POST['commentaire'])) {
        // Enregistrement du commentaire
        $stmt = $pdo->prepare("UPDATE avis SET client_nom = ?, commentaire = ? WHERE id = ?");
        $stmt->execute([
            htmlspecialchars($_POST['client_nom']),
            htmlspecialchars($_POST['commentaire']),
            (int)$_POST['avis_id']
        ]);
    }
    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit();
}

// ==== LISTE DES SERVICES ====
$services = [
    'Services à Domicile' => [
        'Systèmes Électroménagers', 'Sécurité à Domicile', 'Déménagement',
        'Garde d\'Enfants', 'Livraison', 'Organisation d\'Événements',
        'Coaching Personnel', 'Conseil Financier', 'Peinture et Nettoyage',
        'Soins d\'hygiène et infirmiers', 'Entretien Ménager et Jardinage'
    ],
    'Services de Réparation' => [
        'Électricité', 'Réparation d\'Appareils Éléctronique', 'Plomberie',
        'Mécanique', 'Soudure', 'Réparation de Caméras',
        'Réparation de Montres et Accessoires', 'Réparation de Vélo',
        'Réparation de Climatisation et Micro-ondes',
        'Réparation de Systèmes Audio et Mixeurs', 'Réparation d\'Instruments de Musique'
    ]
];

// ==== RÉCUPÉRATION DES NOTES ====
$notes_moyennes = [];
$dernier_avis_id = [];

foreach ($services as $categorie => $noms) {
    foreach ($noms as $nom) {
        // Calcul des moyennes
        $stmt = $pdo->prepare("SELECT AVG(note) as moyenne, COUNT(*) as total FROM avis WHERE service_categorie = ? AND service_nom = ?");
        $stmt->execute([$categorie, $nom]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $notes_moyennes[$categorie][$nom] = [
            'moyenne' => round($result['moyenne'], 1),
            'total' => $result['total'] ?? 0
        ];
        
        // ID du dernier avis pour commentaire
        $stmt = $pdo->prepare("SELECT id FROM avis WHERE service_categorie = ? AND service_nom = ? ORDER BY date_creation DESC LIMIT 1");
        $stmt->execute([$categorie, $nom]);
        $dernier_avis_id[$categorie][$nom] = $stmt->fetchColumn();
    }
}
?>

<!-- ==== AFFICHAGE DES SERVICES ==== -->
<div class="container py-4 border border-info border-3" id="avis">
<h1 class="text-center text-info-emphasis text-decoration-underline">Évaluation des Services</h1>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mb-4">Merci pour votre avis !</div>
    <?php endif; ?>
    
    <div class="row g-4">
        <?php foreach ($services as $categorie => $noms): ?>
            <div class="col-md-6">
                <div class="p-3 rounded-3 h-100" style="background-color: #f8f9fa;">
                    <h2 class="h4 border-bottom pb-2 text-info-emphasis mb-3"><?= htmlspecialchars($categorie) ?></h2>
                    
                    <?php foreach ($noms as $nom): ?>
                        <div class="bg-white p-3 rounded-2 mb-3 shadow-sm" id="service-<?= md5($categorie.$nom) ?>">
                            <h3 class="h5 mb-2"><?= htmlspecialchars($nom) ?></h3>
                            
                            <!-- Formulaire de notation -->
                            <form method="POST" class="mb-2">
                                <input type="hidden" name="service_nom" value="<?= htmlspecialchars($nom) ?>">
                                <input type="hidden" name="service_categorie" value="<?= htmlspecialchars($categorie) ?>">
                                
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php
                                            $note_moyenne = $notes_moyennes[$categorie][$nom]['moyenne'] ?? 0;
                                            $star_class = $i <= round($note_moyenne) ? 'star-'.round($note_moyenne) : '';
                                            ?>
                                            <span class="rating-star <?= $star_class ?>" 
                                                  style="font-size: 1.5rem; color: <?= $i <= round($note_moyenne) ? 'inherit' : '#ddd' ?>; cursor: pointer;"
                                                  onclick="this.parentNode.querySelector('input[name=note]').value=<?= $i ?>; this.closest('form').submit()">★</span>
                                        <?php endfor; ?>
                                        <input type="hidden" name="note">
                                    </div>
                                    
                                    <small class="text-muted">
                                        <?= number_format($notes_moyennes[$categorie][$nom]['moyenne'] ?? 0, 1) ?> 
                                        (<?= $notes_moyennes[$categorie][$nom]['total'] ?? 0 ?> avis)
                                    </small>
                                </div>
                            </form>
                            
                            <!-- Lien pour commenter -->
                            <div class="text-primary mb-2" style="cursor: pointer;" onclick="document.getElementById('comment-form-<?= md5($categorie.$nom) ?>').classList.toggle('d-none')">
                                <small><i class="bi bi-chat-left-text"></i> Ajouter un commentaire</small>
                            </div>
                            
                            <!-- Formulaire de commentaire -->
                            <form method="POST" class="d-none" id="comment-form-<?= md5($categorie.$nom) ?>">
                                <input type="hidden" name="avis_id" value="<?= $dernier_avis_id[$categorie][$nom] ?? '' ?>">
                                <div class="mb-2">
                                    <input type="text" name="client_nom" class="form-control form-control-sm" placeholder="Votre nom (optionnel)">
                                </div>
                                <div class="mb-2">
                                    <textarea name="commentaire" class="form-control form-control-sm" rows="2" placeholder="Votre commentaire"></textarea>
                                </div>
                                <button type="submit" class="btn btn-sm btn-success">Envoyer</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ==== AFFICHAGE DES COMMENTAIRES (À PLACER OÙ VOUS VOULEZ) ==== -->
<div class="container mt-5 border border-2 border-primary-subtle">
    <h2 class="h4 mb-4">Commentaires des clients</h2>
    
    <?php foreach ($services as $categorie => $noms): ?>
        <h3 class="h5 mt-4 mb-3"><?= htmlspecialchars($categorie) ?></h3>
        
        <?php foreach ($noms as $nom): ?>
            <?php 
            $stmt = $pdo->prepare("SELECT * FROM avis WHERE service_categorie = ? AND service_nom = ? AND commentaire IS NOT NULL ORDER BY date_creation DESC");
            $stmt->execute([$categorie, $nom]);
            $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($commentaires)): ?>
                <h4 class="h6 text-muted mb-2"><?= htmlspecialchars($nom) ?></h4>
                
                <div class="row g-3">
                    <?php foreach ($commentaires as $commentaire): ?>
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold">
                                            <?= htmlspecialchars($commentaire['client_nom'] ?? 'Anonyme') ?>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span style="color: <?= $i <= $commentaire['note'] ? '#ffc107' : '#ddd' ?>">★</span>
                                            <?php endfor; ?>
                                        </span>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($commentaire['date_creation'])) ?>
                                        </small>
                                    </div>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($commentaire['commentaire'])) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
  
</div>

<!-- ==== STYLE MINIMAL ==== -->
<style>
    .rating-star { transition: transform 0.2s; }
    .rating-star:hover { transform: scale(1.2); }
    .star-1 { color: #ff6b6b !important; }
    .star-2 { color: #ffa502 !important; }
    .star-3 { color: #ffd32a !important; }
    .star-4 { color: #7bed9f !important; }
    .star-5 { color: #1dd1a1 !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script>
    function loadContent(content) {
        const mainFrame = document.getElementById("mainFrame");
        mainFrame.src = content;
        document.getElementById('services').style.display = 'none'; // Masquer la section des services
    }

    function showHome() {
        const mainFrame = document.getElementById("mainFrame");
        mainFrame.src = ''; // Réinitialiser l'iframe
        document.getElementById('services').style.display = 'block'; // Afficher la section des services
    }


    // Gérer l'ouverture et la fermeture des dropdowns
    document.querySelectorAll('.dropdown-toggle').forEach(dropdownToggle => {
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault(); // Empêche le comportement par défaut du lien
            const dropdown = this.nextElementSibling;

            // Ferme tous les autres dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(otherDropdown => {
                if (otherDropdown !== dropdown) {
                    otherDropdown.classList.remove('show');
                }
            });

            // Toggle l'état du dropdown actuel
            dropdown.classList.toggle('show');
        });
    });

    // Ferme les dropdowns si l'utilisateur clique en dehors
    window.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-toggle')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(openDropdown => {
                openDropdown.classList.remove('show');
            });
        }
    });
</script>

</body>


<!-- Modale de connexion -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Connexion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm" action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="loginEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="loginPassword" name="mot_de_passe" required>
                            <span class="input-group-text" onclick="togglePasswordVisibility('loginPassword')">👁️</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Me connecter</button>
                    <div id="loginError" class="alert alert-danger mt-3" style="display:none;"></div>
                    <div id="loginSuccess" class="alert alert-success mt-3" style="display:none;"></div>
                </form>
                <div class="mt-3">
                    <a href="#">Mot de passe oublié ?</a>
                </div>
                <div class="mt-2">
                    <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#creerCompteModal" data-bs-dismiss="modal">Créer un compte</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(passwordFieldId) {
        const passwordInput = document.getElementById(passwordFieldId);
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
    }
</script>

<script>
    document.getElementById("loginForm").onsubmit = function(event) {
        event.preventDefault(); // Empêche le rechargement de la page

        var formData = new FormData(this); // Récupère les données du formulaire

        fetch(this.action, { // Utilise l'action du formulaire (login.php)
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            var loginError = document.getElementById("loginError");
            var loginSuccess = document.getElementById("loginSuccess");
            loginError.style.display = 'none'; // Masquer les messages d'erreur
            loginSuccess.style.display = 'none'; // Masquer les messages de succès

            if (data.success) {
                // Affiche un message de succès
                loginSuccess.innerHTML = data.message;
                loginSuccess.style.display = 'block';

                // Fermer le modal après un court délai (par exemple, 1 seconde)
                setTimeout(() => {
                    $('#loginModal').modal('hide'); // Assurez-vous d'utiliser l'ID correct de votre modal
                }, 1000);

                // Redirige vers mescommandes.php
                loadContent('mescommandes.php'); // Appelle votre fonction pour charger la page
            } else {
                // Affiche le message d'erreur
                loginError.innerHTML = data.message; 
                loginError.style.display = 'block'; 
            }
        })
        .catch((error) => {
            console.error('Erreur:', error);
        });
    };
</script>

<!-- Modal pour le bouton créer un compte -->
<div class="modal fade" id="creerCompteModal" tabindex="-1" aria-labelledby="creerCompteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="creerCompteModalLabel">Créer un Compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="registerForm" method="POST">
                    <div class="mb-3">
                        <label for="registerLastName" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="registerLastName" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerFirstName" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="registerFirstName" name="prenom" required>
                    </div>
                    <div class="mb-3">
                   <label for="registerPassword" class="form-label">Mot de passe</label>
                     <div class="input-group">
                      <input type="password" class="form-control" id="registerPassword" name="mot_de_passe" required>
                       <span class="input-group-text" onclick="togglePasswordVisibility('registerPassword')">👁️</span>
                 </div>
                 </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="registerPassword" name="mot_de_passe" required>
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Pays</label>
                        <select class="form-select" id="country" name="pays" required onchange="toggleOtherCountryInput()">
                            <option value="">Sélectionnez un pays</option>
                            <option value="Burundi">Burundi</option>
                            <option value="R.D.Congo">R.D.Congo</option>
                            <option value="Rwanda">Rwanda</option>
                            <option value="Uganda">Uganda</option>
                            <option value="France">France</option>
                            <option value="Belgique">Belgique</option>
                            <option value="Kenya">Kenya</option>
                            <option value="Amerique">Amerique</option>
                            <option value="Cameroun">Cameroun</option>
                            <option value="Canada">Canada</option>
                            <option value="Tanzanie">Tanzanie</option>
                            <option value="Afrique du sud">Afrique du sud</option>
                            <option value="Autres">Autres</option>
                        </select>
                    </div>
                    <div class="mb-3" id="otherCountryInput" style="display: none;">
                        <label for="otherCountry" class="form-label">Veuillez spécifier votre pays</label>
                        <input type="text" class="form-control" id="otherCountry" name="autre_pays">
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">Ville</label>
                        <input type="text" class="form-control" id="city" name="ville" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sexe</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sexe" id="genderMale" value="Homme" required>
                                <label class="form-check-label" for="genderMale">Homme</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sexe" id="genderFemale" value="Femme" required>
                                <label class="form-check-label" for="genderFemale">Femme</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                        <label class="form-check-label" for="newsletter">
                            Je m'abonne aux newsletters Bibliothèque thématiques
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="partnerOffers" name="partner_offers">
                        <label class="form-check-label" for="partnerOffers">
                            Je souhaite recevoir les offres des partenaires Bibliothèque
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Soumettre</button>
                    <div id="registerError" class="alert alert-danger mt-3" style="display:none;"></div>
                    <div id="registerSuccess" class="alert alert-success mt-3" style="display:none;"></div>
                    <div class="mt-2">
                    <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Se connecter</button>
                </div>
                </form>
                <script>
                    function toggleOtherCountryInput() {
                        const countrySelect = document.getElementById('country');
                        const otherCountryInput = document.getElementById('otherCountryInput');
                    
                        if (countrySelect.value === 'Autres') {
                            otherCountryInput.style.display = 'block';
                        } else {
                            otherCountryInput.style.display = 'none';
                            document.getElementById('otherCountry').value = '';
                        }
                    }

                    document.addEventListener("DOMContentLoaded", function() {
                        // Réinitialiser les champs du formulaire après la fermeture du modal
                        document.getElementById("creerCompteModal").addEventListener("hidden.bs.modal", function() {
                            var registerForm = document.getElementById("registerForm");
                            registerForm.reset();
                            document.getElementById("otherCountryInput").style.display = 'none';
                            document.getElementById("registerError").style.display = 'none';
                            document.getElementById("registerSuccess").style.display = 'none';
                        });
                    });

                    document.getElementById("registerForm").onsubmit = function(event) {
                        event.preventDefault(); // Empêche le rechargement de la page

                        var formData = new FormData(this); // Récupère les données du formulaire

                        fetch("Client.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            var registerError = document.getElementById("registerError");
                            var registerSuccess = document.getElementById("registerSuccess");
                            registerError.style.display = 'none'; // Masquer les messages d'erreur
                            registerSuccess.style.display = 'none'; // Masquer les messages de succès

                            if (data.success) {
                                registerSuccess.innerHTML = data.message; // Affiche le message de succès
                                registerSuccess.style.display = 'block';
                            } else {
                                registerError.innerHTML = data.message; // Affiche le message d'erreur
                                registerError.style.display = 'block';
                            }
                        })
                        .catch((error) => {
                            console.error('Erreur:', error);
                        });
                    };
                </script>
            </div>
        </div>
    </div>
</div>
<script>
    function loadContent(content) {
        const mainFrame = document.getElementById("mainFrame");
        mainFrame.src = content;

        // Masquer la barre de défilement de la page principale
        document.body.style.overflow = 'hidden'; // Masque la barre de défilement
        document.getElementById('services').style.display = 'none'; // Masquer la section des services
        document.getElementById('avis').style.display = 'none'; // Masquer la section des avis
    }

    // Écouteur d'événements pour réafficher la barre de défilement lorsque l'iframe est réinitialisé
    document.getElementById("mainFrame").onload = function() {
        // Vérifie si l'iframe a été chargé
        const iframe = document.getElementById("mainFrame");
        const url = iframe.contentWindow.location.href;

        // Si l'URL n'est pas vide, on garde la barre de défilement masquée
        if (url) {
            return; // On ne fait rien, la barre reste masquée
        }
    };

    function showHome() {
        const mainFrame = document.getElementById("mainFrame");
        mainFrame.src = ''; // Réinitialiser l'iframe
        document.body.style.overflow = 'auto'; // Réafficher la barre de défilement
        document.getElementById('services').style.display = 'block'; // Afficher la section des services
        document.getElementById('avis').style.display = 'block'; // Afficher la section des avis
    }
</script>
</html>