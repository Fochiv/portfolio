// ============================================
// THEME SWITCH
// ============================================
const savedTheme = localStorage.getItem('theme') || 'dark';
document.documentElement.setAttribute('data-theme', savedTheme);

function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    document.querySelectorAll('.theme-toggle i').forEach(icon => {
        icon.className = next === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    });
}

// ============================================
// LANGUAGE SWITCHER
// ============================================
const translations = {
    fr: {
        nav_home: 'Accueil', nav_about: 'À propos', nav_skills: 'Compétences',
        nav_portfolio: 'Portfolio', nav_services: 'Services', nav_experience: 'Expérience',
        nav_contact: 'Contact',
        hero_greeting: 'Bonjour, je suis',
        hero_subtitle: ['Professionnel IT', 'Développeur Web', 'Designer UI/UX'],
        btn_cv: 'Télécharger mon CV', btn_contact: 'Me contacter',
        stat_years: "Ans d'expérience", stat_projects: 'Projets réalisés', stat_clients: 'Clients satisfaits',
        available: '✓ Disponible', unavailable: '✗ Indisponible',
        section_about: 'À PROPOS', title_about: 'Qui suis-je ?',
        about_p1: 'Je suis Ben FOCH SALAOUDINE, professionnel IT et développeur web basé à Dschang, Cameroun. Passionné par la technologie et le design, j\'accompagne mes clients dans leur transformation numérique.',
        about_p2: 'Avec plusieurs années d\'expérience en développement web, design UI/UX et administration réseau, je propose des solutions complètes adaptées aux besoins de chaque projet.',
        info_location: 'Dschang, Cameroun', info_email: 'aldofoch@gmail.com',
        info_phone: '+237 658 547 295', info_lang: 'Français / Anglais',
        section_skills: 'COMPÉTENCES', title_skills: 'Technologies & Outils',
        section_portfolio: 'PORTFOLIO', title_portfolio: 'Mes Réalisations',
        filter_all: 'Tous', filter_web: 'Développement Web', filter_design: 'Design Graphique', filter_network: 'Projets Réseaux',
        section_services: 'SERVICES', title_services: 'Ce que je propose',
        section_exp: 'PARCOURS', title_exp: 'Expérience & Formation',
        tab_work: '💼 Expériences', tab_edu: '🎓 Formations',
        section_testi: 'TÉMOIGNAGES', title_testi: 'Ce que disent mes clients',
        section_contact: 'CONTACT', title_contact: 'Travaillons ensemble',
        contact_intro: 'Vous avez un projet en tête ? N\'hésitez pas à me contacter. Je suis disponible pour des missions freelance, des collaborations ou tout simplement pour échanger.',
        form_name: 'Votre nom', form_email: 'Votre email',
        form_subject: 'Sujet', form_message: 'Votre message',
        btn_send: 'Envoyer le message', btn_sending: 'Envoi en cours...',
        footer_copy: 'Conçu et développé par Ben FOCH © 2026. Tous droits réservés.',
        btn_details: 'Voir détails',
        msg_success: '✓ Message envoyé avec succès !',
        msg_error: '✗ Erreur lors de l\'envoi.',
    },
    en: {
        nav_home: 'Home', nav_about: 'About', nav_skills: 'Skills',
        nav_portfolio: 'Portfolio', nav_services: 'Services', nav_experience: 'Experience',
        nav_contact: 'Contact',
        hero_greeting: 'Hello, I am',
        hero_subtitle: ['IT Professional', 'Web Developer', 'UI/UX Designer'],
        btn_cv: 'Download my CV', btn_contact: 'Contact me',
        stat_years: 'Years of experience', stat_projects: 'Projects completed', stat_clients: 'Satisfied clients',
        available: '✓ Available', unavailable: '✗ Unavailable',
        section_about: 'ABOUT', title_about: 'Who am I?',
        about_p1: 'I am Ben FOCH SALAOUDINE, an IT professional and web developer based in Dschang, Cameroon. Passionate about technology and design, I help clients in their digital transformation.',
        about_p2: 'With several years of experience in web development, UI/UX design and network administration, I offer comprehensive solutions tailored to each project\'s needs.',
        info_location: 'Dschang, Cameroon', info_email: 'aldofoch@gmail.com',
        info_phone: '+237 658 547 295', info_lang: 'French / English',
        section_skills: 'SKILLS', title_skills: 'Technologies & Tools',
        section_portfolio: 'PORTFOLIO', title_portfolio: 'My Work',
        filter_all: 'All', filter_web: 'Web Development', filter_design: 'Graphic Design', filter_network: 'Network Projects',
        section_services: 'SERVICES', title_services: 'What I offer',
        section_exp: 'CAREER', title_exp: 'Experience & Education',
        tab_work: '💼 Experience', tab_edu: '🎓 Education',
        section_testi: 'TESTIMONIALS', title_testi: 'What clients say',
        section_contact: 'CONTACT', title_contact: 'Let\'s work together',
        contact_intro: 'Have a project in mind? Don\'t hesitate to contact me. I\'m available for freelance missions, collaborations or just to chat.',
        form_name: 'Your name', form_email: 'Your email',
        form_subject: 'Subject', form_message: 'Your message',
        btn_send: 'Send message', btn_sending: 'Sending...',
        footer_copy: 'Designed and developed by Ben FOCH © 2026. All rights reserved.',
        btn_details: 'View details',
        msg_success: '✓ Message sent successfully!',
        msg_error: '✗ Error sending message.',
    }
};

