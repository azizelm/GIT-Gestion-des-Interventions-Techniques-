<?php include 'db.php'; ?>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
// Traiter le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Filtrer et valider les données
    $id_client = $_POST['id_client'];
    $id_technicien = $_POST['id_technicien'];
    $date = $_POST['date'];
    $type = $_POST['type_intervention'];
    $statut = $_POST['statut'];
    $remarque = $_POST['remarque'];

    // Requête préparée pour insérer les données dans la base de données
    try {
        $stmt = $pdo->prepare("INSERT INTO interventions (client_id, technicien_id, date, type_intervention, statut, remarque) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_client, $id_technicien, $date, $type, $statut, $remarque]);

        // Redirection vers la page d'accueil après l'ajout
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        // Gérer l'erreur et afficher un message
        echo "Erreur lors de l'ajout de l'intervention : " . $e->getMessage();
    }
}

// Récupérer les clients et techniciens
$clients = $pdo->query("SELECT * FROM clients")->fetchAll();
$techniciens = $pdo->query("SELECT * FROM techniciens")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une intervention</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>➕ Nouvelle intervention</h1>
        <form method="POST">
            <label>Client:</label>
            <select name="id_client" required>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= htmlspecialchars($client['id']) ?>"><?= htmlspecialchars($client['nom_entreprise']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Technicien:</label>
            <select name="id_technicien" required>
                <?php foreach ($techniciens as $tech): ?>
                    <option value="<?= htmlspecialchars($tech['id']) ?>"><?= htmlspecialchars($tech['nom']) . ' ' . htmlspecialchars($tech['prenom']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Date d'intervention:</label>
            <input type="date" name="date" required>

            <label>Type d'intervention:</label>
            <input type="text" name="type_intervention" required>

            <label>Statut:</label>
            <select name="statut" required>
                <option value="En cours">En cours</option>
                <option value="Terminée">Terminée</option>
                <option value="Annulée">Annulée</option>
            </select>

            <label>Remarque:</label>
            <textarea name="remarque"></textarea>

            <button type="submit">Enregistrer</button>
        </form>
        <a class="back" href="index.php">⬅️ Retour à la liste</a>
    </div>
</body>
</html>
