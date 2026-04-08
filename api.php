<?php
require_once 'config.php';

$db = getDB();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ============================================
// SEND MESSAGE
// ============================================
if ($action === 'send_message') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        jsonResponse(['success' => false, 'error' => 'Champs requis manquants.'], 400);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(['success' => false, 'error' => 'Email invalide.'], 400);
    }

    $stmt = $db->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $subject, $message]);

    jsonResponse(['success' => true, 'message' => 'Message envoyé.']);
}

// ============================================
// GET SETTINGS (public)
// ============================================
if ($action === 'get_settings') {
    $settings = [];
    $rows = $db->query("SELECT `key`, value FROM settings WHERE `key` IN ('availability','cv_file','profile_photo')")->fetchAll();
    foreach ($rows as $row) {
        $settings[$row['key']] = $row['value'];
    }
    jsonResponse(['success' => true, 'data' => $settings]);
}

// ============================================
// ADMIN: LOGIN
// ============================================
if ($action === 'admin_login') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $db->prepare("SELECT value FROM settings WHERE `key` = 'admin_email'");
    $stmt->execute();
    $storedEmail = $stmt->fetchColumn();

    $stmt2 = $db->prepare("SELECT value FROM settings WHERE `key` = 'admin_password'");
    $stmt2->execute();
    $storedHash = $stmt2->fetchColumn();

    if ($email === $storedEmail && password_verify($password, $storedHash)) {
        $_SESSION['admin_logged_in'] = true;
        jsonResponse(['success' => true]);
    } else {
        jsonResponse(['success' => false, 'error' => 'Identifiants incorrects.'], 401);
    }
}

// ============================================
// ADMIN: LOGOUT
// ============================================
if ($action === 'admin_logout') {
    session_destroy();
    jsonResponse(['success' => true]);
}

// ==== ALL BELOW REQUIRE AUTH ====
if (!isAdmin()) {
    jsonResponse(['success' => false, 'error' => 'Non autorisé.'], 403);
}

// ============================================
// ADMIN: GET DASHBOARD DATA
// ============================================
if ($action === 'get_dashboard') {
    $settings = [];
    $rows = $db->query("SELECT `key`, value FROM settings")->fetchAll();
    foreach ($rows as $r) $settings[$r['key']] = $r['value'];

    $projects  = $db->query("SELECT COUNT(*) FROM projects WHERE status='published'")->fetchColumn();
    $messages  = $db->query("SELECT COUNT(*) FROM messages")->fetchColumn();
    $unread    = $db->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();

    jsonResponse(['success' => true, 'settings' => $settings, 'stats' => [
        'projects' => $projects, 'messages' => $messages, 'unread' => $unread
    ]]);
}

// ============================================
// ADMIN: UPDATE SETTING
// ============================================
if ($action === 'update_setting') {
    $key   = trim($_POST['key'] ?? '');
    $value = trim($_POST['value'] ?? '');
    $allowed = ['availability', 'admin_email', 'whatsapp', 'telegram', 'email', 'linkedin', 'github', 'location'];
    if (!in_array($key, $allowed)) {
        jsonResponse(['success' => false, 'error' => 'Clé non autorisée.'], 400);
    }
    $stmt = $db->prepare("INSERT INTO `settings` (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)");
    $stmt->execute([$key, $value]);
    jsonResponse(['success' => true]);
}

// ============================================
// ADMIN: CHANGE PASSWORD
// ============================================
if ($action === 'change_password') {
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';

    $stmt = $db->prepare("SELECT value FROM settings WHERE `key` = 'admin_password'");
    $stmt->execute();
    $hash = $stmt->fetchColumn();

    if (!password_verify($current, $hash)) {
        jsonResponse(['success' => false, 'error' => 'Mot de passe actuel incorrect.'], 400);
    }
    if (strlen($new) < 6) {
        jsonResponse(['success' => false, 'error' => 'Nouveau mot de passe trop court (min 6 chars).'], 400);
    }
    $newHash = password_hash($new, PASSWORD_DEFAULT);
    $stmt2 = $db->prepare("INSERT INTO `settings` (`key`, `value`) VALUES ('admin_password', ?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)");
    $stmt2->execute([$newHash]);
    jsonResponse(['success' => true]);
}

// ============================================
// ADMIN: UPLOAD PROFILE PHOTO
// ============================================
if ($action === 'upload_photo') {
    if (!isset($_FILES['photo'])) jsonResponse(['success' => false, 'error' => 'Aucun fichier.'], 400);
    $file = $_FILES['photo'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        jsonResponse(['success' => false, 'error' => 'Format non supporté (JPG, PNG uniquement).'], 400);
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        jsonResponse(['success' => false, 'error' => 'Fichier trop grand (max 5MB).'], 400);
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . time() . '.' . $ext;
    $dest = 'uploads/profiles/' . $filename;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        $stmt = $db->prepare("INSERT INTO `settings` (`key`, `value`) VALUES ('profile_photo', ?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)");
        $stmt->execute([$dest]);
        jsonResponse(['success' => true, 'path' => $dest]);
    }
    jsonResponse(['success' => false, 'error' => 'Erreur upload.'], 500);
}

