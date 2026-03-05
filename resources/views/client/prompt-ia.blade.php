@extends('client.layouts.app')

@section('title','Prompt IA')

@section('content')

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fa-solid fa-robot"></i> Prompt IA
        </h2>
        <button onclick="openAddModal()" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Nouveau Prompt
        </button>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Groupe</th>
                    <th>Prompt</th>
                    <th class="actions-header">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prompts as $prompt)
                <tr>
                    <td class="groupe-cell">
                        <span class="groupe-badge">{{ $prompt->nom_groupe }}</span>
                    </td>
                    <td class="prompt-cell">
                        <div class="prompt-content">
                            {{ Str::limit($prompt->prompt, 120) }}
                        </div>
                    </td>
                    <td class="actions-cell">
                        <div class="action-buttons">
                            <button class="btn-icon btn-edit" 
                                    onclick="openEditModal(
                                        '{{ $prompt->id }}',
                                        `{{ $prompt->nom_groupe }}`,
                                        `{{ $prompt->prompt }}`
                                    )" 
                                    title="Modifier">
                                <i class="fa-solid fa-pen"></i>
                                <span class="btn-text">Modifier</span>
                            </button>
                            <button class="btn-icon btn-delete" 
                                    onclick="openDeleteModal('{{ $prompt->id }}')"
                                    title="Supprimer">
                                <i class="fa-solid fa-trash"></i>
                                <span class="btn-text">Supprimer</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($prompts->isEmpty())
    <div class="empty-state">
        <i class="fa-solid fa-message"></i>
        <p>Aucun prompt disponible</p>
        <button onclick="openAddModal()" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Créer votre premier prompt
        </button>
    </div>
    @endif
</div>

{{-- ADD MODAL --}}
<div id="addModal" class="modal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3>
                <i class="fa-solid fa-plus-circle"></i> Nouveau Prompt
            </h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('client.prompt-ia.store') }}">
                @csrf
                <div class="form-group">
                    <label for="add_groupe">
                        <i class="fa-solid fa-layer-group"></i> Groupe
                    </label>
                    <select name="nom_groupe" id="add_groupe" required>
                        <option value="">Sélectionner un groupe</option>
                        @foreach($groupesAdd as $groupe)
                        <option value="{{ $groupe }}">{{ $groupe }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="add_prompt">
                        <i class="fa-solid fa-message"></i> Prompt
                    </label>
                    <textarea name="prompt" id="add_prompt" rows="6" placeholder="Saisissez votre prompt ici..." required></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">
                        <i class="fa-solid fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div id="editModal" class="modal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3>
                <i class="fa-solid fa-edit"></i> Modifier Prompt
            </h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit_groupe">
                        <i class="fa-solid fa-layer-group"></i> Groupe
                    </label>
                    <select name="nom_groupe" id="edit_groupe" required>
                        @foreach($groupesEdit as $groupe)
                        <option value="{{ $groupe }}">{{ $groupe }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_prompt">
                        <i class="fa-solid fa-message"></i> Prompt
                    </label>
                    <textarea name="prompt" id="edit_prompt" rows="6" required></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">
                        <i class="fa-solid fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> Modifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- DELETE MODAL --}}
<div id="deleteModal" class="modal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-container modal-sm">
        <div class="modal-header modal-header-danger">
            <h3>
                <i class="fa-solid fa-exclamation-triangle"></i> Confirmation
            </h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="delete-confirm">
                <i class="fa-solid fa-trash-alt delete-icon"></i>
                <p>Êtes-vous sûr de vouloir supprimer ce prompt ?</p>
                <p class="delete-warning">Cette action est irréversible.</p>
            </div>
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">
                        <i class="fa-solid fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i> Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* ============================================================================
   DESIGN SYSTEM VARIABLES
   ============================================================================ */
:root {
    /* Couleurs principales */
    --primary-50: #f0f9ff;
    --primary-100: #e0f2fe;
    --primary-200: #bae6fd;
    --primary-300: #7dd3fc;
    --primary-400: #38bdf8;
    --primary-500: #0ea5e9;
    --primary-600: #0284c7;
    --primary-700: #0369a1;
    
    /* Couleurs de succès */
    --success-50: #f0fdf4;
    --success-100: #dcfce7;
    --success-500: #22c55e;
    --success-600: #16a34a;
    
    /* Couleurs de danger */
    --danger-50: #fef2f2;
    --danger-100: #fee2e2;
    --danger-500: #ef4444;
    --danger-600: #dc2626;
    
    /* Couleurs de warning */
    --warning-50: #fffbeb;
    --warning-500: #f59e0b;
    
    /* Ombres */
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    
    /* Espacements */
    --spacing-1: 0.25rem;
    --spacing-2: 0.5rem;
    --spacing-3: 0.75rem;
    --spacing-4: 1rem;
    --spacing-5: 1.25rem;
    --spacing-6: 1.5rem;
    --spacing-8: 2rem;
    
    /* Bordures */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    
    /* Transitions */
    --transition-base: all 0.2s ease;
}

/* ============================================================================
   CARD STYLES
   ============================================================================ */
.card {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    transition: var(--transition-base);
    border: 1px solid #e5e7eb;
    margin: var(--spacing-4);
}

.card:hover {
    box-shadow: var(--shadow-xl);
}

.card-header {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-4);
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-5) var(--spacing-6);
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(to right, #f9fafb, #ffffff);
}

.card-title {
    display: flex;
    align-items: center;
    gap: var(--spacing-2);
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.card-title i {
    color: var(--primary-600);
    font-size: 1.5rem;
}

/* ============================================================================
   BUTTON STYLES
   ============================================================================ */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-2);
    padding: var(--spacing-2) var(--spacing-4);
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: var(--radius-md);
    border: none;
    cursor: pointer;
    transition: var(--transition-base);
    white-space: nowrap;
}

