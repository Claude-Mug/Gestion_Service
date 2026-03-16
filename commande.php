<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services à Domicile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            margin: 0; /* Supprime les marges par défaut */
            padding-top: 56px; /* Espace pour la navbar */
            overflow-y: hidden; /* Empêche le défilement de la page principale */
            height: 100vh; /* Hauteur de la fenêtre */
        }
        #mainContent {
            width: 100%;
            height: 100%; /* Remplit complètement la fenêtre */
            overflow: auto; /* Permet le défilement vertical dans cette zone */
        }
        .info-section {
            background-color: #e5edf0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
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

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-primary fixed-top">
    <div class="container">
        <a class="navbar-brand text-white" href="#">Services à Domicile</a>
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
                        <li><a class="dropdown-item" href="#" onclick="loadContent('electromenagers.html');">Systèmes Électroménagers</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('securite.html');">Sécurité à Domicile</a></li>
                        <li><a class="dropdown-item" href="#" onclick="loadContent('demenagement.html');">Déménagement</a></li>
                        <!-- Ajoutez d'autres services ici -->
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link text-white" href="#contact">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenu principal -->
<div id="mainContent" class="container mt-5">
    <h1 class="text-center">Bienvenue chez Homme Services !</h1>
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="images/home service.jpg" alt="Entretien Ménager et Jardinage" class="img-fluid rounded" style="width: 100%; height: 400px;">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="info-section">
                <h3 class="header-text">Pourquoi Choisir Homme Services ?</h3>
                <p class="paragraph-text">Nous sommes fiers d'être les meilleurs dans ce que nous faisons...</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-section">
                <h3 class="header-text">Services en Ligne</h3>
                <p class="paragraph-text">Grâce à la technologie, nous avons simplifié la manière dont vous accédez à nos services...</p>
            </div>
        </div>
    </div>
    <h2 class="text-center text-info-emphasis">Découvrez nos services et laissez-nous vous aider à simplifier votre vie.</h2>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script>
    function loadContent(content) {
        const mainContent = document.getElementById("mainContent");
        mainContent.innerHTML = `<iframe src="${content}" frameborder="0" style="width: 100%; height: 100%;"></iframe>`;
    }

    function showHome() {
        const mainContent = document.getElementById("mainContent");
        mainContent.innerHTML = `
            <h1 class="text-center">Bienvenue chez Homme Services !</h1>
            <div class="row">
                <div class="col-md-12 text-center">
                    <img src="images/home service.jpg" alt="Entretien Ménager et Jardinage" class="img-fluid rounded" style="width: 100%; height: 400px;">
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="info-section">
                        <h3 class="header-text">Pourquoi Choisir Homme Services ?</h3>
                        <p class="paragraph-text">Nous sommes fiers d'être les meilleurs dans ce que nous faisons...</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-section">
                        <h3 class="header-text">Services en Ligne</h3>
                        <p class="paragraph-text">Grâce à la technologie, nous avons simplifié la manière dont vous accédez à nos services...</p>
                    </div>
                </div>
            </div>
            <h2 class="text-center text-info-emphasis">Découvrez nos services et laissez-nous vous aider à simplifier votre vie.</h2>
        `;
    }
</script>

</body>
</html>