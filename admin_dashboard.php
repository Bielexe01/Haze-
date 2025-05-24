<?php
session_start();
include('config.php');

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    die("Acesso negado!");
}

// Recebe termos de busca
$busca = $_GET['busca'] ?? '';
$filtro = $_GET['filtro'] ?? 'produtos';

// Monta a query com base no filtro
if ($filtro === 'produtos') {
    $sql = "SELECT * FROM produtos WHERE nome LIKE :busca";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['busca' => "%$busca%"]);
    $dados = $stmt->fetchAll();
} else {
    $sql = "SELECT * FROM usuarios WHERE email LIKE :busca OR tipo LIKE :busca";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['busca' => "%$busca%"]);
    $dados = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrativo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        .filtros { margin-top: 20px; }
        .logout { float: right; background-color: #555; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        .editar { background-color: #4CAF50; color: white; padding: 5px; border-radius: 5px; text-decoration: none; }
        .deletar { background-color: #f44336; color: white; padding: 5px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Dashboard Administrativo</h2>
    <a href="logout.php" class="logout">Sair</a>

    <form method="get" class="filtros">
        <input type="text" name="busca" placeholder="Buscar..." value="<?= htmlspecialchars($busca) ?>">
        <select name="filtro">
            <option value="produtos" <?= $filtro === 'produtos' ? 'selected' : '' ?>>Produtos</option>
            <option value="usuarios" <?= $filtro === 'usuarios' ? 'selected' : '' ?>>Usuários</option>
        </select>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($filtro === 'produtos'): ?>
        <h3>Produtos</h3>
        <table>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($dados as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                <td><?= $produto['estoque'] ?></td>
                <td>
                    <a href="editar_produto.php?id=<?= $produto['id'] ?>" class="editar">Editar</a>
                    <a href="deletar_produto.php?id=<?= $produto['id'] ?>" class="deletar" onclick="return confirm('Tem certeza?')">Deletar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="adicionar_produto.php" class="editar" style="margin-top: 10px; display: inline-block;">Adicionar Produto</a>
    <?php else: ?>
        <h3>Usuários</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($dados as $usuario): ?>
            <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td><?= $usuario['tipo'] ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="editar">Alterar Tipo</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
