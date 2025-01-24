<?php

require_once 'dbconnect.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrateur - Bibliothèque</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="dashboard-container">
        <header>
            <h1>Bienvenue, <?= htmlspecialchars($_SESSION['username']); ?> !</h1>
        </header>
        <h2>Gestion des Livres</h2>

        <a href="list_books.php" class="button">Modifier un livre</a>
        <a href="statistique.php" class="button">Quelques statistique</a>
        <a href="add_book.php" class="button">Ajoutons un ouvrage</a>
        <a href="search_book.php" class="button">À la recherche d'un livre</a>

        <a href="login.php" class="button">Retour à la page de connexion</a>
    </div>

</body>
</html>
