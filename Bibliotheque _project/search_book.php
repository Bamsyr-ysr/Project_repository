<?php

require_once 'dbconnect.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$searchTerm = '';
$books = [];
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE :searchTerm OR author LIKE :searchTerm OR genres LIKE :searchTerm");
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
    $stmt->execute();
    $books = $stmt->fetchAll();
}

if (isset($_GET['delete'])) {
    $bookId = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
    $stmt->bindParam(':id', $bookId);
    $stmt->execute();
    header("Location: search_book.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Rechercher livre</title>
</head>
<body>

    <div class="dashboard-container">
        
        <form method="POST" action="" class="form-group">
            <input type="text" name="search" placeholder="Rechercher un livre" value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit">Rechercher</button>
        </form>

        <a href="list_books.php" class="button">Afficher tous les livres</a>

        <?php if (!empty($books)): ?>
            <h3>Résultats de la recherche</h3>
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
                                <a href="search_book.php?delete=<?= $book['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($searchTerm !== ''): ?>
            <p>Aucun livre trouvé pour la recherche : "<?= htmlspecialchars($searchTerm) ?>"</p>
        <?php endif; ?>

        <a href="admin_board.php" class="button">Retour au tableau de bord</a>
    </div>

</body>
</html>

