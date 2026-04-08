<?php
require_once 'config.php';
require_once 'db_init.php';

$db = getDB();

// Get settings
function getSetting($key) {
    global $db;
    $stmt = $db->prepare("SELECT value FROM settings WHERE key = ?");
    $stmt->execute([$key]);
    return $stmt->fetchColumn() ?: '';
}

$availability = getSetting('availability');
$profile_photo = getSetting('profile_photo');
$cv_file = getSetting('cv_file') ?: 'uploads/cvs/cv.pdf';

// Get data
$projects = $db->query("SELECT * FROM projects WHERE status='published' ORDER BY sort_order ASC, id DESC")->fetchAll();
$skills_raw = $db->query("SELECT * FROM skills ORDER BY category, sort_order")->fetchAll();
$services = $db->query("SELECT * FROM services ORDER BY sort_order")->fetchAll();
$experiences_work = $db->query("SELECT * FROM experiences WHERE type='work' ORDER BY sort_order ASC")->fetchAll();
$experiences_edu = $db->query("SELECT * FROM experiences WHERE type='education' ORDER BY sort_order ASC")->fetchAll();
$testimonials = $db->query("SELECT * FROM testimonials ORDER BY sort_order ASC")->fetchAll();

// Group skills by category
$skills = [];
foreach ($skills_raw as $s) {
    $skills[$s['category']][] = $s;
}
?>
<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ben FOCH - Professionnel IT, Développeur Web et Designer UI/UX basé à Dschang, Cameroun.">
    <title>Ben FOCH | Portfolio</title>
    <link rel="icon" type="image/jpeg" href="assets/img/logo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container">
        <a href="#hero" class="navbar-logo">
            <img src="assets/img/logo.jpg" alt="Logo Ben FOCH">
            <span>Ben FOCH</span>
        </a>
        <ul class="nav-links">
            <li><a href="#hero" data-i18n="nav_home">Accueil</a></li>
            <li><a href="#about" data-i18n="nav_about">À propos</a></li>
            <li><a href="#skills" data-i18n="nav_skills">Compétences</a></li>
            <li><a href="#portfolio" data-i18n="nav_portfolio">Portfolio</a></li>
            <li><a href="#services" data-i18n="nav_services">Services</a></li>
            <li><a href="#experience" data-i18n="nav_experience">Expérience</a></li>
            <li><a href="#contact" data-i18n="nav_contact">Contact</a></li>
        </ul>
        <div class="nav-controls">
            <div class="lang-switcher">
                <button class="lang-btn active" data-lang="fr" onclick="setLang('fr')">FR</button>
                <span style="color:var(--text-muted)">|</span>
                <button class="lang-btn" data-lang="en" onclick="setLang('en')">EN</button>
            </div>
            <button class="theme-toggle" onclick="toggleTheme()" title="Changer le thème">
                <i class="fas fa-sun"></i>
            </button>
        </div>
        <!-- Controls always visible on mobile -->
        <div class="nav-controls-mobile">
            <div class="lang-switcher">
                <button class="lang-btn active" data-lang="fr" onclick="setLang('fr')">FR</button>
                <span style="color:var(--text-muted)">|</span>
                <button class="lang-btn" data-lang="en" onclick="setLang('en')">EN</button>
            </div>
            <button class="theme-toggle" onclick="toggleTheme()" title="Changer le thème">
                <i class="fas fa-sun" class="mobile-theme-icon"></i>
            </button>
        </div>
        <button class="hamburger" onclick="toggleMobileMenu()" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<!-- MOBILE MENU -->
<div class="mobile-menu" id="mobile-menu">
    <a href="#hero" data-i18n="nav_home" onclick="toggleMobileMenu()">Accueil</a>
    <a href="#about" data-i18n="nav_about" onclick="toggleMobileMenu()">À propos</a>
    <a href="#skills" data-i18n="nav_skills" onclick="toggleMobileMenu()">Compétences</a>
    <a href="#portfolio" data-i18n="nav_portfolio" onclick="toggleMobileMenu()">Portfolio</a>
    <a href="#services" data-i18n="nav_services" onclick="toggleMobileMenu()">Services</a>
    <a href="#experience" data-i18n="nav_experience" onclick="toggleMobileMenu()">Expérience</a>
    <a href="#contact" data-i18n="nav_contact" onclick="toggleMobileMenu()">Contact</a>
</div>

