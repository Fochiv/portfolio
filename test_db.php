<?php
// Fichier de diagnostic - supprimer apres utilisation
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'sql305.iceiy.com';
$db   = 'icei_41611066_portfolio_benfoch';
$user = 'icei_41611066';
$pass = '1214161820Ben';

echo "<h2>Test de connexion MySQL</h2>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>✅ Connexion MySQL réussie !</p>";

    // Test table settings
    $rows = $pdo->query("SELECT `key`, value FROM settings LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    echo "<p style='color:green'>✅ Table settings accessible. Entrées trouvées : " . count($rows) . "</p>";
    foreach ($rows as $r) {
        echo "<p>&nbsp;&nbsp;- <b>" . htmlspecialchars($r['key']) . "</b> : " . htmlspecialchars(substr($r['value'], 0, 50)) . "</p>";
    }

    // Test table projects
    $count = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    echo "<p style='color:green'>✅ Table projects : $count entrée(s)</p>";

    // Test table skills
    $count = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
    echo "<p style='color:green'>✅ Table skills : $count entrée(s)</p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Erreur : " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><b>PHP version :</b> " . PHP_VERSION . "</p>";
echo "<p><b>Extensions PDO :</b> " . (extension_loaded('pdo_mysql') ? '✅ pdo_mysql chargé' : '❌ pdo_mysql manquant') . "</p>";
echo "<p style='color:orange'><b>ATTENTION :</b> Supprimez ce fichier après diagnostic !</p>";
