<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques de Paiement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Statistiques de Paiement</h2>

        <?php
        session_start(); // Démarrer la session

        // Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "Services";

        // Créer la connexion
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            echo '<div class="alert alert-danger">';
            echo '<h4>Erreur de connexion à la base de données: ' . htmlspecialchars($conn->connect_error) . '</h4>';
            echo '</div>';
            exit; // Terminer le script si la connexion échoue
        }

        // Récupération des données par jour
        $stmt = $conn->prepare("SELECT DATE(date_paiement) AS date, SUM(montant) AS montant_total FROM revenus GROUP BY DATE(date_paiement)");
        $stmt->execute();
        $revenus_data = $stmt->get_result();

        $dates_revenus = [];
        $montants_revenus_fb = [];
        while ($row = $revenus_data->fetch_assoc()) {
            $dates_revenus[] = $row['date'];
            $montants_revenus_fb[] = $row['montant_total'];
        }

        // Récupération des données pour Historique par jour
        $stmt = $conn->prepare("SELECT DATE(created_at) AS date, SUM(prix) AS montant_total FROM historique GROUP BY DATE(created_at)");
        $stmt->execute();
        $historique_data = $stmt->get_result();

        $dates_historique = [];
        $montants_historique_fb = [];
        while ($row = $historique_data->fetch_assoc()) {
            $dates_historique[] = $row['date'];
            $montants_historique_fb[] = $row['montant_total'];
        }

        // Récupération des données pour CommandDomicile par jour
        $stmt = $conn->prepare("SELECT DATE(created_at) AS date, SUM(prix) AS montant_total FROM commanddomicile GROUP BY DATE(created_at)");
        $stmt->execute();
        $commandes_data = $stmt->get_result();

        $dates_commandes = [];
        $montants_commandes_fb = [];
        while ($row = $commandes_data->fetch_assoc()) {
            $dates_commandes[] = $row['date'];
            $montants_commandes_fb[] = $row['montant_total'];
        }

        // Conversion des montants en dollars
        $montants_revenus_usd = array_map(fn($montant) => $montant / 7000, $montants_revenus_fb);
        $montants_historique_usd = array_map(fn($montant) => $montant / 7000, $montants_historique_fb);
        $montants_commandes_usd = array_map(fn($montant) => $montant / 7000, $montants_commandes_fb);
        ?>

        <div class="row">
            <div class="col-4">
                <h3>Revenus</h3>
                <canvas id="revenusChart"></canvas>
            </div>
            <div class="col-4">
                <h3>Historique</h3>
                <canvas id="historiqueChart"></canvas>
            </div>
            <div class="col-4">
                <h3>Commandes Domicile</h3>
                <canvas id="commandesChart"></canvas>
            </div>
        </div>

        <script>
            // Fonction pour générer des couleurs en fonction du temps (date)
            function getColorByDate(date) {
                const days = new Date(date).getDay(); // Jour de la semaine (0 = dimanche, 6 = samedi)
                const colors = [
                    'rgba(255, 99, 132, 0.6)', // Dimanche
                    'rgba(54, 162, 235, 0.6)', // Lundi
                    'rgba(255, 206, 86, 0.6)', // Mardi
                    'rgba(75, 192, 192, 0.6)', // Mercredi
                    'rgba(153, 102, 255, 0.6)', // Jeudi
                    'rgba(255, 159, 64, 0.6)', // Vendredi
                    'rgba(199, 199, 199, 0.6)' // Samedi
                ];
                return colors[days];
            }

            // Graphique pour les Revenus
            const ctxRevenus = document.getElementById('revenusChart').getContext('2d');
            const revenusChart = new Chart(ctxRevenus, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($dates_revenus); ?>,
                    datasets: [{
                        label: 'Montant Total en FB',
                        data: <?php echo json_encode($montants_revenus_fb); ?>,
                        backgroundColor: <?php echo json_encode(array_map('getColorByDate', $dates_revenus)); ?>,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' FB / ' + (value / 7000).toFixed(2) + ' $';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.dataset.label + ': ' + tooltipItem.raw + ' FB (' + (tooltipItem.raw / 7000).toFixed(2) + ' $)';
                                }
                            }
                        }
                    }
                }
            });

            // Graphique pour l'Historique
            const ctxHistorique = document.getElementById('historiqueChart').getContext('2d');
            const historiqueChart = new Chart(ctxHistorique, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($dates_historique); ?>,
                    datasets: [{
                        label: 'Montant Total en FB',
                        data: <?php echo json_encode($montants_historique_fb); ?>,
                        backgroundColor: <?php echo json_encode(array_map('getColorByDate', $dates_historique)); ?>,
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' FB / ' + (value / 7000).toFixed(2) + ' $';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.dataset.label + ': ' + tooltipItem.raw + ' FB (' + (tooltipItem.raw / 7000).toFixed(2) + ' $)';
                                }
                            }
                        }
                    }
                }
            });

            // Graphique pour les Commandes Domicile
            const ctxCommandes = document.getElementById('commandesChart').getContext('2d');
            const commandesChart = new Chart(ctxCommandes, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($dates_commandes); ?>,
                    datasets: [{
                        label: 'Montant Total en FB',
                        data: <?php echo json_encode($montants_commandes_fb); ?>,
                        backgroundColor: <?php echo json_encode(array_map('getColorByDate', $dates_commandes)); ?>,
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' FB / ' + (value / 7000).toFixed(2) + ' $';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.dataset.label + ': ' + tooltipItem.raw + ' FB (' + (tooltipItem.raw / 7000).toFixed(2) + ' $)';
                                }
                            }
                        }
                    }
                }
            });
        </script>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>