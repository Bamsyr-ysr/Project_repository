<?php

require_once 'dbconnect.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

function getGenreCounts($pdo) {
    $genres = ['Aventure', 'Romance', 'Fiction', 'Sci-Fi', 'Comédie', 'Drame', 'Mystère', 'Polar', 'Matériel D\'étude'];
    $counts = [];

    foreach ($genres as $genre) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM books WHERE FIND_IN_SET(:genres, genres)");
        $stmt->bindParam(':genres', $genre);
        $stmt->execute();
        $counts[] = $stmt->fetchColumn();
    }

    return $counts;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Quelques stats</title>
</head>
<body>

<div class="dashboard-container">

    <h3>Statistiques des Livres</h3>
    <canvas id="chart"></canvas>

    <a href="admin_board.php" class="button">Retour au tableau de bord</a>

    <script>
        const ctx = document.getElementById('chart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Aventure', 'Romance', 'Fiction', 'Sci-Fi', 'Comédie', 'Drame', 'Mystère', 'Polar', 'Matériel D\'étude'],
                datasets: [{
                    label: 'Nombre de livres par genre',
                    data: [<?= implode(',', getGenreCounts($pdo)) ?>],
                    backgroundColor: '#E2725B',
                    borderColor: '#8B4513',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</div>
</body>
</html>