.btn i {
    font-size: 1rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
    color: white;
    box-shadow: 0 2px 4px rgba(2, 132, 199, 0.2);
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(2, 132, 199, 0.3);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger-600), var(--danger-500));
    color: white;
}

.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
}

.btn-icon {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-2);
    padding: var(--spacing-2) var(--spacing-3);
    border: none;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition-base);
    color: white;
}

.btn-icon i {
    font-size: 0.875rem;
}

.btn-edit {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
}

.btn-edit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
}

.btn-delete {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
}

.btn-delete:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
}

/* ============================================================================
   TABLE STYLES
   ============================================================================ */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}

.table th {
    text-align: left;
    padding: var(--spacing-4) var(--spacing-6);
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #e5e7eb;
    white-space: nowrap;
}

.table td {
    padding: var(--spacing-4) var(--spacing-6);
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
    color: #4b5563;
}

.table tbody tr {
    transition: var(--transition-base);
}

.table tbody tr:hover {
    background: #f9fafb;
}

.actions-header {
    text-align: right;
}

.groupe-cell {
    white-space: nowrap;
}

.groupe-badge {
    display: inline-block;
    padding: var(--spacing-1) var(--spacing-3);
    background: linear-gradient(135deg, var(--primary-50), var(--primary-100));
    color: var(--primary-700);
    font-size: 0.8125rem;
    font-weight: 600;
    border-radius: var(--radius-full);
    border: 1px solid var(--primary-200);
}

.prompt-cell {
    max-width: 500px;
}

.prompt-content {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5;
    color: #4b5563;
}

.actions-cell {
    text-align: right;
    white-space: nowrap;
}

.action-buttons {
    display: flex;
    gap: var(--spacing-2);
    justify-content: flex-end;
}

/* ============================================================================
   FORM STYLES
   ============================================================================ */
.form-group {
    margin-bottom: var(--spacing-5);
}

