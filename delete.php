<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
include 'db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM interventions WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php");
exit();
?>
