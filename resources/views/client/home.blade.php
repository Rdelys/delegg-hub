@extends('client.layouts.app')

@section('title', 'Bienvenue - FOLLUP.IO')

@section('content')

<style>
    /* Version optimisée - Plus légère et rapide */
    .welcome-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecf5 100%);
    }

    /* Carte principale - Design simplifié */
    .welcome-card {
        background: white;
        border-radius: 30px;
        padding: 50px 40px;
        max-width: 800px;
        width: 100%;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        transform: translateY(0);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .welcome-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 50px rgba(102, 126, 234, 0.15);
    }

    /* Logo simplifié */
    .logo-animated {
        text-align: center;
        margin-bottom: 25px;
    }

    .logo-animated i {
        font-size: 70px;
        color: #667eea;
        animation: softFloat 3s ease-in-out infinite;
    }

    @keyframes softFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* Titre épuré */
    .welcome-title {
        color: #2d3748;
        font-size: 2.8rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .welcome-title span {
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Message simplifié */
    .welcome-message {
        color: #4a5568;
        font-size: 1.3rem;
        line-height: 1.6;
        text-align: center;
        margin-bottom: 30px;
        background: #f8fafc;
        padding: 25px 30px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }

    .welcome-message strong {
        color: #667eea;
        font-weight: 600;
    }

    /* Badge entreprise simplifié */
    .company-info {
        background: linear-gradient(135deg, #f0f4ff, #f5f0ff);
        border-radius: 50px;
        padding: 15px 30px;
        margin-bottom: 35px;
        display: inline-block;
        width: auto;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }

    .company-badge {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .company-badge i {
        font-size: 28px;
        color: white;
        background: linear-gradient(135deg, #667eea, #764ba2);
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .company-badge .company-details {
        text-align: left;
    }

    .company-badge .company-details .label {
        font-size: 0.85rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .company-badge .company-details .name {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }

    /* Boutons optimisés */
    .action-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-welcome {
        padding: 15px 35px;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-primary-welcome {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
    }

    .btn-primary-welcome:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.3);
    }

    .btn-outline-welcome {
        background: transparent;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-outline-welcome:hover {
        background: #667eea;
        color: white;
        transform: translateY(-3px);
    }

    .btn-welcome i {
        transition: transform 0.2s ease;
    }

    .btn-welcome:hover i {
        transform: translateX(3px);
    }

    /* Footer simplifié */
    .footer-message {
        text-align: center;
        color: #a0aec0;
        margin-top: 35px;
        font-size: 0.95rem;
    }

    .footer-message i {
        color: #667eea;
        animation: softPulse 2s ease-in-out infinite;
    }

    @keyframes softPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* Design responsive */
    @media (max-width: 768px) {
        .welcome-card {
            padding: 35px 25px;
        }
        
        .welcome-title {
            font-size: 2.2rem;
        }
        
        .welcome-message {
            font-size: 1.1rem;
            padding: 20px;
        }
        
        .company-badge {
            flex-direction: column;
            text-align: center;
        }
        
        .company-badge .company-details {
            text-align: center;
        }
        
        .company-badge .company-details .name {
            font-size: 1.4rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-welcome {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .welcome-card {
            padding: 25px 20px;
        }
        
        .welcome-title {
            font-size: 1.8rem;
        }
        
        .logo-animated i {
            font-size: 55px;
        }
    }
</style>

<div class="welcome-container">
    <div class="welcome-card">
        
        <!-- Logo -->
        <div class="logo-animated">
            <i class="fas fa-rocket"></i>
        </div>
        
        <!-- Titre -->
        <h1 class="welcome-title">
            Bienvenue sur <span>FOLLUP.IO</span>
        </h1>
        
        <!-- Message -->
        <div class="welcome-message">
            Bonjour <strong>{{ session('client.first_name', 'Client') }}</strong>,<br>
            vous êtes connecté à l'espace de
            <strong>{{ session('client.company', 'votre entreprise') }}</strong>
        </div>
        
        <!-- Badge entreprise -->
        <div style="text-align: center;">
            <div class="company-info">
                <div class="company-badge">
                    <i class="fas fa-building"></i>
                    <div class="company-details">
                        <div class="label">Espace professionnel</div>
                        <div class="name">{{ session('client.company', 'Entreprise') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Boutons d'action -->
        <div class="action-buttons">
            <a href="{{ url('/crm/leads') }}" class="btn-welcome btn-primary-welcome">
                <i class="fas fa-tachometer-alt"></i>
                Leads
            </a>
            <a href="{{ url('/google') }}" class="btn-welcome btn-outline-welcome">
                <i class="fas fa-map-marked-alt"></i>
                Google Maps
            </a>
        </div>
        
        <!-- Footer -->
        <div class="footer-message">
            <i class="fas fa-magic"></i> Prêt à générer des leads ?
        </div>
    </div>
</div>

<!-- Script minimal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si Font Awesome est chargé
    if (!document.querySelector('link[href*="font-awesome"]')) {
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css';
        document.head.appendChild(link);
    }
});
</script>

@endsection