.form-group label {
    display: flex;
    align-items: center;
    gap: var(--spacing-2);
    margin-bottom: var(--spacing-2);
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.form-group label i {
    color: var(--primary-600);
    font-size: 1rem;
}

.form-group select,
.form-group textarea {
    width: 100%;
    padding: var(--spacing-3);
    border: 1px solid #d1d5db;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-family: inherit;
    transition: var(--transition-base);
    background: white;
}

.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

.form-group select {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right var(--spacing-2) center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: var(--spacing-8);
    appearance: none;
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

/* ============================================================================
   MODAL STYLES
   ============================================================================ */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal.show {
    display: flex;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    animation: fadeIn 0.2s ease;
}

.modal-container {
    position: relative;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-xl);
    animation: slideUp 0.3s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.modal-sm {
    max-width: 400px;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-5) var(--spacing-6);
    background: linear-gradient(to right, #f9fafb, #ffffff);
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    display: flex;
    align-items: center;
    gap: var(--spacing-2);
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.modal-header h3 i {
    color: var(--primary-600);
}

.modal-header-danger h3 i {
    color: var(--danger-600);
}

.modal-close {
    background: transparent;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #6b7280;
    transition: var(--transition-base);
}

.modal-close:hover {
    background: #f3f4f6;
    color: #111827;
    transform: rotate(90deg);
}

.modal-body {
    padding: var(--spacing-6);
    overflow-y: auto;
}

.modal-actions {
    display: flex;
    gap: var(--spacing-3);
    justify-content: flex-end;
    margin-top: var(--spacing-6);
    padding-top: var(--spacing-4);
    border-top: 1px solid #e5e7eb;
}

/* ============================================================================
   DELETE CONFIRMATION STYLES
   ============================================================================ */
.delete-confirm {
    text-align: center;
    padding: var(--spacing-4);
}

.delete-icon {
    font-size: 3rem;
    color: var(--danger-600);
    margin-bottom: var(--spacing-4);
    animation: shake 0.5s ease;
}

.delete-confirm p {
    font-size: 1rem;
    color: #374151;
    margin-bottom: var(--spacing-2);
}

.delete-warning {
    font-size: 0.875rem;
    color: #6b7280;
}

/* ============================================================================
   EMPTY STATE STYLES
   ============================================================================ */
.empty-state {
    text-align: center;
    padding: var(--spacing-12) var(--spacing-6);
}

.empty-state i {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: var(--spacing-4);
}

.empty-state p {
    color: #6b7280;
    margin-bottom: var(--spacing-6);
    font-size: 1.125rem;
}

/* ============================================================================
   ANIMATIONS
   ============================================================================ */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

/* ============================================================================
   RESPONSIVE DESIGN
   ============================================================================ */

/* Tablettes */
@media (max-width: 1024px) {
    .card {
        margin: var(--spacing-3);
    }
    
    .card-header {
        padding: var(--spacing-4) var(--spacing-5);
    }
    
    .table th,
    .table td {
        padding: var(--spacing-3) var(--spacing-4);
    }
}

/* Mobiles */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: stretch;
        gap: var(--spacing-3);
    }
    
    .card-title {
        font-size: 1.125rem;
    }
    
    .btn {
        width: 100%;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-icon {
        width: 100%;
        justify-content: center;
    }
    
    .modal-container {
        width: 95%;
        max-height: 95vh;
    }
    
    .modal-header {
        padding: var(--spacing-4);
    }
    
    .modal-body {
        padding: var(--spacing-4);
    }
    
    .modal-actions {
        flex-direction: column-reverse;
    }
    
    .modal-actions .btn {
        width: 100%;
    }
    
    .table th,
    .table td {
        padding: var(--spacing-3);
    }
    
    .groupe-badge {
        white-space: normal;
        word-break: break-word;
    }
}

/* Petits mobiles */
@media (max-width: 480px) {
    .card {
        margin: var(--spacing-2);
        border-radius: var(--radius-lg);
    }
    
    .card-header {
        padding: var(--spacing-3);
    }
    
    .card-title {
        font-size: 1rem;
    }
    
    .table th,
    .table td {
        padding: var(--spacing-2);
        font-size: 0.8125rem;
    }
    
    .btn-icon {
        padding: var(--spacing-2);
        font-size: 0.8125rem;
    }
    
    .btn-text {
        display: none;
    }
    
    .btn-icon i {
        font-size: 1rem;
        margin: 0;
    }
    
    .modal-container {
        border-radius: var(--radius-lg);
    }
    
    .modal-header h3 {
        font-size: 1rem;
    }
    
    .empty-state i {
        font-size: 3rem;
    }
    
    .empty-state p {
        font-size: 1rem;
    }
}

/* Mode paysage sur mobile */
@media (max-width: 768px) and (orientation: landscape) {
    .modal-container {
        max-height: 85vh;
    }
    
    .modal-body {
        max-height: 60vh;
    }
}

/* Très petits écrans */
@media (max-width: 360px) {
    .table th,
    .table td {
        padding: var(--spacing-2);
        font-size: 0.75rem;
    }
    
    .groupe-badge {
        padding: var(--spacing-1) var(--spacing-2);
        font-size: 0.75rem;
    }
}

/* ============================================================================
   ACCESSIBILITY
   ============================================================================ */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

:focus-visible {
    outline: 2px solid var(--primary-600);
    outline-offset: 2px;
}

/* ============================================================================
   UTILITIES
   ============================================================================ */
.text-center {
    text-align: center;
}

.mt-4 {
    margin-top: var(--spacing-4);
}

.mb-4 {
    margin-bottom: var(--spacing-4);
}
</style>

<script>
function openAddModal() {
    document.getElementById('addModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function openEditModal(id, groupe, prompt) {
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('edit_groupe').value = groupe;
    document.getElementById('edit_prompt').value = prompt;
    document.getElementById('editForm').action = "/prompt-ia/" + id;
    document.body.style.overflow = 'hidden';
}

function openDeleteModal(id) {
    document.getElementById('deleteModal').style.display = 'flex';
    document.getElementById('deleteForm').action = "/prompt-ia/" + id;
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.querySelectorAll('.modal').forEach(m => {
        m.style.display = 'none';
    });
    document.body.style.overflow = '';
}

// Fermer la modale avec la touche Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Empêcher la propagation des clics depuis la modale vers l'overlay
document.querySelectorAll('.modal-container').forEach(container => {
    container.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>

@endsection