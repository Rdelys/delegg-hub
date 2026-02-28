@extends('client.layouts.app')

@section('title', 'Mails reçus')

@section('content')

<!-- Font Awesome 6 (Free) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
/* ================= BASE & RESET ================= */
:root {
    --primary: #4361ee;
    --primary-dark: #3a56d4;
    --secondary: #06d6a0;
    --secondary-dark: #05b586;
    --danger: #ef476f;
    --danger-dark: #d43f62;
    --warning: #ffd166;
    --success: #06d6a0;
    --dark: #2b2d42;
    --gray: #6c757d;
    --light: #f8f9fa;
    --white: #ffffff;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.08);
    --shadow-lg: 0 16px 32px rgba(0, 0, 0, 0.1);
    --radius: 16px;
    --radius-sm: 10px;
    --radius-lg: 20px;
    --transition: all 0.2s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* ================= CONTAINER PRINCIPAL ================= */
.mail-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px 20px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #f5f7fb;
    min-height: 100vh;
}

/* ================= CARTES ================= */
.mail-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    padding: 28px 30px;
    margin-bottom: 28px;
    transition: var(--transition);
    border: 1px solid rgba(0, 0, 0, 0.02);
}

.mail-card:hover {
    box-shadow: var(--shadow-md);
}

.section-title {
    font-weight: 600;
    font-size: 1.5rem;
    margin-bottom: 24px;
    color: var(--dark);
    letter-spacing: -0.01em;
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 2px solid #eef2f6;
    padding-bottom: 16px;
}

.section-title i {
    color: var(--primary);
    font-size: 1.8rem;
}

.section-title::before {
    content: '';
    width: 4px;
    height: 28px;
    background: var(--primary);
    border-radius: 4px;
}

/* ================= CONFIG IMAP ================= */
.config-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.config-card {
    background: #f8fafd;
    border-radius: var(--radius-sm);
    padding: 20px;
    border: 1px solid #e9ecef;
}

.config-card h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.config-card h3 i {
    color: var(--primary);
}

.config-details {
    background: var(--white);
    border-radius: 8px;
    padding: 12px;
    margin: 15px 0;
    border: 1px dashed #d0d9e8;
}

.config-details p {
    margin-bottom: 8px;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.config-details p:last-child {
    margin-bottom: 0;
}

.config-details i {
    width: 20px;
    color: var(--primary);
}

/* ================= ÉTAT IMAP ================= */
.imap-status {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
    padding: 18px 22px;
    background: #f8fafd;
    border-radius: var(--radius-sm);
    border: 1px solid #e9ecef;
}

.imap-status i {
    font-size: 2.2rem;
}

.imap-status .status-text {
    font-weight: 600;
    font-size: 1.1rem;
}

.imap-status .status-details {
    color: var(--gray);
    font-size: 0.9rem;
    margin-top: 4px;
}

.status-success {
    color: var(--success);
}

.status-error {
    color: var(--danger);
}

.status-warning {
    color: #ffb703;
}

/* ================= FORMULAIRES ================= */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 500;
    color: var(--dark);
    margin-bottom: 8px;
    display: block;
    font-size: 0.95rem;
}

.form-group label i {
    margin-right: 8px;
    color: var(--primary);
    width: 18px;
}

.form-control {
    width: 100%;
    padding: 12px 18px;
    font-size: 0.95rem;
    border: 2px solid #e9ecef;
    border-radius: var(--radius-sm);
    transition: var(--transition);
    background: #fafbfc;
    color: var(--dark);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--white);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

/* ================= BOUTONS ================= */
.btn-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 28px;
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 50px;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    letter-spacing: 0.2px;
    box-shadow: var(--shadow-sm);
    gap: 10px;
}

.btn-modern i {
    font-size: 1rem;
}

.btn-modern:active {
    transform: translateY(1px);
    box-shadow: none;
}

.btn-primary-modern {
    background: var(--primary);
    color: var(--white);
}

.btn-primary-modern:hover {
    background: var(--primary-dark);
    box-shadow: var(--shadow-md);
}

.btn-success-modern {
    background: var(--secondary);
    color: var(--white);
}

.btn-success-modern:hover {
    background: var(--secondary-dark);
    box-shadow: var(--shadow-md);
}

.btn-outline-modern {
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
    box-shadow: none;
}

.btn-outline-modern:hover {
    background: var(--primary);
    color: var(--white);
}

.btn-test {
    background: #6c5ce7;
    color: white;
}

.btn-test:hover {
    background: #5b4bc4;
}

