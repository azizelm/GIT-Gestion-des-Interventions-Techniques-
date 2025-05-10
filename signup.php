<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $message = "❌ Les mots de passe ne correspondent pas.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashed_password]);

        $message = "✅ Inscription réussie! Vous pouvez maintenant vous connecter.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📝 Inscription</h1>

        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nom d'utilisateur:</label>
            <input type="text" name="username" required>

            <label>Mot de passe:</label>
            <input type="password" name="password" required>

            <label>Confirmer le mot de passe:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">S'inscrire</button>
        </form>
        <a class="back" href="login.php">⬅️ Retour à la connexion</a>
    </div>
</body>
</html>
