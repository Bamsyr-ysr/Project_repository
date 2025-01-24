<?php

require_once 'dbconnect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$searchTerm = '';
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE :searchTerm OR author LIKE :searchTerm OR genres LIKE :searchTerm");
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
    $stmt->execute();
    $books = $stmt->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT * FROM books ORDER BY RAND() LIMIT 3");
    $stmt->execute();
    $suggestions = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client - Bibliothèque</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="dashboard-container">
    <header>
        <h1>Bienvenue, <?= htmlspecialchars($_SESSION['username']); ?> !</h1>
    </header>
        <h2>Rechercher un livre</h2>
        <form method="POST" action="client_board.php">
            <input type="text" name="search" placeholder="Rechercher par titre, auteur ou genre" value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit">Rechercher</button>
        </form>

        <?php if (!empty($searchTerm)): ?>
            <h3>Résultats de la recherche :</h3>
            <ul>
                <?php foreach ($books as $book): ?>
                    <li>
                        <a href="book_details.php?id=<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?> - <?= htmlspecialchars($book['author']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <h3>Suggestions de livres :</h3>
            <ul>
                <?php foreach ($suggestions as $book): ?>
                    <li>
                        <a href="book_details.php?id=<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?> - <?= htmlspecialchars($book['author']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a href="login.php" class="button">Retour à la page de connexion</a>
    </div>
</body>
</html>
