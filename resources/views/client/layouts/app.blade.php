<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Follup.io')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    
    <!-- Font Awesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /*=============================================================================
          ULTRA MODERN PROFESSIONAL LAYOUT - 2025 DESIGN TRENDS
          =============================================================================*/

        /*---------------------------------------
          DESIGN SYSTEM VARIABLES - NOUVELLE PALETTE
        ---------------------------------------*/
        :root {
            /* Primary Colors - Élégant violet/indigo */
            --primary-50: #f3f1ff;
            --primary-100: #ebe5ff;
            --primary-200: #d9ceff;
            --primary-300: #bea6ff;
            --primary-400: #a075ff;
            --primary-500: #884dff;
            --primary-600: #7735e0;
            --primary-700: #6622cc;
            --primary-800: #4f17a3;
            --primary-900: #361273;
            
            /* Secondary Colors - Bleu océan sophistiqué */
            --secondary-50: #eef8ff;
            --secondary-100: #dcf0ff;
            --secondary-200: #bae2ff;
            --secondary-300: #8fcaff;
            --secondary-400: #5aa9ff;
            --secondary-500: #3b82f6;
            --secondary-600: #2563eb;
            --secondary-700: #1d4ed8;
            --secondary-800: #1e3a8a;
            --secondary-900: #172554;

            /* Accent Colors - Rose gold */
            --accent-50: #fff1f4;
            --accent-100: #ffe4e8;
            --accent-200: #fecdd6;
            --accent-300: #fda4b4;
            --accent-400: #fb7185;
            --accent-500: #f43f5e;
            --accent-600: #e11d48;
            --accent-700: #be123c;
            --accent-800: #9f1239;
            --accent-900: #881337;

            /* Success Colors - Vert émeraude */
            --success-50: #ecfdf5;
            --success-100: #d1fae5;
            --success-200: #a7f3d0;
            --success-300: #6ee7b7;
            --success-400: #34d399;
            --success-500: #10b981;
            --success-600: #059669;
            --success-700: #047857;
            --success-800: #065f46;
            --success-900: #064e3b;

            /* Danger Colors - Rouge corail */
            --danger-50: #fff1f0;
            --danger-100: #ffe4e2;
            --danger-200: #ffc9c5;
            --danger-300: #ffa8a2;
            --danger-400: #ff7a72;
            --danger-500: #f7554a;
            --danger-600: #e03d32;
            --danger-700: #c53030;
            --danger-800: #9b2c2c;
            --danger-900: #742a2a;

            /* Warning Colors - Orange ambré */
            --warning-50: #fffbeb;
            --warning-100: #fef3c7;
            --warning-200: #fde68a;
            --warning-300: #fcd34d;
            --warning-400: #fbbf24;
            --warning-500: #f59e0b;
            --warning-600: #d97706;
            --warning-700: #b45309;
            --warning-800: #92400e;
            --warning-900: #78350f;

            /* Info Colors - Bleu ciel */
            --info-50: #e0f2fe;
            --info-100: #bae6fd;
            --info-200: #7dd3fc;
            --info-300: #38bdf8;
            --info-400: #0ea5e9;
            --info-500: #0284c7;
            --info-600: #0369a1;
            --info-700: #075985;
            --info-800: #0c4a6e;
            --info-900: #0f3b5a;
            
            /* Sidebar Colors - Dégradé moderne */
            --sidebar-bg: #0B1120;
            --sidebar-bg-light: #1A2332;
            --sidebar-bg-lighter: #252F3F;
            --sidebar-hover: #2D374B;
            --sidebar-active: linear-gradient(135deg, var(--primary-600) 0%, var(--secondary-600) 100%);
            --sidebar-active-glow: rgba(136, 77, 255, 0.3);
            --sidebar-border: rgba(255, 255, 255, 0.08);
            
            /* Text Colors */
            --text-primary: #FFFFFF;
            --text-secondary: #E2E8F0;
            --text-muted: #94A3B8;
            --text-dark: #0F172A;
            --text-light: #F8FAFC;
            
            /* Background Colors - Nouveau fond plus élégant */
            --bg-main: #F9FAFF;
            --bg-card: #FFFFFF;
            --bg-hover: #F1F5F9;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            
            /* Shadows */
            --shadow-sm: 0 2px 4px 0 rgba(0, 0, 0, 0.02);
            --shadow-md: 0 4px 8px 0 rgba(0, 0, 0, 0.03), 0 2px 4px 0 rgba(0, 0, 0, 0.02);
            --shadow-lg: 0 8px 16px 0 rgba(0, 0, 0, 0.04), 0 4px 8px 0 rgba(0, 0, 0, 0.02);
            --shadow-xl: 0 20px 24px -4px rgba(0, 0, 0, 0.06), 0 8px 8px -4px rgba(0, 0, 0, 0.02);
            --shadow-2xl: 0 25px 30px -12px rgba(0, 0, 0, 0.15);
            --shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.02);
            --shadow-glow: 0 0 0 3px rgba(136, 77, 255, 0.15);
            --shadow-glow-hover: 0 0 0 4px rgba(136, 77, 255, 0.25);
            
            /* Border Radius */
            --radius-sm: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.25rem;
            --radius-2xl: 1.5rem;
            --radius-3xl: 2rem;
            --radius-full: 9999px;
            
            /* Transitions */
            --transition-base: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            
            /* Spacing */
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
        }

        /*---------------------------------------
          RESET & BASE STYLES
        ---------------------------------------*/
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, var(--bg-main) 0%, #f0f3ff 100%);
            color: var(--text-dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        /*---------------------------------------
          MAIN LAYOUT
        ---------------------------------------*/
        .layout {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /*---------------------------------------
          MOBILE HEADER (visible only on mobile)
        ---------------------------------------*/
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(11, 17, 32, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            color: var(--text-primary);
            padding: var(--spacing-4) var(--spacing-6);
            z-index: 100;
            box-shadow: var(--shadow-xl);
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mobile-brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, var(--primary-300) 50%, var(--secondary-300) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% auto;
            animation: gradientFlow 3s ease infinite;
            letter-spacing: -0.5px;
        }

        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .mobile-brand small {
            font-size: 0.75rem;
            display: block;
            color: var(--text-secondary);
            -webkit-text-fill-color: var(--text-secondary);
            font-weight: 400;
            letter-spacing: normal;
            opacity: 0.8;
        }

        .menu-toggle-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            width: 44px;
            height: 44px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.25rem;
            transition: var(--transition-bounce);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .menu-toggle-btn:hover {
            background: var(--sidebar-active);
            border-color: transparent;
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 0 20px var(--sidebar-active-glow);
        }

        .menu-toggle-btn.active {
            background: var(--sidebar-active);
            border-color: transparent;
            transform: rotate(90deg);
        }

        /*---------------------------------------
          SIDEBAR - MODERN DESIGN
        ---------------------------------------*/
        .sidebar {
            width: 320px;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, var(--sidebar-bg-light) 100%);
            color: var(--text-primary);
            padding: var(--spacing-8) var(--spacing-5);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 20px 0 30px -10px rgba(0, 0, 0, 0.15);
            border-right: 1px solid var(--sidebar-border);
            z-index: 90;
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Glass morphism effect on scroll */
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, transparent 100%);
            pointer-events: none;
            z-index: 1;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(0deg, var(--sidebar-bg) 0%, transparent 100%);
            pointer-events: none;
            z-index: 1;
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-full);
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--primary-500) 0%, var(--secondary-500) 100%);
        }

        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
        }

        /*---------------------------------------
          BRAND SECTION - NOUVEAU DESIGN
        ---------------------------------------*/
        .brand {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: var(--spacing-12);
            text-align: left;
            padding: 0 var(--spacing-3);
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #ffffff 0%, var(--primary-300) 25%, var(--secondary-300) 50%, var(--accent-300) 75%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 300% auto;
            animation: gradientShift 6s ease infinite;
            position: relative;
            transform: translateZ(0);
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .brand span {
            display: block;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: var(--spacing-3);
            font-weight: 400;
            letter-spacing: normal;
            -webkit-text-fill-color: var(--text-secondary);
            background: none;
            border-top: 1px solid var(--sidebar-border);
            padding-top: var(--spacing-4);
            opacity: 0.8;
        }

        /*---------------------------------------
          MENU STYLES - NOUVEAU DESIGN
        ---------------------------------------*/
        .menu, .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu > li {
            margin-bottom: var(--spacing-2);
            transform: translateZ(0);
        }

        /* Menu Links */
        .menu a {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
            padding: var(--spacing-3) var(--spacing-4);
            border-radius: var(--radius-xl);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            border: 1px solid transparent;
            backdrop-filter: blur(5px);
        }

        .menu a i {
            width: 24px;
            font-size: 1.2rem;
            color: var(--text-muted);
            transition: var(--transition-bounce);
            text-align: center;
            filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.1));
        }

        /* Menu item hover effect - Nouveau */
        .menu a::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, 
                rgba(255, 255, 255, 0.05) 0%,
                rgba(255, 255, 255, 0.1) 50%,
                transparent 100%
            );
            transform: translateX(-100%);
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu a:hover::before {
            transform: translateX(0);
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            transform: translateX(8px) scale(1.02);
            box-shadow: var(--shadow-glow);
        }

        .menu a:hover i {
            color: var(--primary-400);
            transform: scale(1.2) rotate(5deg);
            filter: drop-shadow(0 0 8px var(--primary-400));
        }

        /* Active State - Nouveau design */
        .menu a.active {
            background: var(--sidebar-active);
            color: white;
            box-shadow: 0 8px 20px -4px var(--sidebar-active-glow);
            border-color: transparent;
            position: relative;
            overflow: hidden;
        }

        .menu a.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, 
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .menu a.active i {
            color: white;
            transform: scale(1.15);
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }

        .menu a.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: linear-gradient(180deg, var(--accent-400) 0%, var(--secondary-400) 100%);
            border-radius: var(--radius-full) 0 0 var(--radius-full);
            box-shadow: 0 0 15px var(--accent-400);
        }

        /* Menu Item with Submenu Indicator */
        .menu > li > a:not(:only-child)::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 1rem;
            color: var(--text-muted);
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .menu > li > a.open:not(:only-child)::after {
            transform: rotate(-180deg) translateY(2px);
            color: var(--primary-400);
        }

        /*---------------------------------------
          SUBMENU STYLES - NOUVEAU DESIGN
        ---------------------------------------*/
        .submenu {
            margin-left: var(--spacing-8);
            margin-top: var(--spacing-1);
            margin-bottom: var(--spacing-1);
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top;
        }

        .submenu.open {
            max-height: 800px;
            opacity: 1;
        }

        /* Nested Submenu (Third Level) */
        .submenu .submenu {
            margin-left: var(--spacing-8);
            margin-top: 0;
            border-left: 1px dashed var(--sidebar-border);
            padding-left: var(--spacing-2);
        }

        .submenu .submenu a {
            font-size: 0.875rem;
            padding: var(--spacing-2) var(--spacing-4);
        }

        /* Submenu Links */
        .submenu a {
            padding: var(--spacing-2) var(--spacing-4);
            color: var(--text-muted);
            font-size: 0.875rem;
            border-left: 2px solid transparent;
            transition: var(--transition-smooth);
            cursor: pointer;
            gap: var(--spacing-3);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-1);
        }

        .submenu a i {
            font-size: 0.95rem;
            width: 20px;
            color: var(--text-muted);
            transition: var(--transition-bounce);
        }

        .submenu a:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.02);
            border-left-color: var(--accent-500);
            transform: translateX(8px);
            box-shadow: var(--shadow-sm);
        }

        .submenu a:hover i {
            color: var(--accent-400);
            transform: scale(1.15) translateX(2px);
        }

        .submenu a.active {
            background: linear-gradient(90deg, rgba(136, 77, 255, 0.1) 0%, transparent 100%);
            color: var(--primary-300);
            border-left-color: var(--accent-500);
            font-weight: 600;
            position: relative;
        }

        .submenu a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, var(--accent-500) 0%, var(--secondary-500) 100%);
            border-radius: var(--radius-full);
        }

        .submenu a.active i {
            color: var(--accent-400);
            filter: drop-shadow(0 0 5px var(--accent-400));
        }

        /* Submenu item with nested submenu */
        .submenu > li > a:not(:only-child)::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 0.875rem;
            color: var(--text-muted);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .submenu > li > a.open:not(:only-child)::after {
            transform: rotate(-180deg) translateY(2px);
            color: var(--accent-400);
        }

        /*---------------------------------------
          LOGOUT BUTTON - NOUVEAU DESIGN
        ---------------------------------------*/
        .logout {
            margin-top: auto;
            padding-top: var(--spacing-8);
            border-top: 1px solid var(--sidebar-border);
            position: relative;
        }

        .logout::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 10%;
            right: 10%;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent, 
                var(--accent-500), 
                var(--primary-500), 
                var(--secondary-500), 
                transparent
            );
        }

        .logout button {
            width: 100%;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--sidebar-border);
            color: var(--text-secondary);
            padding: var(--spacing-4) var(--spacing-4);
            border-radius: var(--radius-xl);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-3);
            transition: var(--transition-bounce);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .logout button::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, 
                transparent,
                rgba(244, 63, 94, 0.2),
                transparent
            );
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .logout button i {
            font-size: 1.2rem;
            transition: var(--transition-bounce);
            color: var(--accent-500);
            filter: drop-shadow(0 2px 5px rgba(244, 63, 94, 0.3));
        }

        .logout button:hover {
            background: rgba(244, 63, 94, 0.1);
            color: var(--text-primary);
            border-color: var(--accent-500);
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 25px -5px rgba(244, 63, 94, 0.4);
        }

        .logout button:hover::before {
            transform: translateX(100%);
        }

        .logout button:hover i {
            transform: translateX(8px) scale(1.2);
            color: var(--accent-400);
        }

        /*---------------------------------------
          MAIN CONTENT - NOUVEAU DESIGN
        ---------------------------------------*/
        .content {
            flex: 1;
            padding: var(--spacing-10);
            margin-left: 320px;
            min-height: 100vh;
            overflow-y: auto;
            background: var(--bg-main);
            transition: var(--transition-smooth);
            position: relative;
        }

        /* Content background pattern */
        .content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(136, 77, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(59, 130, 246, 0.03) 0%, transparent 50%),
                repeating-linear-gradient(45deg, rgba(0, 0, 0, 0.01) 0px, rgba(0, 0, 0, 0.01) 1px, transparent 1px, transparent 10px);
            pointer-events: none;
        }

        /* Custom Scrollbar for Content */
        .content::-webkit-scrollbar {
            width: 10px;
        }

        .content::-webkit-scrollbar-track {
            background: var(--bg-main);
        }

        .content::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--primary-400) 0%, var(--secondary-500) 100%);
            border-radius: var(--radius-full);
            border: 2px solid var(--bg-main);
        }

        .content::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--primary-500) 0%, var(--secondary-600) 100%);
        }

        /*---------------------------------------
          CARD STYLES - NOUVEAU DESIGN
        ---------------------------------------*/
        .card {
            background: var(--bg-card);
            border-radius: var(--radius-2xl);
            padding: var(--spacing-8);
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(0, 0, 0, 0.02);
            transition: var(--transition-bounce);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-500) 0%, var(--secondary-500) 50%, var(--accent-500) 100%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-2xl);
            transform: translateY(-6px) scale(1.01);
            border-color: transparent;
        }

        .card:hover::before {
            transform: translateX(0);
        }

        /*---------------------------------------
          OVERLAY FOR MOBILE
        ---------------------------------------*/
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 80;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .sidebar-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        /*---------------------------------------
          ANIMATIONS
        ---------------------------------------*/
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sidebar {
            animation: slideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .menu a.active i {
            animation: float 3s ease infinite;
        }

        /*---------------------------------------
          RESPONSIVE DESIGN - AMÉLIORÉ
        ---------------------------------------*/

        /* Tablettes et petits écrans */
        @media (max-width: 1024px) {
            .sidebar {
                width: 280px;
            }
            
            .content {
                margin-left: 280px;
                padding: var(--spacing-8);
            }
            
            .brand {
                font-size: 1.75rem;
            }

            .menu a {
                font-size: 0.9rem;
            }
        }

        /* Mobile - Version avec dropdown */
        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
            }

            .layout {
                padding-top: 72px;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 320px;
                top: 72px;
                height: calc(100vh - 72px);
                box-shadow: 4px 0 30px rgba(0, 0, 0, 0.3);
                border-right: 1px solid var(--sidebar-border);
                border-top: 1px solid var(--sidebar-border);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding: var(--spacing-6);
                width: 100%;
            }

            .sidebar-overlay {
                display: block;
            }

            .submenu {
                margin-left: var(--spacing-8);
            }

            .submenu .submenu {
                margin-left: var(--spacing-6);
            }

            .card {
                padding: var(--spacing-6);
            }
        }

        /* Petits mobiles */
        @media (max-width: 480px) {
            .mobile-header {
                padding: var(--spacing-3) var(--spacing-4);
            }

            .mobile-brand {
                font-size: 1.25rem;
            }

            .mobile-brand small {
                font-size: 0.7rem;
            }

            .menu-toggle-btn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .sidebar {
                width: 100%;
                max-width: 320px;
            }

            .menu a {
                padding: var(--spacing-3) var(--spacing-3);
                font-size: 0.875rem;
            }
            
            .submenu a {
                font-size: 0.8125rem;
                padding: var(--spacing-2) var(--spacing-3);
            }
            
            .content {
                padding: var(--spacing-4);
            }

            .card {
                padding: var(--spacing-4);
                border-radius: var(--radius-xl);
            }

            .logout button {
                padding: var(--spacing-3) var(--spacing-3);
                font-size: 0.875rem;
            }
        }

        /* Très petits écrans */
        @media (max-width: 360px) {
            .sidebar {
                max-width: 280px;
            }

            .menu a {
                font-size: 0.8125rem;
                padding: var(--spacing-2);
            }

            .menu a i {
                width: 20px;
                font-size: 0.9375rem;
            }

            .submenu {
                margin-left: var(--spacing-6);
            }

            .brand {
                font-size: 1.5rem;
            }
        }

        /* Mode paysage sur mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .sidebar {
                width: 280px;
            }

            .content {
                padding: var(--spacing-4);
            }

            .menu a {
                padding: var(--spacing-2) var(--spacing-3);
            }
        }

        /*---------------------------------------
          FOCUS STATES
        ---------------------------------------*/
        :focus-visible {
            outline: 2px solid var(--primary-500);
            outline-offset: 2px;
            border-radius: var(--radius-sm);
        }

        /*---------------------------------------
          UTILITY CLASSES
        ---------------------------------------*/
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--secondary-500) 50%, var(--accent-500) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bg-gradient {
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--secondary-500) 100%);
        }

        .menu-separator {
            height: 1px;
            background: linear-gradient(90deg, 
                transparent, 
                var(--sidebar-border), 
                var(--accent-500), 
                var(--sidebar-border), 
                transparent
            );
            margin: var(--spacing-6) 0;
        }

        /* Support des grands écrans */
        @media (min-width: 1920px) {
            .sidebar {
                width: 360px;
            }

            .content {
                margin-left: 360px;
                padding: var(--spacing-12);
            }

            .menu a {
                font-size: 1rem;
                padding: var(--spacing-4) var(--spacing-6);
            }

            .submenu a {
                font-size: 0.9375rem;
            }

            .brand {
                font-size: 2.25rem;
            }
        }

        /* Impression */
        @media print {
            .sidebar,
            .mobile-header,
            .logout {
                display: none;
            }

            .content {
                margin-left: 0;
                padding: 0;
            }

            .card {
                box-shadow: none;
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

<!-- Mobile Header (visible uniquement sur mobile) -->
<div class="mobile-header">
    <div class="mobile-brand">
        FOLLUP.IO
        <small>{{ session('client.company') }}</small>
    </div>
    <button class="menu-toggle-btn" id="mobileMenuToggle" aria-label="Menu">
        <i class="fa-solid fa-bars-staggered"></i>
    </button>
</div>

<!-- Overlay pour mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="layout">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            FOLLUP.IO
            <span>{{ session('client.company') }}</span>
        </div>

        <ul class="menu" id="mainMenu">
            <!-- CRM -->
            <li>
                <a href="#" class="menu-toggle" data-target="crm-submenu">
                    <i class="fa-solid fa-chart-pie"></i> CRM
                </a>
                <ul class="submenu" id="crm-submenu">
                    @if(session('client.role') === 'superadmin')
                    <li><a href="{{ route('client.crm.dashboard') }}"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>
                    @endif
                    <li><a href="{{ route('client.crm.leads') }}"><i class="fa-solid fa-users"></i> Mes Leads</a></li>
                </ul>
            </li>

            <!-- MAILS -->
            <li>
                <a href="#" class="menu-toggle" data-target="mails-submenu">
                    <i class="fa-regular fa-envelope"></i> Mails
                </a>
                <ul class="submenu" id="mails-submenu">
                    <li><a href="{{ route('client.mails.programmes') }}"><i class="fa-regular fa-clock"></i> Mes mails programmés</a></li>
                    <li>
                        <a href="{{ route('client.mails.plus') }}">
                            <i class="fa-solid fa-bullhorn"></i> Envoi mail en masse
                        </a>
                    </li>
                    <li>
                        <a href="#" class="menu-toggle" data-target="inbox-submenu">
                            <i class="fa-regular fa-inbox"></i> Boite de réception
                        </a>
                        <ul class="submenu" id="inbox-submenu">
                            <li><a href="{{ route('client.mails.envoyes') }}"><i class="fa-regular fa-paper-plane"></i> Mails envoyés</a></li>
                            <li><a href="{{ route('client.mails.recus') }}"><i class="fa-regular fa-circle-down"></i> Mails reçus</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <!-- SCRAPPING -->
            <li>
                <a href="#" class="menu-toggle" data-target="scraping-submenu">
                    <i class="fa-solid fa-robot"></i> SCRAPPING
                </a>
                <ul class="submenu" id="scraping-submenu">
                    <li><a href="{{ route('client.google') }}"><i class="fa-solid fa-map-location-dot"></i> Google Maps</a></li>
                    <li><a href="{{ route('client.web') }}"><i class="fa-solid fa-globe"></i> Site Web</a></li>
                </ul>
            </li>

            <!-- PROMPT IA -->
            <li>
                <a href="{{ route('client.prompt-ia') }}">
                    <i class="fa-solid fa-brain"></i> Prompt IA
                </a>
            </li>

            <!-- COMMUNICATION -->
            <li class="disabled-communication">
    <a href="#" class="menu-toggle" data-target="communication-submenu">
        <i class="fa-solid fa-comments"></i> Communication
    </a>

    <ul class="submenu" id="communication-submenu">
        <li>
            <a href="{{ route('client.communication.whatsapp') }}">
                <i class="fa-brands fa-whatsapp"></i> WhatsApp
            </a>
        </li>
        <li>
            <a href="{{ route('client.communication.sms') }}">
                <i class="fa-solid fa-message"></i> SMS
            </a>
        </li>
    </ul>
</li>

<style>
.disabled-communication {
    position: relative;
    opacity: 0.6;
    cursor: not-allowed;
}

/* Bloquer tous les clics */
.disabled-communication a {
    pointer-events: none;
}

/* cacher le submenu */
#communication-submenu {
    display: none !important;
}

/* Tooltip */
.disabled-communication:hover::after {
    content: "En cours de développement";
    position: absolute;
    left: 60px;
    top: 0;
    background: #333;
    color: #fff;
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 5px;
    white-space: nowrap;
}
</style>

            <!-- PARAMETRES -->
            <li>
                <a href="#" class="menu-toggle" data-target="params-submenu">
                    <i class="fa-solid fa-sliders"></i> Paramètres
                </a>
                <ul class="submenu" id="params-submenu">
                    <li><a href="{{ route('client.profil') }}"><i class="fa-regular fa-user"></i> Profil</a></li>
                    @if(session('client.role') === 'superadmin')
                        <li><a href="{{ route('client.users') }}"><i class="fa-solid fa-users-cog"></i> Utilisateurs</a></li>
                    @endif
                </ul>
            </li>
        </ul>

        <div class="logout">
            <form method="POST" action="{{ route('client.logout') }}">
                @csrf
                <button type="submit">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion
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

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeMobileMenu();
        initializeMenuSystem();
        initializeActiveLinks();
        handleResize();
        addRippleEffect();
    });

    // Gestion du menu mobile (dropdown)
    function initializeMobileMenu() {
        const mobileToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (!mobileToggle || !sidebar || !overlay) return;

        // Ouvrir/fermer le menu mobile
        mobileToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isActive = sidebar.classList.contains('active');
            
            if (!isActive) {
                // Ouvrir
                sidebar.classList.add('active');
                overlay.classList.add('active');
                mobileToggle.classList.add('active');
                document.body.style.overflow = 'hidden';
                // Animation du bouton
                const icon = this.querySelector('i');
                icon.classList.remove('fa-bars-staggered');
                icon.classList.add('fa-xmark');
            } else {
                // Fermer
                closeMobileMenu();
            }
        });

        // Fermer avec l'overlay
        overlay.addEventListener('click', function() {
            closeMobileMenu();
        });

        // Fermer avec la touche Echap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                closeMobileMenu();
            }
        });

        function closeMobileMenu() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            mobileToggle.classList.remove('active');
            document.body.style.overflow = '';
            const icon = mobileToggle.querySelector('i');
            icon.classList.remove('fa-xmark');
            icon.classList.add('fa-bars-staggered');
        }

        // Fermer le menu après avoir cliqué sur un lien (mobile)
        const menuLinks = sidebar.querySelectorAll('a:not(.menu-toggle)');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    closeMobileMenu();
                }
            });
        });
    }

    // Initialize click-to-open menu system
    function initializeMenuSystem() {
        const menuToggles = document.querySelectorAll('.menu-toggle');
        
        menuToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const targetId = this.dataset.target;
                const targetMenu = document.getElementById(targetId);
                
                if (targetMenu) {
                    // Toggle current menu
                    targetMenu.classList.toggle('open');
                    this.classList.toggle('open');
                    
                    // Close sibling menus at the same level
                    const parent = this.closest('li');
                    if (parent) {
                        const siblingMenus = parent.parentElement.querySelectorAll(':scope > li > .submenu');
                        siblingMenus.forEach(menu => {
                            if (menu.id !== targetId) {
                                menu.classList.remove('open');
                                const siblingToggle = menu.parentElement.querySelector('.menu-toggle');
                                if (siblingToggle) {
                                    siblingToggle.classList.remove('open');
                                }
                            }
                        });
                    }
                    
                    // Animate icon rotation and scale
                    const icon = this.querySelector('i:first-child');
                    if (icon) {
                        icon.style.transform = targetMenu.classList.contains('open') ? 'rotate(90deg) scale(1.2)' : 'rotate(0) scale(1)';
                        setTimeout(() => {
                            icon.style.transform = '';
                        }, 400);
                    }
                }
            });
        });
    }

    // Initialize active links based on current URL
    function initializeActiveLinks() {
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.menu a:not(.menu-toggle)');
        
        menuLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                // Mark link as active
                link.classList.add('active');
                
                // Open all parent menus
                let parent = link.closest('.submenu');
                while (parent) {
                    parent.classList.add('open');
                    
                    // Find and mark parent toggle as open
                    const parentLi = parent.closest('li');
                    if (parentLi) {
                        const parentToggle = parentLi.querySelector(':scope > .menu-toggle');
                        if (parentToggle) {
                            parentToggle.classList.add('open');
                        }
                    }
                    
                    parent = parent.parentElement.closest('.submenu');
                }
            }
        });
    }

    // Gestion du redimensionnement
    function handleResize() {
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const mobileToggle = document.getElementById('mobileMenuToggle');
                
                if (window.innerWidth > 768) {
                    // Mode desktop
                    if (sidebar) sidebar.classList.remove('active');
                    if (overlay) overlay.classList.remove('active');
                    if (mobileToggle) {
                        mobileToggle.classList.remove('active');
                        const icon = mobileToggle.querySelector('i');
                        icon.classList.remove('fa-xmark');
                        icon.classList.add('fa-bars-staggered');
                    }
                    document.body.style.overflow = '';
                }
            }, 250);
        });
    }

    // Effet de ripple sur les boutons
    function addRippleEffect() {
        const buttons = document.querySelectorAll('.menu a, .logout button');
        
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    }

    // Sauvegarde de l'état du menu (optionnel)
    function saveMenuState() {
        const openMenus = [];
        document.querySelectorAll('.submenu.open').forEach(menu => {
            openMenus.push(menu.id);
        });
        localStorage.setItem('openMenus', JSON.stringify(openMenus));
    }

    function loadMenuState() {
        const savedMenus = localStorage.getItem('openMenus');
        if (savedMenus) {
            const openMenus = JSON.parse(savedMenus);
            openMenus.forEach(menuId => {
                const menu = document.getElementById(menuId);
                const toggle = document.querySelector(`[data-target="${menuId}"]`);
                if (menu && toggle) {
                    menu.classList.add('open');
                    toggle.classList.add('open');
                }
            });
        }
    }

    // Décommenter pour activer la persistance
    loadMenuState();
    // 
     window.addEventListener('beforeunload', function() {
         saveMenuState();
     });

})();
</script>

<style>
/* Ripple effect */
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    transform: scale(0);
    animation: ripple-animation 0.6s ease-out;
    pointer-events: none;
    z-index: 999;
}

@keyframes ripple-animation {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.menu a, .logout button {
    position: relative;
    overflow: hidden;
}
</style>

</body>
</html>