<!-- HERO -->
<section id="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>
    <div class="hero-content">
        <div class="hero-text">
            <div class="availability-badge">
                <span class="availability-dot <?= $availability ? '' : 'unavailable' ?>"></span>
                <span class="availability-text <?= $availability ? '' : 'unavailable' ?>" id="avail-text">
                    <?= $availability ? '✓ Disponible' : '✗ Indisponible' ?>
                </span>
            </div>
            <p class="hero-greeting" data-i18n="hero_greeting">Bonjour, je suis</p>
            <h1 class="hero-name">Ben FOCH</h1>
            <p class="hero-title typing-cursor"><span id="typing-text"></span></p>
            <div class="hero-cta">
                <a href="<?= htmlspecialchars($cv_file) ?>" class="btn-primary" download>
                    <i class="fas fa-download"></i>
                    <span data-i18n="btn_cv">Télécharger mon CV</span>
                </a>
                <a href="#contact" class="btn-outline">
                    <i class="fas fa-envelope"></i>
                    <span data-i18n="btn_contact">Me contacter</span>
                </a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">3+</div>
                    <div class="stat-label" data-i18n="stat_years">Ans d'expérience</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= count($projects) ?>+</div>
                    <div class="stat-label" data-i18n="stat_projects">Projets réalisés</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">20+</div>
                    <div class="stat-label" data-i18n="stat_clients">Clients satisfaits</div>
                </div>
            </div>
        </div>
        <div class="hero-image">
            <div class="profile-frame">
                <?php if ($profile_photo && file_exists($profile_photo)): ?>
                    <img src="<?= htmlspecialchars($profile_photo) ?>" alt="Ben FOCH" class="profile-img">
                <?php else: ?>
                    <div class="profile-placeholder"><i class="fas fa-user"></i></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT -->
<section id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-text animate-on-scroll">
                <p class="section-label" data-i18n="section_about">À PROPOS</p>
                <h2 class="section-title" data-i18n="title_about">Qui suis-je ?</h2>
                <p data-i18n="about_p1">Je suis Ben FOCH SALAOUDINE, professionnel IT et développeur web basé à Dschang, Cameroun. Passionné par la technologie et le design, j'accompagne mes clients dans leur transformation numérique.</p>
                <p data-i18n="about_p2">Avec plusieurs années d'expérience en développement web, design UI/UX et administration réseau, je propose des solutions complètes adaptées aux besoins de chaque projet.</p>
                <div class="about-info-grid" style="margin-top:1.5rem">
                    <div class="info-item"><i class="fas fa-map-marker-alt"></i><span data-i18n="info_location">Dschang, Cameroun</span></div>
                    <div class="info-item"><i class="fas fa-envelope"></i><span>aldofoch@gmail.com</span></div>
                    <div class="info-item"><i class="fas fa-phone"></i><span>+237 658 547 295</span></div>
                    <div class="info-item"><i class="fas fa-language"></i><span data-i18n="info_lang">Français / Anglais</span></div>
                </div>
                <div style="margin-top:1.5rem">
                    <a href="<?= htmlspecialchars($cv_file) ?>" class="btn-primary" download>
                        <i class="fas fa-download"></i> <span data-i18n="btn_cv">Télécharger mon CV</span>
                    </a>
                </div>
            </div>
            <div class="about-stats animate-on-scroll">
                <div class="stat-card">
                    <div class="number">3+</div>
                    <div class="label">Ans d'expérience</div>
                </div>
                <div class="stat-card">
                    <div class="number"><?= count($projects) ?>+</div>
                    <div class="label">Projets réalisés</div>
                </div>
                <div class="stat-card">
                    <div class="number">20+</div>
                    <div class="label">Clients satisfaits</div>
                </div>
                <div class="stat-card">
                    <div class="number">2</div>
                    <div class="label">Certifications</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SKILLS -->