/* ================= ALERTES ================= */
.alert {
    padding: 18px 20px;
    border-radius: var(--radius-sm);
    margin-top: 22px;
    font-size: 0.95rem;
    border-left: 4px solid transparent;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.alert i {
    font-size: 1.3rem;
    margin-top: 2px;
}

.alert-info {
    background: #e7f1ff;
    border-left-color: var(--primary);
    color: #1e4a6b;
}

.alert-info i {
    color: var(--primary);
}

.alert-warning {
    background: #fff3cd;
    border-left-color: #ffc107;
    color: #856404;
}

.alert-warning i {
    color: #ffc107;
}

.alert-success {
    background: #d1fae5;
    border-left-color: var(--success);
    color: #0b5e42;
}

.alert-success i {
    color: var(--success);
}

/* ================= STATISTIQUES EMAILS ================= */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.stat-item {
    background: #f8fafd;
    border-radius: var(--radius-sm);
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 15px;
    border: 1px solid #e9ecef;
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: var(--primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.4rem;
}

.stat-icon.green {
    background: var(--success);
}

.stat-icon.orange {
    background: #fb8b24;
}

.stat-icon.purple {
    background: #9d4edd;
}

.stat-content h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    line-height: 1.2;
}

.stat-content p {
    color: var(--gray);
    font-size: 0.9rem;
    font-weight: 500;
}

/* ================= LISTE EMAILS ================= */
.inbox-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.inbox-actions {
    display: flex;
    gap: 10px;
}

.search-box {
    display: flex;
    align-items: center;
    background: #f8fafd;
    border: 2px solid #e9ecef;
    border-radius: 50px;
    padding: 5px 5px 5px 18px;
    min-width: 280px;
}

.search-box i {
    color: var(--gray);
}

.search-box input {
    border: none;
    background: transparent;
    padding: 10px 12px;
    flex: 1;
    outline: none;
    font-size: 0.95rem;
}

.search-box button {
    background: var(--primary);
    border: none;
    color: white;
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.search-box button:hover {
    background: var(--primary-dark);
}

.email-list {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.email-item {
    display: flex;
    align-items: center;
    padding: 18px 20px;
    background: var(--white);
    border: 1px solid #edf2f7;
    border-radius: 12px;
    transition: var(--transition);
    cursor: pointer;
    gap: 15px;
    flex-wrap: wrap;
}

.email-item:hover {
    background: #f8fafd;
    border-color: var(--primary);
    transform: translateX(4px);
    box-shadow: var(--shadow-sm);
}

.email-item.unread {
    background: #f0f4fe;
    border-left: 4px solid var(--primary);
}

.email-check {
    width: 30px;
}

.email-check input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--primary);
}

.email-star {
    color: #ffb703;
    font-size: 1.2rem;
    width: 30px;
}

.email-star .far {
    color: #ced4da;
}

.email-sender {
    min-width: 200px;
    font-weight: 600;
    color: var(--dark);
}

.email-sender i {
    margin-right: 8px;
    color: var(--primary);
    font-size: 0.9rem;
}

.email-subject {
    flex: 1;
    min-width: 250px;
    color: #4a5568;
}

.email-subject .subject-text {
    font-weight: 500;
    margin-right: 8px;
}

.email-subject .preview-text {
    color: var(--gray);
    font-size: 0.9rem;
}

.email-attachment {
    width: 30px;
    color: var(--gray);
}

.email-date {
    min-width: 100px;
    text-align: right;
    color: var(--gray);
    font-size: 0.9rem;
    font-weight: 500;
}

.email-date i {
    margin-right: 5px;
}

/* ================= PAGINATION ================= */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 25px;
    flex-wrap: wrap;
}

.page-item {
    list-style: none;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--white);
    border: 1px solid #e9ecef;
    color: var(--dark);
    font-weight: 500;
    transition: var(--transition);
    cursor: pointer;
}

.page-link:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.page-item.active .page-link {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

/* ================= FOLDERS ================= */
.folders-section {
    margin-top: 25px;
}

.folder-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.folder-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background: #f8fafd;
    border-radius: 50px;
    border: 1px solid #e9ecef;
    transition: var(--transition);
    cursor: pointer;
}

.folder-item:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.folder-item:hover i,
.folder-item:hover span {
    color: white;
}

.folder-item i {
    color: var(--primary);
    font-size: 1rem;
}

.folder-item span {
    color: var(--dark);
    font-weight: 500;
}

.folder-item .count {
    background: #e9ecef;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.8rem;
    color: var(--gray);
}

