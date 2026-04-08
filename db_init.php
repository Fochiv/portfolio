<?php
require_once 'config.php';

$db = getDB();

$db->exec("
CREATE TABLE IF NOT EXISTS settings (
    key TEXT PRIMARY KEY,
    value TEXT
);

CREATE TABLE IF NOT EXISTS projects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    category TEXT NOT NULL,
    description TEXT,
    technologies TEXT,
    image TEXT,
    demo_url TEXT,
    status TEXT DEFAULT 'published',
    sort_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    email TEXT,
    subject TEXT,
    message TEXT,
    is_read INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS skills (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category TEXT,
    name TEXT,
    level INTEGER DEFAULT 80,
    sort_order INTEGER DEFAULT 0
);

CREATE TABLE IF NOT EXISTS services (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    description TEXT,
    icon TEXT,
    sort_order INTEGER DEFAULT 0
);

CREATE TABLE IF NOT EXISTS testimonials (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_name TEXT,
    client_title TEXT,
    content TEXT,
    rating INTEGER DEFAULT 5,
    photo TEXT,
    sort_order INTEGER DEFAULT 0
);

CREATE TABLE IF NOT EXISTS experiences (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type TEXT DEFAULT 'work',
    title TEXT,
    company TEXT,
    period TEXT,
    description TEXT,
    sort_order INTEGER DEFAULT 0
);
");

// Default settings
$defaults = [
    'admin_email' => 'aldofoch@gmail.com',
    'admin_password' => password_hash('Admin@2026', PASSWORD_DEFAULT),
    'availability' => '1',
    'profile_photo' => '',
    'cv_file' => 'uploads/cvs/cv.pdf',
    'site_lang' => 'fr',
    'whatsapp' => '+237658547295',
    'telegram' => 'https://t.me/Justinvestguys',
    'email' => 'aldofoch@gmail.com',
    'linkedin' => 'https://www.linkedin.com/in/ben-foch-511525393/',
    'github' => 'https://github.com/Fochiv',
    'location' => 'Dschang, Cameroun',
];

foreach ($defaults as $key => $value) {
    $stmt = $db->prepare("INSERT OR IGNORE INTO settings (key, value) VALUES (?, ?)");
    $stmt->execute([$key, $value]);
}

// Default skills
$skillGroups = [
    ['Languages', ['PHP', 'JavaScript', 'SQL', 'HTML', 'CSS', 'XHTML']],
    ['Frontend', ['HTML/CSS', 'Bootstrap', 'Responsive Design', 'UI Layout']],
    ['Backend', ['PHP (PDO)', 'MySQL', 'MS Exchange']],
    ['Design & UX', ['Moqups', 'draw.io', 'Figma', 'Wireframes', 'UML']],
    ['Tools & Automation', ['VS Code', 'GitHub', 'WampServer', 'Cisco Packet Tracer', 'Eclipse']],
    ['AI Tools', ['ChatGPT', 'Claude', 'GitHub Copilot']],
    ['IT & Hardware', ['Windows/Linux', 'PC Setup', 'Routers/Switches', 'Troubleshooting', 'Cisco Basics']],
    ['Certifications', ['CISCO', 'SecNum']],
    ['Soft Skills', ['Bilingual EN/FR', 'Technical writing', 'Team training']],
];

$count = $db->query("SELECT COUNT(*) FROM skills")->fetchColumn();
if ($count == 0) {
    foreach ($skillGroups as $group) {
        foreach ($group[1] as $skill) {
            $stmt = $db->prepare("INSERT INTO skills (category, name, level) VALUES (?, ?, ?)");
            $stmt->execute([$group[0], $skill, rand(75, 95)]);
        }
    }
}

// Default services
$count = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
if ($count == 0) {
    $services = [
        ['Développement Web', 'Création de sites et applications web modernes, responsives et performants avec HTML, CSS, JS et PHP.', 'fas fa-code'],
        ['UI/UX Design', 'Conception d\'interfaces utilisateur intuitives et esthétiques avec Figma, Moqups et draw.io.', 'fas fa-paint-brush'],
        ['Consulting IT', 'Conseil et support technique en infrastructure réseau, matériel informatique et systèmes d\'exploitation.', 'fas fa-server'],
        ['Support & Formation', 'Formation des équipes aux outils numériques et rédaction de documentation technique.', 'fas fa-chalkboard-teacher'],
        ['Design Graphique', 'Création de visuels et supports de communication modernes et percutants.', 'fas fa-vector-square'],
        ['Administration Réseau', 'Configuration de routeurs, switches, et maintenance des infrastructures réseau.', 'fas fa-network-wired'],
    ];
    foreach ($services as $s) {
        $stmt = $db->prepare("INSERT INTO services (title, description, icon) VALUES (?, ?, ?)");
        $stmt->execute($s);
    }
}

// Default projects
$count = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
if ($count == 0) {
    $projects = [
        ['BenStore', 'Développement Web', 'Boutique e-commerce complète avec système de paiement et gestion des stocks.', '["PHP","MySQL","Bootstrap","JavaScript"]', 'assets/img/BenStore.PNG', '', 'published'],
        ['Allianz', 'Design Graphique', 'Refonte visuelle et identité de marque pour une agence de services.', '["Figma","Photoshop","UI/UX"]', 'assets/img/Allianz.PNG', '', 'published'],
        ['Tesla Landing Page', 'Développement Web', 'Page d\'atterrissage moderne et responsive inspirée du design Tesla.', '["HTML","CSS","JavaScript","Responsive"]', 'assets/img/Tesla.PNG', '', 'published'],
        ['Sourire de Pâques', 'Design Graphique', 'Campagne visuelle festive pour un événement saisonnier.', '["Figma","Design Graphique"]', 'assets/img/SourirePaques.PNG', '', 'published'],
    ];
    foreach ($projects as $p) {
        $stmt = $db->prepare("INSERT INTO projects (name, category, description, technologies, image, demo_url, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute($p);
    }
}

// Default experiences
$count = $db->query("SELECT COUNT(*) FROM experiences")->fetchColumn();
if ($count == 0) {
    $exps = [
        ['work', 'Technicien Informatique & Développeur Web', 'Freelance', '2022 – Présent', 'Développement de sites web et applications pour clients locaux et internationaux. Support IT, formation utilisateurs, maintenance des systèmes.'],
        ['work', 'Designer UI/UX', 'Projets Indépendants', '2021 – Présent', 'Conception d\'interfaces utilisateur pour des applications web et mobiles. Création de maquettes et prototypes avec Figma.'],
        ['work', 'Technicien Réseau', 'Stage Professionnel', '2020 – 2021', 'Configuration de routeurs et switches. Maintenance des réseaux LAN/WAN. Diagnostic et résolution de pannes.'],
        ['education', 'Licence en Informatique', 'Université de Dschang', '2019 – 2022', 'Formation en développement logiciel, réseaux informatiques et systèmes d\'information.'],
        ['education', 'Certification CISCO', 'Cisco Networking Academy', '2021', 'Certification en réseaux informatiques et administration système.'],
        ['education', 'Certification SecNum', 'ANSSI', '2022', 'Formation en cybersécurité et sécurité des systèmes d\'information.'],
    ];
    foreach ($exps as $e) {
        $stmt = $db->prepare("INSERT INTO experiences (type, title, company, period, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute($e);
    }
}

// Default testimonials
$count = $db->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
if ($count == 0) {
    $testi = [
        ['Marie Dupont', 'Directrice, Agence Marketing', 'Ben a réalisé notre site web avec professionnalisme et créativité. Le résultat dépasse nos attentes. Je recommande vivement !', 5],
        ['Jean-Pierre Kamdem', 'CEO, TechStart Cameroun', 'Un développeur talentueux et réactif. Il a livré notre projet dans les délais avec une qualité irréprochable.', 5],
        ['Sophie Nkemdirim', 'Chef de Projet, ONG Vision', 'Excellent travail sur notre plateforme digitale. Ben est professionnel, créatif et très attentif aux besoins du client.', 5],
        ['Ahmed Diallo', 'Entrepreneur', 'J\'ai fait appel à Ben pour la refonte de mon site. Le résultat est magnifique et les performances sont excellentes.', 4],
    ];
    foreach ($testi as $t) {
        $stmt = $db->prepare("INSERT INTO testimonials (client_name, client_title, content, rating) VALUES (?, ?, ?, ?)");
        $stmt->execute($t);
    }
}

echo "Database initialized successfully.\n";
