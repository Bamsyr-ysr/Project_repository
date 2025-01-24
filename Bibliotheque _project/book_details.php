<?php

session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Livre non trouvé.");
}

$book_id = intval($_GET['id']);
$query = "SELECT * FROM books WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $book_id, PDO::PARAM_INT);
$stmt->execute();
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die("Livre non trouvé.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    if ($book['status'] === 'indisponible') {
        $error = "Le livre est actuellement indisponible.";
    } else {
        $insertQuery = "INSERT INTO borrowed_books (user_id, book_id, start_date, end_date) 
                        VALUES (:user_id, :book_id, :date_debut, :date_fin)";
        $stmt = $pdo->prepare($insertQuery);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $stmt->bindParam(':date_debut', $date_debut);
        $stmt->bindParam(':date_fin', $date_fin);

        if ($stmt->execute()) {
            $updateQuery = "UPDATE books SET status = 'indisponible' WHERE id = :id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':id', $book_id, PDO::PARAM_INT);
            $updateStmt->execute();

            $success = "Le livre a été emprunté avec succès !";
            $book['status'] = 'indisponible';
        } else {
            $error = "Une erreur est survenue lors de l'emprunt.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Détails du livre</title>
</head>
<body>
    <div class="dashboard-container">
        <h1>Détails du livre</h1>
        <p><strong>Titre :</strong> <?php echo htmlspecialchars($book['title']); ?></p>
        <p><strong>Auteur :</strong> <?php echo htmlspecialchars($book['author']); ?></p>
        <p><strong>Année :</strong> <?php echo $book['year']; ?></p>
        <p><strong>Genres :</strong> <?php echo $book['genres']; ?></p>
        <p><strong>Synopsis :</strong> <?php echo htmlspecialchars($book['synopsis']); ?></p>
        <p><strong>Statut :</strong> <?php echo ucfirst($book['status']); ?></p>

        <?php if ($book['status'] === 'disponible'): ?>
            <h2>Emprunter ce livre</h2>
            <form method="POST" action="">
                <label for="date_debut">Date de début :</label>
                <input type="date" id="date_debut" name="date_debut" required>
                <br>
                <label for="date_fin">Date de fin :</label>
                <input type="date" id="date_fin" name="date_fin" required>
                <br>
                <button type="submit">Emprunter</button>
            </form>
        <?php else: ?>
            <p style="color: red;">Ce livre est actuellement indisponible.</p>
        <?php endif; ?>

        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p style='color: green;'>$success</p>"; ?>

        <a href="client_board.php" class="button">Retour au tableau de bord</a>
    </div>
</body>
</html>
