@extends('client.layouts.app')

@section('title', 'Système d\'envoi de mails')

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
    --transition: all 0.2s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* ================= CONTAINER PRINCIPAL ================= */
.mail-container {
    max-width: 1300px;
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
    transform: translateY(-2px);
}

.section-title {
    font-weight: 600;
    font-size: 1.4rem;
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
    font-size: 1.6rem;
}

.section-title::before {
    content: '';
    width: 4px;
    height: 28px;
    background: var(--primary);
    border-radius: 4px;
}

/* ================= STATISTIQUES ================= */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 22px;
}

.stat-box {
    border-radius: var(--radius);
    padding: 28px 20px;
    text-align: center;
    color: var(--white);
    font-weight: 500;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-box::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.1);
    opacity: 0;
    transition: var(--transition);
}

.stat-box:hover::after {
    opacity: 1;
}

.stat-box i {
    font-size: 2.5rem;
    margin-bottom: 15px;
    opacity: 0.9;
}

.stat-box h3 {
    font-size: 2.6rem;
    font-weight: 700;
    margin-bottom: 8px;
    line-height: 1.2;
}

.stat-box p {
    font-size: 1rem;
    opacity: 0.9;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    font-weight: 500;
}

.bg-blue { 
    background: linear-gradient(145deg, #4361ee, #3a0ca3);
}

.bg-green { 
    background: linear-gradient(145deg, #06d6a0, #118ab2);
}

.bg-red { 
    background: linear-gradient(145deg, #ef476f, #bc4e9c);
}

/* ================= ÉTAT SMTP ================= */
.smtp-status {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
    padding: 15px 20px;
    background: #f8fafd;
    border-radius: var(--radius-sm);
    border: 1px solid #e9ecef;
}

.smtp-status i {
    font-size: 2.2rem;
}

.smtp-status .status-text {
    font-weight: 600;
    font-size: 1.1rem;
}

.smtp-status .status-details {
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

/* ================= CONFIGURATION SMTP ================= */
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

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.btn-test {
    background: #6c5ce7;
    color: white;
}

.btn-test:hover {
    background: #5b4bc4;
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

textarea.form-control {
    min-height: 120px;
    resize: vertical;
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

.btn-link-modern {
    background: transparent;
    color: var(--primary);
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    transition: var(--transition);
    border: 2px solid transparent;
}

.btn-link-modern:hover {
    background: rgba(67, 97, 238, 0.1);
    border-color: var(--primary);
}

/* ================= ALERTES & NOTIFICATIONS ================= */
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

.alert strong {
    font-weight: 700;
    display: block;
    margin-bottom: 8px;
    font-size: 1rem;
}

/* ================= TUTORIEL ================= */
.tutorial-steps {
    list-style: none;
    padding: 0;
    margin-bottom: 20px;
}

.tutorial-steps li {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px dashed #e9ecef;
}

.tutorial-steps li:last-child {
    border-bottom: none;
}

.tutorial-steps li i {
    width: 28px;
    height: 28px;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.tutorial-steps li span {
    color: #2d3e50;
    font-weight: 450;
    line-height: 1.5;
}

.tutorial-links {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

/* ================= TABLEAU ================= */
.table-responsive {
    overflow-x: auto;
    border-radius: var(--radius-sm);
    margin-top: 10px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    background: var(--white);
    border-radius: var(--radius-sm);
    overflow: hidden;
}

.table thead tr {
    background: #f2f5f9;
    border-bottom: 2px solid #e2e8f0;
}

.table th {
    padding: 16px 18px;
    font-weight: 600;
    color: var(--dark);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
}

.table th i {
    margin-right: 8px;
    color: var(--primary);
    font-size: 0.9rem;
}

.table td {
    padding: 14px 18px;
    border-bottom: 1px solid #edf2f7;
    color: #4a5568;
    font-size: 0.95rem;
}

.table tbody tr:hover {
    background: #f8fafd;
}

.table tbody tr:last-child td {
    border-bottom: none;
}

/* ================= BADGES ================= */
.badge-success {
    background: rgba(6, 214, 160, 0.12);
    color: #0b8e6b;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid rgba(6, 214, 160, 0.2);
}

.badge-success i {
    color: #0b8e6b;
    font-size: 0.7rem;
}

.badge-danger {
    background: rgba(239, 71, 111, 0.12);
    color: #b13e5b;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid rgba(239, 71, 111, 0.2);
}

.badge-danger i {
    color: #b13e5b;
    font-size: 0.7rem;
}

/* ================= OPTIONS AVANCÉES ================= */
details {
    background: #f8fafd;
    border-radius: var(--radius-sm);
    padding: 15px;
    margin-top: 20px;
}

details summary {
    font-weight: 600;
    color: var(--dark);
    cursor: pointer;
    list-style: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

details summary i {
    color: var(--primary);
    transition: var(--transition);
}

details[open] summary i {
    transform: rotate(180deg);
}

.form-check {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.form-check input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--primary);
}

.mail-container {
    max-width: 1300px;
    margin: 0 auto;
    padding: 24px 20px;
    background: #f5f7fb;
}
.mail-card {
    background: #fff;
    border-radius: 16px;
    padding: 28px 30px;
    margin-bottom: 28px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
}
.section-title {
    font-weight: 600;
    font-size: 1.4rem;
    margin-bottom: 24px;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 22px;
}
.stat-box {
    border-radius: 16px;
    padding: 28px 20px;
    text-align: center;
    color: #fff;
}
.bg-blue { background: linear-gradient(145deg,#4361ee,#3a0ca3); }
.bg-green { background: linear-gradient(145deg,#06d6a0,#118ab2); }
.bg-red { background: linear-gradient(145deg,#ef476f,#bc4e9c); }
.table {
    width: 100%;
    border-collapse: collapse;
}
.table th, .table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}
.badge-success {
    background: rgba(6,214,160,0.12);
    color:#0b8e6b;
    padding:6px 12px;
    border-radius:20px;
}
.badge-danger {
    background: rgba(239,71,111,0.12);
    color:#b13e5b;
    padding:6px 12px;
    border-radius:20px;
}
.btn-modern {
    padding: 12px 24px;
    border-radius: 50px;
    border: none;
    cursor: pointer;
    font-weight: 600;
}
.btn-primary-modern { background:#4361ee; color:white; }
.btn-success-modern { background:#06d6a0; color:white; }
.form-control {
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:8px;
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
        margin-bottom: 20px;
    }
    
    .config-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .stat-box {
        padding: 22px 15px;
    }
    
    .stat-box h3 {
        font-size: 2.2rem;
    }
    
    .mail-card {
        padding: 20px 16px;
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 1.2rem;
        padding-bottom: 12px;
    }
    
    .btn-modern {
        width: 100%;
        padding: 14px 20px;
    }
    
    .table th,
    .table td {
        padding: 12px 10px;
        font-size: 0.85rem;
    }
    
    .alert {
        padding: 14px 16px;
    }
    
    .smtp-status {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .tutorial-links {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .mail-container {
        padding: 16px 12px;
    }
    
    .mail-card {
        padding: 16px 14px;
        border-radius: 14px;
    }
    
    .section-title {
        font-size: 1.1rem;
        margin-bottom: 16px;
    }
    
    .stat-box h3 {
        font-size: 2rem;
    }
    
    .stat-box p {
        font-size: 0.85rem;
    }
    
    .form-control {
        padding: 10px 14px;
        font-size: 0.9rem;
    }
    
    .btn-modern {
        padding: 12px 16px;
        font-size: 0.9rem;
    }
    
    .table th,
    .table td {
        padding: 10px 8px;
        font-size: 0.8rem;
    }
    
    .badge-success,
    .badge-danger {
        padding: 4px 10px;
        font-size: 0.7rem;
    }
    
    .tutorial-steps li {
        gap: 10px;
        padding: 10px 0;
    }
    
    .tutorial-steps li i {
        width: 24px;
        height: 24px;
        font-size: 0.8rem;
    }
    
    .tutorial-steps li span {
        font-size: 0.9rem;
    }
}

@media (max-width: 360px) {
    .section-title {
        font-size: 1rem;
    }
    
    .stat-box h3 {
        font-size: 1.8rem;
    }
    
    .table th,
    .table td {
        padding: 8px 6px;
        font-size: 0.75rem;
    }
}

@media (min-width: 1600px) {
    .mail-container {
        max-width: 1500px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* ================= UTILITAIRES ================= */
.mb-3 {
    margin-bottom: 1rem;
}

.mt-4 {
    margin-top: 1.5rem;
}

.text-muted {
    color: var(--gray);
}

.d-flex {
    display: flex;
}

.align-center {
    align-items: center;
}

.gap-2 {
    gap: 8px;
}
</style>

<div class="mail-container">

    @if(session('success'))
        <div class="mail-card" style="background:#d1fae5;color:#065f46;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mail-card" style="background:#fee2e2;color:#991b1b;">
            {{ session('error') }}
        </div>
    @endif
    {{-- ================= STATISTIQUES ================= --}}
    <div class="mail-card">
        <h2 class="section-title"><i class="fas fa-chart-pie"></i> Statistiques</h2>
        <div class="stats-grid">
            <div class="stat-box bg-blue">
                <i class="fas fa-envelope"></i>
                <h3>{{ $stats['total'] }}</h3>
                <p>Mails envoyés</p>
            </div>
            <div class="stat-box bg-green">
                <i class="fas fa-check-circle"></i>
                <h3>{{ $stats['success'] }}</h3>
                <p>Succès</p>
            </div>
            <div class="stat-box bg-red">
                <i class="fas fa-exclamation-circle"></i>
                <h3>{{ $stats['failed'] }}</h3>
                <p>Échecs</p>
            </div>
        </div>
    </div>

    <!-- ================= CONFIG SMTP ================= -->
<div class="mail-card">
    <h2 class="section-title">
        <i class="fas fa-cog"></i>
        Configuration SMTP
    </h2>

    {{-- ================= ÉTAT SMTP DYNAMIQUE ================= --}}
    <div class="smtp-status">
        @if($smtp && $smtp->last_test_success)
            <i class="fas fa-check-circle status-success"></i>
            <div>
                <div class="status-text status-success">
                    <i class="fas fa-circle"></i> SMTP opérationnel
                </div>
                <div class="status-details">
                    Serveur: {{ $smtp->host }}:{{ $smtp->port }}
                    | {{ strtoupper($smtp->encryption ?? 'Aucune') }}
                </div>
            </div>
        @elseif($smtp && $smtp->last_test_success === false)
            <i class="fas fa-times-circle status-error"></i>
            <div>
                <div class="status-text status-error">
                    <i class="fas fa-circle"></i> Erreur SMTP
                </div>
                <div class="status-details">
                    Dernier test échoué
                </div>
            </div>
        @else
            <i class="fas fa-exclamation-circle status-warning"></i>
            <div>
                <div class="status-text status-warning">
                    <i class="fas fa-circle"></i> SMTP non testé
                </div>
                <div class="status-details">
                    Configurez puis testez votre SMTP
                </div>
            </div>
        @endif
    </div>


    <div class="config-grid">

        {{-- ================= FORMULAIRE ================= --}}
        <div class="config-card">
            <h3><i class="fas fa-sliders-h"></i> Paramètres SMTP</h3>

            <form method="POST" action="{{ route('client.mails.smtp.save') }}">
                @csrf

                <div class="form-group">
                    <label><i class="fas fa-server"></i> Serveur SMTP</label>
                    <input type="text" name="host"
                           class="form-control"
                           value="{{ $smtp->host ?? 'smtp.gmail.com' }}"
                           required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-plug"></i> Port</label>
                        <input type="number" name="port"
                               class="form-control"
                               value="{{ $smtp->port ?? 587 }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-shield-alt"></i> Sécurité</label>
                        <select name="encryption" class="form-control">
                            <option value="tls" {{ ($smtp->encryption ?? '')=='tls'?'selected':'' }}>TLS</option>
                            <option value="ssl" {{ ($smtp->encryption ?? '')=='ssl'?'selected':'' }}>SSL</option>
                            <option value="">Aucune</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email expéditeur</label>
                    <input type="email" name="username"
                           class="form-control"
                           value="{{ $smtp->username ?? '' }}"
                           required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-key"></i> Mot de passe d'application</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Laisser vide pour conserver l'ancien">
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <button type="submit" class="btn-modern btn-primary-modern">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>

            {{-- Bouton TEST SMTP --}}
            @if($smtp)
                <form method="POST" action="{{ route('client.mails.smtp.test') }}" style="margin-top:10px;">
                    @csrf
                    <button type="submit" class="btn-modern btn-test">
                        <i class="fas fa-vial"></i> Tester SMTP
                    </button>
                </form>
            @endif
        </div>


        {{-- ================= CONFIG ACTUELLE ================= --}}
        <div class="config-card">
            <h3><i class="fas fa-info-circle"></i> Configuration actuelle</h3>

            @if($smtp)
                <div class="config-details">
                    <p>
                        <i class="fas fa-server"></i>
                        <strong>Serveur:</strong>
                        {{ $smtp->host }}:{{ $smtp->port }}
                    </p>

                    <p>
                        <i class="fas fa-shield-alt"></i>
                        <strong>Sécurité:</strong>
                        {{ strtoupper($smtp->encryption ?? 'Aucune') }}
                    </p>

                    <p>
                        <i class="fas fa-envelope"></i>
                        <strong>Email:</strong>
                        {{ $smtp->username }}
                    </p>

                    @if($smtp->last_tested_at)
                        <p>
                            <i class="fas fa-clock"></i>
                            <strong>Dernier test:</strong>
                            {{ $smtp->last_tested_at->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Aucune configuration SMTP enregistrée.
                </div>
            @endif

            <div class="alert alert-info" style="margin-top:15px;">
                <i class="fas fa-lightbulb"></i>
                <div>
                    <strong>Gmail :</strong><br>
                    Serveur : smtp.gmail.com<br>
                    Port : 587<br>
                    Sécurité : TLS
                </div>
            </div>
        </div>

    </div>
</div>

    <!-- ================= TUTORIEL AVEC LIENS ================= -->
    <div class="mail-card">
        <h2 class="section-title">
            <i class="fas fa-graduation-cap"></i>
            Tutoriel Gmail
        </h2>

        <ul class="tutorial-steps">
            <li>
                <i class="fas fa-1"></i>
                <span>Connectez-vous à votre compte Google</span>
            </li>
            <li>
                <i class="fas fa-2"></i>
                <span>Allez dans Sécurité</span>
            </li>
            <li>
                <i class="fas fa-3"></i>
                <span>Activez la validation en 2 étapes</span>
            </li>
            <li>
                <i class="fas fa-4"></i>
                <span>Accédez à "Mots de passe des applications"</span>
            </li>
            <li>
                <i class="fas fa-5"></i>
                <span>Générez un mot de passe pour Mail</span>
            </li>
            <li>
                <i class="fas fa-6"></i>
                <span>Copiez et collez-le ici</span>
            </li>
        </ul>

        <!-- Liens directs vers Gmail -->
        <div class="tutorial-links">
            <a href="https://myaccount.google.com/security" target="_blank" class="btn-link-modern">
                <i class="fas fa-external-link-alt"></i>
                Sécurité du compte Google
            </a>
            <a href="https://myaccount.google.com/apppasswords" target="_blank" class="btn-link-modern">
                <i class="fas fa-external-link-alt"></i>
                Créer un mot de passe d'application
            </a>
            <a href="https://mail.google.com/" target="_blank" class="btn-link-modern">
                <i class="fas fa-external-link-alt"></i>
                Accéder à Gmail
            </a>
        </div>

        <div class="alert alert-warning mt-4">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>⚠️ Important :</strong> Ne jamais utiliser votre vrai mot de passe Gmail. Utilisez uniquement un mot de passe d'application généré spécialement.
            </div>
        </div>

        <div class="alert alert-success mt-3">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>📱 Mot de passe d'application :</strong> Il doit être généré depuis <a href="https://myaccount.google.com/apppasswords" target="_blank" style="color: var(--success); text-decoration: underline;">cette page</a> après avoir activé la validation en 2 étapes.
            </div>
        </div>
    </div>

   <!-- ================= ENVOI MAIL ================= -->
<div class="mail-card">
    <h2 class="section-title">
        <i class="fas fa-paper-plane"></i>
        Envoyer un mail
    </h2>

    @if(!$smtp)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Configurez votre SMTP avant d’envoyer un mail.
        </div>
    @else
        <form method="POST" action="{{ route('client.mails.send') }}">
            @csrf

            <div class="form-group">
                <label>
                    <i class="fas fa-user"></i>
                    Destinataire
                </label>
                <input type="email"
                       name="to"
                       class="form-control"
                       value="{{ old('to') }}"
                       required>
                @error('to')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-heading"></i>
                    Sujet
                </label>
                <input type="text"
                       name="subject"
                       class="form-control"
                       value="{{ old('subject') }}"
                       required>
                @error('subject')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-comment"></i>
                    Message (HTML autorisé)
                </label>
                <textarea name="message"
                          class="form-control"
                          rows="5"
                          required>{{ old('message') }}</textarea>
                @error('message')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-modern btn-success-modern">
                <i class="fas fa-paper-plane"></i>
                Envoyer
            </button>
        </form>
    @endif
</div>



<!-- ================= LISTE MAILS ================= -->
<div class="mail-card">
    <h2 class="section-title">
        <i class="fas fa-inbox"></i>
        Historique des mails envoyés
    </h2>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th><i class="fas fa-user"></i> Destinataire</th>
                    <th><i class="fas fa-tag"></i> Sujet</th>
                    <th><i class="fas fa-calendar"></i> Date</th>
                    <th><i class="fas fa-flag"></i> Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>
                            <i class="fas fa-envelope-open-text"></i>
                            {{ $log->to }}
                        </td>
                        <td>{{ $log->subject }}</td>
                        <td>
                            <i class="fas fa-clock"></i>
                            {{ $log->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            @if($log->status === 'success')
                                <span class="badge-success">
                                    <i class="fas fa-check-circle"></i> Succès
                                </span>
                            @else
                                <span class="badge-danger">
                                    <i class="fas fa-times-circle"></i> Échec
                                </span>

                                @if($log->error_message)
                                    <div style="font-size:12px;color:#b13e5b;margin-top:4px;">
                                        {{ Str::limit($log->error_message, 80) }}
                                    </div>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:20px;">
                            Aucun mail envoyé pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination si activée --}}
    @if(method_exists($logs, 'links'))
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    @endif
</div>

    <!-- ================= RAPPEL SECURITE ================= -->
    <div class="mail-card" style="background: linear-gradient(145deg, #f8fafd, #ffffff);">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
            <i class="fas fa-shield-alt" style="font-size: 3rem; color: var(--primary);"></i>
            <div style="flex: 1;">
                <h3 style="margin-bottom: 10px;">Sécurisez votre envoi</h3>
                <p style="color: var(--gray);">Pour une configuration réussie, suivez le tutoriel et utilisez les liens directs vers Gmail. N'oubliez pas d'activer la validation en 2 étapes avant de créer votre mot de passe d'application.</p>
            </div>
            <a href="https://myaccount.google.com/security" target="_blank" class="btn-modern btn-primary-modern">
                <i class="fas fa-external-link-alt"></i>
                Aller à la sécurité Google
            </a>
        </div>
    </div>

</div>

@endsection