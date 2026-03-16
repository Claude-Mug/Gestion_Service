<?php
session_start(); // Start the session

// Database connection
$host = 'localhost';
$dbname = 'Services';
$username = 'root';
$password = '';

$exchangeRate = 7000; // Taux de change FBU à USD, initialisé par défaut

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Récupérer l'ID utilisateur de la session

        // Récupérer les montants des commandes
        $stmtOrders = $pdo->prepare("SELECT service, prix FROM CommandDomicile WHERE user_id = :user_id");
        $stmtOrders->bindParam(':user_id', $user_id);
        $stmtOrders->execute();

        $commandes = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);
        $totalMontant = 0; // Initialiser le montant total

        // Calculer le montant total
        foreach ($commandes as $commande) {
            $totalMontant += (float)$commande['prix'];
        }

        // Traitement du formulaire si soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $montant = $_POST['montant'];
            $numero = $_POST['numero'];
            $type_paiement = 'mobile'; // Type de paiement

            // Préparer la requête d'insertion
            $stmt = $pdo->prepare("INSERT INTO revenus (user_id, montant, numero, type_paiement) VALUES (:user_id, :montant, :numero, :type_paiement)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':montant', $montant);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':type_paiement', $type_paiement);

            // Exécuter la requête
            if ($stmt->execute()) {
                $message = "Paiement effectué avec succès de " . htmlspecialchars($montant) . " sur le numéro " . htmlspecialchars($numero) . ".";
            } else {
                $message = "Erreur lors du paiement. Veuillez réessayer.";
            }
        }
    } else {
        $message = "Veuillez vous connecter pour effectuer un paiement.";
    }
} catch (PDOException $e) {
    $message = "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payer par Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        const exchangeRate = <?= $exchangeRate ?>; // Taux de change FBU à USD

        function updateMontant() {
            const numero = document.getElementById('numero').value;
            const totalMontant = parseFloat(document.getElementById('total-montant-fbu').value);
            const montantInput = document.getElementById('montant');
            const montantUSD = document.getElementById('montant-usd');

            if (numero.startsWith('+243')) { // Si c'est un numéro USD
                montantInput.value = (totalMontant / exchangeRate).toFixed(2); // Afficher le montant en USD
                montantInput.setAttribute('data-currency', 'USD');
                montantUSD.innerText = totalMontant.toFixed(2) + " FBU"; // Afficher le montant en FBU
            } else { // Si c'est un numéro FBU
                montantInput.value = totalMontant; // Afficher le montant en FBU
                montantInput.setAttribute('data-currency', 'FBU');
                montantUSD.innerText = (totalMontant / exchangeRate).toFixed(2) + " $"; // Afficher le montant en USD
            }
        }

        function convertMontant() {
            const montantInput = document.getElementById('montant').value;
            const montantUSD = document.getElementById('montant-usd');
            const selectedCurrency = document.getElementById('montant').getAttribute('data-currency');

            if (selectedCurrency === 'FBU') {
                montantUSD.innerText = (montantInput / exchangeRate).toFixed(2) + " $"; // Convertir FBU à USD
            } else {
                montantUSD.innerText = (montantInput * exchangeRate).toFixed(2) + " FBU"; // Convertir USD à FBU
            }
        }
    </script>
    <style>
      .container{
        background-color: paleturquoise;
      }  
    </style>
</head>
<body>
    <div class="container mt-5 col-7">
        <h2 class="text-center text-warning">Payer par Mobile</h2>

        <?php if (isset($message)): ?>
            <div class="alert <?= strpos($message, 'succès') !== false ? 'alert-success' : 'alert-danger' ?>" role="alert">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="numero">Sélectionnez votre numéro:</label>
                <select id="numero" name="numero" class="form-control" required onchange="updateMontant()">
                    <option value="+257456789123" selected>Numéro 2 - +257 456789123 (Fbu)</option>
                    <option value="+243123456789">Numéro 1 - +243 123456789 (USD)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="total-montant">Montant total des commandes:</label>
                <input type="text" class="form-control" id="total-montant-fbu" value="<?= number_format($totalMontant, 2) ?> FBU" readonly>
            </div>
            <div class="form-group">
                <label for="montant">Montant à payer:</label>
                <input type="number" id="montant" name="montant" class="form-control" required min="1" step="0.01" oninput="convertMontant()">
            </div>
            <div class="form-group">
                <label>Montant équivalent:</label>
                <div id="montant-usd" style="font-weight: bold;">
                    <!-- Affichage du montant équivalent en USD -->
                    <?= (number_format($totalMontant / $exchangeRate, 2)) . " $" ?>
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-3">Confirmer le Paiement</button>
        </form>
    </div>
</body>
</html>