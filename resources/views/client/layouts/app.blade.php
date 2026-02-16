<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Delegg Hub')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    
    <!-- Font Awesome 6 & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /*=============================================================================
          ULTRA PROFESSIONAL LAYOUT - PREMIUM RESPONSIVE EXPERIENCE
          =============================================================================*/

        /*---------------------------------------
          DESIGN SYSTEM AVANCÉ
        ---------------------------------------*/
        :root {
            /* Couleurs principales - Palette sophistiquée */
            --primary-50: #eef2ff;
            --primary-100: #e0e7ff;
            --primary-200: #c7d2fe;
            --primary-300: #a5b4fc;
            --primary-400: #818cf8;
            --primary-500: #6366f1;
            --primary-600: #4f46e5;
            --primary-700: #4338ca;
            --primary-800: #3730a3;
            --primary-900: #312e81;
            
            /* Dégradés premium */
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-sidebar: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            --gradient-shine: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            
            /* Sidebar */
            --sidebar-bg: #0f172a;
            --sidebar-bg-light: #1a253b;
            --sidebar-hover: #1e293b;
            --sidebar-active: var(--primary-600);
            --sidebar-border: #334155;
            
            /* Texte */
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --text-dark: #0f172a;
            
            /* Background */
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --bg-hover: #f1f5f9;
            
            /* Ombres premium */
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            --shadow-2xl: 0 25px 50px -12px rgba(0,0,0,0.25);
            --shadow-inner: inset 0 2px 4px 0 rgba(0,0,0,0.06);
            --shadow-outline: 0 0 0 3px rgba(99,102,241,0.5);
            
            /* Bordures */
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            --radius-full: 9999px;
            
            /* Animations */
            --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            --transition-spring: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            
            /* Espacements */
            --spacing-1: 0.25rem;
            --spacing-2: 0.5rem;
            --spacing-3: 0.75rem;
            --spacing-4: 1rem;
            --spacing-5: 1.25rem;
            --spacing-6: 1.5rem;
            --spacing-8: 2rem;
            --spacing-10: 2.5rem;
            --spacing-12: 3rem;
            --spacing-16: 4rem;
            --spacing-20: 5rem;
            
            /* Z-index */
            --z-dropdown: 1000;
            --z-sticky: 1020;
            --z-fixed: 1030;
            --z-modal-backdrop: 1040;
            --z-modal: 1050;
            --z-popover: 1060;
            --z-tooltip: 1070;
        }

        /*---------------------------------------
          RESET & BASE STYLES
        ---------------------------------------*/
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            font-size: 16px;
            scroll-behavior: smooth;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-main);
            color: var(--text-dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /*---------------------------------------
          LAYOUT PRINCIPAL
        ---------------------------------------*/
        .layout {
            display: flex;
            min-height: 100vh;
            position: relative;
            background: var(--bg-main);
        }

        /*---------------------------------------
          MOBILE HEADER - DESIGN PREMIUM
        ---------------------------------------*/
        .mobile-header {
            display: none;
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: var(--text-primary);
            padding: var(--spacing-3) var(--spacing-4);
            z-index: var(--z-fixed);
            box-shadow: var(--shadow-lg);
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .mobile-brand {
            font-size: 1.35rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, var(--primary-300) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
            position: relative;
        }

        .mobile-brand small {
            font-size: 0.7rem;
            display: block;
            color: var(--text-secondary);
            -webkit-text-fill-color: var(--text-secondary);
            font-weight: 400;
            letter-spacing: normal;
            margin-top: 2px;
        }

        .menu-toggle-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: var(--text-primary);
            width: 44px;
            height: 44px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.25rem;
            transition: var(--transition-base);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .menu-toggle-btn:hover {
            background: var(--primary-600);
            border-color: var(--primary-400);
            transform: scale(1.05);
        }

        .menu-toggle-btn:active {
            transform: scale(0.95);
        }

        .menu-toggle-btn i {
            transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .menu-toggle-btn.active i {
            transform: rotate(180deg);
        }

        /*---------------------------------------
          SIDEBAR - DESIGN PREMIUM
        ---------------------------------------*/
        .sidebar {
            width: 300px;
            background: var(--gradient-sidebar);
            color: var(--text-primary);
            padding: var(--spacing-6) var(--spacing-4);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: var(--shadow-2xl);
            border-right: 1px solid rgba(255,255,255,0.05);
            z-index: var(--z-fixed);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Scrollbar personnalisée */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--sidebar-border);
            border-radius: var(--radius-full);
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-500);
        }

        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: var(--sidebar-border) transparent;
        }

        /* Effet de glassmorphisme subtil */
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.05), transparent 70%);
            pointer-events: none;
        }

        /*---------------------------------------
          BRAND SECTION - ANIMÉ
        ---------------------------------------*/
        .brand {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: var(--spacing-8);
            text-align: left;
            padding: 0 var(--spacing-2);
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #fff 0%, var(--primary-300) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            animation: fadeInDown 0.6s ease-out;
        }

        .brand span {
            display: block;
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: var(--spacing-1);
            font-weight: 400;
            letter-spacing: normal;
            -webkit-text-fill-color: var(--text-secondary);
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .brand::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-shine);
            transform: translateX(-100%);
            animation: shimmer 3s infinite;
        }

        /*---------------------------------------
          MENU STYLES - ULTRA MODERNE
        ---------------------------------------*/
        .menu, .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu > li {
            margin-bottom: var(--spacing-1);
            animation: slideInRight 0.4s ease-out;
            animation-fill-mode: both;
        }

        .menu > li:nth-child(1) { animation-delay: 0.1s; }
        .menu > li:nth-child(2) { animation-delay: 0.15s; }
        .menu > li:nth-child(3) { animation-delay: 0.2s; }
        .menu > li:nth-child(4) { animation-delay: 0.25s; }

        /* Menu Links */
        .menu a {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
            padding: var(--spacing-3) var(--spacing-4);
            border-radius: var(--radius-lg);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .menu a i {
            width: 20px;
            font-size: 1.1rem;
            color: var(--text-muted);
            transition: var(--transition-bounce);
        }

        /* Effet de vague au hover */
        .menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(255,255,255,0.1) 0%, transparent 100%);
            transform: scale(0);
            opacity: 0;
            transition: transform 0.5s, opacity 0.3s;
            z-index: -1;
        }

        .menu a:hover::before {
            transform: scale(2);
            opacity: 1;
        }

        .menu a:hover {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.1);
            color: var(--text-primary);
            transform: translateX(5px);
            box-shadow: var(--shadow-lg);
        }

        .menu a:hover i {
            color: var(--primary-400);
            transform: scale(1.1);
        }

        /* Active State */
        .menu a.active {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
            border-color: transparent;
        }

        .menu a.active i {
            color: white;
            transform: scale(1.1);
        }

        .menu a.active::before {
            display: none;
        }

        /* Indicateur de sous-menu */
        .menu > li > a:not(:only-child)::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 0.9rem;
            color: var(--text-muted);
            transition: var(--transition-bounce);
        }

        .menu > li > a.open:not(:only-child)::after {
            transform: rotate(-180deg) scale(1.2);
            color: var(--primary-400);
        }

        /*---------------------------------------
          SUBMENU - ANIMATIONS FLUIDES
        ---------------------------------------*/
        .submenu {
            margin-left: var(--spacing-8);
            margin-top: var(--spacing-1);
            margin-bottom: var(--spacing-1);
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        margin 0.3s ease;
            transform-origin: top;
        }

        .submenu.open {
            max-height: 500px;
            opacity: 1;
        }

        /* Animation des items du sous-menu */
        .submenu li {
            animation: slideInLeft 0.3s ease-out;
            animation-fill-mode: both;
        }

        .submenu li:nth-child(1) { animation-delay: 0.05s; }
        .submenu li:nth-child(2) { animation-delay: 0.1s; }
        .submenu li:nth-child(3) { animation-delay: 0.15s; }

        /* Nested Submenu */
        .submenu .submenu {
            margin-left: var(--spacing-6);
            margin-top: 0;
        }

        .submenu .submenu a {
            font-size: 0.85rem;
            padding: var(--spacing-2) var(--spacing-3);
        }

        /* Submenu Links */
        .submenu a {
            padding: var(--spacing-2) var(--spacing-4);
            color: var(--text-muted);
            font-size: 0.85rem;
            border-left: 2px solid transparent;
            transition: var(--transition-smooth);
            cursor: pointer;
        }

        .submenu a:hover {
            color: var(--text-primary);
            background: rgba(255,255,255,0.02);
            border-left-color: var(--primary-500);
            transform: translateX(8px);
            padding-left: calc(var(--spacing-4) + 4px);
        }

        .submenu a.active {
            background: transparent;
            color: var(--primary-400);
            border-left-color: var(--primary-500);
            font-weight: 600;
        }

        .submenu a.active i {
            color: var(--primary-400);
        }

        /* Submenu item with nested submenu */
        .submenu > li > a:not(:only-child)::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 0.75rem;
            color: var(--text-muted);
            transition: var(--transition-bounce);
        }

        .submenu > li > a.open:not(:only-child)::after {
            transform: rotate(-180deg) scale(1.2);
            color: var(--primary-400);
        }

        /*---------------------------------------
          LOGOUT BUTTON - PREMIUM
        ---------------------------------------*/
        .logout {
            margin-top: auto;
            padding-top: var(--spacing-6);
            border-top: 1px solid rgba(255,255,255,0.1);
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }

        .logout button {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-secondary);
            padding: var(--spacing-3) var(--spacing-4);
            border-radius: var(--radius-lg);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-2);
            transition: var(--transition-smooth);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .logout button i {
            font-size: 1.1rem;
            transition: var(--transition-bounce);
        }

        .logout button:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border-color: transparent;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.4);
        }

        .logout button:hover i {
            transform: translateX(8px) scale(1.1);
        }

        .logout button:active {
            transform: translateY(-1px) scale(1);
        }

        /*---------------------------------------
          MAIN CONTENT - DESIGN MODERNE
        ---------------------------------------*/
        .content {
            flex: 1;
            padding: var(--spacing-8);
            margin-left: 300px;
            min-height: 100vh;
            overflow-y: auto;
            background: var(--bg-main);
            transition: var(--transition-smooth);
            position: relative;
        }

        /* Scrollbar personnalisée pour le contenu */
        .content::-webkit-scrollbar {
            width: 10px;
        }

        .content::-webkit-scrollbar-track {
            background: var(--bg-main);
        }

        .content::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--primary-300), var(--primary-500));
            border-radius: var(--radius-full);
            border: 2px solid var(--bg-main);
        }

        .content::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--primary-400), var(--primary-600));
        }

        /*---------------------------------------
          CARD STYLES - GLASSMORPHISM
        ---------------------------------------*/
        .card {
            background: var(--bg-card);
            border-radius: var(--radius-2xl);
            padding: var(--spacing-8);
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255,255,255,0.5);
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-400), var(--primary-600));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-2xl);
            transform: translateY(-4px);
        }

        .card:hover::before {
            opacity: 1;
        }

        /*---------------------------------------
          OVERLAY PREMIUM
        ---------------------------------------*/
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: var(--z-modal-backdrop);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }

        .sidebar-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        /*---------------------------------------
          ANIMATIONS PREMIUM
        ---------------------------------------*/
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        /*---------------------------------------
          RESPONSIVE DESIGN - ULTRA OPTIMISÉ
        ---------------------------------------*/

        /* Grands écrans */
        @media (min-width: 1920px) {
            :root {
                font-size: 18px;
            }
            
            .sidebar {
                width: 350px;
            }
            
            .content {
                margin-left: 350px;
                padding: var(--spacing-10);
            }
            
            .card {
                padding: var(--spacing-10);
            }
        }

        /* Écrans moyens */
        @media (max-width: 1280px) {
            .sidebar {
                width: 280px;
            }
            
            .content {
                margin-left: 280px;
                padding: var(--spacing-6);
            }
        }

        /* Tablettes */
        @media (max-width: 1024px) {
            .sidebar {
                width: 260px;
            }
            
            .content {
                margin-left: 260px;
                padding: var(--spacing-5);
            }
            
            .brand {
                font-size: 1.5rem;
            }
        }

        /* Mobile - Version drawer premium */
        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
            }

            .layout {
                padding-top: 0;
            }

            .sidebar {
                position: fixed;
                transform: translateX(-100%);
                width: 85%;
                max-width: 320px;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: var(--z-modal);
                border-radius: 0 20px 20px 0;
                box-shadow: var(--shadow-2xl);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding: var(--spacing-4);
                width: 100%;
                min-height: 100vh;
                padding-top: calc(60px + var(--spacing-4));
            }

            .sidebar-overlay {
                display: block;
            }

            /* Ajustements menu mobile */
            .submenu {
                margin-left: var(--spacing-6);
            }

            .submenu .submenu {
                margin-left: var(--spacing-4);
            }

            .card {
                padding: var(--spacing-5);
                border-radius: var(--radius-xl);
            }

            /* Boutons plus grands pour le tactile */
            .menu a {
                padding: var(--spacing-4) var(--spacing-4);
                font-size: 1rem;
            }

            .submenu a {
                padding: var(--spacing-3) var(--spacing-4);
                font-size: 0.95rem;
            }

            .logout button {
                padding: var(--spacing-4);
                font-size: 1rem;
            }
        }

        /* Petits mobiles */
        @media (max-width: 480px) {
            .mobile-header {
                padding: var(--spacing-2) var(--spacing-3);
            }

            .mobile-brand {
                font-size: 1.2rem;
            }

            .mobile-brand small {
                font-size: 0.65rem;
            }

            .menu-toggle-btn {
                width: 40px;
                height: 40px;
            }

            .sidebar {
                width: 90%;
                max-width: 280px;
                padding: var(--spacing-4) var(--spacing-3);
            }

            .menu a {
                padding: var(--spacing-3);
                font-size: 0.95rem;
            }
            
            .submenu a {
                font-size: 0.9rem;
                padding: var(--spacing-2) var(--spacing-3);
            }
            
            .content {
                padding: var(--spacing-3);
                padding-top: calc(56px + var(--spacing-3));
            }

            .card {
                padding: var(--spacing-4);
                border-radius: var(--radius-lg);
            }

            .brand {
                font-size: 1.35rem;
                margin-bottom: var(--spacing-6);
            }
        }

        /* Très petits écrans */
        @media (max-width: 360px) {
            .sidebar {
                max-width: 260px;
            }

            .menu a {
                font-size: 0.9rem;
                padding: var(--spacing-2);
            }

            .menu a i {
                width: 18px;
                font-size: 1rem;
            }

            .submenu {
                margin-left: var(--spacing-4);
            }
        }

        /* Mode paysage sur mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .sidebar {
                width: 70%;
                max-width: 300px;
            }

            .menu a {
                padding: var(--spacing-2) var(--spacing-3);
            }

            .content {
                padding-top: calc(56px + var(--spacing-2));
            }
        }

        /* Tablettes en mode paysage */
        @media (min-width: 769px) and (max-width: 1024px) and (orientation: landscape) {
            .sidebar {
                width: 240px;
            }

            .content {
                margin-left: 240px;
            }
        }

        /*---------------------------------------
          ACCESSIBILITÉ & FOCUS
        ---------------------------------------*/
        :focus-visible {
            outline: 2px solid var(--primary-500);
            outline-offset: 2px;
            border-radius: var(--radius-sm);
        }

        /* Réduction de mouvement si préféré */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /*---------------------------------------
          UTILITY CLASSES
        ---------------------------------------*/
        .text-gradient {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bg-gradient {
            background: var(--gradient-primary);
        }

        .shadow-premium {
            box-shadow: var(--shadow-2xl);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Support impression */
        @media print {
            .sidebar,
            .mobile-header,
            .logout,
            .sidebar-overlay {
                display: none !important;
            }

            .content {
                margin-left: 0;
                padding: 0;
            }

            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>

<!-- Mobile Header Premium -->
<div class="mobile-header">
    <div class="mobile-brand">
        DELEGG HUB
        <small>{{ session('client.company') }}</small>
    </div>
    <button class="menu-toggle-btn" id="mobileMenuToggle" aria-label="Menu" aria-expanded="false">
        <i class="fa-solid fa-bars-staggered"></i>
    </button>
</div>

<!-- Overlay premium -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="layout">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            DELEGG HUB
            <span>{{ session('client.company') }}</span>
        </div>

        <ul class="menu" id="mainMenu">
            <!-- CRM -->
            <li>
                <a href="#" class="menu-toggle" data-target="crm-submenu" aria-expanded="false">
                    <i class="fa-solid fa-chart-line"></i> 
                    <span>CRM</span>
                </a>
                <ul class="submenu" id="crm-submenu">
                    <li><a href="{{ route('client.crm.dashboard') }}"><i class="fa-regular fa-chart-bar"></i> Dashboard</a></li>
                    <li><a href="{{ route('client.crm.leads') }}"><i class="fa-regular fa-star"></i> Mes Leads</a></li>
                </ul>
            </li>

            <!-- MAILS -->
            <li>
                <a href="#" class="menu-toggle" data-target="mails-submenu" aria-expanded="false">
                    <i class="fa-solid fa-envelope"></i> 
                    <span>Mails</span>
                </a>
                <ul class="submenu" id="mails-submenu">
                    <li><a href="{{ route('client.mails.programmes') }}"><i class="fa-regular fa-clock"></i> Mes mails programmés</a></li>
                    <li>
                        <a href="#" class="menu-toggle" data-target="inbox-submenu" aria-expanded="false">
                            <i class="fa-regular fa-inbox"></i> Boite de réception
                        </a>
                        <ul class="submenu" id="inbox-submenu">
                            <li><a href="{{ route('client.mails.envoyes') }}"><i class="fa-regular fa-paper-plane"></i> Mails envoyés</a></li>
                            <li><a href="{{ route('client.mails.recus') }}"><i class="fa-regular fa-envelope-open"></i> Mails reçus</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <!-- SCRAPPING -->
            <li>
                <a href="#" class="menu-toggle" data-target="scraping-submenu" aria-expanded="false">
                    <i class="fa-solid fa-magnifying-glass"></i> 
                    <span>SCRAPPING</span>
                </a>
                <ul class="submenu" id="scraping-submenu">
                    <li><a href="{{ route('client.google') }}"><i class="fa-regular fa-map"></i> Google Maps</a></li>
                    <li><a href="{{ route('client.web') }}"><i class="fa-regular fa-window-maximize"></i> Site Web</a></li>
                </ul>
            </li>

            <!-- PARAMETRES -->
            <li>
                <a href="#" class="menu-toggle" data-target="params-submenu" aria-expanded="false">
                    <i class="fa-solid fa-gear"></i> 
                    <span>Paramètres</span>
                </a>
                <ul class="submenu" id="params-submenu">
                    <li><a href="{{ route('client.profil') }}"><i class="fa-regular fa-user"></i> Profil</a></li>
                    @if(session('client.role') === 'superadmin')
                        <li><a href="{{ route('client.users') }}"><i class="fa-regular fa-users"></i> Utilisateurs</a></li>
                    @endif
                </ul>
            </li>
        </ul>

        <div class="logout">
            <form method="POST" action="{{ route('client.logout') }}">
                @csrf
                <button type="submit" aria-label="Déconnexion">
                    <i class="fa-solid fa-right-from-bracket"></i> 
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="content">
        @yield('content')
    </main>
</div>

<script>
(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        mobileBreakpoint: 768,
        animationDuration: 300,
        menuStateKey: 'delegg_menu_state'
    };

    // DOM Elements
    const elements = {
        mobileToggle: document.getElementById('mobileMenuToggle'),
        sidebar: document.getElementById('sidebar'),
        overlay: document.getElementById('sidebarOverlay'),
        body: document.body
    };

    // État de l'application
    const state = {
        isMobileMenuOpen: false,
        isDesktop: window.innerWidth > CONFIG.mobileBreakpoint,
        touchStartX: 0
    };

    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
        initializeApp();
    });

    function initializeApp() {
        initializeMobileMenu();
        initializeMenuSystem();
        initializeActiveLinks();
        initializeSwipeGesture();
        initializeResizeHandler();
        initializeAriaAttributes();
        loadMenuState();
    }

    // Menu Mobile Premium
    function initializeMobileMenu() {
        if (!elements.mobileToggle || !elements.sidebar || !elements.overlay) return;

        // Toggle menu
        elements.mobileToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleMobileMenu();
        });

        // Fermer avec l'overlay
        elements.overlay.addEventListener('click', closeMobileMenu);

        // Fermer avec Echap
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && state.isMobileMenuOpen) {
                closeMobileMenu();
            }
        });

        // Fermer après clic sur un lien (mobile)
        document.querySelectorAll('.menu a:not(.menu-toggle)').forEach(link => {
            link.addEventListener('click', () => {
                if (!state.isDesktop && state.isMobileMenuOpen) {
                    closeMobileMenu();
                }
            });
        });
    }

    function toggleMobileMenu() {
        if (state.isMobileMenuOpen) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    }

    function openMobileMenu() {
        if (!elements.sidebar || !elements.overlay || !elements.mobileToggle) return;
        
        state.isMobileMenuOpen = true;
        elements.sidebar.classList.add('active');
        elements.overlay.classList.add('active');
        elements.mobileToggle.classList.add('active');
        elements.mobileToggle.setAttribute('aria-expanded', 'true');
        elements.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        if (!elements.sidebar || !elements.overlay || !elements.mobileToggle) return;
        
        state.isMobileMenuOpen = false;
        elements.sidebar.classList.remove('active');
        elements.overlay.classList.remove('active');
        elements.mobileToggle.classList.remove('active');
        elements.mobileToggle.setAttribute('aria-expanded', 'false');
        elements.body.style.overflow = '';
    }

    // Système de menu premium
    function initializeMenuSystem() {
        const menuToggles = document.querySelectorAll('.menu-toggle');
        
        menuToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const targetId = this.dataset.target;
                const targetMenu = document.getElementById(targetId);
                
                if (!targetMenu) return;

                const isOpening = !targetMenu.classList.contains('open');
                
                // Fermer les menus au même niveau
                if (isOpening) {
                    const parent = this.closest('li');
                    if (parent) {
                        const siblingMenus = parent.parentElement.querySelectorAll(':scope > li > .submenu');
                        siblingMenus.forEach(menu => {
                            if (menu.id !== targetId) {
                                closeSubmenu(menu);
                            }
                        });
                    }
                }

                // Toggle le menu actuel
                toggleSubmenu(this, targetMenu);
                
                // Animation de l'icône
                animateIcon(this, targetMenu);
            });
        });
    }

    function toggleSubmenu(toggle, menu) {
        const isOpen = menu.classList.contains('open');
        
        if (isOpen) {
            closeSubmenu(menu);
            toggle.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        } else {
            openSubmenu(menu);
            toggle.classList.add('open');
            toggle.setAttribute('aria-expanded', 'true');
        }
    }

    function openSubmenu(menu) {
        menu.classList.add('open');
        
        // Animation progressive des items
        const items = menu.children;
        Array.from(items).forEach((item, index) => {
            item.style.animation = `slideInLeft 0.3s ease-out ${index * 0.05}s both`;
        });
    }

    function closeSubmenu(menu) {
        menu.classList.remove('open');
        
        // Reset des animations
        const items = menu.children;
        Array.from(items).forEach(item => {
            item.style.animation = '';
        });
    }

    function animateIcon(toggle, menu) {
        const icon = toggle.querySelector('i:first-child');
        if (!icon) return;
        
        if (menu.classList.contains('open')) {
            icon.style.transform = 'rotate(90deg) scale(1.1)';
            setTimeout(() => {
                icon.style.transform = '';
            }, 300);
        }
    }

    // Gestion des liens actifs
    function initializeActiveLinks() {
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.menu a:not(.menu-toggle)');
        
        menuLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                setActiveLink(link);
            }
        });
    }

    function setActiveLink(link) {
        // Marquer le lien actif
        link.classList.add('active');
        
        // Ouvrir tous les menus parents
        let parent = link.closest('.submenu');
        while (parent) {
            parent.classList.add('open');
            
            const parentLi = parent.closest('li');
            if (parentLi) {
                const parentToggle = parentLi.querySelector(':scope > .menu-toggle');
                if (parentToggle) {
                    parentToggle.classList.add('open');
                    parentToggle.setAttribute('aria-expanded', 'true');
                }
            }
            
            parent = parent.parentElement.closest('.submenu');
        }
    }

    // Gestion du swipe pour mobile
    function initializeSwipeGesture() {
        if (!elements.sidebar) return;

        elements.sidebar.addEventListener('touchstart', (e) => {
            state.touchStartX = e.touches[0].clientX;
        }, { passive: true });

        elements.sidebar.addEventListener('touchmove', (e) => {
            if (!state.isMobileMenuOpen) return;
            
            const touchEndX = e.touches[0].clientX;
            const diffX = touchEndX - state.touchStartX;
            
            // Swipe vers la gauche pour fermer
            if (diffX < -50) {
                e.preventDefault();
                closeMobileMenu();
            }
        }, { passive: false });
    }

    // Gestion du redimensionnement
    function initializeResizeHandler() {
        let resizeTimer;
        
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            
            resizeTimer = setTimeout(() => {
                const wasDesktop = state.isDesktop;
                state.isDesktop = window.innerWidth > CONFIG.mobileBreakpoint;
                
                if (state.isDesktop && !wasDesktop) {
                    // Passage de mobile à desktop
                    closeMobileMenu();
                    elements.body.style.overflow = '';
                }
                
                // Mettre à jour l'état du menu
                updateMenuForScreenSize();
            }, 150);
        });
    }

    function updateMenuForScreenSize() {
        if (state.isDesktop) {
            // En desktop, on peut garder certains menus ouverts
            const activeLinks = document.querySelectorAll('.menu a.active');
            activeLinks.forEach(link => {
                let parent = link.closest('.submenu');
                while (parent) {
                    parent.classList.add('open');
                    const toggle = parent.parentElement.querySelector(':scope > .menu-toggle');
                    if (toggle) toggle.classList.add('open');
                    parent = parent.parentElement.closest('.submenu');
                }
            });
        } else {
            // En mobile, fermer tous les menus par défaut sauf les actifs
            const openMenus = document.querySelectorAll('.submenu.open');
            openMenus.forEach(menu => {
                if (!menu.querySelector('.active')) {
                    menu.classList.remove('open');
                    const toggle = document.querySelector(`[data-target="${menu.id}"]`);
                    if (toggle) toggle.classList.remove('open');
                }
            });
        }
    }

    // Accessibilité ARIA
    function initializeAriaAttributes() {
        // Labels pour les boutons
        document.querySelectorAll('.menu-toggle').forEach((toggle, index) => {
            if (!toggle.hasAttribute('aria-label')) {
                const text = toggle.textContent.trim();
                toggle.setAttribute('aria-label', `Menu ${text}`);
            }
            toggle.setAttribute('aria-haspopup', 'true');
        });
    }

    // Persistance de l'état du menu
    function saveMenuState() {
        try {
            const openMenus = [];
            document.querySelectorAll('.submenu.open').forEach(menu => {
                openMenus.push(menu.id);
            });
            localStorage.setItem(CONFIG.menuStateKey, JSON.stringify(openMenus));
        } catch (e) {
            console.warn('Impossible de sauvegarder l\'état du menu:', e);
        }
    }

    function loadMenuState() {
        try {
            const savedMenus = localStorage.getItem(CONFIG.menuStateKey);
            if (!savedMenus) return;
            
            const openMenus = JSON.parse(savedMenus);
            
            // Ne restaurer que si on est en desktop
            if (state.isDesktop) {
                openMenus.forEach(menuId => {
                    const menu = document.getElementById(menuId);
                    const toggle = document.querySelector(`[data-target="${menuId}"]`);
                    if (menu && toggle && !menu.querySelector('.active')) {
                        menu.classList.add('open');
                        toggle.classList.add('open');
                    }
                });
            }
        } catch (e) {
            console.warn('Impossible de charger l\'état du menu:', e);
        }
    }

    // Sauvegarde avant de quitter
    window.addEventListener('beforeunload', () => {
        saveMenuState();
    });

    // Gestion des erreurs
    window.addEventListener('error', (e) => {
        console.error('Erreur applicative:', e.error);
    });

})();
</script>

</body>
</html>