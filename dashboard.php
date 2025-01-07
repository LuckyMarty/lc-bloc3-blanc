<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('./security/utils.php');
require_once('./classes/Dashboard.php');
require_once('./classes/Vehicule.php');
require_once('./classes/Client.php');

session_start();

isLogged();

$dashboard = new Dashboard();
$vehicule = new Vehicule();
$client = new Client();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                if (strlen($_POST['nb_identification']) > 12) {
                    $error = "Le numéro d'identification ne doit pas dépasser 12 caractères.";
                } else {
                    $vehicule->addVehicule($_POST['nb_identification'], $_POST['marque'], $_POST['modele'], $_POST['annee'], $_POST['client_id']);
                    header("Location: /dashboard.php");
                    exit();
                }
                break;
            case 'delete':
                $vehicule->deleteVehicule($_POST['id']);
                header("Location: /dashboard.php");
                exit();
                break;
        }
    }
}

$totalClients = $dashboard->getTotalClients();
$totalVehicules = $dashboard->getTotalVehicules();
$totalRendezvous = $dashboard->getTotalRendezvous();
$vehicules = $vehicule->getVehicules();
$clients = $client->getClients();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">

    <title>Tableau de Bord Garage Train</title>
</head>

<body>

    <header>
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <h1><a class="navbar-brand" href="/dashboard.php">Tableau de Bord Garage Train</a></h1>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="/logout.php">Déconnexion</a></li>
                </ul>
            </div>
    </header>

    <?php if ($error): ?>
        <div class="container">
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        </div>
    <?php endif; ?>

    <div class="container mt-5">


        <div class="row">
            <div class="col-md-4">
                <h2>Clients</h2>
                <p>Total Clients: <?= $totalClients ?></p>
            </div>
            <div class="col-md-4">
                <h2>Véhicules</h2>
                <p>Total Véhicules: <?= $totalVehicules ?></p>
            </div>
            <div class="col-md-4">
                <h2>Rendez-vous</h2>
                <p>Total Rendez-vous: <?= $totalRendezvous ?></p>
            </div>
        </div>
        <div>
            <h2>Liste des Véhicules</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Numéro d'identification</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Année</th>
                        <th>Client</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicules as $vehicule): ?>
                        <tr>
                            <td><?= $vehicule['nb_identification'] ? htmlspecialchars($vehicule['nb_identification']) : "Non renseigné" ?></td>
                            <td><?= htmlspecialchars($vehicule['marque']) ?></td>
                            <td><?= htmlspecialchars($vehicule['modele']) ?></td>
                            <td><?= htmlspecialchars($vehicule['annee']) ?></td>
                            <td><?= htmlspecialchars($vehicule['client']) ?></td>
                            <td>
                                <form method="post" style="display:inline;" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="id" value="<?= $vehicule['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                </form>
                                <form method="get" action="vehicule_edit.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $vehicule['id'] ?>">
                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div>
            <h2>Ajouter un Véhicule</h2>
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nb_identification">Numéro d'identification:</label>
                            <div class="input-group">
                                <input type="text" id="nb_identification" name="nb_identification" class="form-control">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" title="Numéro d'identification du véhicule"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="marque">Marque:</label>
                            <input type="text" id="marque" name="marque" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modele">Modèle:</label>
                            <input type="text" id="modele" name="modele" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="annee">Année:</label>
                            <input type="number" id="annee" name="annee" class="form-control" min="1900" max="2099" step="1" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="client_id">Client:</label>
                            <select id="client_id" name="client_id" class="form-control" required>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= htmlspecialchars($client['id']) ?>">
                                        <?= htmlspecialchars($client['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success" style="margin-top: 25px;">Ajouter</button>
            </form>
        </div>
    </div>


    <footer class="mt-5">
        <div class="container">
            <p class="text-center">Garage Train - 2021 by luckymarty</p>
        </div>
    </footer>


    <script>
        function confirmDelete() {
            return confirm("Êtes-vous sûr de vouloir supprimer ce véhicule ?");
        }
    </script>
</body>

</html>