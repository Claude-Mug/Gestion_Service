<?php
session_start();
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

// Récupérer tous les clients
$sql_clients = "SELECT id, nom, email, telephone FROM clients";
$result_clients = $conn->query($sql_clients);

// Récupérer tous les prestataires
$sql_prestataires = "SELECT id, nom, email, telephone FROM prestataire";
$result_prestataires = $conn->query($sql_prestataires);

// Traitement de l'envoi de notifications
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $recipients = $_POST['recipients']; // Tableau des destinataires
    $send_now = $_POST['send_now']; // Option pour envoyer immédiatement ou programmer
    $send_date = $_POST['send_date'] ?? null; // Date d'envoi programmée

    $send_email = isset($_POST['send_email']); // Vérifie si l'option email est cochée
    $send_whatsapp = isset($_POST['send_whatsapp']); // Vérifie si l'option WhatsApp est cochée

    // Variables pour stocker les IDs et le nombre de destinataires
    $ids_clients = [];
    $ids_prestataires = [];

    foreach ($recipients as $recipient) {
        // Ajouter l'ID à la liste appropriée
        if (strpos($recipient, 'client_') === 0) {
            $ids_clients[] = str_replace('client_', '', $recipient);
        } elseif (strpos($recipient, 'prestataire_') === 0) {
            $ids_prestataires[] = str_replace('prestataire_', '', $recipient);
        }
    }

    // Enregistrer la notification dans la base de données
    $ids_clients_str = implode(',', $ids_clients); // Convertir le tableau en chaîne
    $ids_prestataires_str = implode(',', $ids_prestataires); // Convertir le tableau en chaîne
    $nombre_destinataires = count($ids_clients) + count($ids_prestataires); // Compter le nombre total de destinataires

    // Déterminer le type de message
    $type_message = '';
    if ($send_email) {
        $type_message = 'email';
    }
    if ($send_whatsapp) {
        $type_message = 'whatsapp';
    }

    $stmt = $conn->prepare("INSERT INTO notifications (type_destinataire, ids_clients, ids_prestataires, message, type_message, nombre_destinataires) VALUES (?, ?, ?, ?, ?, ?)");
    $type_destinataire = (count($ids_clients) > 0) ? 'client' : 'prestataire'; // Déterminer le type de destinataire
    $stmt->bind_param("ssssis", $type_destinataire, $ids_clients_str, $ids_prestataires_str, $message, $type_message, $nombre_destinataires);
    $stmt->execute();

    // Envoi des notifications
    if ($send_now) {
        foreach ($recipients as $recipient) {
            if ($send_email) {
                sendEmail($recipient, $message); // Fonction pour envoyer l'email
            }
            if ($send_whatsapp) {
                sendWhatsApp($recipient, $message); // Fonction pour envoyer WhatsApp
            }
        }
    } else if ($send_date) {
        // Programmer l'envoi (vous pouvez enregistrer cela dans une tâche cron)
        scheduleNotification($recipients, $message, $send_date);
    }

    echo "Notifications traitées.";
}

function sendEmail($recipient, $message) {
    // Utiliser PHPMailer pour envoyer l'email
    require 'vendor/autoload.php'; // Assurez-vous que PHPMailer est installé via Composer
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com'; // Remplacez par votre hôte SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com'; // Remplacez par votre email
    $mail->Password = 'your_password'; // Remplacez par votre mot de passe
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Déterminer l'email du destinataire
    $recipient_email = ''; // Récupérer l'email en fonction de l'ID
    if (strpos($recipient, 'client_') === 0) {
        $client_id = str_replace('client_', '', $recipient);
        $result = $GLOBALS['conn']->query("SELECT email FROM clients WHERE id = $client_id");
        $row = $result->fetch_assoc();
        $recipient_email = $row['email'];
    } elseif (strpos($recipient, 'prestataire_') === 0) {
        $prestataire_id = str_replace('prestataire_', '', $recipient);
        $result = $GLOBALS['conn']->query("SELECT email FROM prestataires WHERE id = $prestataire_id");
        $row = $result->fetch_assoc();
        $recipient_email = $row['email'];
    }

    $mail->setFrom('your_email@example.com', 'Nom de l\'expéditeur');
    $mail->addAddress($recipient_email);
    $mail->Subject = 'Notification';
    $mail->Body    = $message;

    if (!$mail->send()) {
        echo 'Message non envoyé. Mailer Error: ' . $mail->ErrorInfo;
    }
}

function sendWhatsApp($recipient, $message) {
    // Utiliser Twilio pour envoyer un message WhatsApp
    $sid = 'your_twilio_sid'; // Remplacez par votre SID Twilio
    $token = 'your_twilio_token'; // Remplacez par votre token Twilio
    $client = new Twilio\Rest\Client($sid, $token);

    // Déterminer le numéro de téléphone du destinataire
    $recipient_phone = ''; // Récupérer le numéro de téléphone en fonction de l'ID
    if (strpos($recipient, 'client_') === 0) {
        $client_id = str_replace('client_', '', $recipient);
        $result = $GLOBALS['conn']->query("SELECT telephone FROM clients WHERE id = $client_id");
        $row = $result->fetch_assoc();
        $recipient_phone = $row['telephone'];
    } elseif (strpos($recipient, 'prestataire_') === 0) {
        $prestataire_id = str_replace('prestataire_', '', $recipient);
        $result = $GLOBALS['conn']->query("SELECT telephone FROM prestataires WHERE id = $prestataire_id");
        $row = $result->fetch_assoc();
        $recipient_phone = $row['telephone'];
    }

    $client->messages->create(
        "whatsapp:$recipient_phone",
        array(
            'from' => 'whatsapp:your_twilio_whatsapp_number', // Remplacez par votre numéro WhatsApp Twilio
            'body' => $message
        )
    );
}