.folder-item:hover .count {
    background: rgba(255,255,255,0.2);
    color: white;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 1024px) {
    .mail-container {
        padding: 20px 16px;
    }
    
    .mail-card {
        padding: 22px 20px;
    }
    
    .section-title {
        font-size: 1.3rem;
    }
    
    .config-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .mail-card {
        padding: 18px 16px;
    }
    
    .email-item {
        padding: 15px;
    }
    
    .email-sender {
        min-width: 150px;
    }
    
    .email-subject {
        min-width: 200px;
    }
    
    .inbox-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box {
        width: 100%;
    }
    
    .imap-status {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 580px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .email-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .email-check,
    .email-star {
        display: none;
    }
    
    .email-sender {
        width: 100%;
    }
    
    .email-subject {
        width: 100%;
    }
    
    .email-date {
        width: 100%;
        text-align: left;
    }
    
    .folder-item {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .mail-container {
        padding: 12px 10px;
    }
    
    .mail-card {
        padding: 15px 12px;
        border-radius: 14px;
    }
    
    .section-title {
        font-size: 1.2rem;
        padding-bottom: 12px;
    }
    
    .section-title i {
        font-size: 1.4rem;
    }
    
    .btn-modern {
        width: 100%;
        padding: 12px 16px;
    }
    
    .inbox-actions {
        flex-direction: column;
    }
    
    .search-box {
        flex-direction: column;
        background: transparent;
        border: none;
        padding: 0;
        gap: 10px;
    }
    
    .search-box input {
        width: 100%;
        border: 2px solid #e9ecef;
        border-radius: 50px;
        padding: 12px 18px;
    }
    
    .search-box button {
        width: 100%;
    }
    
    .pagination {
        gap: 4px;
    }
    
    .page-link {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
}

@media (max-width: 360px) {
    .section-title {
        font-size: 1.1rem;
    }
    
    .stat-item {
        padding: 12px;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    
    .stat-content h4 {
        font-size: 1.2rem;
    }
}

@media (min-width: 1600px) {
    .mail-container {
        max-width: 1600px;
    }
}

.mt-4 {
    margin-top: 1.5rem;
}
</style>

<div class="mail-container">

    <!-- ================= CONFIGURATION IMAP ================= -->
    <div class="mail-card">
        <h2 class="section-title">
            <i class="fas fa-cog"></i>
            Configuration IMAP
        </h2>

        <!-- ÉTAT IMAP STATIQUE -->
        <div class="imap-status">
            <i class="fas fa-check-circle status-success"></i>
            <div>
                <div class="status-text status-success">
                    <i class="fas fa-circle"></i> IMAP configuré et opérationnel
                </div>
                <div class="status-details">
                    Serveur: imap.gmail.com:993 | SSL/TLS activé
                </div>
            </div>
        </div>

        <div class="config-grid">
            <!-- Formulaire de configuration -->
            <div class="config-card">
                <h3><i class="fas fa-sliders-h"></i> Paramètres IMAP</h3>
                
                <form>
                    <div class="form-group">
                        <label>
                            <i class="fas fa-server"></i>
                            Serveur IMAP
                        </label>
                        <input type="text" class="form-control" value="imap.gmail.com" placeholder="ex: imap.gmail.com">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <i class="fas fa-plug"></i>
                                Port
                            </label>
                            <input type="number" class="form-control" value="993" placeholder="993">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fas fa-shield-alt"></i>
                                Sécurité
                            </label>
                            <select class="form-control">
                                <option selected>SSL/TLS</option>
                                <option>STARTTLS</option>
                                <option>Aucune</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>
                        <input type="email" class="form-control" value="contact@monentreprise.com" placeholder="votre@email.com">
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-lock"></i>
                            Mot de passe
                        </label>
                        <input type="password" class="form-control" value="xxxxxxxx" placeholder="Mot de passe">
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-folder"></i>
                            Dossier racine
                        </label>
                        <input type="text" class="form-control" value="INBOX" placeholder="INBOX">
                    </div>

                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="btn-modern btn-primary-modern">
                            <i class="fas fa-save"></i>
                            Enregistrer
                        </button>
                        <button type="button" class="btn-modern btn-test">
                            <i class="fas fa-vial"></i>
                            Tester la connexion
                        </button>
                    </div>
                </form>
            </div>

            <!-- Informations et configuration actuelle -->
            <div class="config-card">
                <h3><i class="fas fa-info-circle"></i> Configuration actuelle</h3>
                
                <div class="config-details">
                    <p><i class="fas fa-check-circle" style="color: var(--success);"></i> <strong>Statut:</strong> Connecté</p>
                    <p><i class="fas fa-server"></i> <strong>Serveur:</strong> imap.gmail.com:993</p>
                    <p><i class="fas fa-shield-alt"></i> <strong>Sécurité:</strong> SSL/TLS</p>
                    <p><i class="fas fa-envelope"></i> <strong>Email:</strong> contact@monentreprise.com</p>
                    <p><i class="fas fa-folder"></i> <strong>Dossier:</strong> INBOX</p>
                    <p><i class="fas fa-clock"></i> <strong>Dernière synchro:</strong> 15:24:38</p>
                </div>

                <div class="alert alert-info" style="margin-top: 15px;">
                    <i class="fas fa-lightbulb"></i>
                    <div>
                        <strong>Validation en 2 étapes requise</strong><br>
                        Pour Gmail, utilisez un mot de passe d'application.
                    </div>
                </div>
            </div>
        </div>

        <!-- Options avancées -->
        <div style="margin-top: 20px;">
            <details style="background: #f8fafd; border-radius: var(--radius-sm); padding: 15px;">
                <summary style="font-weight: 600; color: var(--dark); cursor: pointer;">
                    <i class="fas fa-chevron-down" style="margin-right: 8px;"></i>
                    Options avancées
                </summary>
                <div style="margin-top: 15px; display: grid; gap: 15px;">
                    <div class="form-check" style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" id="ssl" checked style="width: 18px; height: 18px;">
                        <label for="ssl" style="font-weight: 500;">Valider le certificat SSL</label>
                    </div>
                    <div class="form-check" style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" id="validate" checked style="width: 18px; height: 18px;">
                        <label for="validate" style="font-weight: 500;">Valider l'authentification</label>
                    </div>
                    <div class="form-group">
                        <label>Timeout de connexion (secondes)</label>
                        <input type="number" class="form-control" value="30" style="max-width: 200px;">
                    </div>
                </div>
            </details>
        </div>
    </div>

    <!-- ================= BOÎTE DE RÉCEPTION ================= -->
    <div class="mail-card">
        <h2 class="section-title">
            <i class="fas fa-inbox"></i>
            Mails reçus
        </h2>

        <!-- STATISTIQUES RAPIDES -->
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-content">
                    <h4>24</h4>
                    <p>Non lus</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon green">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h4>156</h4>
                    <p>Total</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon orange">
                    <i class="fas fa-paperclip"></i>
                </div>
                <div class="stat-content">
                    <h4>12</h4>
                    <p>Avec pièces jointes</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon purple">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h4>8</h4>
                    <p>Cette semaine</p>
                </div>
            </div>
        </div>

        <!-- DOSSIERS RAPIDES -->
        <div class="folders-section">
            <div class="folder-list">
                <div class="folder-item">
                    <i class="fas fa-inbox"></i>
                    <span>Boîte de réception</span>
                    <span class="count">156</span>
                </div>
                <div class="folder-item">
                    <i class="fas fa-paper-plane"></i>
                    <span>Envoyés</span>
                    <span class="count">89</span>
                </div>
                <div class="folder-item">
                    <i class="fas fa-star"></i>
                    <span>Favoris</span>
                    <span class="count">12</span>
                </div>
                <div class="folder-item">
                    <i class="fas fa-clock"></i>
                    <span>Planifiés</span>
                    <span class="count">5</span>
                </div>
                <div class="folder-item">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Spam</span>
                    <span class="count">3</span>
                </div>
                <div class="folder-item">
                    <i class="fas fa-trash"></i>
                    <span>Corbeille</span>
                    <span class="count">23</span>
                </div>
            </div>
        </div>

        <!-- ALERTE INFO -->
        <div class="alert alert-info">
            <i class="fas fa-sync-alt fa-spin"></i>
            <div>
                <strong>Synchronisation IMAP</strong><br>
                Dernière mise à jour : il y a 5 minutes • 24 nouveaux messages
            </div>
        </div>

        <!-- BARRE D'OUTILS -->
        <div class="inbox-header">
            <div class="inbox-actions">
                <button class="btn-modern btn-outline-modern">
                    <i class="fas fa-sync-alt"></i>
                    <span class="hide-mobile">Synchroniser</span>
                </button>
                <button class="btn-modern btn-outline-modern">
                    <i class="fas fa-archive"></i>
                    <span class="hide-mobile">Archiver</span>
                </button>
                <button class="btn-modern btn-outline-modern">
                    <i class="fas fa-trash"></i>
                    <span class="hide-mobile">Supprimer</span>
                </button>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Rechercher dans les mails...">
                <button>Rechercher</button>
            </div>
        </div>

        <!-- LISTE DES EMAILS -->
        <div class="email-list">
            <!-- Email non lu -->
            <div class="email-item unread">
                <div class="email-check">
                    <input type="checkbox">
                </div>
                <div class="email-star">
                    <i class="far fa-star"></i>
                </div>
                <div class="email-sender">
                    <i class="fas fa-user-circle"></i>
                    Jean Dupont
                </div>
                <div class="email-subject">
                    <span class="subject-text">Proposition commerciale</span>
                    <span class="preview-text">- Voici ma proposition pour le projet...</span>
                </div>
                <div class="email-attachment">
                    <i class="fas fa-paperclip"></i>
                </div>
                <div class="email-date">
                    <i class="fas fa-clock"></i> 09:45
                </div>
            </div>

            <!-- Email avec pièce jointe -->
            <div class="email-item">
                <div class="email-check">
                    <input type="checkbox">
                </div>
                <div class="email-star">
                    <i class="fas fa-star" style="color: #ffb703;"></i>
                </div>
                <div class="email-sender">
                    <i class="fas fa-building"></i>
                    Factures.com
                </div>
                <div class="email-subject">
                    <span class="subject-text">Votre facture F2026-012</span>
                    <span class="preview-text">- Merci de trouver ci-joint...</span>
                </div>
                <div class="email-attachment">
                    <i class="fas fa-paperclip"></i>
                </div>
                <div class="email-date">
                    <i class="fas fa-calendar"></i> 28/02/26
                </div>
            </div>

            <!-- Email simple -->
            <div class="email-item">
                <div class="email-check">
                    <input type="checkbox">
                </div>
                <div class="email-star">
                    <i class="far fa-star"></i>
                </div>
                <div class="email-sender">
                    <i class="fas fa-user-circle"></i>
                    Marie Martin
                </div>
                <div class="email-subject">
                    <span class="subject-text">Réunion équipe</span>
                    <span class="preview-text">- Rappel réunion demain à 10h...</span>
                </div>
                <div class="email-attachment">
                    <i class="fas fa-paperclip" style="opacity: 0.3;"></i>
                </div>
                <div class="email-date">
                    <i class="fas fa-calendar"></i> 27/02/26
                </div>
            </div>

            <!-- Email non lu -->
            <div class="email-item unread">
                <div class="email-check">
                    <input type="checkbox">
                </div>
                <div class="email-star">
                    <i class="far fa-star"></i>
                </div>
                <div class="email-sender">
                    <i class="fas fa-user-circle"></i>
                    Support Technique
                </div>
                <div class="email-subject">
                    <span class="subject-text">Maintenance prévue</span>
                    <span class="preview-text">- Intervention programmée...</span>
                </div>
                <div class="email-attachment">
                    <i class="fas fa-paperclip" style="opacity: 0.3;"></i>
                </div>
                <div class="email-date">
                    <i class="fas fa-clock"></i> 14:20
                </div>
            </div>

            <!-- Email important -->
            <div class="email-item">
                <div class="email-check">
                    <input type="checkbox">
                </div>
                <div class="email-star">
                    <i class="fas fa-star" style="color: #ffb703;"></i>
                </div>
                <div class="email-sender">
                    <i class="fas fa-user-circle"></i>
                    Paul Bernard
                </div>
                <div class="email-subject">
                    <span class="subject-text">URGENT: Contrat à signer</span>
                    <span class="preview-text">- Merci de valider avant demain...</span>
                </div>
                <div class="email-attachment">
                    <i class="fas fa-paperclip"></i>
                </div>
                <div class="email-date">
                    <i class="fas fa-calendar"></i> 26/02/26
                </div>
            </div>
        </div>

        <!-- PAGINATION -->
        <ul class="pagination">
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
            </li>
            <li class="page-item active"><span class="page-link">1</span></li>
            <li class="page-item"><span class="page-link">2</span></li>
            <li class="page-item"><span class="page-link">3</span></li>
            <li class="page-item"><span class="page-link">4</span></li>
            <li class="page-item"><span class="page-link">5</span></li>
            <li class="page-item">
                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
            </li>
        </ul>

        <!-- ALERTE SUCCÈS -->
        <div class="alert alert-success mt-4">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Configuration IMAP active</strong><br>
                Serveur : imap.gmail.com:993 (SSL) • Utilisateur : contact@monentreprise.com
            </div>
        </div>
    </div>
</div>

@endsection