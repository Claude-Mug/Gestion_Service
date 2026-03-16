<?php  
session_start(); // Démarrer la session

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

// Traitement de la connexion de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Requête pour vérifier l'email et le mot de passe d'un admin
    $stmt = $conn->prepare("SELECT id, nom FROM admins WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si l'admin n'est pas trouvé, vérifier les prestataires
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nom'];
        $_SESSION['user_type'] = 'admin';
    } else {
        $stmt->close();
        // Vérifier si c'est un prestataire
        $stmt = $conn->prepare("SELECT id, nom FROM prestataire WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nom'];
            $_SESSION['user_type'] = 'prestataire';
        } else {
            echo "<p>Identifiant ou mot de passe invalide.</p>";
        }
    }
    $stmt->close();
}

if (!isset($_SESSION['user_id'])) {
    echo '<form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
          </form>';
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Récupération des messages de l'utilisateur
$messages = [];
$sql = "SELECT m.*, COALESCE(a.nom, p.nom) AS nom_envoyeur
        FROM messages m
        LEFT JOIN admins a ON m.id_admin = a.id
        LEFT JOIN prestataire p ON m.id_prestataire = p.id
        WHERE (m.id_admin = ? OR m.id_prestataire = ?) 
        ORDER BY m.timestamp DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);

// Envoi d'un message ou d'une réponse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {  
    $message = $_POST['message'];  
    $destinataire_id = $_POST['destinataire_id']; 
    $destinataire_type = $_POST['destinataire_type']; 
    $file_name = $_FILES['file']['name'] ?? null;  
    $file_path = 'uploads/' . basename($file_name);  

    if (!empty($message) && $destinataire_id) {
        // Gérer le téléchargement de fichier
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
                die("Erreur lors du téléchargement du fichier.");
            }
        } else {
            $file_name = null;
            $file_path = null;
        }

        // Préparer la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO messages (message, file_name, file_path, is_private, timestamp, id_client, id_admin, id_prestataire) VALUES (?, ?, ?, 1, NOW(), ?, ?, ?)");  

        $id_admin = null;
        $id_prestataire = null;

        if ($destinataire_type === 'admin') {
            $id_admin = $destinataire_id;
        } elseif ($destinataire_type === 'prestataire') {
            $id_prestataire = $destinataire_id;
        }

        $stmt->bind_param("sssiii", $message, $file_name, $file_path, $user_id, $id_admin, $id_prestataire);
        
        if (!$stmt->execute()) {
            die("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }
        $stmt->close();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();  
    }  
}  
?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">  
    <title>Tableau de Bord</title>  
    <style>  
        .message-box { margin-bottom: 15px; padding: 10px; border-radius: 5px; background: #f8f9fa; border: 1px solid #e9ecef; }  
        .messages-container { height: 400px; overflow-y: auto; margin-bottom: 20px; border: 1px solid #dee2e6; border-radius: 5px; padding: 10px; background: white; }  
        .reply-form { display: none; margin-top: 10px; }  
    </style>  
</head>  
<body>  
<div class="container">  
    <h1 class="mt-5">Tableau de Bord</h1>  
    <h5>Bienvenue, <?php echo htmlspecialchars($user_name); ?>!</h5> 

    <h3>Vos Messages</h3>  
    <div class="messages-container mt-4">  
        <?php foreach ($messages as $msg): ?>  
            <div class="message-box">  
                <p><strong><?php echo htmlspecialchars($msg['nom_envoyeur']); ?>:</strong> <?php echo htmlspecialchars($msg['message']); ?></p>  
                <?php if ($msg['file_path']): ?>  
                    <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $msg['file_name'])): ?>  
                        <img src="<?php echo htmlspecialchars($msg['file_path']); ?>" alt="<?php echo htmlspecialchars($msg['file_name']); ?>" style="max-width: 100%; height: auto;">  
                    <?php elseif (preg_match('/\.pdf$/i', $msg['file_name'])): ?>  
                        <p><a href="<?php echo htmlspecialchars($msg['file_path']); ?>" class="btn btn-link">Lire le PDF : <?php echo htmlspecialchars($msg['file_name']); ?></a></p>  
                    <?php elseif (preg_match('/\.(doc|docx)$/i', $msg['file_name'])): ?>  
                        <p><a href="<?php echo htmlspecialchars($msg['file_path']); ?>" class="btn btn-link">Télécharger le document Word : <?php echo htmlspecialchars($msg['file_name']); ?></a></p>  
                    <?php else: ?>  
                        <p><a href="<?php echo htmlspecialchars($msg['file_path']); ?>" class="btn btn-link">Fichier joint : <?php echo htmlspecialchars($msg['file_name']); ?></a></p>  
                    <?php endif; ?>  
                <?php endif; ?>  
                <small>Posté le : <?php echo htmlspecialchars($msg['timestamp']); ?></small>  
                <button class="btn btn-secondary btn-sm reply-button" onclick="toggleReplyForm(<?php echo $msg['id']; ?>)">Répondre</button>  

                <form class="reply-form" id="reply-form-<?php echo $msg['id']; ?>" method="post" action="" enctype="multipart/form-data">  
                    <textarea class="form-control col-7" name="message" placeholder="Écrire une réponse..." required></textarea>  
                    <input type="file" name="file" class="form-control-file d-inline-flex">  
                    <input type="hidden" name="destinataire_id" value="<?php echo $msg['id_client']; ?>">  
                    <input type="hidden" name="destinataire_type" value="<?php echo ($msg['id_admin'] ? 'admin' : 'prestataire'); ?>">  
                    <button type="submit" class="btn btn-outline-success mt-2 d-inline-flex">Envoyer</button>  
                </form>  
            </div>  
        <?php endforeach; ?>  
    </div>  
</div>  

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>  
<script>  
    function toggleReplyForm(messageId) {
        const replyForm = document.getElementById(`reply-form-${messageId}`);
        if (replyForm.style.display === "none" || replyForm.style.display === "") {
            replyForm.style.display = "block";
        } else {
            replyForm.style.display = "none";
        }
    }
</script>  
</body>  
</html>