function scheduleNotification($recipients, $message, $send_date) {
    // Logique pour programmer l'envoi
    // Vous pouvez enregistrer les détails dans une table dédiée pour le traitement ultérieur
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Envoyer des Notifications</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Envoyer des Notifications</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="message">Message :</label>
                <textarea class="form-control" id="message" name="message" required></textarea>
            </div>

            <h3>Clients</h3>
            <?php if ($result_clients->num_rows > 0): ?>
                <?php while ($client = $result_clients->fetch_assoc()): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="recipients[]" value="client_<?php echo $client['id']; ?>" id="client_<?php echo $client['id']; ?>">
                        <label class="form-check-label" for="client_<?php echo $client['id']; ?>">
                            <?php echo htmlspecialchars($client['nom']) . ' - ' . htmlspecialchars($client['email']) . ' - ' . htmlspecialchars($client['telephone']); ?>
                        </label>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aucun client disponible.</p>
            <?php endif; ?>

            <h3>Prestataires</h3>
            <?php if ($result_prestataires->num_rows > 0): ?>
                <?php while ($prestataire = $result_prestataires->fetch_assoc()): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="recipients[]" value="prestataire_<?php echo $prestataire['id']; ?>" id="prestataire_<?php echo $prestataire['id']; ?>">
                        <label class="form-check-label" for="prestataire_<?php echo $prestataire['id']; ?>">
                            <?php echo htmlspecialchars($prestataire['nom']) . ' - ' . htmlspecialchars($prestataire['email']) . ' - ' . htmlspecialchars($prestataire['telephone']); ?>
                        </label>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aucun prestataire disponible.</p>
            <?php endif; ?>

            <div class="form-group mt-4">
                <label>Mode d'envoi :</label><br>
                <input type="checkbox" name="send_email" checked> Envoyer par Email<br>
                <input type="checkbox" name="send_whatsapp" checked> Envoyer par WhatsApp
            </div>

            <div class="form-group mt-4">
                <label for="send_now">Envoyer maintenant :</label>
                <input type="radio" name="send_now" value="1" checked> Oui
                <input type="radio" name="send_now" value="0"> Non
            </div>

            <div class="form-group" id="schedule_section" style="display:none;">
                <label for="send_date">Programmer l'envoi :</label>
                <input type="date" class="form-control" id="send_date" name="send_date">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Envoyer Notifications</button>
        </form>
    </div>

    <script>
        document.querySelectorAll('input[name="send_now"]').forEach((elem) => {
            elem.addEventListener('change', function(event) {
                const scheduleSection = document.getElementById('schedule_section');
                if (event.target.value === '0') {
                    scheduleSection.style.display = 'block';
                } else {
                    scheduleSection.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>

<?php
$conn->close(); // Fermer la connexion à la base de données
?>

<?php
// Inclure les fichiers PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Créer une instance de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuration du serveur SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.example.com'; // Remplacez par votre hôte SMTP
    $mail->SMTPAuth   = true;
    $mail->Username   = 'your_email@example.com'; // Remplacez par votre email
    $mail->Password   = 'your_password'; // Remplacez par votre mot de passe
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Destinataire
    $mail->setFrom('your_email@example.com', 'Nom de l\'expéditeur');
    $mail->addAddress('recipient@example.com', 'Nom du destinataire'); // Ajoutez un destinataire

    // Contenu de l'email
    $mail->isHTML(true);
    $mail->Subject = 'Voici le sujet';
    $mail->Body    = 'Ceci est le corps de l\'email en <b>HTML</b>';
    $mail->AltBody = 'Ceci est le corps de l\'email en texte brut pour les clients qui ne prennent pas en charge HTML';

    // Envoyer l'email
    $mail->send();
    echo 'Message a été envoyé';
} catch (Exception $e) {
    echo "Message non envoyé. Erreur: {$mail->ErrorInfo}";
}
?>


<?php
// Inclure le SDK Twilio
require 'path/to/twilio-php/src/Twilio/autoload.php'; // Modifiez le chemin selon votre structure de dossier

use Twilio\Rest\Client;

// Vos identifiants Twilio
$sid = 'your_twilio_sid'; // Remplacez par votre SID Twilio
$token = 'your_twilio_token'; // Remplacez par votre token Twilio
$client = new Client($sid, $token);

// Envoyer un message WhatsApp
try {
    $message = $client->messages->create(
        'whatsapp:+number_of_recipient', // Remplacez par le numéro du destinataire, format: whatsapp:+1234567890
        [
            'from' => 'whatsapp:+14155238886', // Remplacez par votre numéro de sandbox WhatsApp
            'body' => 'Ceci est un message WhatsApp envoyé avec Twilio!'
        ]
    );

    echo "Message envoyé avec succès! ID: " . $message->sid;
} catch (Exception $e) {
    echo "Erreur lors de l'envoi du message: " . $e->getMessage();
}
?>