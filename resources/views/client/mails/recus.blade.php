@extends('client.layouts.app')

@section('title', 'Mails reçus')

@section('content')

<!-- Font Awesome 6 (Free) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
:root {
    --primary: #2563eb;
    --primary-light: #3b82f6;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-500: #6b7280;
    --gray-700: #374151;
    --gray-900: #111827;
    --success: #10b981;
    --warning: #f59e0b;
    --error: #ef4444;
    --shadow: 0 1px 3px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
    --radius: 12px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.mail-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 1.5rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: var(--gray-50);
    min-height: 100vh;
}

.card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--gray-200);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    color: var(--primary);
}

/* Status */
.status {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: var(--radius);
    border: 1px solid var(--gray-200);
    margin-bottom: 1.5rem;
}

.status-success { color: var(--success); }
.status-error { color: var(--error); }
.status-warning { color: var(--warning); }

/* Grid */
.grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 768px) {
    .grid-2 { grid-template-columns: 1fr; }
}

/* Form elements */
.form-group {
    margin-bottom: 1.25rem;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: 0.375rem;
}

.form-group label i {
    color: var(--primary);
    margin-right: 0.375rem;
    width: 1rem;
}

.form-control {
    width: 100%;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    border: 1px solid var(--gray-200);
    border-radius: 0.5rem;
    background: white;
    transition: all 0.15s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 0.5rem;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.15s;
    background: white;
    color: var(--gray-700);
    border-color: var(--gray-200);
}

.btn:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
}

.btn-primary {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.btn-primary:hover {
    background: var(--primary-light);
    border-color: var(--primary-light);
}

.btn-success {
    background: var(--success);
    color: white;
}

.btn-success:hover {
    opacity: 0.9;
}

.btn-outline {
    background: transparent;
    border-color: var(--primary);
    color: var(--primary);
}

.btn-outline:hover {
    background: var(--primary);
    color: white;
}

.btn-link {
    background: transparent;
    border: none;
    color: var(--primary);
    padding: 0.375rem 0.75rem;
}

.btn-link:hover {
    background: var(--gray-100);
}

/* Alerts */
.alert {
    padding: 0.875rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    margin-top: 1rem;
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
}

.alert-info {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1e40af;
}

.alert-success {
    background: #f0fdf4;
    border-color: #bbf7d0;
    color: #166534;
}

.alert-warning {
    background: #fffbeb;
    border-color: #fde68a;
    color: #92400e;
}

/* Tutorial links */
.tutorial-links {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin: 1rem 0;
    padding: 1rem 0;
    border-top: 1px solid var(--gray-200);
    border-bottom: 1px solid var(--gray-200);
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}

.stat-item {
    background: var(--gray-50);
    border-radius: 0.75rem;
    padding: 1rem;
    border: 1px solid var(--gray-200);
}

.stat-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
    color: var(--primary);
    border: 1px solid var(--gray-200);
}

.stat-content h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
}

.stat-content p {
    font-size: 0.75rem;
    color: var(--gray-500);
}

/* Email list */
.email-list {
    border: 1px solid var(--gray-200);
    border-radius: 0.75rem;
    overflow: hidden;
}

.email-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: white;
    border-bottom: 1px solid var(--gray-200);
    cursor: pointer;
    transition: all 0.15s;
}

.email-item:last-child {
    border-bottom: none;
}

.email-item:hover {
    background: var(--gray-50);
}

.email-sender {
    font-weight: 500;
    color: var(--gray-900);
    min-width: 180px;
}

.email-subject {
    color: var(--gray-700);
    font-size: 0.875rem;
}

.email-subject .preview {
    color: var(--gray-500);
    margin-left: 0.5rem;
}

.email-date {
    font-size: 0.75rem;
    color: var(--gray-500);
    white-space: nowrap;
}

/* Modal */
.mail-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.mail-modal-content {
    background: white;
    border-radius: 1rem;
    width: 90%;
    max-width: 700px;
    max-height: 80vh;
    overflow-y: auto;
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
}

.mail-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--gray-200);
}

.mail-modal-header h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-900);
}

.mail-modal-header button {
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: var(--gray-500);
    padding: 0.25rem 0.5rem;
}

.mail-modal-meta {
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
    color: var(--gray-700);
}

.mail-modal-meta p {
    margin-bottom: 0.25rem;
}

/* Pagination */
.pagination {
    display: flex;
    gap: 0.25rem;
    justify-content: center;
    margin-top: 1.5rem;
}

.pagination a, .pagination span {
    padding: 0.375rem 0.75rem;
    border: 1px solid var(--gray-200);
    border-radius: 0.375rem;
    color: var(--gray-700);
    text-decoration: none;
    font-size: 0.875rem;
}

.pagination .active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
</style>

