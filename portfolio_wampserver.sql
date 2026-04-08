-- ================================================
-- Portfolio Ben FOCH - Base de données MySQL/MariaDB
-- Compatible WampServer (MySQL 5.7+ / MariaDB 10.x)
-- ================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- Création de la base de données (décommenter si nécessaire)
-- CREATE DATABASE IF NOT EXISTS `portfolio_benfoch` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `portfolio_benfoch`;

-- ================================================
-- Table: settings
-- ================================================
CREATE TABLE IF NOT EXISTS `settings` (
  `key` VARCHAR(100) NOT NULL,
  `value` TEXT,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- Table: projects
-- ================================================
CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `technologies` TEXT,
  `image` VARCHAR(500),
  `demo_url` VARCHAR(500),
  `status` VARCHAR(20) DEFAULT 'published',
  `sort_order` INT(11) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- Table: messages
-- ================================================
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255),
  `email` VARCHAR(255),
  `subject` VARCHAR(500),
  `message` TEXT,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- Table: skills
-- ================================================
CREATE TABLE IF NOT EXISTS `skills` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(100),
  `name` VARCHAR(255),
  `level` INT(3) DEFAULT 80,
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- Table: services
-- ================================================
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255),
  `description` TEXT,
  `icon` VARCHAR(100),
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- Table: testimonials
-- ================================================
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `client_name` VARCHAR(255),
  `client_title` VARCHAR(255),
  `content` TEXT,
  `rating` INT(1) DEFAULT 5,
  `photo` VARCHAR(500),
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- Table: experiences
-- ================================================
CREATE TABLE IF NOT EXISTS `experiences` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(20) DEFAULT 'work',
  `title` VARCHAR(255),
  `company` VARCHAR(255),
  `period` VARCHAR(100),
  `description` TEXT,
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- DONNÉES: settings
-- Mot de passe par défaut : Admin@2026
-- ================================================
INSERT INTO `settings` (`key`, `value`) VALUES
('admin_email', 'aldofoch@gmail.com'),
('admin_password', '$2y$10$DYfl2.HkGHW9QV30NhRgVuMNHLgdlG6ajKxiD.6TpBZ49Uu7rDvT2'),
('availability', '1'),
('profile_photo', 'uploads/profiles/PhotoProfil.jpeg'),
('cv_file', 'uploads/cvs/cv.pdf'),
('site_lang', 'fr'),
('whatsapp', '+237658547295'),
('telegram', 'https://t.me/Justinvestguys'),
('email', 'aldofoch@gmail.com'),
('linkedin', 'https://www.linkedin.com/in/ben-foch-511525393/'),
('github', 'https://github.com/Fochiv'),
('location', 'Dschang, Cameroun');

-- Note: Le mot de passe par défaut est 'Admin@2026'. 
-- Changez-le via l'interface admin après import.
-- Pour hacher un nouveau mot de passe : password_hash('VotreMotDePasse', PASSWORD_DEFAULT)

-- ================================================
-- DONNÉES: skills
-- ================================================
INSERT INTO `skills` (`category`, `name`, `level`, `sort_order`) VALUES
('Languages', 'PHP', 90, 1),
('Languages', 'JavaScript', 85, 2),
('Languages', 'SQL', 80, 3),
('Languages', 'HTML', 95, 4),
('Languages', 'CSS', 90, 5),
('Languages', 'XHTML', 80, 6),
('Frontend', 'HTML/CSS', 95, 1),
('Frontend', 'Bootstrap', 88, 2),
('Frontend', 'Responsive Design', 90, 3),
('Frontend', 'UI Layout', 85, 4),
('Backend', 'PHP (PDO)', 88, 1),
('Backend', 'MySQL', 82, 2),
('Backend', 'MS Exchange', 70, 3),
('Design & UX', 'Moqups', 80, 1),
('Design & UX', 'draw.io', 82, 2),
('Design & UX', 'Figma', 85, 3),
('Design & UX', 'Wireframes', 80, 4),
('Design & UX', 'UML', 75, 5),
('Tools & Automation', 'VS Code', 92, 1),
('Tools & Automation', 'GitHub', 85, 2),
('Tools & Automation', 'WampServer', 88, 3),
('Tools & Automation', 'Cisco Packet Tracer', 78, 4),
('Tools & Automation', 'Eclipse', 72, 5),
('AI Tools', 'ChatGPT', 90, 1),
('AI Tools', 'Claude', 88, 2),
('AI Tools', 'GitHub Copilot', 80, 3),
('IT & Hardware', 'Windows/Linux', 85, 1),
('IT & Hardware', 'PC Setup', 90, 2),
('IT & Hardware', 'Routers/Switches', 78, 3),
('IT & Hardware', 'Troubleshooting', 88, 4),
('IT & Hardware', 'Cisco Basics', 75, 5),
('Certifications', 'CISCO', 100, 1),
('Certifications', 'SecNum', 100, 2),
('Soft Skills', 'Bilingual EN/FR', 95, 1),
('Soft Skills', 'Technical writing', 85, 2),
('Soft Skills', 'Team training', 82, 3);

