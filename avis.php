<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'Services';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement des données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['note'])) {
        // Notation simple
        $stmt = $pdo->prepare("INSERT INTO avis (service_nom, service_categorie, note) VALUES (?, ?, ?)");
        $stmt->execute([
            $_POST['service_nom'],
            $_POST['service_categorie'],
            (int)$_POST['note']
        ]);
    } 
    elseif (isset($_POST['commentaire'])) {
        // Commentaire complet
        $stmt = $pdo->prepare("UPDATE avis SET client_nom = ?, commentaire = ? WHERE id = ?");
        $stmt->execute([
            htmlspecialchars($_POST['client_nom']),
            htmlspecialchars($_POST['commentaire']),
            (int)$_POST['avis_id']
        ]);
    }
    
    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit();
}

// Liste des services
$services = [
    'Services à Domicile' => [
        'Systèmes Électroménagers', 'Sécurité à Domicile', 'Déménagement',
        'Garde d\'Enfants', 'Livraison', 'Organisation d\'Événements',
        'Coaching Personnel', 'Conseil Financier', 'Peinture et Nettoyage',
        'Soins d\'hygiène et infirmiers', 'Entretien Ménager et Jardinage'
    ],
    'Services de Réparation' => [
        'Électricité', 'Réparation d\'Appareils Éléctronique', 'Plomberie',
        'Mécanique', 'Soudure', 'Réparation de Caméras',
        'Réparation de Montres et Accessoires', 'Réparation de Vélo',
        'Réparation de Climatisation et Micro-ondes',
        'Réparation de Systèmes Audio et Mixeurs', 'Réparation d\'Instruments de Musique'
    ]
];

// Récupération des données
$notes_moyennes = [];
$dernier_avis_id = [];
$tous_commentaires = [];

