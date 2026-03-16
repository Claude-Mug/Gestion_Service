<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connexion à la base de données
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "services";       

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Vérifier si le formulaire est soumis via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Définir un tableau pour les champs manquants
    $missing_fields = [];

    // Afficher les données reçues pour débogage
    echo "<pre>";
    var_dump($_POST); // Affiche toutes les données reçues
    echo "</pre>";

    // Vérifier que toutes les données sont présentes dans $_POST
    if (empty($_POST['nom_service'])) {
        $missing_fields[] = 'nom_service';
    }
    if (empty($_POST['Domaine'])) {
        $missing_fields[] = 'Domaine';
    }
    if (empty($_POST['service'])) {
        $missing_fields[] = 'service';
    }
    if (empty($_POST['user_name'])) {
        $missing_fields[] = 'user_name';
    }
    if (empty($_POST['user_email'])) {
        $missing_fields[] = 'user_email';
    }
    if (empty($_POST['user_phone'])) {
        $missing_fields[] = 'user_phone';
    }
    if (empty($_POST['user_address'])) {
        $missing_fields[] = 'user_address';
    }

    // Si des champs sont manquants, afficher un message
    if (!empty($missing_fields)) {
        echo "Les champs suivants sont manquants : " . implode(", ", $missing_fields);
    } else {
        // Récupérer les valeurs envoyées par le formulaire
        $nom_service = $_POST['nom_service'];
        $domaine = $_POST['Domaine'];
        $service = $_POST['service'];
        $other_service = isset($_POST['other_service']) ? $_POST['other_service'] : ''; 
        $request_type = isset($_POST['request_type']) ? $_POST['request_type'] : '';
        $request_date = isset($_POST['request_date']) ? $_POST['request_date'] : '';
        $user_name = $_POST['user_name'];
        $user_email = $_POST['user_email'];
        $user_phone = $_POST['user_phone'];
        $user_address = $_POST['user_address'];
        $user_comments = isset($_POST['user_comments']) ? $_POST['user_comments'] : '';

        // Récupérer l'ID de l'utilisateur depuis la session
        $user_id = $_SESSION['user_id'];

        // Insérer dans la base de données
        $sql = "INSERT INTO commanddomicile 
                (nom_service, domaine, service, other_service, request_type, request_date, user_name, user_id, statut, user_email, user_phone, user_address, user_comments, prix, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'En attente', ?, ?, ?, ?, NULL, NOW())";

        // Préparer la requête
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête : " . $conn->error);
        }

        // Lier les paramètres à la requête préparée
        $stmt->bind_param("ssssssssssss", $nom_service, $domaine, $service, $other_service, $request_type, $request_date, $user_name, $user_id, $user_email, $user_phone, $user_address, $user_comments);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Si l'insertion réussie, rediriger vers mescommandes.php
            header("Location: mescommandes.php");
            exit;
        } else {
            // Afficher l'erreur d'exécution
            echo "Erreur lors de l'insertion des données : " . $stmt->error;
        }

        // Fermer la requête
        $stmt->close();
    }
} else {
    // Si le formulaire n'a pas été soumis, afficher un message
    echo "Formulaire non soumis.";
}

// Fermer la connexion à la base de données
$conn->close();
?>