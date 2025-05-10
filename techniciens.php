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
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("INSERT INTO techniciens (nom, prenom, email) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email]);
    header("Location: techniciens.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM techniciens WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: techniciens.php");
    exit();
}

$techniciens = $pdo->query("SELECT * FROM techniciens")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>🛠️ Techniciens</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>🛠️ Techniciens</h1>
    <form method="POST" class="form-grid">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit" name="ajouter">➕ Ajouter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($techniciens as $tech): ?>
                <tr>
                    <td><?= $tech['nom'] ?></td>
                    <td><?= $tech['prenom'] ?></td>
                    <td><?= $tech['email'] ?></td>
                    <td>
                        <a href="?delete=<?= $tech['id'] ?>" onclick="return confirm('Supprimer ce technicien ?')">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php" class="back">⬅️ Retour</a>
</div>
</body>
</html>