<section id="skills">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <p class="section-label" data-i18n="section_skills">COMPÉTENCES</p>
            <h2 class="section-title" data-i18n="title_skills">Technologies & Outils</h2>
        </div>
        <div class="skills-grid">
            <?php foreach ($skills as $category => $items): ?>
            <div class="skill-category-card animate-on-scroll">
                <div class="skill-cat-title">
                    <span class="skill-cat-dot"></span>
                    <?= htmlspecialchars($category) ?>
                </div>
                <div class="skill-tags">
                    <?php foreach ($items as $skill): ?>
                    <span class="skill-tag"><?= htmlspecialchars($skill['name']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PORTFOLIO -->
<section id="portfolio">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <p class="section-label" data-i18n="section_portfolio">PORTFOLIO</p>
            <h2 class="section-title" data-i18n="title_portfolio">Mes Réalisations</h2>
        </div>
        <div class="portfolio-filters">
            <button class="filter-btn active" data-filter="all" onclick="filterProjects('all')" data-i18n="filter_all">Tous</button>
            <button class="filter-btn" data-filter="Développement Web" onclick="filterProjects('Développement Web')" data-i18n="filter_web">Développement Web</button>
            <button class="filter-btn" data-filter="Design Graphique" onclick="filterProjects('Design Graphique')" data-i18n="filter_design">Design Graphique</button>
            <button class="filter-btn" data-filter="Projets Réseaux" onclick="filterProjects('Projets Réseaux')" data-i18n="filter_network">Projets Réseaux</button>
        </div>
        <div class="projects-grid">
            <?php foreach ($projects as $p):
                $techs = json_decode($p['technologies'] ?? '[]', true) ?: [];
            ?>
            <div class="project-card animate-on-scroll" data-category="<?= htmlspecialchars($p['category']) ?>">
                <?php if ($p['image'] && file_exists($p['image'])): ?>
                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="project-img">
                <?php else: ?>
                    <div style="height:200px;background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                        <i class="fas fa-image" style="font-size:2rem"></i>
                    </div>
                <?php endif; ?>
                <div class="project-info">
                    <div class="project-category"><?= htmlspecialchars($p['category']) ?></div>
                    <h3 class="project-name"><?= htmlspecialchars($p['name']) ?></h3>
                    <p class="project-desc"><?= htmlspecialchars($p['description']) ?></p>
                    <?php if ($techs): ?>
                    <div class="tech-badges">
                        <?php foreach ($techs as $tech): ?>
                        <span class="tech-badge"><?= htmlspecialchars($tech) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <div class="project-actions">
                        <?php if ($p['demo_url']): ?>
                        <a href="<?= htmlspecialchars($p['demo_url']) ?>" target="_blank" class="project-link" data-i18n="btn_details">
                            <i class="fas fa-external-link-alt"></i> Voir détails
                        </a>
                        <?php else: ?>
                        <span class="project-link" style="cursor:default;opacity:0.5;">
                            <i class="fas fa-eye"></i> <span data-i18n="btn_details">Voir détails</span>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section id="services">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <p class="section-label" data-i18n="section_services">SERVICES</p>
            <h2 class="section-title" data-i18n="title_services">Ce que je propose</h2>
        </div>
        <div class="services-grid">
            <?php foreach ($services as $s): ?>
            <div class="service-card animate-on-scroll">
                <div class="service-icon"><i class="<?= htmlspecialchars($s['icon']) ?>"></i></div>
                <h3 class="service-title"><?= htmlspecialchars($s['title']) ?></h3>
                <p class="service-desc"><?= htmlspecialchars($s['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- EXPERIENCE -->
<section id="experience">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <p class="section-label" data-i18n="section_exp">PARCOURS</p>
            <h2 class="section-title" data-i18n="title_exp">Expérience & Formation</h2>
        </div>
        <div class="exp-tabs">
            <button class="exp-tab active" data-type="work" onclick="showExpTab('work')" data-i18n="tab_work">💼 Expériences</button>
            <button class="exp-tab" data-type="education" onclick="showExpTab('education')" data-i18n="tab_edu">🎓 Formations</button>
        </div>
        <!-- Work timeline -->
        <div class="timeline" data-type="work">
            <?php foreach ($experiences_work as $exp): ?>
            <div class="timeline-item animate-on-scroll">
                <div class="timeline-dot"></div>
                <div class="timeline-card">
                    <div class="timeline-period"><i class="far fa-calendar-alt"></i> <?= htmlspecialchars($exp['period']) ?></div>
                    <div class="timeline-title"><?= htmlspecialchars($exp['title']) ?></div>
                    <div class="timeline-company"><?= htmlspecialchars($exp['company']) ?></div>
                    <div class="timeline-desc"><?= htmlspecialchars($exp['description']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- Education timeline -->
        <div class="timeline" data-type="education" style="display:none">
            <?php foreach ($experiences_edu as $exp): ?>
            <div class="timeline-item animate-on-scroll">
                <div class="timeline-dot"></div>
                <div class="timeline-card">
                    <div class="timeline-period"><i class="far fa-calendar-alt"></i> <?= htmlspecialchars($exp['period']) ?></div>
                    <div class="timeline-title"><?= htmlspecialchars($exp['title']) ?></div>
                    <div class="timeline-company"><?= htmlspecialchars($exp['company']) ?></div>
                    <div class="timeline-desc"><?= htmlspecialchars($exp['description']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section id="testimonials">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <p class="section-label" data-i18n="section_testi">TÉMOIGNAGES</p>
            <h2 class="section-title" data-i18n="title_testi">Ce que disent mes clients</h2>
        </div>
        <div class="testimonials-wrapper">
            <div class="testimonials-track">
                <?php
                // Duplicate for infinite scroll
                $all_testi = array_merge($testimonials, $testimonials);
                foreach ($all_testi as $t):
                    $stars = str_repeat('★', intval($t['rating'])) . str_repeat('☆', 5 - intval($t['rating']));
                    $initials = strtoupper(substr($t['client_name'] ?? 'C', 0, 1));
                ?>
                <div class="testimonial-card">
                    <div class="testi-stars"><?= $stars ?></div>
                    <p class="testi-content">"<?= htmlspecialchars($t['content']) ?>"</p>
                    <div class="testi-author">
                        <?php if (!empty($t['photo']) && file_exists($t['photo'])): ?>
                            <img src="<?= htmlspecialchars($t['photo']) ?>" style="width:42px;height:42px;border-radius:50%;object-fit:cover;" alt="">
                        <?php else: ?>
                            <div class="testi-avatar"><?= $initials ?></div>
                        <?php endif; ?>
                        <div>
                            <div class="testi-name"><?= htmlspecialchars($t['client_name']) ?></div>
                            <div class="testi-title"><?= htmlspecialchars($t['client_title'] ?? '') ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT -->
<section id="contact">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <p class="section-label" data-i18n="section_contact">CONTACT</p>
            <h2 class="section-title" data-i18n="title_contact">Travaillons ensemble</h2>
        </div>
        <div class="contact-grid">
            <div class="contact-info animate-on-scroll">
                <p class="section-subtitle" data-i18n="contact_intro">Vous avez un projet en tête ? N'hésitez pas à me contacter.</p>
                <div class="contact-items" style="margin-top:1.5rem">
                    <a href="https://wa.me/237658547295" target="_blank" class="contact-item">
                        <div class="contact-icon"><i class="fab fa-whatsapp"></i></div>
                        <span>+237 658 547 295</span>
                    </a>
                    <a href="https://t.me/Justinvestguys" target="_blank" class="contact-item">
                        <div class="contact-icon"><i class="fab fa-telegram"></i></div>
                        <span>@Justinvestguys</span>
                    </a>
                    <a href="mailto:aldofoch@gmail.com" class="contact-item">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <span>aldofoch@gmail.com</span>
                    </a>
                    <a href="https://www.linkedin.com/in/ben-foch-511525393/" target="_blank" class="contact-item">
                        <div class="contact-icon"><i class="fab fa-linkedin"></i></div>
                        <span>ben-foch-511525393</span>
                    </a>
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <span>Dschang, Cameroun</span>
                    </div>
                </div>
                <div class="social-links">
                    <a href="https://www.linkedin.com/in/ben-foch-511525393/" target="_blank" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://github.com/Fochiv" target="_blank" class="social-link"><i class="fab fa-github"></i></a>
                    <a href="https://wa.me/237658547295" target="_blank" class="social-link"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://t.me/Justinvestguys" target="_blank" class="social-link"><i class="fab fa-telegram"></i></a>
                    <a href="mailto:aldofoch@gmail.com" class="social-link"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
            <div class="contact-form-wrapper animate-on-scroll">
                <form id="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label data-i18n="form_name">Votre nom</label>
                            <input type="text" name="name" class="form-control" data-i18n-placeholder="form_name" placeholder="Votre nom" required>
                        </div>
                        <div class="form-group">
                            <label data-i18n="form_email">Votre email</label>
                            <input type="email" name="email" class="form-control" data-i18n-placeholder="form_email" placeholder="Votre email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label data-i18n="form_subject">Sujet</label>
                        <input type="text" name="subject" class="form-control" data-i18n-placeholder="form_subject" placeholder="Sujet">
                    </div>
                    <div class="form-group">
                        <label data-i18n="form_message">Votre message</label>
                        <textarea name="message" class="form-control" rows="5" data-i18n-placeholder="form_message" placeholder="Votre message" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                        <i class="fas fa-paper-plane"></i>
                        <span data-i18n="btn_send">Envoyer le message</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="footer-inner">
            <p class="footer-copy" data-i18n="footer_copy">Conçu et développé par Ben FOCH © 2026. Tous droits réservés.</p>
            <div class="footer-links">
                <a href="#hero" data-i18n="nav_home">Accueil</a>
                <a href="#about" data-i18n="nav_about">À propos</a>
                <a href="#portfolio" data-i18n="nav_portfolio">Portfolio</a>
                <a href="#contact" data-i18n="nav_contact">Contact</a>
            </div>
        </div>
    </div>
</footer>

<!-- BACK TO TOP -->
<a href="#hero" class="back-top" id="back-top">
    <i class="fas fa-chevron-up"></i>
</a>

<script src="assets/js/main.js"></script>
</body>
</html>
