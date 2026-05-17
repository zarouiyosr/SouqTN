<?php

$username = 'admin';
$email    = 'admin@souqtn.tn';
$password = 'admin123';
$role     = 'admin';

function box($title, $body, $ok = true) {
    $color = $ok ? '#16A34A' : '#C0392B';
    echo "<div style='background:#fff;border-left:5px solid $color;";
    echo "padding:16px 20px;margin:12px 0;border-radius:8px;";
    echo "box-shadow:0 4px 14px rgba(0,0,0,.06)'>";
    echo "<strong style='color:$color'>$title</strong><br>";
    echo "<span style='color:#444;font-size:14px'>$body</span></div>";
}

echo "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'>";
echo "<title>Diagnostic Admin - SouqTN</title>";
echo "<style>body{font-family:'Segoe UI',sans-serif;background:#F3F4F6;";
echo "max-width:640px;margin:40px auto;padding:0 20px}";
echo "h1{font-size:20px;color:#C8401A}code{background:#FEF0EA;color:#C8401A;";
echo "padding:2px 7px;border-radius:5px}a{color:#C8401A;font-weight:600}</style>";
echo "</head><body><h1>Diagnostic du compte administrateur</h1>";

require_once __DIR__ . '/../core/Database.php';
try {
    $db = Database::getConnection();
    box("Etape 1 - Connexion a la base de donnees", "Reussie.");
} catch (Exception $e) {
    box("Etape 1 - Connexion a la base ECHOUEE", htmlspecialchars($e->getMessage())
        . "<br><br>Verifiez : MySQL demarre dans XAMPP, base <code>souqtn</code> importee, "
        . "et le bon port dans <code>core/Database.php</code>.", false);
    echo "</body></html>"; exit;
}

try {
    $db->query("SELECT 1 FROM users LIMIT 1");
    box("Etape 2 - Table users", "Presente.");
} catch (Exception $e) {
    box("Etape 2 - Table users introuvable",
        "Importez <code>database.sql</code> dans phpMyAdmin (la base est vide).", false);
    echo "</body></html>"; exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);
try {
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $db->prepare("UPDATE users SET username=?, password=?, role=? WHERE email=?")
           ->execute([$username, $hash, $role, $email]);
        box("Etape 3 - Compte admin", "Compte existant <strong>mis a jour</strong> "
            . "avec un nouveau mot de passe valide.");
    } else {
        $db->prepare("INSERT INTO users(username,email,password,role) VALUES(?,?,?,?)")
           ->execute([$username, $email, $hash, $role]);
        box("Etape 3 - Compte admin", "Compte <strong>cree</strong>.");
    }
} catch (Exception $e) {
    box("Etape 3 - Echec creation du compte", htmlspecialchars($e->getMessage()), false);
    echo "</body></html>"; exit;
}

$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    box("Etape 4 - Test de connexion", "Le couple <code>$email</code> / "
        . "<code>$password</code> fonctionne. Role : <strong>{$user['role']}</strong>.");
    echo "<div style='background:#16A34A;color:#fff;padding:18px 22px;";
    echo "border-radius:10px;margin-top:20px'>";
    echo "<strong>Tout est pret</strong><br>";
    echo "Connectez-vous : <a style='color:#fff;text-decoration:underline' ";
    echo "href='/SouqTN/public/login'>page de connexion</a> "
        . "avec <code style='background:rgba(255,255,255,.25);color:#fff'>$email</code> / "
        . "<code style='background:rgba(255,255,255,.25);color:#fff'>$password</code></div>";
    echo "<p style='margin-top:16px;padding:12px;background:#FEF3C7;border-radius:8px;";
    echo "font-size:14px;color:#92400E'>Supprimez maintenant le fichier "
        . "<code>public/setup_admin.php</code> pour la securite.</p>";
} else {
    box("Etape 4 - Le test de connexion echoue encore",
        "Anormal : le hash vient d'etre regenere. Verifiez la version de PHP de XAMPP.", false);
}

echo "</body></html>";

