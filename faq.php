<?php
// faq.php
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
    <title>FAQ - Services à Domicile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7fa;
        }
        .container {
            margin-top: 50px;
        }
        h1 {
            margin-bottom: 30px;
            color: #343a40;
        }
        .faq-item {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .faq-item:hover {
            transform: scale(1.02);
        }
        .faq-question {
            cursor: pointer;
            padding: 15px;
            font-weight: bold;
            color: #007bff;
            border-bottom: 1px solid #dee2e6;
        }
        .faq-answer {
            display: none; /* Masquer les réponses par défaut */
            padding: 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">Foire Aux Questions (FAQ)</h1>
    <p class="text-center">Cliquez sur une question pour afficher la réponse. Les réponses sont masquées par défaut.</p>

    <?php
    $faqs = [
        ["Quels types de services proposez-vous ?", "Nous proposons une large gamme de services à domicile, y compris l'entretien ménager, la sécurité à domicile, la garde d'enfants, le déménagement, et bien plus encore."],
        ["Comment puis-je réserver un service ?", "Vous pouvez réserver un service en utilisant notre plateforme en ligne. Sélectionnez le service désiré, choisissez une date et une heure, puis suivez les instructions pour finaliser votre réservation."],
        ["Quels sont vos horaires de service ?", "Nos services sont disponibles du lundi au dimanche, de 8h00 à 20h00."],
        ["Comment gérez-vous les annulations ?", "Vous pouvez annuler ou reprogrammer votre réservation jusqu'à 24 heures avant l'heure prévue du service sans frais."],
        ["Quelles sont vos politiques de paiement ?", "Nous acceptons plusieurs modes de paiement, y compris les cartes de crédit, PayPal, et les paiements en espèces."],
        ["Comment sont sélectionnés vos prestataires de services ?", "Tous nos prestataires sont soigneusement sélectionnés et formés."],
        ["Puis-je demander un prestataire spécifique ?", "Oui, si vous avez un prestataire préféré, vous pouvez le sélectionner lors de la réservation."],
        ["Que faire si je ne suis pas satisfait du service ?", "Si vous n'êtes pas satisfait, veuillez nous contacter dans les 24 heures suivant le service."],
        ["Proposez-vous des services d'urgence ?", "Oui, nous proposons des services d'urgence pour certaines situations."],
        ["Comment puis-je vous contacter pour des questions supplémentaires ?", "Vous pouvez nous contacter par téléphone, email, ou via notre formulaire de contact."],
        ["Comment se déroule un service à domicile ?", "Un de nos prestataires se rendra à votre domicile à l'heure convenue pour effectuer le service."],
        ["Est-ce que vos services sont assurés ?", "Oui, tous nos prestataires sont assurés pour garantir votre tranquillité d'esprit."],
        ["Avez-vous des réductions pour les nouveaux clients ?", "Nous proposons parfois des promotions pour les nouveaux clients. Consultez notre site pour plus d'informations."],
        ["Comment puis-je laisser un avis ?", "Vous pouvez laisser un avis sur notre site après avoir utilisé nos services."],
        ["Est-ce que vous vérifiez les antécédents de vos prestataires ?", "Oui, tous les prestataires passent par une vérification d'antécédents."],
        ["Puis-je modifier ma réservation après l'avoir faite ?", "Oui, vous pouvez modifier votre réservation en vous connectant à votre compte."],
        ["Comment puis-je obtenir un remboursement ?", "Pour obtenir un remboursement, veuillez nous contacter dans les 14 jours suivant votre réservation."],
        ["Proposez-vous des services pour des événements spéciaux ?", "Oui, nous proposons des services pour des événements tels que des mariages, anniversaires, etc."],
        ["Quels types d'avis recueillez-vous de la part de vos clients ?", "Nous recueillons des avis sur la qualité du service, la ponctualité, et la satisfaction générale."],
        ["Est-ce que je dois fournir des équipements pour le service ?", "Cela dépend du service. La plupart du temps, nos prestataires apportent tout le nécessaire."],
        ["Combien de temps à l'avance dois-je réserver un service ?", "Nous recommandons de réserver au moins 48 heures à l'avance."],
        ["Que se passe-t-il en cas de retard du prestataire ?", "Nous vous contacterons pour vous informer du retard et proposer une nouvelle heure."],
        ["Avez-vous un service clientèle disponible ?", "Oui, notre service clientèle est disponible du lundi au vendredi."],
        ["Comment puis-je mettre à jour mes informations personnelles ?", "Vous pouvez modifier vos informations en vous connectant à votre compte sur notre site."],
        ["Est-ce que vos services sont disponibles dans toutes les régions ?", "Nos services sont disponibles dans plusieurs régions. Vérifiez notre site pour plus de détails."],
        ["Est-ce que je peux annuler un service le jour même ?", "Les annulations le jour même peuvent entraîner des frais. Veuillez consulter notre politique."],
        ["Offrez-vous des services de nettoyage après construction ?", "Oui, nous proposons des services de nettoyage après construction."],
        ["Est-ce que vos prestataires sont formés ?", "Oui, tous nos prestataires reçoivent une formation avant de commencer."],
        ["Comment puis-je suivre ma commande ?", "Vous pouvez suivre votre commande dans votre compte sur le site."],
        ["Est-ce que vous avez une politique de confidentialité ?", "Oui, nous avons une politique de confidentialité qui protège vos informations."],
        ["Que faire si je ne peux pas être présent lors du service ?", "Vous pouvez désigner quelqu'un d'autre pour être présent à votre place."],
        ["Est-ce que vous proposez des services de jardinage ?", "Oui, nous proposons des services d'entretien de jardin."],
        ["Comment puis-je laisser un commentaire sur votre site ?", "Vous pouvez laisser un commentaire sur notre page de contact."],
        ["Est-ce que vous avez des services pour les personnes âgées ?", "Oui, nous avons des services spécialement conçus pour les personnes âgées."],
        ["Comment puis-je obtenir un devis pour un service ?", "Vous pouvez demander un devis en ligne pour le service souhaité."],
        ["Avez-vous des promotions en cours ?", "Nous proposons régulièrement des promotions, consultez notre site pour plus de détails."],
        ["Est-ce que vous offrez des services de déménagement ?", "Oui, nous proposons des services de déménagement complets."],
        ["Comment puis-je consulter mon historique de commandes ?", "Vous pouvez consulter votre historique de commandes dans votre compte."],
        ["Comment gérez-vous les plaintes ?", "Nous prenons les plaintes au sérieux et nous travaillons pour les résoudre rapidement."],
        ["Est-ce que vous proposez des services de nettoyage de bureaux ?", "Oui, nous proposons des services de nettoyage de bureaux."],
        ["Comment puis-je contacter le service client ?", "Vous pouvez contacter le service client par téléphone ou email."],
        ["Avez-vous des politiques de sécurité pour vos prestataires ?", "Oui, nous avons des politiques strictes en matière de sécurité."],
        ["Comment puis-je m'inscrire à votre newsletter ?", "Vous pouvez vous inscrire à notre newsletter sur notre site."],
        ["Est-ce que vous avez un service d'assistance disponible ?", "Oui, notre service d'assistance est disponible pour répondre à vos questions."],
        ["Comment puis-je annuler ma réservation ?", "Vous pouvez annuler votre réservation en vous connectant à votre compte."],
        ["Proposez-vous des services de nettoyage de fenêtres ?", "Oui, nous proposons des services de nettoyage de fenêtres."],
        ["Comment puis-je obtenir un devis pour un service ?", "Vous pouvez demander un devis en ligne pour le service souhaité."],
        ["Est-ce que vous avez des témoignages de clients ?", "Oui, vous pouvez consulter les témoignages sur notre site."],
        ["Comment se passe le processus de réservation ?", "Le processus de réservation est simple : choisissez un service, choisissez une date et effectuez le paiement."],
        ["Est-ce que vous offrez des services de réparation ?", "Oui, nous proposons des services de réparation pour divers besoins."],
        ["Quels types de services d'assistance proposez-vous ?", "Nous proposons des services d'assistance pour les personnes âgées, les familles, et plus encore."],
        ["Est-ce que vos services incluent des garanties ?", "Oui, nous offrons des garanties sur certains services."],
        ["Comment puis-je savoir si un service est disponible ?", "Vous pouvez vérifier la disponibilité des services sur notre site."],
        ["Est-ce que vous proposez des services de nettoyage de tapis ?", "Oui, nous proposons des services de nettoyage de tapis."],
        ["Comment puis-je signaler un problème avec un service ?", "Veuillez nous contacter dès que possible pour signaler un problème."],
        ["Est-ce que vous avez un programme de fidélité ?", "Oui, nous avons un programme de fidélité qui offre des réductions à nos clients réguliers."],
        ["Comment gérez-vous les retours d'expérience ?", "Nous recueillons les retours d'expérience pour améliorer nos services."],
        ["Proposez-vous des services de nettoyage de véhicules ?", "Oui, nous avons des services de nettoyage de véhicules disponibles."],
        ["Est-ce que vous offrez des services de coaching personnel ?", "Oui, nous proposons des services de coaching personnel pour divers besoins."],
        ["Comment puis-je vérifier l'état de ma réservation ?", "Vous pouvez vérifier l'état de votre réservation dans votre compte."],
        ["Est-ce que vous avez des politiques de confidentialité pour les données personnelles ?", "Oui, nous respectons votre vie privée et avons des politiques en place pour protéger vos données."],
        ["Comment puis-je demander un service spécifique ?", "Vous pouvez demander un service en utilisant notre formulaire de contact sur le site."],
        ["Est-ce que vous proposez des services de stylisme ?", "Oui, nous proposons également des services de stylisme à domicile."],
    ];

    foreach ($faqs as $index => $faq) {
        echo '<div class="faq-item">';
        echo '<div class="faq-question" onclick="toggleAnswer(this)">' . ($index + 1) . '. ' . htmlspecialchars($faq[0]) . '</div>';
        echo '<div class="faq-answer">' . htmlspecialchars($faq[1]) . '</div>';
        echo '</div>';
    }
    ?>
    <h3 class="m-3 text-center">pour plus d'informations contacter nous ici</h3>
    <div class="row mt-4 text-center">
        <div class="col-md-3 text-center">
            <a href="discuter.php">
                <i class="fas fa-comments footer-icon"></i>
                <h4>Discuter avec nous</h4>
                <p>Cliquez ici pour discuter en direct avec un de nos représentants.</p>
            </a>
        </div>
        <div class="col-md-3">
            <i class="fas fa-phone footer-icon"></i>
            <h4>Contactez-nous</h4>
            <p>Pour toute question, appelez-nous au <strong>+257 769021</strong> ou envoyez-nous un email à <strong>contact@hommeservices.com</strong>.</p>
        </div>
        </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<script>
    function toggleAnswer(element) {
        const answer = element.nextElementSibling;
        if (answer.style.display === "none" || answer.style.display === "") {
            answer.style.display = "block";
        } else {
            answer.style.display = "none";
        }
    }
</script>

</body>
</html>