-- ================================================
-- DONNÉES: services
-- ================================================
INSERT INTO `services` (`title`, `description`, `icon`, `sort_order`) VALUES
('Développement Web', 'Création de sites et applications web modernes, responsives et performants avec HTML, CSS, JS et PHP.', 'fas fa-code', 1),
('UI/UX Design', 'Conception d\'interfaces utilisateur intuitives et esthétiques avec Figma, Moqups et draw.io.', 'fas fa-paint-brush', 2),
('Consulting IT', 'Conseil et support technique en infrastructure réseau, matériel informatique et systèmes d\'exploitation.', 'fas fa-server', 3),
('Support & Formation', 'Formation des équipes aux outils numériques et rédaction de documentation technique.', 'fas fa-chalkboard-teacher', 4),
('Design Graphique', 'Création de visuels et supports de communication modernes et percutants.', 'fas fa-vector-square', 5),
('Administration Réseau', 'Configuration de routeurs, switches, et maintenance des infrastructures réseau.', 'fas fa-network-wired', 6);

-- ================================================
-- DONNÉES: projects
-- ================================================
INSERT INTO `projects` (`name`, `category`, `description`, `technologies`, `image`, `demo_url`, `status`, `sort_order`) VALUES
('BenStore', 'Développement Web', 'Boutique e-commerce complète avec système de paiement et gestion des stocks.', '["PHP","MySQL","Bootstrap","JavaScript"]', 'assets/img/BenStore.PNG', 'https://benstore.iceiy.com', 'published', 1),
('Allianz', 'Design Graphique', 'Refonte visuelle et identité de marque pour une agence de services.', '["Figma","Photoshop","UI/UX"]', 'assets/img/Allianz.PNG', 'https://allianzgroup.iceiy.com', 'published', 2),
('Tesla Landing Page', 'Développement Web', 'Page d\'atterrissage moderne et responsive inspirée du design Tesla.', '["HTML","CSS","JavaScript","Responsive"]', 'assets/img/Tesla.PNG', 'https://teslausa.iceiy.com/index.php', 'published', 3),
('Sourire de Pâques', 'Design Graphique', 'Campagne visuelle festive pour un événement saisonnier.', '["Figma","Design Graphique"]', 'assets/img/SourirePaques.PNG', 'https://sourirepaques.iceiy.com', 'published', 4);

-- ================================================
-- DONNÉES: experiences
-- ================================================
INSERT INTO `experiences` (`type`, `title`, `company`, `period`, `description`, `sort_order`) VALUES
('work', 'Technicien Informatique & Développeur Web', 'Freelance', '2022 – Présent', 'Développement de sites web et applications pour clients locaux et internationaux. Support IT, formation utilisateurs, maintenance des systèmes.', 1),
('work', 'Designer UI/UX', 'Projets Indépendants', '2021 – Présent', 'Conception d\'interfaces utilisateur pour des applications web et mobiles. Création de maquettes et prototypes avec Figma.', 2),
('work', 'Technicien Réseau', 'Stage Professionnel', '2020 – 2021', 'Configuration de routeurs et switches. Maintenance des réseaux LAN/WAN. Diagnostic et résolution de pannes.', 3),
('education', 'Licence en Informatique', 'Université de Dschang', '2019 – 2022', 'Formation en développement logiciel, réseaux informatiques et systèmes d\'information.', 1),
('education', 'Certification CISCO', 'Cisco Networking Academy', '2021', 'Certification en réseaux informatiques et administration système.', 2),
('education', 'Certification SecNum', 'ANSSI', '2022', 'Formation en cybersécurité et sécurité des systèmes d\'information.', 3);

-- ================================================
-- DONNÉES: testimonials
-- ================================================
INSERT INTO `testimonials` (`client_name`, `client_title`, `content`, `rating`, `sort_order`) VALUES
('Marie Dupont', 'Directrice, Agence Marketing', 'Ben a réalisé notre site web avec professionnalisme et créativité. Le résultat dépasse nos attentes. Je recommande vivement !', 5, 1),
('Jean-Pierre Kamdem', 'CEO, TechStart Cameroun', 'Un développeur talentueux et réactif. Il a livré notre projet dans les délais avec une qualité irréprochable.', 5, 2),
('Sophie Nkemdirim', 'Chef de Projet, ONG Vision', 'Excellent travail sur notre plateforme digitale. Ben est professionnel, créatif et très attentif aux besoins du client.', 5, 3),
('Ahmed Diallo', 'Entrepreneur', 'J\'ai fait appel à Ben pour la refonte de mon site. Le résultat est magnifique et les performances sont excellentes.', 4, 4);

-- ================================================
-- NOTES POUR WAMPSERVER
-- ================================================
-- 1. Importer ce fichier via phpMyAdmin ou la commande :
--    mysql -u root -p portfolio_benfoch < portfolio_wampserver.sql
--
-- 2. Modifier config.php pour utiliser MySQL au lieu de SQLite :
--    $db = new PDO('mysql:host=localhost;dbname=portfolio_benfoch;charset=utf8mb4', 'root', '');
--
-- 3. Le mot de passe admin par défaut dans ce fichier est 'password'.
--    Connectez-vous à l'admin et changez-le immédiatement.
--    Pour générer un hash correct : 
--    echo password_hash('VotreMotDePasse', PASSWORD_DEFAULT);
--
-- 4. Créer les dossiers uploads/ :
--    uploads/profiles/
--    uploads/cvs/
--    uploads/projects/
-- ================================================
