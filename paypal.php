<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement via PayPal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .container{
        background-color: paleturquoise;
      }  
    </style>
</head>
<body>
    <div class="container mt-5 col-7">
        <h2 class="text-center mb-4 text-warning">Formulaire de Paiement via PayPal</h2>
        <form id="paymentForm" action="" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom sur la Carte</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="montant" class="form-label">Montant (en Dollars)</label>
                <input type="number" class="form-control" id="montant" name="montant" value="100" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Votre Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="email_destinataire" class="form-label">Choisissez un Email de Paiement</label>
                <select class="form-select" id="email_destinataire" name="email_destinataire" required>
                    <option value="">Sélectionnez un Email</option>
                    <option value="claude.entreprises@gmail.com">claude.entreprises@gmail.com</option>
                    <option value="mugisha.bruce@gmail.com">mugisha.bruce@gmail.com</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Payer via PayPal</button>
            <br>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $montant = htmlspecialchars($_POST['montant']);
            $email_destinataire = htmlspecialchars($_POST['email_destinataire']);

            // Redirection vers PayPal
            $url = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=" . urlencode($email_destinataire) . "&item_name=Paiement&amount=" . urlencode($montant) . "&currency_code=USD";
            header("Location: $url");
            exit();
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>