<?php

require_once 'dbconnect.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: list_books.php");
    exit;
}

$bookId = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
$stmt->bindParam(':id', $bookId);
$stmt->execute();
$book = $stmt->fetch();

if (!$book) {
    echo "Livre non trouvé.";
    exit;
}

if (isset($_POST['update_book'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $year = trim($_POST['year']);
    $synopsis = trim($_POST['synopsis']);
    $genres = isset($_POST['genres']) ? implode(',', $_POST['genres']) : $book['genres'];
    $status = $_POST['status'];

    if ($title && $author && $year && $synopsis && $genres) {
        $stmt = $pdo->prepare("UPDATE books SET title = :title, author = :author, year = :year, synopsis = :synopsis, genres = :genres, status = :status WHERE id = :id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':synopsis', $synopsis);
        $stmt->bindParam(':genres', $genres);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $bookId);
        $stmt->execute();

        header("Location: admin_board.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Livre - Bibliothèque</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="dashboard-container">
        <h2>Modifier les informations du livre</h2>

        <form method="POST" action="edit_book.php?id=<?= $bookId ?>">
            <div class="form-group">
                <label for="title">Titre :</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="author">Auteur :</label>
                <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
            </div>
            <div class="form-group">
                <label for="year">Année de Publication :</label>
                <input type="number" id="year" name="year" value="<?= htmlspecialchars($book['year']) ?>" required>
            </div>
            <div class="form-group">
                <label for="synopsis">Synopsis :</label>
                <textarea id="synopsis" name="synopsis" required><?= htmlspecialchars($book['synopsis']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Genre :</label>
                <div class="checkbox">
                    <input type="checkbox" name="genres[]" value="Aventure" <?= in_array('Aventure', explode(',', $book['genres'])) ? 'checked' : '' ?>> Aventure
                    <input type="checkbox" name="genres[]" value="Romance" <?= in_array('Romance', explode(',', $book['genres'])) ? 'checked' : '' ?>> Romance
                    <input type="checkbox" name="genres[]" value="Fiction" <?= in_array('Fiction', explode(',', $book['genres'])) ? 'checked' : '' ?>> Fiction
                    <input type="checkbox" name="genres[]" value="Sci-Fi" <?= in_array('Sci-Fi', explode(',', $book['genres'])) ? 'checked' : '' ?>> Sci-Fi
                    <input type="checkbox" name="genres[]" value="Comédie" <?= in_array('Comédie', explode(',', $book['genres'])) ? 'checked' : '' ?>> Comédie
                    <input type="checkbox" name="genres[]" value="Drame" <?= in_array('Drame', explode(',', $book['genres'])) ? 'checked' : '' ?>> Drame
                    <input type="checkbox" name="genres[]" value="Mystère" <?= in_array('Mystère', explode(',', $book['genres'])) ? 'checked' : '' ?>> Mystère
                    <input type="checkbox" name="genres[]" value="Polar" <?= in_array('Polar', explode(',', $book['genres'])) ? 'checked' : '' ?>> Polar
                    <input type="checkbox" name="genres[]" value="Matériel D'étude" <?= in_array('Matériel D\'étude', explode(',', $book['genres'])) ? 'checked' : '' ?>> Matériel D'étude
                </div>
            </div>
            <div class="form-group">
                <label for="status">Statut :</label>
                <select id="status" name="status">
                    <option value="disponible" <?= $book['status'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                    <option value="indisponible" <?= $book['status'] == 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
                </select>
            </div>
            <button type="submit" name="update_book">Mettre à jour</button>
        </form>

        <a href="admin_board.php" class="button">Retour au tableau de bord</a>
    </div>
</body>
</html>
