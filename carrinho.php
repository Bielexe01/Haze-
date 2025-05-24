<?php
session_start();
include('config.php'); // Conexão com o banco

if (!isset($_SESSION['usuario_id'])) {
    die("Você precisa estar logado para adicionar itens ao carrinho.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $produto_id = $_POST['produto_id'] ?? null;
    $cor = $_POST['cor'] ?? '';
    $tamanho = $_POST['tamanho'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 1;

    if (!$produto_id) {
        die("Produto inválido: ID não informado.");
    }

    // Verifica se o produto existe no banco de dados
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$produto_id]);
    $produto = $stmt->fetch();

    if (!$produto) {
        die("Produto inválido: Produto não encontrado.");
    }

    // Verifica se o produto já está no carrinho
    $stmt = $conn->prepare("SELECT id FROM carrinho WHERE usuario_id = ? AND produto_id = ? AND cor = ? AND tamanho = ?");
    $stmt->execute([$usuario_id, $produto_id, $cor, $tamanho]);
    $existe = $stmt->fetch();

    if ($existe) {
        // Atualiza a quantidade se o produto já estiver no carrinho
        $stmt = $conn->prepare("UPDATE carrinho SET quantidade = quantidade + ? WHERE id = ?");
        $stmt->execute([$quantidade, $existe['id']]);
    } else {
        // Insere um novo item no carrinho
        $stmt = $conn->prepare("INSERT INTO carrinho (usuario_id, produto_id, quantidade, cor, tamanho) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$usuario_id, $produto_id, $quantidade, $cor, $tamanho]);
    }

    header("Location:vizualizar_carrinho.php");
    exit();
} else {
    echo "Erro ao adicionar ao carrinho.";
}
?>