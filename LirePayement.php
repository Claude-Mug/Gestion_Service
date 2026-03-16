<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Paiements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Liste des Paiements</h2>
        
        <?php
        session_start(); // Start the session

        // Database connection
        $host = 'localhost';
        $dbname = 'Services';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête pour récupérer les informations de la table revenus avec les détails des clients
            $stmt = $pdo->prepare("
                SELECT 
                    r.id,
                    c.nom,
                    c.prenom,
                    c.email,
                    r.montant,
                    r.numero,
                    r.type_paiement,
                    r.date_paiement
                FROM 
                    revenus r
                JOIN 
                    clients c ON r.user_id = c.id
            ");
            $stmt->execute();

            // Initialiser les totaux
            $total_dollars = 0;
            $total_fb = 0;
            $conversion_rate_fb = 7000; // Exemple de taux de conversion en FBU

            // Affichage des résultats
            echo '<table class="table table-striped table-hover table-bordered">';
            echo '<thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Montant</th>
                        <th>Numéro</th>
                        <th>Type de Paiement</th>
                        <th>Date de Paiement</th>
                    </tr>
                  </thead>';
            echo '<tbody>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Accumuler le total
                $montant = $row['montant'];
                $total_dollars += $montant;
                $total_fb += $montant * $conversion_rate_fb;

                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nom']}</td>
                        <td>{$row['prenom']}</td>
                        <td>{$row['email']}</td>
                        <td>{$montant}</td>
                        <td>{$row['numero']}</td>
                        <td>{$row['type_paiement']}</td>
                        <td>{$row['date_paiement']}</td>
                      </tr>";
            }

            echo '</tbody>';
            echo '</table>';

            // Affichage des totaux
            echo '<h4 class="text-end text-decoration-underline text-success">Total en: </h4>';
            echo '<h4 class="text-end text-info">   Dollars: $' . number_format($total_dollars, 2) . '</h4>';
            echo '<h4 class="text-end text-info">F.Bur: FBU ' . number_format($total_fb, 2) . '</h4>';

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>