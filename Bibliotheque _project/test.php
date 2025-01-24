
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
// Exemple de génération d'un mot de passe crypté en PHP
$admin_password = password_hash('adminpassword', PASSWORD_BCRYPT);
$client_password = password_hash('clientpassword', PASSWORD_BCRYPT);

// Afficher les mots de passe cryptés
echo $admin_password;

?>
</body>
</html>