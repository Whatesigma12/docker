<?php
$pdo = new PDO(
    'mysql:host=db;dbname=expenses;charset=utf8mb4',
    'appuser',
    'apppass'
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $amount = $_POST['amount'] ?? '';

    if ($title !== '' && is_numeric($amount) && $amount > 0) {
        $stmt = $pdo->prepare(
            'INSERT INTO expenses (title, amount) VALUES (?, ?)'
        );
        $stmt->execute([$title, $amount]);

        header('Location: /');
        exit;
    }
}

$expenses = $pdo->query(
    'SELECT * FROM expenses ORDER BY id DESC'
)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Учёт расходов</title>
</head>
<body>
    <h1>Учёт личных расходов</h1>

    <form method="post">
        <input name="title" placeholder="На что потратил?" required>
        <input name="amount" type="number" step="0.01"
               min="0.01" placeholder="Сумма" required>
        <button type="submit">Добавить расход</button>
    </form>

    <h2>Список расходов</h2>

    <table border="1" cellpadding="8">
        <tr>
            <th>Название</th>
            <th>Сумма</th>
            <th>Дата</th>
        </tr>
        <?php foreach ($expenses as $expense): ?>
        <tr>
            <td><?= htmlspecialchars($expense['title']) ?></td>
            <td><?= htmlspecialchars($expense['amount']) ?></td>
            <td><?= htmlspecialchars($expense['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
