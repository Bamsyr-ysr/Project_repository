<?php

require_once 'dbconnect.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['add_book'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $year = trim($_POST['year']);
    $synopsis = trim($_POST['synopsis']);
    $genres = isset($_POST['genres']) ? implode(',', $_POST['genres']) : '';
    $status = 'disponible';

    if ($title && $author && $year && $synopsis && $genres) {
        $stmt = $pdo->prepare("INSERT INTO books (title, author, year, synopsis, genres, status) VALUES (:title, :author, :year, :synopsis, :genres, :status)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':synopsis', $synopsis);
        $stmt->bindParam(':genres', $genres);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Ajouter un livre</title>
</head>
<body>
    <div class="dashboard-container">
        <h3>Ajouter un Nouveau Livre</h3>
        <form method="POST" action="admin_board.php">
            <div class="form-group">
                <label for="title">Titre :</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="author">Auteur :</label>
                <input type="text" id="author" name="author" required>
            </div>
            <div class="form-group">
                <label for="year">Année de Publication :</label>
                <input type="number" id="year" name="year" required>
            </div>
            <div class="form-group">
                <label for="synopsis">Synopsis :</label>
                <textarea id="synopsis" name="synopsis" required></textarea>
            </div>
            <div class="form-group">
                <label>Genre :</label>
                <input type="checkbox" name="genres[]" value="Aventure"> Aventure
                <input type="checkbox" name="genres[]" value="Romance"> Romance
                <input type="checkbox" name="genres[]" value="Fiction"> Fiction
                <input type="checkbox" name="genres[]" value="Sci-Fi"> Sci-Fi
                <input type="checkbox" name="genres[]" value="Comédie"> Comédie
                <input type="checkbox" name="genres[]" value="Drame"> Drame
                <input type="checkbox" name="genres[]" value="Mystère"> Mystère
                <input type="checkbox" name="genres[]" value="Polar"> Polar
                <input type="checkbox" name="genres[]" value="Matériel D'étude"> Matériel D'étude
            </div>
            <button type="submit" name="add_book">Ajouter le Livre</button>
        </form>

        <a href="admin_board.php" class="button">Retour au tableau de bord</a>
    </div>
</body>
</html>