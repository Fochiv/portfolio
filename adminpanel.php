<?php
require_once 'config.php';

// Handle logout redirect
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: adminpanel.php');
    exit;
}

$isLogged = isAdmin();
?>
<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ben FOCH | Admin</title>
    <link rel="icon" type="image/jpeg" href="assets/img/logo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ===== ADMIN SPECIFIC STYLES ===== */
        body { overflow: auto; }

        /* LOGIN PAGE */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-primary);
            position: relative;
            overflow: hidden;
        }
        .login-page::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 30% 40%, rgba(0,212,255,0.07) 0%, transparent 55%),
                        radial-gradient(ellipse at 70% 60%, rgba(124,58,237,0.07) 0%, transparent 55%);
        }
        .login-card {
            position: relative;
            z-index: 1;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-logo img {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            border: 2px solid var(--accent-cyan);
            object-fit: cover;
            margin-bottom: 1rem;
        }
        .login-logo h1 {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #00d4ff, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .login-logo p { color: var(--text-muted); font-size: 0.85rem; }
        .login-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            color: #ef4444;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            display: none;
        }
        .login-theme {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
        }

        /* ADMIN LAYOUT */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: var(--bg-card);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: transform 0.3s;
        }
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-header img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid var(--accent-cyan);
        }
        .sidebar-header .info { flex: 1; min-width: 0; }
        .sidebar-header .name {
            font-weight: 700;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-header .role {
            font-size: 0.75rem;
            color: var(--accent-cyan);
        }
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
        }
        .nav-section-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
            padding: 0.5rem 0.75rem;
            margin-top: 0.5rem;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.88rem;
            font-weight: 500;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            transition: all 0.2s;
            margin-bottom: 2px;
            position: relative;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(0,212,255,0.08);
            color: var(--accent-cyan);
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 25%;
            bottom: 25%;
            width: 3px;
            background: var(--accent-cyan);
            border-radius: 0 3px 3px 0;
        }
        .sidebar-link .badge {
            margin-left: auto;
            background: var(--accent-pink);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 10px;
        }
        .sidebar-link i { width: 18px; text-align: center; font-size: 0.95rem; }
        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid var(--border-color);
        }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #ef4444;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s;
            text-decoration: none;
        }
        .logout-btn:hover {
            background: rgba(239,68,68,0.15);
            border-color: #ef4444;
        }

        /* ADMIN MAIN */
        .admin-main {
            flex: 1;
            margin-left: 260px;
            background: var(--bg-primary);
            min-height: 100vh;
        }
        .admin-topbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            padding: 0 2rem;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .page-title {
            font-size: 1.1rem;
            font-weight: 700;
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* CONTENT */
        .admin-content {
            padding: 2rem;
        }

        /* PANELS */
        .panel { display: none; }
        .panel.active { display: block; }

        /* STATS BAR */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-widget {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s;
        }
        .stat-widget:hover {
            border-color: var(--accent-cyan);
            box-shadow: var(--shadow-cyan);
        }
        .stat-widget-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .icon-cyan { background: rgba(0,212,255,0.1); color: var(--accent-cyan); }
        .icon-pink { background: rgba(255,107,157,0.1); color: var(--accent-pink); }
        .icon-purple { background: rgba(124,58,237,0.1); color: #a78bfa; }
        .icon-green { background: rgba(34,197,94,0.1); color: #22c55e; }
        .stat-widget-val {
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1;
        }
        .stat-widget-label { font-size: 0.78rem; color: var(--text-muted); margin-top: 3px; }

        /* SECTION CARDS */
        .admin-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .admin-card-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .admin-card-title i { color: var(--accent-cyan); }

        /* TOGGLE */
        .toggle-wrapper {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .toggle {
            position: relative;
            width: 52px;
            height: 28px;
        }
        .toggle input { display: none; }
        .toggle-slider {
            position: absolute;
            inset: 0;
            background: var(--text-muted);
            border-radius: 14px;
            cursor: pointer;
            transition: 0.3s;
        }
        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            background: #fff;
            border-radius: 50%;
            top: 3px;
            left: 3px;
            transition: 0.3s;
        }
        .toggle input:checked + .toggle-slider { background: #22c55e; }
        .toggle input:checked + .toggle-slider::before { transform: translateX(24px); }

        /* UPLOAD ZONE */
        .upload-zone {
            border: 2px dashed var(--border-color);
            border-radius: 14px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-zone:hover {
            border-color: var(--accent-cyan);
            background: rgba(0,212,255,0.03);
        }
        .upload-zone i { font-size: 2rem; color: var(--text-muted); margin-bottom: 0.75rem; display: block; }
        .upload-zone p { color: var(--text-secondary); font-size: 0.88rem; }
        .upload-zone .file-types { font-size: 0.78rem; color: var(--text-muted); margin-top: 6px; }

        /* PHOTO PREVIEW */
        .photo-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-cyan);
            margin-bottom: 1rem;
        }

        /* TABLE */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }
        .admin-table th {
            text-align: left;
            padding: 10px 14px;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid var(--border-color);
        }
        .admin-table td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            color: var(--text-secondary);
            vertical-align: middle;
        }
        .admin-table tr:hover td { background: rgba(0,212,255,0.02); }
        .admin-table .status-badge {
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-published { background: rgba(34,197,94,0.1); color: #22c55e; border: 1px solid rgba(34,197,94,0.3); }
        .badge-draft { background: rgba(100,116,139,0.1); color: var(--text-muted); border: 1px solid rgba(100,116,139,0.3); }
        .badge-unread { background: rgba(0,212,255,0.1); color: var(--accent-cyan); border: 1px solid rgba(0,212,255,0.3); }

        /* ACTION BUTTONS */
        .btn-sm {
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            border: 1px solid var(--border-color);
            cursor: pointer;
            background: none;
            color: var(--text-secondary);
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-sm:hover { border-color: var(--accent-cyan); color: var(--accent-cyan); }
        .btn-sm.danger:hover { border-color: #ef4444; color: #ef4444; }
        .btn-sm.success { border-color: #22c55e; color: #22c55e; }

        /* MODAL */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            z-index: 9000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            width: 100%;
            max-width: 580px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 2rem;
        }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        .modal-header h3 { font-size: 1.1rem; font-weight: 700; }
        .modal-close {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 4px;
            transition: color 0.2s;
        }
        .modal-close:hover { color: var(--text-primary); }

        /* MESSAGE DETAIL */
        .msg-body {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 1rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.8;
            margin-top: 1rem;
        }

        /* SIDEBAR MOBILE */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.2rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .stats-bar { grid-template-columns: 1fr 1fr; }
            .sidebar-toggle { display: block; }
            .admin-content { padding: 1rem; }
        }
    </style>
</head>
<body>

<?php if (!$isLogged): ?>
<!-- ================================
     LOGIN PAGE
================================ -->
<div class="login-page">
    <button class="theme-toggle login-theme" onclick="toggleThemeAdmin()" title="Thème">
        <i class="fas fa-sun"></i>
    </button>
    <div class="login-card">
        <div class="login-logo">
            <img src="assets/img/logo.jpg" alt="Logo">
            <h1>Espace Admin</h1>
            <p>Tableau de bord de Ben FOCH</p>
        </div>
        <div class="login-error" id="login-error"></div>
        <form id="login-form">
            <div class="form-group">
                <label>Email</label>
                <input type="email" id="login-email" class="form-control" placeholder="aldofoch@gmail.com" required autocomplete="email">
            </div>
            <div class="form-group" style="position:relative">
                <label>Mot de passe</label>
                <input type="password" id="login-pwd" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                <button type="button" onclick="togglePwd()" style="position:absolute;right:12px;bottom:10px;background:none;border:none;color:var(--text-muted);cursor:pointer;">
                    <i class="fas fa-eye" id="pwd-eye"></i>
                </button>
            </div>
            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;margin-top:0.5rem;">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>
        <div style="text-align:center;margin-top:1.5rem">
            <a href="index.php" style="color:var(--text-muted);font-size:0.82rem;text-decoration:none;">
                <i class="fas fa-arrow-left"></i> Retour au portfolio
            </a>
        </div>
    </div>
</div>

<?php else: ?>
<!-- ================================
     ADMIN DASHBOARD
================================ -->
<?php
    require_once 'db_init.php';
    $db2 = getDB();
    function getSetting2($key) {
        global $db2;
        $stmt = $db2->prepare("SELECT value FROM settings WHERE key = ?");
        $stmt->execute([$key]);
        return $stmt->fetchColumn() ?: '';
    }
    $profile_photo = getSetting2('profile_photo');
    $availability = getSetting2('availability');
    $cv_file = getSetting2('cv_file') ?: 'uploads/cvs/cv.pdf';
    $total_projects = $db2->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    $total_messages = $db2->query("SELECT COUNT(*) FROM messages")->fetchColumn();
    $unread_messages = $db2->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
    $total_skills = $db2->query("SELECT COUNT(*) FROM skills")->fetchColumn();
?>
<div class="admin-layout">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="assets/img/logo.jpg" alt="Logo">
            <div class="info">
                <div class="name">Ben FOCH</div>
                <div class="role">Administrateur</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-label">Navigation</div>
            <button class="sidebar-link active" onclick="showPanel('dashboard', this)">
                <i class="fas fa-th-large"></i> Tableau de bord
            </button>
            <button class="sidebar-link" onclick="showPanel('profile', this)">
                <i class="fas fa-user-circle"></i> Mon Profil
            </button>
            <div class="nav-section-label">Contenu</div>
            <button class="sidebar-link" onclick="showPanel('projects', this)">
                <i class="fas fa-briefcase"></i> Projets
            </button>
            <button class="sidebar-link" onclick="showPanel('skills', this)">
                <i class="fas fa-code"></i> Compétences
            </button>
            <div class="nav-section-label">Communication</div>
            <button class="sidebar-link" onclick="showPanel('messages', this)" id="msg-btn">
                <i class="fas fa-envelope"></i> Messages
                <?php if ($unread_messages > 0): ?>
                <span class="badge"><?= $unread_messages ?></span>
                <?php endif; ?>
            </button>
            <div class="nav-section-label">Système</div>
            <button class="sidebar-link" onclick="showPanel('settings', this)">
                <i class="fas fa-cog"></i> Paramètres
            </button>
            <a href="index.php" target="_blank" class="sidebar-link">
                <i class="fas fa-external-link-alt"></i> Voir le site
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="adminpanel.php?logout=1" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="admin-main">
        <header class="admin-topbar">
            <div style="display:flex;align-items:center;gap:12px">
                <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="page-title" id="page-title">Tableau de bord</span>
            </div>
            <div class="topbar-actions">
                <button class="theme-toggle" onclick="toggleThemeAdmin()"><i class="fas fa-sun" id="admin-theme-icon"></i></button>
            </div>
        </header>

        <div class="admin-content">

            <!-- ======================== DASHBOARD ======================== -->
            <div class="panel active" id="panel-dashboard">
                <div class="stats-bar">
                    <div class="stat-widget">
                        <div class="stat-widget-icon icon-cyan"><i class="fas fa-briefcase"></i></div>
                        <div>
                            <div class="stat-widget-val"><?= $total_projects ?></div>
                            <div class="stat-widget-label">Projets</div>
                        </div>
                    </div>
                    <div class="stat-widget">
                        <div class="stat-widget-icon icon-pink"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div class="stat-widget-val"><?= $total_messages ?></div>
                            <div class="stat-widget-label">Messages</div>
                        </div>
                    </div>
                    <div class="stat-widget">
                        <div class="stat-widget-icon icon-purple"><i class="fas fa-code"></i></div>
                        <div>
                            <div class="stat-widget-val"><?= $total_skills ?></div>
                            <div class="stat-widget-label">Compétences</div>
                        </div>
                    </div>
                    <div class="stat-widget">
                        <div class="stat-widget-icon icon-green"><i class="fas fa-bell"></i></div>
                        <div>
                            <div class="stat-widget-val"><?= $unread_messages ?></div>
                            <div class="stat-widget-label">Non lus</div>
                        </div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
                    <div class="admin-card">
                        <div class="admin-card-title"><i class="fas fa-circle-check"></i> Statut rapide</div>
                        <div class="toggle-wrapper" style="margin-bottom:1rem">
                            <label class="toggle">
                                <input type="checkbox" id="avail-toggle-dash" <?= $availability ? 'checked' : '' ?> onchange="updateAvailability(this.checked)">
                                <span class="toggle-slider"></span>
                            </label>
                            <span id="avail-label-dash" style="font-size:0.9rem;font-weight:600;color:<?= $availability ? '#22c55e' : '#ef4444' ?>">
                                <?= $availability ? '✓ Disponible' : '✗ Indisponible' ?>
                            </span>
                        </div>
                        <p style="color:var(--text-muted);font-size:0.82rem">Ce statut s'affiche en temps réel sur votre portfolio public.</p>
                    </div>
                    <div class="admin-card">
                        <div class="admin-card-title"><i class="fas fa-link"></i> Accès rapide</div>
                        <div style="display:flex;flex-direction:column;gap:8px">
                            <a href="index.php" target="_blank" class="btn-sm"><i class="fas fa-eye"></i> Voir le portfolio</a>
                            <button class="btn-sm" onclick="showPanel('profile', document.querySelector('.sidebar-link:nth-child(2))'))"><i class="fas fa-user"></i> Modifier le profil</button>
                            <button class="btn-sm" onclick="showPanel('projects', null)"><i class="fas fa-plus"></i> Ajouter un projet</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================== PROFILE ======================== -->
            <div class="panel" id="panel-profile">
                <div class="admin-card">
                    <div class="admin-card-title"><i class="fas fa-user-circle"></i> Photo de profil</div>
                    <div style="text-align:center;margin-bottom:1.5rem">
                        <?php if ($profile_photo && file_exists($profile_photo)): ?>
                        <img src="<?= htmlspecialchars($profile_photo) ?>" class="photo-preview" id="photo-preview" alt="Photo de profil">
                        <?php else: ?>
                        <div id="photo-preview" style="width:100px;height:100px;border-radius:50%;background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;border:2px solid var(--accent-cyan);font-size:2.5rem;color:var(--accent-cyan)"><i class="fas fa-user"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="upload-zone" onclick="document.getElementById('photo-input').click()">
                        <i class="fas fa-camera"></i>
                        <p>Cliquez pour uploader votre photo de profil</p>
                        <p class="file-types">JPG, PNG — Max 5MB</p>
                    </div>
                    <input type="file" id="photo-input" accept="image/jpeg,image/png" style="display:none" onchange="uploadPhoto(this)">
                    <div id="photo-status" style="margin-top:0.75rem;font-size:0.85rem;color:var(--text-muted)"></div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-title"><i class="fas fa-toggle-on"></i> Statut de disponibilité</div>
                    <div class="toggle-wrapper">
                        <label class="toggle">
                            <input type="checkbox" id="avail-toggle" <?= $availability ? 'checked' : '' ?> onchange="updateAvailability(this.checked)">
                            <span class="toggle-slider"></span>
                        </label>
                        <div>
                            <div id="avail-label" style="font-weight:700;color:<?= $availability ? '#22c55e' : '#ef4444' ?>">
                                <?= $availability ? '✓ Disponible pour de nouvelles missions' : '✗ Indisponible actuellement' ?>
                            </div>
                            <div style="font-size:0.8rem;color:var(--text-muted)">Visible en temps réel sur le portfolio</div>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-title"><i class="fas fa-file-pdf"></i> CV (PDF)</div>
                    <?php if ($cv_file && file_exists($cv_file)): ?>
                    <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--bg-secondary);border-radius:12px;margin-bottom:1rem">
                        <i class="fas fa-file-pdf" style="font-size:1.5rem;color:#ef4444"></i>
                        <div style="flex:1;min-width:0">
                            <div style="font-weight:600;font-size:0.88rem">CV actuel</div>
                            <div style="font-size:0.78rem;color:var(--text-muted)"><?= basename($cv_file) ?></div>
                        </div>
                        <a href="<?= htmlspecialchars($cv_file) ?>" target="_blank" class="btn-sm"><i class="fas fa-eye"></i> Voir</a>
                        <a href="<?= htmlspecialchars($cv_file) ?>" download class="btn-sm"><i class="fas fa-download"></i></a>
                    </div>
                    <?php endif; ?>
                    <div class="upload-zone" onclick="document.getElementById('cv-input').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Uploader un nouveau CV</p>
                        <p class="file-types">PDF uniquement — Max 10MB</p>
                    </div>
                    <input type="file" id="cv-input" accept="application/pdf" style="display:none" onchange="uploadCV(this)">
                    <div id="cv-status" style="margin-top:0.75rem;font-size:0.85rem;color:var(--text-muted)"></div>
                </div>
            </div>

            <!-- ======================== PROJECTS ======================== -->
            <div class="panel" id="panel-projects">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
                    <h2 style="font-size:1.1rem;font-weight:700">Gestion des Projets</h2>
                    <button class="btn-primary" onclick="openProjectModal()">
                        <i class="fas fa-plus"></i> Nouveau projet
                    </button>
                </div>
                <div class="admin-card" style="padding:0;overflow:hidden">
                    <div style="overflow-x:auto">
                        <table class="admin-table" id="projects-table">
                            <thead>
                                <tr>
                                    <th>Projet</th>
                                    <th>Catégorie</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="projects-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ======================== SKILLS ======================== -->
            <div class="panel" id="panel-skills">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
                    <h2 style="font-size:1.1rem;font-weight:700">Gestion des Compétences</h2>
                    <button class="btn-primary" onclick="openSkillModal()">
                        <i class="fas fa-plus"></i> Nouvelle compétence
                    </button>
                </div>
                <div class="admin-card" style="padding:0;overflow:hidden">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Nom</th><th>Catégorie</th><th>Actions</th></tr>
                        </thead>
                        <tbody id="skills-tbody"></tbody>
                    </table>
                </div>
            </div>

            <!-- ======================== MESSAGES ======================== -->
            <div class="panel" id="panel-messages">
                <div class="admin-card" style="padding:0;overflow:hidden">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Nom</th><th>Email</th><th>Sujet</th><th>Date</th><th>Actions</th></tr>
                        </thead>
                        <tbody id="messages-tbody"></tbody>
                    </table>
                </div>
            </div>

            <!-- ======================== SETTINGS ======================== -->
            <div class="panel" id="panel-settings">
                <div class="admin-card">
                    <div class="admin-card-title"><i class="fas fa-address-card"></i> Informations de contact</div>
                    <form onsubmit="saveContactSettings(event)" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <div class="form-group">
                            <label>WhatsApp</label>
                            <input type="text" id="s-whatsapp" class="form-control" value="<?= htmlspecialchars(getSetting2('whatsapp')) ?>">
                        </div>
                        <div class="form-group">
                            <label>Telegram</label>
                            <input type="text" id="s-telegram" class="form-control" value="<?= htmlspecialchars(getSetting2('telegram')) ?>">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="s-email" class="form-control" value="<?= htmlspecialchars(getSetting2('email')) ?>">
                        </div>
                        <div class="form-group">
                            <label>LinkedIn URL</label>
                            <input type="text" id="s-linkedin" class="form-control" value="<?= htmlspecialchars(getSetting2('linkedin')) ?>">
                        </div>
                        <div class="form-group">
                            <label>GitHub URL</label>
                            <input type="text" id="s-github" class="form-control" value="<?= htmlspecialchars(getSetting2('github')) ?>">
                        </div>
                        <div class="form-group">
                            <label>Localisation</label>
                            <input type="text" id="s-location" class="form-control" value="<?= htmlspecialchars(getSetting2('location')) ?>">
                        </div>
                        <div style="grid-column:1/-1">
                            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Sauvegarder</button>
                        </div>
                    </form>
                </div>

                <div class="admin-card">
                    <div class="admin-card-title"><i class="fas fa-lock"></i> Changer le mot de passe</div>
                    <form onsubmit="changePwd(event)" style="max-width:400px">
                        <div class="form-group">
                            <label>Mot de passe actuel</label>
                            <input type="password" id="s-cur-pwd" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Nouveau mot de passe</label>
                            <input type="password" id="s-new-pwd" class="form-control" required minlength="6">
                        </div>
                        <button type="submit" class="btn-primary"><i class="fas fa-key"></i> Modifier</button>
                    </form>
                </div>
            </div>

        </div><!-- /admin-content -->
    </main>
</div>

<!-- ======================== MODAL: PROJECT ======================== -->
<div class="modal-overlay" id="project-modal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="project-modal-title">Nouveau projet</h3>
            <button class="modal-close" onclick="closeModal('project-modal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="project-form" enctype="multipart/form-data" onsubmit="saveProject(event)">
            <input type="hidden" id="proj-id" name="id" value="0">
            <div class="form-group">
                <label>Nom du projet *</label>
                <input type="text" id="proj-name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Catégorie *</label>
                <select id="proj-cat" name="category" class="form-control">
                    <option value="Développement Web">Développement Web</option>
                    <option value="Design Graphique">Design Graphique</option>
                    <option value="Projets Réseaux">Projets Réseaux</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description courte</label>
                <textarea id="proj-desc" name="description" class="form-control" rows="3" maxlength="200"></textarea>
            </div>
            <div class="form-group">
                <label>Technologies (séparées par des virgules)</label>
                <input type="text" id="proj-techs" class="form-control" placeholder="PHP, MySQL, Bootstrap">
            </div>
            <div class="form-group">
                <label>Image du projet</label>
                <div class="upload-zone" onclick="document.getElementById('proj-img-input').click()">
                    <i class="fas fa-image"></i>
                    <p>Cliquer pour uploader</p>
                </div>
                <input type="file" id="proj-img-input" name="image" accept="image/*" style="display:none">
                <div id="proj-img-name" style="font-size:0.8rem;color:var(--text-muted);margin-top:6px"></div>
            </div>
            <div class="form-group">
                <label>Lien démo (optionnel)</label>
                <input type="url" id="proj-demo" name="demo_url" class="form-control" placeholder="https://...">
            </div>
            <div class="form-group">
                <label>Statut</label>
                <select id="proj-status" name="status" class="form-control">
                    <option value="published">Publié</option>
                    <option value="draft">Brouillon</option>
                </select>
            </div>
            <div style="display:flex;gap:1rem;margin-top:0.5rem">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Sauvegarder</button>
                <button type="button" class="btn-outline" onclick="closeModal('project-modal')">Annuler</button>
            </div>
        </form>
    </div>
</div>

<!-- ======================== MODAL: SKILL ======================== -->
<div class="modal-overlay" id="skill-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Compétence</h3>
            <button class="modal-close" onclick="closeModal('skill-modal')"><i class="fas fa-times"></i></button>
        </div>
        <form onsubmit="saveSkill(event)">
            <input type="hidden" id="skill-id" value="0">
            <div class="form-group">
                <label>Catégorie</label>
                <input type="text" id="skill-cat" class="form-control" placeholder="Ex: Languages, Frontend..." required>
            </div>
            <div class="form-group">
                <label>Nom de la compétence</label>
                <input type="text" id="skill-name" class="form-control" required>
            </div>
            <div style="display:flex;gap:1rem;margin-top:0.5rem">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Sauvegarder</button>
                <button type="button" class="btn-outline" onclick="closeModal('skill-modal')">Annuler</button>
            </div>
        </form>
    </div>
</div>

<!-- ======================== MODAL: MESSAGE ======================== -->
<div class="modal-overlay" id="message-modal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="msg-modal-from">Message</h3>
            <button class="modal-close" onclick="closeModal('message-modal')"><i class="fas fa-times"></i></button>
        </div>
        <div id="msg-modal-meta" style="font-size:0.82rem;color:var(--text-muted);margin-bottom:0.5rem"></div>
        <div style="font-weight:700;margin-bottom:0.25rem" id="msg-modal-subject"></div>
        <div class="msg-body" id="msg-modal-body"></div>
        <div style="margin-top:1rem;display:flex;gap:1rem">
            <a id="msg-reply-btn" href="#" class="btn-primary" style="font-size:0.85rem;padding:8px 18px">
                <i class="fas fa-reply"></i> Répondre par email
            </a>
            <button class="btn-sm danger" onclick="deleteCurrentMsg()"><i class="fas fa-trash"></i> Supprimer</button>
        </div>
    </div>
</div>

<?php endif; ?>

<script>
// ============================================
// THEME
// ============================================
const savedTheme = localStorage.getItem('theme') || 'dark';
document.documentElement.setAttribute('data-theme', savedTheme);

function toggleThemeAdmin() {
    const cur = document.documentElement.getAttribute('data-theme');
    const next = cur === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    const icon = document.getElementById('admin-theme-icon');
    if (icon) icon.className = next === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
}

document.addEventListener('DOMContentLoaded', () => {
    const icon = document.getElementById('admin-theme-icon');
    if (icon) icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
});

<?php if ($isLogged): ?>

// ============================================
// PANELS
// ============================================
const panelTitles = {
    dashboard: 'Tableau de bord',
    profile: 'Mon Profil',
    projects: 'Projets',
    skills: 'Compétences',
    messages: 'Messages',
    settings: 'Paramètres'
};

function showPanel(name, btn) {
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.sidebar-link').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + name).classList.add('active');
    document.getElementById('page-title').textContent = panelTitles[name] || name;
    if (btn) btn.classList.add('active');
    // Lazy load data
    if (name === 'projects') loadProjects();
    if (name === 'skills') loadSkills();
    if (name === 'messages') loadMessages();
    // Close mobile sidebar
    document.getElementById('sidebar').classList.remove('open');
}

// ============================================
// API HELPER
// ============================================
async function api(data) {
    const fd = new FormData();
    for (const [k, v] of Object.entries(data)) fd.append(k, v);
    const res = await fetch('api.php', { method: 'POST', body: fd });
    return res.json();
}

// ============================================
// AVAILABILITY
// ============================================
async function updateAvailability(checked) {
    const val = checked ? '1' : '0';
    await api({ action: 'update_setting', key: 'availability', value: val });
    const label = document.getElementById('avail-label');
    const labelDash = document.getElementById('avail-label-dash');
    const text = checked ? '✓ Disponible pour de nouvelles missions' : '✗ Indisponible actuellement';
    const textDash = checked ? '✓ Disponible' : '✗ Indisponible';
    const color = checked ? '#22c55e' : '#ef4444';
    if (label) { label.textContent = text; label.style.color = color; }
    if (labelDash) { labelDash.textContent = textDash; labelDash.style.color = color; }
    // Sync both toggles
    const t1 = document.getElementById('avail-toggle');
    const t2 = document.getElementById('avail-toggle-dash');
    if (t1) t1.checked = checked;
    if (t2) t2.checked = checked;
    showAdminToast(checked ? '✓ Disponible activé' : '✗ Indisponible activé', 'success');
}

// ============================================
// UPLOAD PHOTO
// ============================================
async function uploadPhoto(input) {
    const status = document.getElementById('photo-status');
    status.textContent = 'Upload en cours...';
    const fd = new FormData();
    fd.append('action', 'upload_photo');
    fd.append('photo', input.files[0]);
    const res = await fetch('api.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) {
        status.textContent = '✓ Photo mise à jour !';
        status.style.color = '#22c55e';
        const prev = document.getElementById('photo-preview');
        if (prev.tagName === 'IMG') {
            prev.src = json.path + '?t=' + Date.now();
        } else {
            const img = document.createElement('img');
            img.src = json.path + '?t=' + Date.now();
            img.className = 'photo-preview';
            img.id = 'photo-preview';
            prev.replaceWith(img);
        }
    } else {
        status.textContent = '✗ ' + (json.error || 'Erreur');
        status.style.color = '#ef4444';
    }
}

// ============================================
// UPLOAD CV
// ============================================
async function uploadCV(input) {
    const status = document.getElementById('cv-status');
    status.textContent = 'Upload en cours...';
    const fd = new FormData();
    fd.append('action', 'upload_cv');
    fd.append('cv', input.files[0]);
    const res = await fetch('api.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) {
        status.textContent = '✓ CV mis à jour ! Rechargez la page pour voir le nouveau fichier.';
        status.style.color = '#22c55e';
    } else {
        status.textContent = '✗ ' + (json.error || 'Erreur');
        status.style.color = '#ef4444';
    }
}

// ============================================
// PROJECTS
// ============================================
async function loadProjects() {
    const json = await api({ action: 'get_projects' });
    const tbody = document.getElementById('projects-tbody');
    tbody.innerHTML = '';
    if (!json.data || json.data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem">Aucun projet</td></tr>';
        return;
    }
    json.data.forEach(p => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><strong>${escHtml(p.name)}</strong></td>
            <td>${escHtml(p.category)}</td>
            <td><span class="status-badge badge-${p.status}">${p.status === 'published' ? 'Publié' : 'Brouillon'}</span></td>
            <td style="display:flex;gap:6px;flex-wrap:wrap">
                <button class="btn-sm" onclick='editProject(${JSON.stringify(p)})'><i class="fas fa-edit"></i> Modifier</button>
                <button class="btn-sm danger" onclick="deleteProject(${p.id})"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function openProjectModal(p = null) {
    document.getElementById('proj-id').value = p ? p.id : 0;
    document.getElementById('proj-name').value = p ? p.name : '';
    document.getElementById('proj-cat').value = p ? p.category : 'Développement Web';
    document.getElementById('proj-desc').value = p ? p.description : '';
    document.getElementById('proj-demo').value = p ? (p.demo_url || '') : '';
    document.getElementById('proj-status').value = p ? p.status : 'published';
    document.getElementById('proj-modal-title').textContent = p ? 'Modifier le projet' : 'Nouveau projet';
    // Technologies
    let techs = '';
    if (p && p.technologies) {
        try { techs = JSON.parse(p.technologies).join(', '); } catch { techs = p.technologies; }
    }
    document.getElementById('proj-techs').value = techs;
    document.getElementById('proj-img-name').textContent = p && p.image ? 'Image actuelle: ' + p.image : '';
    openModal('project-modal');
}

function editProject(p) { openProjectModal(p); }

async function saveProject(e) {
    e.preventDefault();
    const fd = new FormData();
    fd.append('action', 'save_project');
    fd.append('id', document.getElementById('proj-id').value);
    fd.append('name', document.getElementById('proj-name').value);
    fd.append('category', document.getElementById('proj-cat').value);
    fd.append('description', document.getElementById('proj-desc').value);
    const techsRaw = document.getElementById('proj-techs').value;
    const techs = techsRaw ? JSON.stringify(techsRaw.split(',').map(t => t.trim()).filter(Boolean)) : '[]';
    fd.append('technologies', techs);
    fd.append('demo_url', document.getElementById('proj-demo').value);
    fd.append('status', document.getElementById('proj-status').value);
    const imgFile = document.getElementById('proj-img-input').files[0];
    if (imgFile) fd.append('image', imgFile);

    const res = await fetch('api.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) {
        closeModal('project-modal');
        loadProjects();
        showAdminToast('✓ Projet sauvegardé !', 'success');
    } else {
        showAdminToast('✗ ' + (json.error || 'Erreur'), 'error');
    }
}

async function deleteProject(id) {
    if (!confirm('Supprimer ce projet ?')) return;
    const json = await api({ action: 'delete_project', id });
    if (json.success) { loadProjects(); showAdminToast('Projet supprimé.', 'success'); }
}

document.getElementById('proj-img-input')?.addEventListener('change', function() {
    document.getElementById('proj-img-name').textContent = this.files[0] ? this.files[0].name : '';
});

// ============================================
// SKILLS
// ============================================
async function loadSkills() {
    const json = await api({ action: 'get_skills' });
    const tbody = document.getElementById('skills-tbody');
    tbody.innerHTML = '';
    if (!json.data || json.data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:2rem">Aucune compétence</td></tr>';
        return;
    }
    json.data.forEach(s => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><strong>${escHtml(s.name)}</strong></td>
            <td>${escHtml(s.category)}</td>
            <td style="display:flex;gap:6px">
                <button class="btn-sm" onclick='openSkillModal(${JSON.stringify(s)})'><i class="fas fa-edit"></i></button>
                <button class="btn-sm danger" onclick="deleteSkill(${s.id})"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function openSkillModal(s = null) {
    document.getElementById('skill-id').value = s ? s.id : 0;
    document.getElementById('skill-cat').value = s ? s.category : '';
    document.getElementById('skill-name').value = s ? s.name : '';
    openModal('skill-modal');
}

async function saveSkill(e) {
    e.preventDefault();
    const json = await api({
        action: 'save_skill',
        id: document.getElementById('skill-id').value,
        category: document.getElementById('skill-cat').value,
        name: document.getElementById('skill-name').value,
        level: 80
    });
    if (json.success) { closeModal('skill-modal'); loadSkills(); showAdminToast('✓ Compétence sauvegardée !', 'success'); }
    else showAdminToast('✗ Erreur', 'error');
}

async function deleteSkill(id) {
    if (!confirm('Supprimer cette compétence ?')) return;
    const json = await api({ action: 'delete_skill', id });
    if (json.success) { loadSkills(); showAdminToast('Compétence supprimée.', 'success'); }
}

// ============================================
// MESSAGES
// ============================================
let currentMsgId = null;

async function loadMessages() {
    const json = await api({ action: 'get_messages' });
    const tbody = document.getElementById('messages-tbody');
    tbody.innerHTML = '';
    if (!json.data || json.data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem">Aucun message</td></tr>';
        return;
    }
    json.data.forEach(m => {
        const tr = document.createElement('tr');
        const date = new Date(m.created_at).toLocaleDateString('fr-FR');
        tr.innerHTML = `
            <td><strong>${escHtml(m.name)}</strong>${m.is_read == 0 ? '<span class="status-badge badge-unread" style="margin-left:8px">Nouveau</span>' : ''}</td>
            <td>${escHtml(m.email)}</td>
            <td>${escHtml(m.subject || '(Sans sujet)')}</td>
            <td>${date}</td>
            <td style="display:flex;gap:6px">
                <button class="btn-sm" onclick='viewMessage(${JSON.stringify(m)})'><i class="fas fa-eye"></i></button>
                <button class="btn-sm danger" onclick="deleteMsg(${m.id})"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

async function viewMessage(m) {
    currentMsgId = m.id;
    document.getElementById('msg-modal-from').textContent = 'De : ' + m.name;
    document.getElementById('msg-modal-meta').textContent = m.email + ' — ' + new Date(m.created_at).toLocaleString('fr-FR');
    document.getElementById('msg-modal-subject').textContent = m.subject || '(Sans sujet)';
    document.getElementById('msg-modal-body').textContent = m.message;
    document.getElementById('msg-reply-btn').href = 'mailto:' + m.email + '?subject=Re: ' + (m.subject || '');
    openModal('message-modal');
    if (!m.is_read) { await api({ action: 'mark_read', id: m.id }); loadMessages(); }
}

async function deleteMsg(id) {
    if (!confirm('Supprimer ce message ?')) return;
    await api({ action: 'delete_message', id });
    loadMessages();
    showAdminToast('Message supprimé.', 'success');
}

async function deleteCurrentMsg() {
    if (!currentMsgId || !confirm('Supprimer ce message ?')) return;
    await api({ action: 'delete_message', id: currentMsgId });
    closeModal('message-modal');
    loadMessages();
    showAdminToast('Message supprimé.', 'success');
}

// ============================================
// SETTINGS
// ============================================
async function saveContactSettings(e) {
    e.preventDefault();
    const fields = [
        { key: 'whatsapp', id: 's-whatsapp' },
        { key: 'telegram', id: 's-telegram' },
        { key: 'email', id: 's-email' },
        { key: 'linkedin', id: 's-linkedin' },
        { key: 'github', id: 's-github' },
        { key: 'location', id: 's-location' },
    ];
    for (const f of fields) {
        await api({ action: 'update_setting', key: f.key, value: document.getElementById(f.id).value });
    }
    showAdminToast('✓ Paramètres sauvegardés !', 'success');
}

async function changePwd(e) {
    e.preventDefault();
    const json = await api({
        action: 'change_password',
        current_password: document.getElementById('s-cur-pwd').value,
        new_password: document.getElementById('s-new-pwd').value
    });
    if (json.success) {
        showAdminToast('✓ Mot de passe modifié !', 'success');
        document.getElementById('s-cur-pwd').value = '';
        document.getElementById('s-new-pwd').value = '';
    } else {
        showAdminToast('✗ ' + (json.error || 'Erreur'), 'error');
    }
}

// ============================================
// MODALS
// ============================================
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); });
});

// ============================================
// TOAST
// ============================================
function showAdminToast(msg, type = 'success') {
    let t = document.getElementById('admin-toast');
    if (!t) {
        t = document.createElement('div');
        t.id = 'admin-toast';
        t.className = 'toast';
        document.body.appendChild(t);
    }
    t.textContent = msg;
    t.className = `toast ${type}`;
    setTimeout(() => t.classList.add('show'), 10);
    setTimeout(() => t.classList.remove('show'), 3500);
}

// ============================================
// UTILS
// ============================================
function escHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

<?php else: ?>
// ============================================
// LOGIN
// ============================================
document.getElementById('login-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const errEl = document.getElementById('login-error');
    const fd = new FormData();
    fd.append('action', 'admin_login');
    fd.append('email', document.getElementById('login-email').value);
    fd.append('password', document.getElementById('login-pwd').value);
    const res = await fetch('api.php', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) {
        window.location.reload();
    } else {
        errEl.textContent = json.error || 'Identifiants incorrects.';
        errEl.style.display = 'block';
    }
});

function togglePwd() {
    const input = document.getElementById('login-pwd');
    const icon = document.getElementById('pwd-eye');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
<?php endif; ?>
</script>
</body>
</html>
