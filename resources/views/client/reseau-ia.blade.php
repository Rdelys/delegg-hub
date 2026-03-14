@extends('client.layouts.app')

@section('title', 'Réseau IA - Génération Publicitaire')

@section('content')
<style>
    /* Variables et reset */
    :root {
        --primary: #4361ee;
        --primary-dark: #3a56d4;
        --secondary: #6c757d;
        --success: #10b981;
        --dark: #1e293b;
        --light: #f8fafc;
        --border: #e2e8f0;
        --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --radius: 12px;
        --radius-sm: 8px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: var(--dark);
        line-height: 1.5;
    }

    /* Layout principal */
    .ai-dashboard {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }

    /* Cartes */
    .ai-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .ai-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg), 0 0 0 2px rgba(67, 97, 238, 0.1);
    }

    /* En-tête */
    .ai-header {
        font-size: 1.75rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1.5rem;
        letter-spacing: -0.025em;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ai-header::before {
        content: '';
        width: 4px;
        height: 32px;
        background: linear-gradient(135deg, var(--primary), #8b5cf6);
        border-radius: 4px;
    }

    /* Formulaire */
    .ai-form {
        background: white;
        border-radius: var(--radius);
        padding: 1.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        color: var(--secondary);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.75rem 1rem;
        border: 2px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 1rem;
        transition: all 0.2s ease;
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), #8b5cf6);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
        width: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        background: linear-gradient(135deg, var(--primary-dark), #7c3aed);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success), #059669);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-sm);
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
        background: linear-gradient(135deg, #059669, #047857);
    }

    /* Aperçu */
    .ai-preview {
        text-align: center;
    }

    .preview-container {
        position: relative;
        display: inline-block;
        margin: 1.5rem 0;
    }

    .ai-preview img {
        max-width: 100%;
        height: auto;
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
        transition: transform 0.3s ease;
    }

    .ai-preview img:hover {
        transform: scale(1.02);
    }

    .preview-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Tableau */
    .table-container {
        overflow-x: auto;
        border-radius: var(--radius);
        background: white;
        padding: 0.5rem;
    }

    .ai-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .ai-table thead tr {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 2px solid var(--border);
    }

    .ai-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--secondary);
    }

    .ai-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .ai-table tbody tr {
        transition: background 0.2s ease;
    }

    .ai-table tbody tr:hover {
        background: rgba(67, 97, 238, 0.02);
    }

    .badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        background: linear-gradient(135deg, var(--primary), #8b5cf6);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .table-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .table-img:hover {
        transform: scale(1.1);
        border-color: var(--primary);
        box-shadow: var(--shadow-md);
    }

    /* Modal */
    .ai-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        backdrop-filter: blur(8px);
        align-items: center;
        justify-content: center;
        z-index: 1000;
        cursor: pointer;
        animation: fadeIn 0.3s ease;
    }

    .modal-content {
        max-width: 90%;
        max-height: 90%;
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
        animation: zoomIn 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes zoomIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .ai-dashboard {
            margin: 1rem auto;
            padding: 0 1rem;
        }

        .ai-card {
            padding: 1.5rem;
        }

        .ai-header {
            font-size: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .btn-primary {
            padding: 0.875rem 1.5rem;
        }

        .table-img {
            width: 50px;
            height: 50px;
        }
    }

    @media (max-width: 480px) {
        .ai-header {
            font-size: 1.25rem;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        .btn-success {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
    }

    /* Loading state */
    .loading {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<div class="ai-dashboard">
    <!-- Formulaire de génération -->
    <div class="ai-card">
        <div class="ai-header">
            Générateur IA - Publicité Réseaux Sociaux
        </div>

        <form method="POST" action="{{route('client.reseau.ia.generate')}}" enctype="multipart/form-data" class="ai-form" id="generationForm">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="network">
                        <i class="fas fa-share-alt" style="margin-right: 0.5rem;"></i>
                        Réseau social
                    </label>
                    <select name="network" id="network" class="form-control" required>
                        <option value="linkedin">LinkedIn - Professionnel</option>
                        <option value="facebook">Facebook - Communautaire</option>
                        <option value="instagram">Instagram - Visuel</option>
                        <option value="twitter">Twitter - Conversation</option>
                        <option value="tiktok">TikTok - Viral</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="prompt">
                        <i class="fas fa-pen" style="margin-right: 0.5rem;"></i>
                        Description de la publicité
                    </label>
                    <textarea name="prompt" id="prompt" class="form-control" placeholder="Ex: Une publicité moderne pour une marque de sport avec des athlètes..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="person_image">
                        <i class="fas fa-user" style="margin-right: 0.5rem;"></i>
                        Photo de personne (optionnel)
                    </label>
                    <input type="file" name="person_image" id="person_image" class="form-control" accept="image/*">
                    <small style="color: var(--secondary);">Formats supportés: JPG, PNG, GIF</small>
                </div>
            </div>

            <button type="submit" class="btn-primary" id="submitBtn">
                <i class="fas fa-magic" style="margin-right: 0.75rem;"></i>
                Générer avec Intelligence Artificielle
            </button>
        </form>
    </div>

    <!-- Dernière image générée -->
    @if($lastImage)
    <div class="ai-card ai-preview">
        <div class="ai-header" style="font-size: 1.5rem;">
            Dernière création
        </div>
        
        <div class="preview-container">
            <img src="{{$lastImage}}" alt="Dernière publicité générée" loading="lazy">
            <span class="preview-badge">
                <i class="far fa-clock" style="margin-right: 0.25rem;"></i>
                Généré récemment
            </span>
        </div>

        <a href="{{$lastImage}}" download class="btn-success">
            <i class="fas fa-download"></i>
            Télécharger l'image
        </a>
    </div>
    @endif

    <!-- Historique des générations -->
    <div class="ai-card">
        <div class="ai-header" style="font-size: 1.5rem;">
            Historique des générations
            <span style="margin-left: 1rem; font-size: 0.9rem; background: var(--primary); color: white; padding: 0.25rem 0.75rem; border-radius: 20px;">
                {{$generations->count()}} publicités
            </span>
        </div>

        <div class="table-container">
            <table class="ai-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Réseau</th>
                        <th>Description</th>
                        <th>Aperçu</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($generations as $gen)
                    <tr>
                        <td>#{{$gen->id}}</td>
                        <td>
                            <span class="badge">
                                @switch($gen->network)
                                    @case('linkedin')
                                        <i class="fab fa-linkedin" style="margin-right: 0.25rem;"></i>
                                        @break
                                    @case('facebook')
                                        <i class="fab fa-facebook" style="margin-right: 0.25rem;"></i>
                                        @break
                                    @case('instagram')
                                        <i class="fab fa-instagram" style="margin-right: 0.25rem;"></i>
                                        @break
                                    @default
                                        <i class="fas fa-share-alt" style="margin-right: 0.25rem;"></i>
                                @endswitch
                                {{ucfirst($gen->network)}}
                            </span>
                        </td>
                        <td style="max-width: 300px;">
                            <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{$gen->prompt}}
                            </div>
                        </td>
                        <td>
                            @if($gen->images->first())
                            <img 
                                src="{{asset('storage/'.$gen->images->first()->image_path)}}"
                                onclick="showImage(this.src)"
                                class="table-img"
                                alt="Aperçu"
                                loading="lazy"
                            >
                            @else
                                <span style="color: var(--secondary);">Aucune image</span>
                            @endif
                        </td>
                        <td>
                            @if($gen->images->first())
                            <a href="{{asset('storage/'.$gen->images->first()->image_path)}}" 
                               download 
                               class="btn-success" 
                               style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                                <i class="fas fa-download"></i>
                                Télécharger
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem; color: var(--secondary);">
                            <i class="fas fa-image" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <br>
                            Aucune génération pour le moment. Commencez par créer votre première publicité IA !
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal pour afficher les images -->
<div id="imageModal" class="ai-modal" onclick="hideImage()">
    <img id="modalImg" class="modal-content" alt="Aperçu agrandi">
</div>

<script>
    // Configuration
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImg');
    
    // Afficher l'image dans le modal
    function showImage(src) {
        modalImg.src = src;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Empêcher le scroll
    }

    // Cacher le modal
    function hideImage() {
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Restaurer le scroll
    }

    // Fermer avec la touche Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            hideImage();
        }
    });

    // Animation de chargement du formulaire
    const form = document.getElementById('generationForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        
        // Le formulaire est soumis normalement, le loading sera désactivé après le rechargement
    });

    // Prévisualisation de l'image uploadée
    const fileInput = document.getElementById('person_image');
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const fileName = this.files[0].name;
            const fileSize = (this.files[0].size / 1024).toFixed(2);
            console.log(`Fichier sélectionné: ${fileName} (${fileSize} KB)`);
        }
    });

    // Animation au survol des lignes du tableau
    const rows = document.querySelectorAll('.ai-table tbody tr');
    rows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(67, 97, 238, 0.02)';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });

    // Gestion des erreurs de chargement d'images
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('error', function() {
            this.src = '{{asset("images/placeholder.jpg")}}'; // Image par défaut si erreur
            this.alt = 'Image non disponible';
        });
    });

    // Message de confirmation avant de quitter si formulaire en cours
    let formChanged = false;
    const formInputs = form.querySelectorAll('input, select, textarea');
    
    formInputs.forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });

    window.addEventListener('beforeunload', (e) => {
        if (formChanged && !submitBtn.disabled) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    console.log('Interface IA chargée avec succès 🚀');
</script>

<!-- Ajout de Font Awesome pour les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Optimisation des polices -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Meta tags pour responsive -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
@endsection