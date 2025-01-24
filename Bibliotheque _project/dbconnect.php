<?php
// Paramètres de connexion à la base de données
$host = 'localhost'; // Adresse du serveur
$dbname = 'library_db'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur MySQL
$password = ''; // Mot de passe MySQL (laisser vide pour XAMPP par défaut)

try {
    // Création d'une connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // Configuration des options PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Afficher un message si la connexion réussit (à supprimer en production)
    // echo "Connexion réussie à la base de données.";
} catch (PDOException $e) {
    // Gérer les erreurs de connexion
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