let currentLang = localStorage.getItem('lang') || 'fr';

function setLang(lang) {
    currentLang = lang;
    localStorage.setItem('lang', lang);
    applyTranslations();
    document.querySelectorAll('.lang-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.lang === lang);
    });
}

function applyTranslations() {
    const t = translations[currentLang];
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.dataset.i18n;
        if (t[key] !== undefined) el.textContent = t[key];
    });
    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        const key = el.dataset.i18nPlaceholder;
        if (t[key] !== undefined) el.placeholder = t[key];
    });
    if (typingInterval) startTyping();
}

// ============================================
// TYPING EFFECT
// ============================================
let typingInterval = null;

function startTyping() {
    const el = document.getElementById('typing-text');
    if (!el) return;
    const t = translations[currentLang];
    const texts = t.hero_subtitle;
    let textIdx = 0, charIdx = 0, isDeleting = false;

    if (typingInterval) clearInterval(typingInterval);

    typingInterval = setInterval(() => {
        const current = texts[textIdx];
        if (!isDeleting) {
            el.textContent = current.substring(0, charIdx + 1);
            charIdx++;
            if (charIdx === current.length) {
                setTimeout(() => { isDeleting = true; }, 2000);
            }
        } else {
            el.textContent = current.substring(0, charIdx - 1);
            charIdx--;
            if (charIdx === 0) {
                isDeleting = false;
                textIdx = (textIdx + 1) % texts.length;
            }
        }
    }, isDeleting ? 60 : 120);
}

// ============================================
// SCROLL ANIMATIONS
// ============================================
const observer = new IntersectionObserver(entries => {
    entries.forEach((e, i) => {
        if (e.isIntersecting) {
            setTimeout(() => e.target.classList.add('animated'), i * 80);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));

// ============================================
// NAVBAR SCROLL
// ============================================
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        navbar.style.boxShadow = window.scrollY > 50 ? '0 4px 30px rgba(0,0,0,0.3)' : 'none';
    }
    // Active nav link
    const sections = document.querySelectorAll('section[id]');
    sections.forEach(section => {
        const top = section.offsetTop - 100;
        const bottom = top + section.offsetHeight;
        const id = section.getAttribute('id');
        const link = document.querySelector(`.nav-links a[href="#${id}"]`);
        if (link) {
            link.classList.toggle('active', window.scrollY >= top && window.scrollY < bottom);
        }
    });
    // Back to top
    const btn = document.querySelector('.back-top');
    if (btn) btn.classList.toggle('visible', window.scrollY > 300);
});

// ============================================
// PORTFOLIO FILTER
// ============================================
function filterProjects(category) {
    document.querySelectorAll('.filter-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.filter === category);
    });
    document.querySelectorAll('.project-card').forEach(card => {
        if (category === 'all' || card.dataset.category === category) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
}

// ============================================
// EXPERIENCE TABS
// ============================================
function showExpTab(type) {
    document.querySelectorAll('.exp-tab').forEach(t => t.classList.toggle('active', t.dataset.type === type));
    document.querySelectorAll('.timeline').forEach(tl => {
        tl.style.display = tl.dataset.type === type ? 'block' : 'none';
    });
}

// ============================================
// CONTACT FORM
// ============================================
async function sendMessage(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const t = translations[currentLang];
    btn.disabled = true;
    btn.textContent = t.btn_sending;

    const data = new FormData(form);
    data.append('action', 'send_message');

    try {
        const res = await fetch('api.php', { method: 'POST', body: data });
        const json = await res.json();
        showToast(json.success ? t.msg_success : t.msg_error, json.success ? 'success' : 'error');
        if (json.success) form.reset();
    } catch {
        showToast(t.msg_error, 'error');
    }
    btn.disabled = false;
    btn.textContent = t.btn_send;
}

// ============================================
// TOAST
// ============================================
function showToast(msg, type = 'success') {
    let toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'toast';
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.className = `toast ${type}`;
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => toast.classList.remove('show'), 4000);
}

// ============================================
// HAMBURGER MENU
// ============================================
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu) menu.classList.toggle('open');
}

// ============================================
// INIT
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    applyTranslations();
    startTyping();

    // Theme icons (desktop + mobile)
    const theme = document.documentElement.getAttribute('data-theme');
    document.querySelectorAll('.theme-toggle i').forEach(icon => {
        icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    });

    // Contact form
    const form = document.getElementById('contact-form');
    if (form) form.addEventListener('submit', sendMessage);

    // Observer
    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));

    // Active lang button
    document.querySelectorAll('.lang-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.lang === currentLang);
    });
});
