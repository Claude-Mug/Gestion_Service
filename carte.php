<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement par Carte Bancaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .body{
            overflow-y: auto;
        }
        .equivalence {
            display: inline-block;
            margin-left: 10px;
            font-weight: bold;
            color: #555;
        }
        .container{
            background-color: paleturquoise;
        }
    </style>
</head>
<body>
    <div class="container mt-5 col-7">
        <h2 class="text-center mb-4 text-warning">Formulaire de Paiement par Carte</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom sur la Carte</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="numero" class="form-label">Numéro de Carte</label>
                <input type="text" class="form-control" id="numero" name="numero" required>
            </div>
            <div class="mb-3">
                <label for="expiration" class="form-label">Date d'Expiration (MM/AA)</label>
                <input type="date" class="form-control" id="expiration" name="expiration" required>
            </div>
            <div class="mb-3">
                <label for="date_paiement" class="form-label">Date de Paiement</label>
                <input type="date" class="form-control" id="date_paiement" name="date_paiement" required>
            </div>
            <div class="mb-3">
                <label for="montant" class="form-label">Montant (en Dollars)</label>
                <input type="number" class="form-control" id="montant" name="montant" value="100" required>
                <span class="equivalence" id="montant_euro">€ 94.50</span>
                <span class="equivalence" id="montant_fb">FBU 65,000</span>
            </div>
            <div class="mb-3">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" required>
            </div>
            <button type="submit" class="btn btn-primary">Payer</button>
        </form>
        <br>

        <?php
        session_start(); // Start the session

        // Simuler l'ID de l'utilisateur connecté
        $_SESSION['user_id'] = 1; // Remplacez ceci par la logique d'authentification

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Connexion à la base de données
            $host = 'localhost';
            $dbname = 'Services';
            $username = 'root';
            $password = '';

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Récupérer les données du formulaire
                $nom = htmlspecialchars($_POST['nom']);
                $numero = htmlspecialchars($_POST['numero']);
                $expiration = htmlspecialchars($_POST['expiration']);
                $cvv = htmlspecialchars($_POST['cvv']);
                $date_paiement = htmlspecialchars($_POST['date_paiement']);
                $montant = htmlspecialchars($_POST['montant']);

                // Convertir le montant en euros et en francs burkinabés
                $montant_euro = $montant * 0.945; // Exemple de taux de conversion
                $montant_fb = $montant * 650; // Exemple de taux de conversion

                // Insérer les données de paiement dans la base de données
                $stmt = $pdo->prepare("INSERT INTO revenus (user_id, montant, numero, type_paiement, date_paiement) VALUES (:user_id, :montant, :numero, 'carte', :date_paiement)");
                $stmt->execute([
                    ':user_id' => $_SESSION['user_id'], // ID de l'utilisateur connecté
                    ':montant' => $montant,
                    ':numero' => $numero,
                    ':date_paiement' => $date_paiement,
                ]);

                echo '<div class="alert alert-success mt-4">Paiement effectué avec succès !</div>';
                echo '<div class="mt-2">Montant en Euros: ' . number_format($montant_euro, 2) . '</div>';
                echo '<div class="mt-2">Montant en Francs Burkinabés: ' . number_format($montant_fb, 2) . '</div>';

            } catch (PDOException $e) {
                echo '<div class="alert alert-danger mt-4">Erreur : ' . $e->getMessage() . '</div>';
            }
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script pour calculer le montant en euros et en francs burkinabés
        document.getElementById('montant').addEventListener('input', function() {
            const montant = parseFloat(this.value);
            const tauxEuro = 0.945; // Exemple de taux de conversion
            const tauxFB = 7000; // Exemple de taux de conversion

            document.getElementById('montant_euro').innerText = '€ ' + (montant * tauxEuro).toFixed(2);
            document.getElementById('montant_fb').innerText = 'FBU ' + (montant * tauxFB).toFixed(2);
        });
    </script>
</body>
</html>