<?php include 'db.php'; ?>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit();
}

// Récupération de l'intervention
$stmt = $pdo->prepare("SELECT * FROM interventions WHERE id = ?");
$stmt->execute([$id]);
$intervention = $stmt->fetch();

// Clients & Techniciens
$clients = $pdo->query("SELECT * FROM clients")->fetchAll();
$techniciens = $pdo->query("SELECT * FROM techniciens")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id_client = $_POST['client_id'];  // Assurez-vous que les champs correspondent aux noms dans la base de données
    $id_technicien = $_POST['technicien_id'];
    $date = $_POST['date'];
    $type = $_POST['type_intervention'];
    $statut = $_POST['statut'];
    $remarque = $_POST['remarque'];

    // Mise à jour dans la base de données
    $stmt = $pdo->prepare("UPDATE interventions SET client_id = ?, technicien_id = ?, date = ?, type_intervention = ?, statut = ?, remarque = ? WHERE id = ?");
    $stmt->execute([$id_client, $id_technicien, $date, $type, $statut, $remarque, $id]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier l'intervention</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Modifier intervention</h1>
        <form method="POST">
            <label>Client:</label>
            <select name="client_id" required>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id'] ?>" <?= ($client['id'] == $intervention['client_id']) ? 'selected' : '' ?>>
                        <?= $client['nom_entreprise'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Technicien:</label>
            <select name="technicien_id" required>
                <?php foreach ($techniciens as $tech): ?>
                    <option value="<?= $tech['id'] ?>" <?= ($tech['id'] == $intervention['technicien_id']) ? 'selected' : '' ?>>
                        <?= $tech['nom'] . ' ' . $tech['prenom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Date d'intervention:</label>
            <input type="date" name="date" value="<?= $intervention['date'] ?>" required>

            <label>Type d'intervention:</label>
            <input type="text" name="type_intervention" value="<?= $intervention['type_intervention'] ?>" required>

            <label>Statut:</label>
            <select name="statut" required>
                <option value="En cours" <?= ($intervention['statut'] == 'En cours') ? 'selected' : '' ?>>En cours</option>
                <option value="Terminée" <?= ($intervention['statut'] == 'Terminée') ? 'selected' : '' ?>>Terminée</option>
                <option value="Annulée" <?= ($intervention['statut'] == 'Annulée') ? 'selected' : '' ?>>Annulée</option>
            </select>

            <label>Remarque:</label>
            <textarea name="remarque"><?= $intervention['remarque'] ?></textarea>

            <button type="submit">💾 Enregistrer</button>
        </form>
        <a class="back" href="index.php">⬅️ Retour</a>
    </div>
</body>
</html>
