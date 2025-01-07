<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('./security/utils.php');
require_once('./classes/Vehicule.php');
require_once('./classes/Client.php');

session_start();

isLogged();

$vehicule = new Vehicule();
$client = new Client();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $vehiculeData = $vehicule->getVehiculeById($id);
    if (!$vehiculeData) {
        die("Véhicule non trouvé");
    }
    $clients = $client->getClients();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    if (strlen($_POST['nb_identification']) > 12) {
        $error = "Le numéro d'identification ne doit pas dépasser 12 caractères.";

        $id = $_POST['id'];
        $vehiculeData = $vehicule->getVehiculeById($id);
        $clients = $client->getClients();
        
    } else {
        $id = $_POST['id'];
        $nb_identification = $_POST['nb_identification'];
        $marque = $_POST['marque'];
        $modele = $_POST['modele'];
        $annee = $_POST['annee'];
        $client_id = $_POST['client_id'];
        $vehicule->editVehicule($id, $nb_identification, $marque, $modele, $annee, $client_id);
        header("Location: /dashboard.php");
        exit();
    }
} else {
    die("Requête invalide");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Véhicule</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>
    <?php if ($error): ?>
        <div class="container">
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        </div>
    <?php endif; ?>

    <div class="container">
        <h1>Modifier Véhicule</h1>
        <form method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($vehiculeData['id']) ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nb_identification">Numéro d'identification:</label>
                        <input type="text" id="nb_identification" name="nb_identification" class="form-control" value="<?= htmlspecialchars($vehiculeData['nb_identification'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="marque">Marque:</label>
                        <input type="text" id="marque" name="marque" class="form-control" value="<?= htmlspecialchars($vehiculeData['marque']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="modele">Modèle:</label>
                        <input type="text" id="modele" name="modele" class="form-control" value="<?= htmlspecialchars($vehiculeData['modele']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="annee">Année:</label>
                        <input type="number" id="annee" name="annee" class="form-control" value="<?= htmlspecialchars($vehiculeData['annee']) ?>" min="1900" max="2099" step="1" required>
                    </div>
                    <div class="form-group">
                        <label for="client_id">Client:</label>
                        <select id="client_id" name="client_id" class="form-control" required>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= htmlspecialchars($client['id']) ?>" <?= $client['id'] == $vehiculeData['client_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($client['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 25px;">Modifier</button>
                    <a href="/dashboard.php" class="btn btn-default" style="margin-top: 25px;">Annuler</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>