// ============================================
// ADMIN: UPLOAD CV
// ============================================
if ($action === 'upload_cv') {
    if (!isset($_FILES['cv'])) jsonResponse(['success' => false, 'error' => 'Aucun fichier.'], 400);
    $file = $_FILES['cv'];
    if ($file['type'] !== 'application/pdf') {
        jsonResponse(['success' => false, 'error' => 'Seul le format PDF est accepté.'], 400);
    }
    if ($file['size'] > 10 * 1024 * 1024) {
        jsonResponse(['success' => false, 'error' => 'Fichier trop grand (max 10MB).'], 400);
    }
    $dest = 'uploads/cvs/cv_' . time() . '.pdf';
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        $stmt = $db->prepare("INSERT INTO `settings` (`key`, `value`) VALUES ('cv_file', ?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)");
        $stmt->execute([$dest]);
        jsonResponse(['success' => true, 'path' => $dest]);
    }
    jsonResponse(['success' => false, 'error' => 'Erreur upload.'], 500);
}

// ============================================
// ADMIN: PROJECTS CRUD
// ============================================
if ($action === 'get_projects') {
    $rows = $db->query("SELECT * FROM projects ORDER BY sort_order ASC, id DESC")->fetchAll();
    jsonResponse(['success' => true, 'data' => $rows]);
}

if ($action === 'save_project') {
    $id          = intval($_POST['id'] ?? 0);
    $name        = trim($_POST['name'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $technologies = trim($_POST['technologies'] ?? '[]');
    $demo_url    = trim($_POST['demo_url'] ?? '');
    $status      = trim($_POST['status'] ?? 'published');

    if (!$name || !$category) jsonResponse(['success' => false, 'error' => 'Nom et catégorie requis.'], 400);

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $file = $_FILES['image'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'project_' . time() . '.' . $ext;
        $dest = 'uploads/projects/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            $image = $dest;
        }
    }

    if ($id > 0) {
        if ($image) {
            $stmt = $db->prepare("UPDATE projects SET name=?,category=?,description=?,technologies=?,image=?,demo_url=?,status=? WHERE id=?");
            $stmt->execute([$name, $category, $description, $technologies, $image, $demo_url, $status, $id]);
        } else {
            $stmt = $db->prepare("UPDATE projects SET name=?,category=?,description=?,technologies=?,demo_url=?,status=? WHERE id=?");
            $stmt->execute([$name, $category, $description, $technologies, $demo_url, $status, $id]);
        }
    } else {
        $stmt = $db->prepare("INSERT INTO projects (name,category,description,technologies,image,demo_url,status) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$name, $category, $description, $technologies, $image, $demo_url, $status]);
        $id = $db->lastInsertId();
    }
    jsonResponse(['success' => true, 'id' => $id]);
}

if ($action === 'delete_project') {
    $id = intval($_POST['id'] ?? 0);
    $db->prepare("DELETE FROM projects WHERE id=?")->execute([$id]);
    jsonResponse(['success' => true]);
}

// ============================================
// ADMIN: MESSAGES
// ============================================
if ($action === 'get_messages') {
    $rows = $db->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();
    jsonResponse(['success' => true, 'data' => $rows]);
}

if ($action === 'mark_read') {
    $id = intval($_POST['id'] ?? 0);
    $db->prepare("UPDATE messages SET is_read=1 WHERE id=?")->execute([$id]);
    jsonResponse(['success' => true]);
}

if ($action === 'delete_message') {
    $id = intval($_POST['id'] ?? 0);
    $db->prepare("DELETE FROM messages WHERE id=?")->execute([$id]);
    jsonResponse(['success' => true]);
}

// ============================================
// ADMIN: SKILLS
// ============================================
if ($action === 'get_skills') {
    $rows = $db->query("SELECT * FROM skills ORDER BY category, sort_order")->fetchAll();
    jsonResponse(['success' => true, 'data' => $rows]);
}

if ($action === 'save_skill') {
    $id       = intval($_POST['id'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $name     = trim($_POST['name'] ?? '');
    $level    = intval($_POST['level'] ?? 80);
    if (!$name || !$category) jsonResponse(['success' => false, 'error' => 'Champs requis.'], 400);
    if ($id > 0) {
        $db->prepare("UPDATE skills SET category=?,name=?,level=? WHERE id=?")->execute([$category, $name, $level, $id]);
    } else {
        $db->prepare("INSERT INTO skills (category,name,level) VALUES (?,?,?)")->execute([$category, $name, $level]);
    }
    jsonResponse(['success' => true]);
}

if ($action === 'delete_skill') {
    $db->prepare("DELETE FROM skills WHERE id=?")->execute([intval($_POST['id'] ?? 0)]);
    jsonResponse(['success' => true]);
}

jsonResponse(['success' => false, 'error' => 'Action inconnue.'], 400);