foreach ($services as $categorie => $noms) {
    foreach ($noms as $nom) {
        // Moyennes
        $stmt = $pdo->prepare("SELECT AVG(note) as moyenne, COUNT(*) as total FROM avis WHERE service_categorie = ? AND service_nom = ?");
        $stmt->execute([$categorie, $nom]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $notes_moyennes[$categorie][$nom] = [
            'moyenne' => round($result['moyenne'], 1),
            'total' => $result['total'] ?? 0
        ];
        
        // Dernier avis ID pour le commentaire
        $stmt = $pdo->prepare("SELECT id FROM avis WHERE service_categorie = ? AND service_nom = ? ORDER BY date_creation DESC LIMIT 1");
        $stmt->execute([$categorie, $nom]);
        $dernier_avis_id[$categorie][$nom] = $stmt->fetchColumn();
        
        // Commentaires pour affichage
        $stmt = $pdo->prepare("SELECT * FROM avis WHERE service_categorie = ? AND service_nom = ? AND commentaire IS NOT NULL ORDER BY date_creation DESC");
        $stmt->execute([$categorie, $nom]);
        $tous_commentaires[$categorie][$nom] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Évaluation </title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        .service-container { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 40px; }
        .service-category { flex: 1; min-width: 300px; background: #f9f9f9; padding: 15px; border-radius: 8px; }
        .service-category h2 { color: #333; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .service-item { background: white; padding: 15px; margin-bottom: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .service-name { font-weight: bold; font-size: 1.1em; margin-bottom: 10px; }
        .rating { display: flex; align-items: center; margin-bottom: 10px; }
        .rating-stars { display: flex; }
        .rating-star { font-size: 24px; color: #ddd; cursor: pointer; transition: all 0.2s; }
        .rating-star:hover { transform: scale(1.2); }
        .rating-count { margin-left: 10px; font-size: 14px; color: #666; }
        .comment-toggle { color: #3498db; cursor: pointer; font-size: 13px; }
        .comment-form { display: none; margin-top: 15px; padding-top: 15px; border-top: 1px dashed #eee; }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 14px; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .form-group textarea { height: 80px; resize: vertical; }
        .submit-btn { background: #2ecc71; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
        .success-message { background: #dff0d8; color: #3c763d; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .comments-section { margin-top: 50px; }
        .comment { background: #f5f5f5; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .comment-header { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .comment-author { font-weight: bold; }
        .comment-date { color: #666; }
        
        /* Couleurs des étoiles */
        .star-1 { color: #ff6b6b !important; }
        .star-2 { color: #ffa502 !important; }
        .star-3 { color: #ffd32a !important; }
        .star-4 { color: #7bed9f !important; }
        .star-5 { color: #1dd1a1 !important; }
    </style>
</head>
<body>
    <h1 class="text-center">Évaluation des Services</h1>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="success-message">Merci pour votre contribution !</div>
    <?php endif; ?>
    
    <div class="service-container">
        <?php foreach ($services as $categorie => $noms): ?>
            <div class="service-category">
                <h2><?= htmlspecialchars($categorie) ?></h2>
                
                <?php foreach ($noms as $nom): ?>
                    <div class="service-item" id="service-<?= md5($categorie.$nom) ?>">
                        <div class="service-name"><?= htmlspecialchars($nom) ?></div>
                        
                        <!-- Notation -->
                        <form method="POST" class="rating-form">
                            <input type="hidden" name="service_nom" value="<?= htmlspecialchars($nom) ?>">
                            <input type="hidden" name="service_categorie" value="<?= htmlspecialchars($categorie) ?>">
                            
                            <div class="rating">
                                <div class="rating-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php
                                        $note_moyenne = $notes_moyennes[$categorie][$nom]['moyenne'] ?? 0;
                                        $is_filled = $i <= round($note_moyenne);
                                        $star_class = $is_filled ? 'star-'.round($note_moyenne) : '';
                                        ?>
                                        <span class="rating-star <?= $star_class ?>" 
                                              data-value="<?= $i ?>"
                                              onclick="rateService(this, <?= $i ?>, '<?= md5($categorie.$nom) ?>')">★</span>
                                    <?php endfor; ?>
                                    <input type="hidden" name="note">
                                </div>
                                
                                <?php
                                $note_info = $notes_moyennes[$categorie][$nom] ?? ['moyenne' => 0, 'total' => 0];
                                $moyenne = $note_info['moyenne'];
                                $total = $note_info['total'];
                                ?>
                                <div class="rating-count">
                                    <?= number_format($moyenne, 1) ?> (<?= $total ?> avis)
                                </div>
                            </div>
                        </form>
                        
                        <!-- Lien pour commenter -->
                        <div class="comment-toggle" onclick="toggleCommentForm('<?= md5($categorie.$nom) ?>')">
                            Ajouter un commentaire...
                        </div>
                        
                        <!-- Formulaire de commentaire (caché par défaut) -->
                        <form method="POST" class="comment-form" id="comment-form-<?= md5($categorie.$nom) ?>">
                            <input type="hidden" name="avis_id" value="<?= $dernier_avis_id[$categorie][$nom] ?? '' ?>">
                            
                            <div class="form-group">
                                <label for="client_nom-<?= md5($categorie.$nom) ?>">Votre nom (optionnel) :</label>
                                <input type="text" name="client_nom" id="client_nom-<?= md5($categorie.$nom) ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="commentaire-<?= md5($categorie.$nom) ?>">Votre commentaire :</label>
                                <textarea name="commentaire" id="commentaire-<?= md5($categorie.$nom) ?>"></textarea>
                            </div>
                            
                            <button type="submit" class="submit-btn">Envoyer le commentaire</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Section des commentaires -->
    <div class="comments-section">
        <h2>Commentaires des clients</h2>
        
        <?php foreach ($services as $categorie => $noms): ?>
            <h3><?= htmlspecialchars($categorie) ?></h3>
            
            <?php foreach ($noms as $nom): ?>
                <?php if (!empty($tous_commentaires[$categorie][$nom])): ?>
                    <h4><?= htmlspecialchars($nom) ?></h4>
                    
                    <?php foreach ($tous_commentaires[$categorie][$nom] as $commentaire): ?>
                        <div class="comment">
                            <div class="comment-header">
                                <span class="comment-author">
                                    <?= htmlspecialchars($commentaire['client_nom'] ?? 'Anonyme') ?>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star-<?= $commentaire['note'] ?>" style="color: <?= $i <= $commentaire['note'] ? 'inherit' : '#ddd' ?>">★</span>
                                    <?php endfor; ?>
                                </span>
                                <span class="comment-date">
                                    <?= date('d/m/Y H:i', strtotime($commentaire['date_creation'])) ?>
                                </span>
                            </div>
                            <p><?= nl2br(htmlspecialchars($commentaire['commentaire'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
    
    <script>
        // Notation d'un service
        function rateService(star, note, serviceId) {
            const form = document.querySelector(`#service-${serviceId} .rating-form`);
            form.querySelector('input[name=note]').value = note;
            form.submit();
        }
        
        // Affichage du formulaire de commentaire
        function toggleCommentForm(serviceId) {
            const form = document.getElementById(`comment-form-${serviceId}`);
            form.style.display = form.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</body>
</html>