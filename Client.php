<?php
session_start(); // Démarrer la session

// Connexion à la base de données
$host = 'localhost';
$dbname = 'Services';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
        $pays = $_POST['pays'];
        $ville = $_POST['ville'];
        $sexe = $_POST['sexe'];
        $newsletter = isset($_POST['newsletter']) ? 1 : 0;
        $partner_offers = isset($_POST['partner_offers']) ? 1 : 0;

        // Préparation de la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO clients (nom, prenom, email, mot_de_passe, pays, ville, sexe, newsletter, partner_offers) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Exécution de la requête
        $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $pays, $ville, $sexe, $newsletter, $partner_offers]);

        $_SESSION['message'] = "Inscription réussie !"; // Stockage du message dans la session
        echo json_encode(['success' => true, 'message' => $_SESSION['message']]);
        exit; // Terminer le script
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => "Erreur de connexion : " . $e->getMessage()]);
}
?>