<div class="mail-container">

    <!-- Configuration IMAP -->
    <div class="card">
        <h2 class="section-title">
            <i class="fas fa-cog"></i>
            Configuration IMAP
        </h2>

        <!-- Status IMAP -->
        <div class="status">
            @if($imap && $imap->last_test_success)
                <i class="fas fa-check-circle status-success"></i>
                <div>
                    <strong style="color: var(--success);">IMAP opérationnel</strong>
                    <div style="font-size: 0.875rem; color: var(--gray-500);">{{ $imap->host }}:{{ $imap->port }}</div>
                </div>
            @elseif($imap && $imap->last_test_success === false)
                <i class="fas fa-times-circle status-error"></i>
                <strong style="color: var(--error);">Erreur de connexion</strong>
            @else
                <i class="fas fa-exclamation-circle status-warning"></i>
                <strong style="color: var(--warning);">Configuration non testée</strong>
            @endif
        </div>

        <!-- Liens Gmail -->
        <div class="tutorial-links">
            <a href="https://myaccount.google.com/security" target="_blank" class="btn btn-link">
                <i class="fas fa-external-link-alt"></i> Sécurité Google
            </a>
            <a href="https://myaccount.google.com/apppasswords" target="_blank" class="btn btn-link">
                <i class="fas fa-external-link-alt"></i> Mots de passe application
            </a>
            <a href="https://mail.google.com/" target="_blank" class="btn btn-link">
                <i class="fas fa-external-link-alt"></i> Gmail
            </a>
        </div>

        <div class="grid-2">
            <!-- Formulaire configuration -->
            <div>
                <form method="POST" action="{{ route('client.mails.imap.save') }}">
                    @csrf
                    <div class="form-group">
                        <label><i class="fas fa-server"></i> Serveur IMAP</label>
                        <input type="text" name="host" class="form-control" value="{{ $imap->host ?? '' }}" placeholder="imap.gmail.com" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-plug"></i> Port</label>
                            <input type="number" name="port" class="form-control" value="{{ $imap->port ?? 993 }}" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-shield-alt"></i> Sécurité</label>
                            <select name="encryption" class="form-control">
                                <option value="ssl" {{ ($imap->encryption ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="tls" {{ ($imap->encryption ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="none" {{ ($imap->encryption ?? '') === 'none' ? 'selected' : '' }}>Aucune</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="username" class="form-control" value="{{ $imap->username ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="{{ $imap ? '••••••••' : '' }}" {{ $imap ? '' : 'required' }}>
                        @if($imap)<small style="color: var(--gray-500);">Laissez vide pour conserver</small>@endif
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-folder"></i> Dossier racine</label>
                        <input type="text" name="folder" class="form-control" value="{{ $imap->folder ?? 'INBOX' }}" required>
                    </div>

                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                </form>

                @if($imap)
                <form method="POST" action="{{ route('client.mails.imap.test') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline">
                        <i class="fas fa-vial"></i> Tester
                    </button>
                </form>
                @endif
                    </div>
            </div>

            <!-- Configuration actuelle -->
            <div>
                <h3 style="font-size: 1rem; margin-bottom: 1rem; display: flex; gap: 0.5rem;">
                    <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                    Configuration actuelle
                </h3>

                @if($imap)
                <div style="background: var(--gray-50); border-radius: 0.5rem; padding: 1rem; border: 1px solid var(--gray-200);">
                    <div style="display: grid; gap: 0.5rem;">
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            @if($imap->last_test_success)
                                <i class="fas fa-check-circle" style="color: var(--success);"></i>
                                <span style="color: var(--success);">Connecté</span>
                            @elseif($imap->last_test_success === false)
                                <i class="fas fa-times-circle" style="color: var(--error);"></i>
                                <span style="color: var(--error);">Erreur</span>
                            @else
                                <i class="fas fa-exclamation-circle" style="color: var(--warning);"></i>
                                <span style="color: var(--warning);">Non testé</span>
                            @endif
                        </div>

                        <div style="font-size: 0.875rem;">
                            <p><strong>Serveur:</strong> {{ $imap->host }}:{{ $imap->port }}</p>
                            <p><strong>Sécurité:</strong> {{ strtoupper($imap->encryption) }}</p>
                            <p><strong>Email:</strong> {{ $imap->username }}</p>
                            <p><strong>Dossier:</strong> {{ $imap->folder }}</p>
                            @if($imap->last_sync_at)
                                <p><strong>Dernière synchro:</strong> {{ $imap->last_sync_at->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Aucune configuration enregistrée</span>
                </div>
                @endif

                <div class="alert alert-info" style="margin-top: 1rem;">
                    <i class="fas fa-lightbulb"></i>
                    <div>
                        <strong>Gmail :</strong> Activez la validation en 2 étapes et utilisez un <a href="https://myaccount.google.com/apppasswords" target="_blank" style="color: var(--primary);">mot de passe d'application</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Options avancées -->
        <details style="margin-top: 1.5rem;">
            <summary style="cursor: pointer; color: var(--primary); font-weight: 500;">
                <i class="fas fa-chevron-down" style="margin-right: 0.5rem;"></i>
                Options avancées
            </summary>
            <div style="margin-top: 1rem; padding: 1rem; background: var(--gray-50); border-radius: 0.5rem;">
                <div style="display: grid; gap: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" checked> Valider certificat SSL
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" checked> Valider authentification
                    </label>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Timeout connexion (sec)</label>
                        <input type="number" class="form-control" value="30" style="max-width: 120px;">
                    </div>
                </div>
            </div>
        </details>
    </div>

    <!-- Boîte de réception -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <h2 class="section-title" style="margin-bottom: 0;">
                <i class="fas fa-inbox"></i>
                Mails reçus
            </h2>

            @if($imap && $imap->last_test_success)
            <form method="POST" action="{{ route('client.mails.imap.sync') }}">
                @csrf
                <button type="submit" class="btn btn-outline">
                    <i class="fas fa-sync-alt"></i> Synchroniser
                </button>
            </form>
            @endif
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                <div class="stat-content">
                    <h4>{{ $stats['unread'] ?? 0 }}</h4>
                    <p>Non lus</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-star"></i></div>
                <div class="stat-content">
                    <h4>{{ $stats['total'] ?? 0 }}</h4>
                    <p>Total</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-paperclip"></i></div>
                <div class="stat-content">
                    <h4>{{ $stats['attachments'] ?? 0 }}</h4>
                    <p>Pièces jointes</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-content">
                    <h4>{{ $stats['week'] ?? 0 }}</h4>
                    <p>Cette semaine</p>
                </div>
            </div>
        </div>

        @if($imap && $imap->last_sync_at)
        <div class="alert alert-info" style="margin-bottom: 1rem;">
            <i class="fas fa-sync-alt"></i>
            <span>Dernière synchronisation : {{ $imap->last_sync_at->diffForHumans() }}</span>
        </div>
        @endif

        <!-- Liste emails -->
        <div class="email-list">
            @forelse($messages as $message)
            <div class="email-item" 
                 data-subject="{{ e($message->getSubject()) }}"
                 data-from="{{ e($message->getFrom()[0]->mail ?? '') }}"
                 data-date="{{ $message->getDate() && $message->getDate()->first() ? \Carbon\Carbon::parse($message->getDate()->first())->format('d/m/Y H:i') : '' }}"
                 data-body="{{ e(Str::limit(strip_tags($message->getTextBody()), 500)) }}"
                 onclick="openMailModal(this)">
                
                <div class="email-sender">
                    <i class="fas fa-user-circle" style="margin-right: 0.5rem; color: var(--primary);"></i>
                    {{ $message->getFrom()[0]->mail ?? 'Inconnu' }}
                </div>

                <div class="email-subject">
                    <span>{{ $message->getSubject() }}</span>
                    @if($message->getTextBody())
                    <span class="preview">- {{ Str::limit(strip_tags($message->getTextBody()), 40) }}</span>
                    @endif
                </div>

                <div class="email-date">
                    @if($message->getDate() && $message->getDate()->first())
                        {{ \Carbon\Carbon::parse($message->getDate()->first())->format('d/m/Y H:i') }}
                    @endif
                </div>
            </div>
            @empty
            <div style="padding: 2rem; text-align: center; color: var(--gray-500);">
                Aucun message trouvé
            </div>
            @endforelse
        </div>

        @if($messages instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="pagination">
            {{ $messages->links() }}
        </div>
        @endif

        @if($imap && $imap->last_test_success)
        <div class="alert alert-success" style="margin-top: 1rem;">
            <i class="fas fa-check-circle"></i>
            <span>Connecté à {{ $imap->host }}:{{ $imap->port }} ({{ $imap->username }})</span>
        </div>
        @endif
    </div>
</div>

<!-- Modal email -->
<div id="mailModal" class="mail-modal">
    <div class="mail-modal-content">
        <div class="mail-modal-header">
            <h3 id="modalSubject"></h3>
            <button onclick="closeMailModal()">✕</button>
        </div>
        <div class="mail-modal-meta">
            <p><strong>De :</strong> <span id="modalFrom"></span></p>
            <p><strong>Date :</strong> <span id="modalDate"></span></p>
        </div>
        <div class="mail-modal-body" id="modalBody" style="line-height: 1.6;"></div>
    </div>
</div>

<script>
function openMailModal(element) {
    document.getElementById('modalSubject').innerText = element.dataset.subject;
    document.getElementById('modalFrom').innerText = element.dataset.from;
    document.getElementById('modalDate').innerText = element.dataset.date;
    document.getElementById('modalBody').innerHTML = element.dataset.body;
    document.getElementById('mailModal').style.display = 'flex';
}

function closeMailModal() {
    document.getElementById('mailModal').style.display = 'none';
}

// Fermer modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMailModal();
});

// Fermer en cliquant hors modal
document.getElementById('mailModal').addEventListener('click', function(e) {
    if (e.target === this) closeMailModal();
});
</script>

@endsection