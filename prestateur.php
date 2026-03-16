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
    <title>Devenir Prestataire de Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-container {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 20px;
        }
        .container {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 20px;
            background-color: white;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header class=" m-1 border border-2 border-info text-dark text-center py-3 header-container col-md-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="m-0">Devenir Prestataire de Services</h1>
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                Se connecter
            </button>
        </div>
    </header>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <p class="border border-info">Vous souhaitez rejoindre notre équipe en tant que prestataire de services? Remplissez le formulaire ci-dessous pour nous faire part de votre intérêt. Nous cherchons des personnes passionnées, motivées et prêtes à offrir des services de qualité.</p>

                <h2>Conditions pour Devenir Prestataire</h2>
                <ul>
                    <li>Être âgé de 18 ans ou plus.</li>
                    <li>Avoir une expérience pertinente dans le domaine choisi.</li>
                    <li>Être en mesure de fournir des références si demandé.</li>
                    <li>Accepter nos conditions générales de service.</li>
                </ul>

                <form class="mb-3" action="prestatairBase.php" method="POST">
                    <div>
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" required>
                    </div>
                    <div class="mb-3">
                        <label for="services" class="form-label">Services Offerts</label>
                        <input type="text" class="form-control" id="services" name="services" placeholder="Ex: Nettoyage, Réparation, etc." required>
                    </div>
                    <div class="mb-3">
                        <label for="experience" class="form-label">Expérience</label>
                        <textarea class="form-control" id="experience" name="experience" rows="3" placeholder="Décrivez votre expérience" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="disponibilite" class="form-label">Disponibilité</label>
                        <input type="text" class="form-control" id="disponibilite" name="disponibilite" placeholder="Ex: Temps plein, Temps partiel" required>
                    </div>
                    <div class="mb-3">
                        <label for="lettreMotivation" class="form-label">Lettre de Motivation</label>
                        <textarea class="form-control" id="lettreMotivation" name="lettreMotivation" rows="3" placeholder="Décrivez pourquoi vous souhaitez devenir prestataire" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cv" class="form-label">CV (optionnel)</label>
                        <input type="file" class="form-control" id="cv" name="cv">
                    </div>
                    <div class="mb-3">
                 <label for="portfolio" class="form-label">Portfolio (optionnel)</label>
                 <input type="file" class="form-control" id="portfolio" name="portfolio[]" multiple>
                 <small class="form-text text-muted">Téléchargez des fichiers de votre portfolio (images, documents, etc.). Vous pouvez sélectionner plusieurs fichiers.</small>
                 </div>
                    <button type="submit" class="btn btn-primary">Devenir Prestataire</button>
                </form>
                <div class="mt-4 p-3 border rounded bg-light">
    <h2>Informations Complémentaires</h2>
    <p>Devenir prestataire de services est une excellente opportunité pour ceux qui cherchent à avoir plus de flexibilité dans leur emploi du temps tout en augmentant leurs revenus. Voici quelques informations supplémentaires :</p>
    
    <h5>Pourquoi Devenir Prestataire ?</h5>
    <ul>
        <li><strong>Flexibilité :</strong> Travaillez à votre rythme et choisissez vos horaires.</li>
        <li><strong>Rémunération Attractive :</strong> Les revenus varient en fonction des services offerts et de votre disponibilité, avec des possibilités de gains allant de 1500 à 3000 € par mois.</li>
        <li><strong>Formation Continue :</strong> Accédez à des formations pour améliorer vos compétences et rester compétitif.</li>
    </ul>

    <h5>Conditions Requises :</h5>
    <ul>
        <li>Avoir au moins 18 ans.</li>
        <li>Posséder une expérience dans le domaine de services choisi.</li>
        <li>Être capable de fournir des références si nécessaire.</li>
        <li>Accepter nos conditions d'utilisation.</li>
    </ul>
    
    <h5>Comment Ça Fonctionne ?</h5>
    <p>Après avoir soumis votre candidature, notre équipe l'examinera et vous contactera pour un entretien. Une fois accepté, vous recevrez une formation et aurez accès à notre plateforme pour gérer vos services.</p>
    
    <h5>Témoignages</h5>
    <blockquote class="blockquote">
        <p>"Cette expérience m'a permis de rencontrer des clients formidables et d'améliorer ma qualité de vie."</p>
        <footer class="blockquote-footer">Sophie Leclerc</footer>
    </blockquote>
</div>
            </div>
            
            <div class="col-md-4 text-center">
                <h2 class="text-info-emphasis">Informations Complémentaires</h2>
                <h4>Avantages de Devenir Prestataire</h4>
<ul class="border border-2 border-secondary">
    <li><strong>Flexibilité de Travail :</strong> Choisissez vos horaires et travaillez selon votre disponibilité.</li>
    <li><strong>Revenu Potentiel :</strong> Augmentez vos revenus selon le nombre de services fournis. Les prestataires peuvent gagner entre 1500€ et 3000€ par mois, selon leur domaine et leur engagement.</li>
    <li><strong>Accès à une Clientèle Variée :</strong> Travaillez avec différents clients et élargissez votre réseau professionnel.</li>
    <li><strong>Formation et Support :</strong> Profitez de formations continues et d'un support pour améliorer vos compétences.</li>
    <li><strong>Évaluation et Reconnaissance :</strong> Recevez des évaluations de clients qui peuvent améliorer votre réputation et attirer plus de clients.</li>
</ul>

<h2>Conditions pour Devenir Prestataire</h2>
<ul class="border border-2 border-danger">
    <li>Être âgé de 18 ans ou plus.</li>
    <li>Avoir une expérience pertinente dans le domaine choisi.</li>
    <li>Être en mesure de fournir des références si demandé.</li>
    <li>Accepter nos conditions générales de service.</li>
    <li>Posséder une assurance responsabilité civile (selon le type de service offert).</li>
</ul>

<h2>Comment ça se Passe ?</h2>
<p class="border border-2 border-info">Une fois votre candidature soumise, notre équipe examine vos informations et vous contacte pour un entretien. Si vous êtes sélectionné, vous recevrez une formation sur nos procédures et standards de qualité. Vous aurez ensuite accès à notre plateforme où vous pourrez gérer vos offres de services, vos horaires et vos clients.</p>

<h2>Revenus et Rémunération</h2>
<p class="border border-2 border-info">Les revenus des prestataires varient en fonction de plusieurs facteurs, notamment le type de services offerts, la demande et le nombre d'heures travaillées. En général, nos prestataires peuvent s'attendre à recevoir un paiement par service réalisé, avec la possibilité de bonus pour des performances exceptionnelles. Nous garantissons des paiements rapides et transparents, vous permettant de gérer vos finances efficacement.</p>

<h2>Témoignages de Prestataires</h2>
<blockquote class="blockquote">
    <p class="border border-2 border-success">"Travailler avec cette plateforme a été une expérience enrichissante. J'ai non seulement amélioré mes compétences, mais j'ai aussi rencontré des clients formidables."</p>
    <footer class="blockquote-footer">Marie Dupuis</footer>
</blockquote>
<blockquote class="blockquote">
    <p class="border border-2 border-info">"Les revenus sont stables et j'apprécie la flexibilité qui me permet de concilier travail et vie personnelle."</p>
    <footer class="blockquote-footer">Paul Martin</footer>
</blockquote>

    <!-- Modal de Connexion -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Connexion Prestataire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" action="login_prestataire.php" method="POST">
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="loginEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginMotdepasse" class="form-label">Mot de Passe</label>
                            <input type="password" class="form-control" id="loginMotdepasse" name="motdepasse" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>