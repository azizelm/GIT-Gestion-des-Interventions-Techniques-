<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $nom = $_POST['nom_entreprise'];
    $adresse = $_POST['adresse'];
    $contact = $_POST['contact'];

    $stmt = $pdo->prepare("INSERT INTO clients (nom_entreprise, adresse, contact) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $adresse, $contact]);
    header("Location: clients.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: clients.php");
    exit();
}

$clients = $pdo->query("SELECT * FROM clients")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>📇 Gestion des Clients</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>📇 Clients</h1>
    <form method="POST" class="form-grid">
        <input type="text" name="nom_entreprise" placeholder="Nom de l'entreprise" required>
        <input type="text" name="adresse" placeholder="Adresse" required>
        <input type="text" name="contact" placeholder="Contact" required>
        <button type="submit" name="ajouter">➕ Ajouter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Contact</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= $client['nom_entreprise'] ?></td>
                    <td><?= $client['adresse'] ?></td>
                    <td><?= $client['contact'] ?></td>
                    <td>
                        <a href="?delete=<?= $client['id'] ?>" onclick="return confirm('Supprimer ce client ?')">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php" class="back">⬅️ Retour</a>
</div>
</body>
</html>
