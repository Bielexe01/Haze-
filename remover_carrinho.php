<?php
session_start();
include('config.php');

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado.");
}

$usuario_id = $_SESSION['usuario_id'];
$item_id = $_GET['id'];

// Remove o item do carrinho
$stmt = $conn->prepare("DELETE FROM carrinho WHERE id = ? AND usuario_id = ?");
$stmt->execute([$item_id, $usuario_id]);

header("Location: vizualizar_carrinho.php");
exit();
?>
