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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            padding-top: 56px;
        }
        
        #mainFrame {
            height: calc(100vh - 56px);
            border: none;
            width: 100%;
        }
        
        .icon-color {
            color: #007bff;
        }
        
        #services {
            display: block;
            margin-top: 20px;
        }
        
        .dropdown-menu {
            display: none;
            flex-wrap: wrap;
            padding: 10px;
            z-index: 1000;
            width: 50vw;
            left: 0;
            position: absolute;
            background-color: rgba(158, 137, 97, 0.187);
            border: 4px solid #1f1919;
            border-radius: 5px;
        }
        
        .dropdown-menu.show {
            display: flex;
        }
        
        .dropdown-item {
            margin-right: 10px;
            padding: 8px 12px;
            border-radius: 3px;
            transition: background-color 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #707172;
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
        
        .rating-star { transition: transform 0.2s; }
        .rating-star:hover { transform: scale(1.2); }
        .star-1 { color: #ff6b6b !important; }
        .star-2 { color: #ffa502 !important; }
        .star-3 { color: #ffd32a !important; }
        .star-4 { color: #7bed9f !important; }
        .star-5 { color: #1dd1a1 !important; }
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
                    <i class="bi bi-envelope"></i> claudemug4@gmail.com / mugishabruce@gmail.com
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
        <a class="navbar-brand text-white" href="#" onclick="showHome();">Services à Domicile</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-white" href="#" onclick="showHome();">Accueil</a></li>
                
                <li class="nav-item dropdown" id="servicesDropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="dropdownServices" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                        <li><a class="dropdown-item" href="#" onclick="loadContent('hygiene.php');"><i class="bi bi-thermometer icon-color"></i> Soins d'hygiène et infirmiers</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('jardinage.php');"><i class="bi bi-house-door icon-color"></i> Entretien Ménager et Jardinage</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown" id="repairDropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="dropdownRepair" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                
                <li class="nav-item"><a class="nav-link text-white" href="#" onclick="loadContent('prestateur.php');">Devenir prestataire</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#" onclick="loadContent('faq.php');">FAQ</a></li>
                <li class="nav-item"><a class="btn btn-outline-dark text-white m-1" href="#" onclick="loadContent('mescommandes.php');">mes commandes</a></li>
                
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
                
                <button type="button" class="bi bi-person btn btn-outline-info text-white" data-bs-toggle="modal" data-bs-target="#loginModal">
                    Se connecter
                </button>
            </ul>
        </div>
    </div>
</nav>

<!-- Section des Services -->
<div class="container mt-5" id="services">
    <h1 class="text-center">Bienvenue chez Homme Services !</h1>   
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="images/home service.jpg" alt="Entretien Ménager et Jardinage" class="img-fluid rounded" style="width: 100%; height: 400px;">
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="info-section">
                <h3 class="header-text">Pourquoi Choisir Homme Services ?</h3>
                <p class="paragraph-text">
                    Nous sommes fiers d'être les meilleurs dans ce que nous faisons. Notre expertise, notre attention aux détails et notre engagement envers la satisfaction client font de nous le choix idéal.
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-section">
                <h3 class="header-text">Services en Ligne</h3>
                <p class="paragraph-text">
                    Grâce à la technologie, nous avons simplifié la manière dont vous accédez à nos services. Notre plateforme en ligne vous permet de réserver facilement des services.
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="info-section">
                <h3 class="header-text">Qualité et Savoir-Faire</h3>
                <p class="paragraph-text">
                    Chez Homme Services, la qualité est notre devise. Chaque membre de notre équipe est formé pour offrir un service de haute qualité, en utilisant les meilleures pratiques de l'industrie.
                </p>
            </div>
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
    
    <h2 class="text-center text-info-emphasis text-decoration-underline">Découvrez nos services et laissez-nous vous aider à simplifier votre vie.</h2>
        <!-- Services à Domicile -->
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
        <!-- Suite du contenu après la section des services -->

    <!-- Section des Avis Clients -->
    <div class="container mt-5 border border-2 border-primary-subtle">
        <h2 class="h4 mb-4">Commentaires des clients</h2>
        <div id="client-reviews">
            <!-- Les avis seront chargés dynamiquement ici -->
        </div>
    </div>

    <!-- Iframe pour le chargement des pages -->
    <div class="container-fluid">
        <iframe id="mainFrame" src="" frameborder="0" style="height: calc(100vh - 180px);"></iframe>
    </div>

    <!-- Modale de connexion -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Connexion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="loginEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Mot de passe</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="loginPassword" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                        <div id="loginMessage" class="mt-3"></div>
                    </form>
                    <div class="mt-3">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#passwordResetModal">Mot de passe oublié ?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale de réinitialisation de mot de passe -->
    <div class="modal fade" id="passwordResetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Réinitialisation du mot de passe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="passwordResetForm">
                        <div class="mb-3">
                            <label for="resetEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="resetEmail" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer le lien de réinitialisation</button>
                        <div id="resetMessage" class="mt-3"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script>
    // Fonction principale pour charger le contenu
    function loadContent(pageUrl) {
        const mainFrame = document.getElementById('mainFrame');
        const servicesSection = document.getElementById('services');
        
        if (pageUrl) {
            mainFrame.src = pageUrl;
            servicesSection.style.display = 'none';
            document.body.style.overflow = 'hidden';
            
            // Ajuster la hauteur de l'iframe après chargement
            mainFrame.onload = function() {
                try {
                    const body = this.contentWindow.document.body;
                    const html = this.contentWindow.document.documentElement;
                    const height = Math.max(
                        body.scrollHeight,
                        body.offsetHeight,
                        html.clientHeight,
                        html.scrollHeight,
                        html.offsetHeight
                    );
                    this.style.height = height + 'px';
                } catch (e) {
                    console.error("Erreur ajustement iframe:", e);
                }
            };
        } else {
            mainFrame.src = '';
            servicesSection.style.display = 'block';
            document.body.style.overflow = 'auto';
        }
    }

    // Fonction pour afficher l'accueil
    function showHome() {
        loadContent('');
    }

    // Gestion des dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation des dropdowns Bootstrap
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdownMenu = this.nextElementSibling;
                
                // Fermer tous les autres dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (menu !== dropdownMenu) {
                        menu.classList.remove('show');
                    }
                });
                
                // Basculer l'état du dropdown actuel
                dropdownMenu.classList.toggle('show');
            });
        });
        
        // Fermer les dropdowns quand on clique ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-toggle')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    });

    // Gestion de la visibilité du mot de passe
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('loginPassword');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });

    // Gestion du formulaire de connexion
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        const messageDiv = document.getElementById('loginMessage');
        
        messageDiv.innerHTML = '';
        messageDiv.className = 'alert alert-info';
        messageDiv.textContent = 'Connexion en cours...';
        messageDiv.style.display = 'block';
        
        // Simulation de requête AJAX
        setTimeout(() => {
            if (email && password) {
                messageDiv.className = 'alert alert-success';
                messageDiv.textContent = 'Connexion réussie !';
                setTimeout(() => {
                    $('#loginModal').modal('hide');
                    loadContent('mescommandes.php');
                }, 1000);
            } else {
                messageDiv.className = 'alert alert-danger';
                messageDiv.textContent = 'Email ou mot de passe incorrect';
            }
        }, 1500);
    });

    // Gestion de la réinitialisation de mot de passe
    document.getElementById('passwordResetForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = document.getElementById('resetEmail').value;
        const messageDiv = document.getElementById('resetMessage');
        
        messageDiv.innerHTML = '';
        messageDiv.className = 'alert alert-info';
        messageDiv.textContent = 'Envoi du lien en cours...';
        messageDiv.style.display = 'block';
        
        // Simulation de requête AJAX
        setTimeout(() => {
            if (email) {
                messageDiv.className = 'alert alert-success';
                messageDiv.textContent = 'Un lien de réinitialisation a été envoyé à votre adresse email.';
            } else {
                messageDiv.className = 'alert alert-danger';
                messageDiv.textContent = 'Veuillez entrer une adresse email valide';
            }
        }, 1500);
    });

    // Chargement initial des avis clients
    function loadClientReviews() {
        fetch('api/avis.php')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('client-reviews');
                let html = '';
                
                data.forEach(review => {
                    html += `
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5 class="card-title">${review.nom_client}</h5>
                                    <div class="text-warning">
                                        ${'★'.repeat(review.note)}${'☆'.repeat(5 - review.note)}
                                    </div>
                                </div>
                                <p class="card-text">${review.commentaire}</p>
                                <small class="text-muted">${new Date(review.date).toLocaleDateString()}</small>
                            </div>
                        </div>
                    `;
                });
                
                container.innerHTML = html || '<p>Aucun avis pour le moment.</p>';
            })
            .catch(error => {
                console.error('Erreur chargement avis:', error);
                document.getElementById('client-reviews').innerHTML = 
                    '<div class="alert alert-danger">Erreur de chargement des avis</div>';
            });
    }

    // Charger les avis au démarrage
    window.addEventListener('load', loadClientReviews);

    // Fonctionnalité de recherche améliorée
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    
    searchInput.addEventListener('input', function() {
        if (this.value.length > 2) {
            executeSearch();
        } else {
            searchResults.style.display = 'none';
        }
    });

    async function executeSearch() {
        const query = searchInput.value.trim();
        
        if (query.length < 3) return;
        
        searchResults.innerHTML = '<div class="text-center p-2"><div class="spinner-border"></div></div>';
        searchResults.style.display = 'block';
        
        try {
            const response = await fetch(`api/recherche.php?q=${encodeURIComponent(query)}`);
            const results = await response.json();
            
            if (results.length > 0) {
                let html = '<div class="list-group">';
                results.forEach(item => {
                    html += `
                        <a href="#" class="list-group-item list-group-item-action" 
                           onclick="loadContent('${item.lien}'); searchResults.style.display='none'">
                            <h6>${item.titre}</h6>
                            <p class="mb-1">${item.description}</p>
                            <small class="text-muted">${item.categorie}</small>
                        </a>
                    `;
                });
                html += '</div>';
                searchResults.innerHTML = html;
            } else {
                searchResults.innerHTML = '<div class="alert alert-info m-2">Aucun résultat trouvé</div>';
            }
        } catch (error) {
            console.error('Erreur recherche:', error);
            searchResults.innerHTML = '<div class="alert alert-danger m-2">Erreur lors de la recherche</div>';
        }
    }
    </script>
</body>
</html>