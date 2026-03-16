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
    <title>Réparation de Systèmes Audio et Mixeurs - Homme Services</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
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

<!-- Image en haut de la page -->
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="Images/Mixeur.jpg" alt="Réparation de Systèmes Audio et Mixeurs" class="img-fluid rounded" style="width: 60%; height: 300px;">
        </div>
    </div>
</div>

<!-- Contenu principal -->
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="d-flex align-items-center">
                <img src="Images/Mixeurs.jpg" alt="Icône Services" class="img-thumbnail mr-3" style="width: 100px; height: 100px;">
                <div>
                    <h3 class="header-text">Nos Services de Réparation Audio</h3>
                    <i class="fas fa-headphones fa-5x text-primary"></i>
                </div>
            </div>
            <ul class="list-unstyled mt-3" style="background-color: rgb(228, 232, 232);">
                <li><i class="fas fa-volume-up text-danger"></i> Réparation de Systèmes Audio (Prix: 50 - 300 €)</li>
                <li><i class="fas fa-microphone text-primary"></i> Réparation de Microphones (Prix: 30 - 150 €)</li>
                <li><i class="fas fa-mixer text-success"></i> Réparation de Mixeurs (Prix: 70 - 400 €)</li>
                <li><i class="fas fa-cogs text-info"></i> Entretien Complet (Prix: 60 - 250 €)</li>
                <li><i class="fas fa-tools text-info"></i> Autre (précisez ci-dessous)</li>
            </ul>
            <p class="text-danger">*Les prix affichés sont pour les demandes normales. Les demandes urgentes auront un coût supérieur.</p>
        </div>

        <div class="col-md-7">
            <h3 class="text-center text-success">Passer une Commande</h3>
            <form method="POST" action="Service.php">

                <div class="form-group" style="display: none;"> 
                    <input type="hidden" name="nom_service" value="service de reparation"> <!-- Champ caché pour le service à domicile -->
                    <input type="hidden" name="Domaine" value="Reparation des mixeurs"> <!-- Champ caché pour soins d’hygiène et infirmiers -->
                </div>

                <div class="form-group">
                    <label for="service-select">Choisissez un Service :</label>
                    <select class="form-control" id="service-select" name="service" required>
                        <option value="" disabled selected>-- Sélectionnez un Service --</option>
                        <option value="systeme_audio">Réparation de Systèmes Audio</option>
                        <option value="microphone">Réparation de Microphones</option>
                        <option value="mixeur">Réparation de Mixeurs</option>
                        <option value="entretien">Entretien Complet</option>
                        <option value="autre">Autre (précisez ci-dessous)</option>
                    </select>
                </div>
                <div class="form-group" id="other-service" style="display: none;">
                    <label for="user-other-service">Quel autre service ?</label>
                    <input type="text" class="form-control" id="user-other-service" name="other_service">
                </div>
                <div class="form-group">
                    <label for="request-type">Type de Demande :</label>
                    <select class="form-control" id="request-type" name="request_type" required>
                        <option value="" disabled selected>-- Choisissez un Type --</option>
                        <option value="normal">Normal</option>
                        <option value="urgent" class="text-danger">Urgent (le prix augmente de 20%)</option>
                    </select>
                </div>
                <div class="form-group" id="date-request" style="display: none;">
                    <label for="request-date">Date de la Demande :</label>
                    <input type="date" class="form-control" id="request-date" name="request_date" required>
                </div>
                <div class="form-group">
                    <label for="user-name">Votre Nom :</label>
                    <input type="text" class="form-control" id="user-name" name="user_name" required>
                </div>
                <div class="form-group">
                    <label for="user-email">Votre Email :</label>
                    <input type="email" class="form-control" id="user-email" name="user_email" required>
                </div>
                <div class="form-group">
                    <label for="user-phone">Votre Téléphone :</label>
                    <input type="tel" class="form-control" id="user-phone" name="user_phone" required>
                </div>
                <div class="form-group">
                    <label for="user-address">Adresse(ville et code postal) :</label>
                    <input type="text" class="form-control" id="user-address" name="user_address" required>
                </div>
                <div class="form-group">
                    <label for="user-comments">Commentaires supplémentaires :</label>
                    <textarea class="form-control" id="user-comments" name="user_comments" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer la Commande</button>
            </form>
        </div>
    </div>

    <!-- Sections supplémentaires en bas -->
    <div class="row mt-4">
        <div class="col-md-3 footer-section">
            <a href="discuter.php">
                <i class="fas fa-comments footer-icon"></i>
                <h4>Discuter avec nous</h4>
                <p>Cliquez ici pour discuter en direct avec un de nos représentants.</p>
            </a>
        </div>
        <div class="col-md-3 footer-section">
            <i class="fas fa-phone footer-icon"></i>
            <h4>Contactez-nous</h4>
            <p>Pour toute question, appelez-nous au <strong>01 23 45 67 89</strong> ou envoyez-nous un email à <strong>contact@hommeservices.com</strong>.</p>
        </div>
        <div class="col-md-3 footer-section">
            <a href="faq.php">
            <i class="fas fa-question-circle footer-icon"></i>
            <h4>FAQ</h4>
            <p>Consultez notre FAQ pour trouver des réponses aux questions fréquentes.</p>
            </a>
        </div>
        <div class="col-md-3 footer-section">
            <i class="fas fa-star footer-icon"></i>
            <h4>Avis Clients</h4>
            <p>Découvrez ce que nos clients disent de nos services.</p>
        </div>
    </div>
</div>

<!-- Pied de page -->
<footer class="bg-light text-center py-3 mt-4">
    <p>&copy; 2025 Homme Services. Tous droits réservés.</p>
</footer>

<br><br><br><br>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#service-select').change(function() {
            if ($(this).val() === 'autre') {
                $('#other-service').show();
            } else {
                $('#other-service').hide();
                $('#user-other-service').val('');
            }
        });

        $('#request-type').change(function() {
            if ($(this).val() === 'normal') {
                $('#date-request').show();
            } else {
                $('#date-request').hide();
                $('#request-date').val('');
            }
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>