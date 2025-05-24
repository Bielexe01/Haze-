<?php
session_start();
include('config.php'); // Conexão com o banco

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    die("Acesso negado! Apenas administradores podem acessar esta página.");
}

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    die("ID do produto não especificado.");
}

$id = $_GET['id'];

// Obtém os dados do produto
$sql = "SELECT * FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$produto = $stmt->fetch();

if (!$produto) {
    die("Produto não encontrado.");
}

// Atualiza o produto se o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];

    $sql = "UPDATE produtos SET nome = ?, preco = ?, estoque = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nome, $preco, $estoque, $id]);

    header("Location: gerenciar_produtos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
</head>
<body>
    <h2>Editar Produto</h2>
    <form method="POST">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required><br>

        <label>Preço:</label>
        <input type="text" name="preco" value="<?php echo $produto['preco']; ?>" required><br>

        <label>Estoque:</label>
        <input type="number" name="estoque" value="<?php echo $produto['estoque']; ?>" required><br>

        <button type="submit">Salvar Alterações</button>
    </form>
</body>
</html>
