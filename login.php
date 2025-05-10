<?php
session_start();
include 'db.php'; 

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: index.php"); 
        exit();
    } else {
        $message = "❌ Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>🔐 Connexion</h1>

    <?php if ($message): ?>
        <div class="message error"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Nom d'utilisateur:</label>
        <input type="text" name="username" required>

        <label>Mot de passe:</label>
        <input type="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>
</div>
</body>
</html>
