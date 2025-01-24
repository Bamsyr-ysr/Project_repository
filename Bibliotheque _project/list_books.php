<?php

require_once 'dbconnect.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM books");
$stmt->execute();
$books = $stmt->fetchAll();

if (isset($_GET['delete'])) {
    $bookId = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
    $stmt->bindParam(':id', $bookId);
    $stmt->execute();
    header("Location: list_books.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Liste des Livres</title>
</head>
<body>

    <div class="allbook_container">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Année</th>
                    <th>Genre</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['year']) ?></td>
                        <td><?= htmlspecialchars($book['genres']) ?></td>
                        <td>
                            <a href="edit_book.php?id=<?= $book['id'] ?>">Modifier</a> |
                            
                            <a href="list_books.php?delete=<?= $book['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="admin_board.php" class="button">Retour au tableau de bord</a>
    </div>

</body>
</html>
