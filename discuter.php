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

    // Requête pour vérifier l'email
    $stmt = $conn->prepare("SELECT id, nom FROM clients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nom'];
    } else {
        echo "<p>Identifiant invalide.</p>";
    }
    $stmt->close();
}

if (!isset($_SESSION['user_id'])) {
    echo '<form method="POST" action="" class="text-center bg-info-subtle">
            <p class="bg-info">Veillez vous connecter pour acceder a cette page.</p>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Se connecter</button>
          </form>';
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Récupération des messages
$sql = "SELECT m.*, COALESCE(a.nom, p.nom) AS nom_envoyeur
        FROM messages m
        LEFT JOIN admins a ON m.id_admin = a.id
        LEFT JOIN prestataire p ON m.id_prestataire = p.id
        WHERE m.id_client = ?
        ORDER BY m.timestamp DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$allMessages = $result->fetch_all(MYSQLI_ASSOC);

// Récupération des admins et prestataires
$admins = $conn->query("SELECT * FROM admins")->fetch_all(MYSQLI_ASSOC);
$prestataires = $conn->query("SELECT * FROM prestataire")->fetch_all(MYSQLI_ASSOC);

// Envoi d'un message
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">  
    <title>Messageries</title>  
    <style>  
        .message-box { margin-bottom: 15px; padding: 10px; border-radius: 5px; background: #f8f9fa; border: 1px solid #e9ecef; }  
        .messages-container { height: 400px; overflow-y: auto; margin-bottom: 20px; border: 1px solid #dee2e6; border-radius: 5px; padding: 10px; background: white; }  
    </style>  
</head>  
<body>  
<div class="container">  
    <h1 class="mt-5">Messagerie</h1>  
    <h5>Bienvenue, <?php echo htmlspecialchars($user_name); ?>!</h5> 

    <div class="row">  
        <div class="col-md-4 destinataires">  
            <h3>Envoyer à :</h3>  
            <h5>Admins</h5>  
            <ul class="list-group">  
                <?php foreach ($admins as $admin): ?>  
                    <li class="list-group-item">  
                        <a href="#" onclick="setDestinataire(<?php echo $admin['id']; ?>, 'admin')"><?php echo htmlspecialchars($admin['nom'] . ' ' . $admin['prenom'] . ' - ' . $admin['role']); ?></a>
                    </li>  
                <?php endforeach; ?>  
            </ul>  

            <h5>Prestataires</h5>  
            <ul class="list-group">  
                <?php foreach ($prestataires as $prestataire): ?>  
                    <li class="list-group-item">  
                        <a href="#" onclick="setDestinataire(<?php echo $prestataire['id']; ?>, 'prestataire')"><?php echo htmlspecialchars($prestataire['nom'] . ' - ' . $prestataire['services']); ?></a>
                    </li>  
                <?php endforeach; ?>  
            </ul>  
        </div>  

        <div class="col-md-8">  
            <h3>Messages</h3>  
            <div class="messages-container mt-4">  
                <?php foreach ($allMessages as $msg): ?>  
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
                    </div>  
                <?php endforeach; ?>  
            </div>  
        </div>  
    </div>  

    <div class="message-area col-12 d-flex justify-content-center">  
        <form method="post" action="" enctype="multipart/form-data" id="message_form" style="width: 70%;">  
            <div class="input-group">  
                <textarea class="form-control" id="message" name="message" required placeholder="Écrire un message..."></textarea>  
                <input type="file" name="file" class="form-control-file bi bi-paperclip file-icon btn btn-outline-primary col-2">  
                <input type="hidden" name="destinataire_id" id="destinataire_id" value="">  
                <input type="hidden" name="destinataire_type" id="destinataire_type" value="">  
                <div class="send-button">  
                    <button type="submit" class="btn btn-outline-success m-2">Envoyer</button>  
                </div>  
            </div>  
        </form>  
    </div>  
</div>  

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>  
<script>  
    function setDestinataire(id, type) {
        document.getElementById('destinataire_id').value = id;
        document.getElementById('destinataire_type').value = type;
    }
</script>  
</body>  
</html>