<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Revenus et Commandes Domicile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Statistiques des Revenus et Commandes Domicile</h2>
        
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

            // Requête pour récupérer les statistiques des revenus
            $stmt = $pdo->prepare("
                SELECT 
                    SUM(montant) AS total_revenus,
                    COUNT(id) AS nombre_paiements,
                    AVG(montant) AS montant_moyen
                FROM 
                    revenus
            ");
            $stmt->execute();

            $stats_revenus = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stats_revenus) {
                $total_revenus = $stats_revenus['total_revenus'] ?? 0;
                $nombre_paiements = $stats_revenus['nombre_paiements'] ?? 0;
                $montant_moyen = $stats_revenus['montant_moyen'] ?? 0;

                // Requête pour le nombre total de clients
                $stmt_clients = $pdo->prepare("SELECT COUNT(DISTINCT user_id) AS nombre_clients FROM revenus");
                $stmt_clients->execute();
                $nombre_clients = $stmt_clients->fetch(PDO::FETCH_ASSOC)['nombre_clients'] ?? 0;

                // Affichage des statistiques des revenus
                echo '<div class="alert alert-info">';
                echo '<h4>Total des Revenus: $' . number_format($total_revenus, 2) . '</h4>';
                echo '<h4>Nombre Total de Paiements: ' . $nombre_paiements . '</h4>';
                echo '<h4>Montant Moyen par Paiement: $' . number_format($montant_moyen, 2) . '</h4>';
                echo '<h4>Nombre Total de Clients: ' . $nombre_clients . '</h4>';
                echo '</div>';
            }

            // Requête pour récupérer les statistiques des commandes
            $stmt = $pdo->prepare("
                SELECT 
                    SUM(prix) AS total_commandes,
                    COUNT(id) AS nombre_commandes,
                    AVG(prix) AS montant_moyen
                FROM 
                    commanddomicile
            ");
            $stmt->execute();

            $stats_commandes = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stats_commandes) {
                $total_commandes = $stats_commandes['total_commandes'] ?? 0;
                $nombre_commandes = $stats_commandes['nombre_commandes'] ?? 0;
                $montant_moyen_commande = $stats_commandes['montant_moyen'] ?? 0;

                // Affichage des statistiques des commandes
                echo '<div class="alert alert-success mt-4">';
                echo '<h4>Total des Commandes: $' . number_format($total_commandes, 2) . '</h4>';
                echo '<h4>Nombre Total de Commandes: ' . $nombre_commandes . '</h4>';
                echo '<h4>Montant Moyen par Commande: $' . number_format($montant_moyen_commande, 2) . '</h4>';
                echo '</div>';
            }

            // Requête pour les commandes par statut
            $stmt = $pdo->prepare("
                SELECT 
                    statut,
                    COUNT(id) AS nombre_par_statut
                FROM 
                    commanddomicile
                GROUP BY 
                    statut
            ");
            $stmt->execute();

            echo '<h4>Commandes par Statut:</h4>';
            echo '<ul class="list-group mb-4">';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<li class="list-group-item">';
                echo $row['statut'] . ': ' . $row['nombre_par_statut'];
                echo '</li>';
            }
            echo '</ul>';

            // Affichage du tableau des paiements
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

            echo '<table class="table table-striped table-hover table-bordered mt-4">';
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
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nom']}</td>
                        <td>{$row['prenom']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['montant']}</td>
                        <td>{$row['numero']}</td>
                        <td>{$row['type_paiement']}</td>
                        <td>{$row['date_paiement']}</td>
                      </tr>";
            }

            echo '</tbody>';
            echo '</table>';

            // Affichage du tableau des commandes
            $stmt = $pdo->prepare("
                SELECT 
                    id,
                    nom_service,
                    domaine,
                    service,
                    other_service,
                    request_type,
                    request_date,
                    user_name,
                    user_email,
                    statut,
                    user_phone,
                    user_address,
                    user_comments,
                    prix,
                    created_at
                FROM 
                    commanddomicile
            ");
            $stmt->execute();

            echo '<table class="table table-striped table-hover table-bordered mt-4">';
            echo '<thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nom du Service</th>
                        <th>Domaine</th>
                        <th>Service</th>
                        <th>Autre Service</th>
                        <th>Type de Demande</th>
                        <th>Date de Demande</th>
                        <th>Nom de l\'Utilisateur</th>
                        <th>Email de l\'Utilisateur</th>
                        <th>Statut</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Commentaires</th>
                        <th>Prix</th>
                        <th>Date de Création</th>
                    </tr>
                  </thead>';
            echo '<tbody>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nom_service']}</td>
                        <td>{$row['domaine']}</td>
                        <td>{$row['service']}</td>
                        <td>{$row['other_service']}</td>
                        <td>{$row['request_type']}</td>
                        <td>{$row['request_date']}</td>
                        <td>{$row['user_name']}</td>
                        <td>{$row['user_email']}</td>
                        <td>{$row['statut']}</td>
                        <td>{$row['user_phone']}</td>
                        <td>{$row['user_address']}</td>
                        <td>{$row['user_comments']}</td>
                        <td>{$row['prix']}</td>
                        <td>{$row['created_at']}</td>
                      </tr>";
            }

            echo '</tbody>';
            echo '</table>';

            // Affichage des statistiques de l'historique
            $stmt = $pdo->prepare("
                SELECT 
                    SUM(prix) AS total_prix_historique,
                    COUNT(DISTINCT user_email) AS nombre_clients_historique,
                    COUNT(service) AS nombre_services
                FROM 
                    Historique
            ");
            $stmt->execute();
            $stats_historique = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stats_historique) {
                $total_prix_historique = $stats_historique['total_prix_historique'] ?? 0;
                $nombre_clients_historique = $stats_historique['nombre_clients_historique'] ?? 0;
                $nombre_services = $stats_historique['nombre_services'] ?? 0;

                // Affichage des statistiques de l'historique
                echo '<div class="alert alert-warning mt-4">';
                echo '<h4>Total Prix Historique: $' . number_format($total_prix_historique, 2) . '</h4>';
                echo '<h4>Nombre Total de Clients dans l\'Historique: ' . $nombre_clients_historique . '</h4>';
                echo '<h4>Nombre Total de Services dans l\'Historique: ' . $nombre_services . '</h4>';
                echo '</div>';
            }

            // Affichage du tableau des historiques
            $stmt = $pdo->prepare("
                SELECT 
                    id,
                    service,
                    prix,
                    user_name,
                    user_email,
                    statut,
                    created_at
                FROM 
                    Historique
            ");
            $stmt->execute();

            echo '<h4 class="mt-4">Historique des Commandes</h4>';
            echo '<table class="table table-striped table-hover table-bordered mt-4">';
            echo '<thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Service</th>
                        <th>Prix (Fbu)</th>
                        <th>Nom Client</th>
                        <th>Email Client</th>
                        <th>Statut</th>
                        <th>Date de Création</th>
                    </tr>
                  </thead>';
            echo '<tbody>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['service']}</td>
                        <td>{$row['prix']}</td>
                        <td>{$row['user_name']}</td>
                        <td>{$row['user_email']}</td>
                        <td>{$row['statut']}</td>
                        <td>{$row['created_at']}</td>
                      </tr>";
            }

            echo '</tbody>';
            echo '</table>';

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>