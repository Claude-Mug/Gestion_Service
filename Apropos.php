<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "services"; // Nom de votre base de données

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
    <title>À Propos de Nos Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .quote {
            font-style: italic;
            color: #555;
            margin: 20px 0;
        }
        .developer-img {
            max-width: 150px;
            border-radius: 50%;
        }
        .developer-img-square {
            width: 200px; /* Largeur fixe pour le carré */
            height: 200px; /* Hauteur fixe pour le carré */
            border-radius: 15%; /* Coins arrondis */
            object-fit: cover; /* Récupérer l'image sans déformer */
        }
        .content-div {
            border: 1px solid #007bff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .footer-section {
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin: 10px;
        }
        .footer-icon {
            font-size: 2rem;
            color: #28a745;
        }
    </style>
</head>
<body>
<header class="m-2  text-white py-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 bg-warning opacity-50 d-flex justify-content-between align-items-center">
                <h1 class="m-0">À Propos de Nos Services</h1>
                <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-key bi-arrow-return-left"></i> Admin
                </a>
            </div>
        </div>
    </div>
</header>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <div class="content-div">
                <h2>Nous sommes une équipe passionnée</h2>
                <p>Nous sommes une équipe passionnée par l'amélioration du quotidien grâce à des services de qualité. Notre mission est de vous offrir des solutions pratiques, que ce soit pour des réparations à domicile, des services de ménage, ou d'autres besoins spécifiques. Nous croyons fermement que chaque service doit être exécuté avec attention et professionnalisme.</p>
                <p>Dans un monde en constante évolution, il est essentiel de pouvoir compter sur des services fiables et efficaces. Chaque membre de notre équipe est formé pour répondre à vos attentes et pour s'assurer que votre expérience soit positive. Ensemble, nous travaillons à bâtir une communauté où chacun peut trouver le soutien dont il a besoin.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="content-div">
                <h2>Citations Inspirantes</h2>
                <blockquote class="quote">
                    <p>"La qualité n'est jamais un accident; c'est toujours le résultat d'un effort intelligent." - John Ruskin</p>
                </blockquote>
                <blockquote class="quote">
                    <p>"Le service à la clientèle est un art." - Anonyme</p>
                </blockquote>
                <blockquote class="quote">
                    <p>"Le meilleur moyen de prédire l'avenir est de l'inventer." - Alan Kay</p>
                </blockquote>
                <blockquote class="quote">
                    <p>"La vraie mesure d'un homme n'est pas comment il se comporte dans les moments de confort, mais comment il se tient dans les moments de défi." - Martin Luther King Jr.</p>
                </blockquote>
                <blockquote class="quote">
                    <p>"La satisfaction de nos clients est notre priorité." - Anonyme</p>
                </blockquote>
                <blockquote class="quote">
                    <p class="border border-2 border-primary">"Le succès dépend de la préparation préalable, et sans une telle préparation, il y a sûrement échec." - Confucius</p>
                </blockquote>
            </div>
        </div>
    </div>

    <div class="content-div">
        <h2>Nos Développeurs</h2>
        <div class="row mt-4 text-center">
            <div class="col text-center">
                <img src="Image/Claude.jpg" alt="Mugisha Sebirayi Caude" class="developer-img">
                <h5>MUGISHA Sebirayi Caude</h5>
            </div>
            <div class="col text-center">
                <img src="Image/bruce.jpg" alt="Mugisha Bruce" class="developer-img">
                <h5>MUGISHA Bruce</h5>
            </div>
            <div class="col text-center">
                <h5>Nom de l'entreprise:</h5>
                <h2>HOMME SERVICE</h2>
                <img src="Image/SERVICE.jpg" alt="Nouveau Développeur" class="developer-img-square">
            </div>
        </div>
    </div>

    <!-- Sections supplémentaires en bas -->
    <div class="row mt-4">
        <h2 class="m-4 text-center text-info-emphasis text-decoration-underline">Pour plus d'informations contacter nous.</h2>
        <div class="col-md-3 footer-section">
            <a href="discuter.php">
                <i class="fas fa-comments footer-icon"></i>
                <h4>Discuter avec nous</h4>
                <p>Cliquez ici pour discuter en direct avec un de nos représentants.</p>
            </a>
        </div>
        
        <div class="col-md-3 footer-section">
            <a href="faq.php">
            <i class="fas fa-question-circle footer-icon"></i>
            <h4>FAQ</h4>
            <p>Consultez notre FAQ pour trouver des réponses aux questions fréquentes.</p>
            </a>
        </div>
        <div class="col-md-3 footer-section">
            <a href="avis.php">
            <i class="fas fa-star footer-icon"></i>
            <h4>Avis Clients</h4>
            <p>Découvrez ce que nos clients disent de nos services.</p>
            </a>
        </div>
    </div>
    <br><br><br><br><br><br>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

<!-- Modal connexion admin -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Connexion Administrateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm" action="loginAdmin.php" method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="motdepasse" class="form-label">Mot de Passe</label>
                        <input type="password" class="form-control" id="motdepasse" name="motdepasse" required>
                    </div>
                    <div class="alert alert-danger" id="error-message" style="display:none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary" id="submitLogin" form="loginForm">Se connecter</button>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche l'envoi du formulaire par défaut

    const formData = new FormData(this);
    fetch('loginAdmin.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const errorMessageDiv = document.getElementById('error-message');
        if (data.success) {
            // Ouvrir Admin.php dans un nouvel onglet
            window.open('Admin.php', '_blank');
            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
            modal.hide();
            // Rediriger vers Admin.php dans l'onglet actuel
            window.location.href = 'Admin.php';
        } else {
            // Afficher le message d'erreur
            errorMessageDiv.textContent = data.message;
            errorMessageDiv.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
});
</script>

</html>