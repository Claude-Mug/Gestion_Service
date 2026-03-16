<?php
session_start();

// Informations de connexion à la base de données
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

// Vérification des informations de connexion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $conn->real_escape_string($_POST['nom']);
    $email = $conn->real_escape_string($_POST['email']);
    $motdepasse = $_POST['motdepasse'];

    // Requête pour récupérer l'administrateur avec le nom et l'email fournis
    $sql = "SELECT * FROM admins WHERE nom = '$nom' AND email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        // Vérifier le mot de passe
        if (password_verify($motdepasse, $admin['mot_de_passe'])) {
            // Stocker les informations de l'administrateur dans la session
            $_SESSION['authenticated'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['nom'];

            // Enregistrer l'historique de connexion
            $date_connexion = date('Y-m-d H:i:s');
            $stmt_historique = $conn->prepare("INSERT INTO historique_connexion (admin_id, date_connexion, nom, email) VALUES (?, ?, ?, ?)");
            $stmt_historique->bind_param("isss", $admin['id'], $date_connexion, $admin['nom'], $admin['email']);
            $stmt_historique->execute();

            // Réponse JSON pour succès
            echo json_encode(['success' => true]);
            exit;
        } else {
            // Mot de passe incorrect
            echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect.']);
            exit;
        }
    } else {
        // Administrateur non trouvé
        echo json_encode(['success' => false, 'message' => 'Nom ou email incorrect.']);
        exit;
    }
}

$conn->close();
?>