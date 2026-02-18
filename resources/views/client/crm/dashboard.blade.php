@extends('client.layouts.app')

@section('title', 'CRM Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Header avec sélecteur de période -->
    <div class="dashboard-header">
        <div>
            <h1>Dashboard CRM</h1>
            <p>Statistiques globales, pipeline, conversions et performances</p>
        </div>
        <div class="period-selector">
            <button class="period-btn active">7 jours</button>
            <button class="period-btn">30 jours</button>
            <button class="period-btn">3 mois</button>
            <button class="period-btn">Cette année</button>
        </div>
    </div>

    <!-- KPI Cards - Stats principales -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon" style="background: linear-gradient(135deg, #4361ee15, #4361ee25)">
                <i class="fas fa-users" style="color: #4361ee"></i>
            </div>
            <div class="kpi-content">
                <span class="kpi-label">Total Leads</span>
                <span class="kpi-value">1,284</span>
                <span class="kpi-trend positive">
                    <i class="fas fa-arrow-up"></i> +12.5%
                </span>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background: linear-gradient(135deg, #06d6a015, #06d6a025)">
                <i class="fas fa-fire" style="color: #06d6a0"></i>
            </div>
            <div class="kpi-content">
                <span class="kpi-label">Leads Chauds</span>
                <span class="kpi-value">342</span>
                <span class="kpi-trend positive">
                    <i class="fas fa-arrow-up"></i> +8.2%
                </span>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background: linear-gradient(135deg, #ffb70315, #ffb70325)">
                <i class="fas fa-clock" style="color: #ffb703"></i>
            </div>
            <div class="kpi-content">
                <span class="kpi-label">En cours</span>
                <span class="kpi-value">573</span>
                <span class="kpi-trend">
                    <i class="fas fa-minus"></i> Stable
                </span>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background: linear-gradient(135deg, #ef476f15, #ef476f25)">
                <i class="fas fa-times-circle" style="color: #ef476f"></i>
            </div>
            <div class="kpi-content">
                <span class="kpi-label">Taux de conversion</span>
                <span class="kpi-value">23.6%</span>
                <span class="kpi-trend positive">
                    <i class="fas fa-arrow-up"></i> +5.4%
                </span>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon" style="background: linear-gradient(135deg, #7209b715, #7209b725)">
                <i class="fas fa-check-circle" style="color: #7209b7"></i>
            </div>
            <div class="kpi-content">
                <span class="kpi-label">Vendus</span>
                <span class="kpi-value">128</span>
                <span class="kpi-trend positive">
                    <i class="fas fa-arrow-up"></i> +15.3%
                </span>
            </div>
        </div>
    </div>

    <!-- Graphiques section -->
    <div class="charts-grid">
        <!-- Pipeline Funnel Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Pipeline des leads</h3>
                <div class="chart-actions">
                    <button class="chart-btn active">Vue funnel</button>
                    <button class="chart-btn">Vue barres</button>
                </div>
            </div>
            <div class="chart-body">
                <!-- Funnel statique -->
                <div class="funnel-container">
                    <div class="funnel-stage" style="width: 100%">
                        <div class="stage-label">
                            <span>Prospection</span>
                            <span class="stage-value">1,284 leads</span>
                        </div>
                        <div class="stage-bar" style="height: 60px; background: linear-gradient(90deg, #4361ee, #7209b7)">
                            <div class="stage-percent">100%</div>
                        </div>
                    </div>
                    <div class="funnel-stage" style="width: 85%">
                        <div class="stage-label">
                            <span>Contact établis</span>
                            <span class="stage-value">1,091 leads</span>
                        </div>
                        <div class="stage-bar" style="height: 50px; background: linear-gradient(90deg, #4361ee, #7209b7)">
                            <div class="stage-percent">85%</div>
                        </div>
                    </div>
                    <div class="funnel-stage" style="width: 62%">
                        <div class="stage-label">
                            <span>Qualifiés</span>
                            <span class="stage-value">796 leads</span>
                        </div>
                        <div class="stage-bar" style="height: 45px; background: linear-gradient(90deg, #4361ee, #7209b7)">
                            <div class="stage-percent">62%</div>
                        </div>
                    </div>
                    <div class="funnel-stage" style="width: 45%">
                        <div class="stage-label">
                            <span>En négociation</span>
                            <span class="stage-value">578 leads</span>
                        </div>
                        <div class="stage-bar" style="height: 40px; background: linear-gradient(90deg, #4361ee, #7209b7)">
                            <div class="stage-percent">45%</div>
                        </div>
                    </div>
                    <div class="funnel-stage" style="width: 28%">
                        <div class="stage-label">
                            <span>Devis envoyés</span>
                            <span class="stage-value">360 leads</span>
                        </div>
                        <div class="stage-bar" style="height: 35px; background: linear-gradient(90deg, #4361ee, #7209b7)">
                            <div class="stage-percent">28%</div>
                        </div>
                    </div>
                    <div class="funnel-stage" style="width: 15%">
                        <div class="stage-label">
                            <span>Converti</span>
                            <span class="stage-value">193 leads</span>
                        </div>
                        <div class="stage-bar" style="height: 30px; background: linear-gradient(90deg, #06d6a0, #0b8a6b)">
                            <div class="stage-percent">15%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition par source -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Sources d'acquisition</h3>
                <div class="chart-legend">
                    <span class="legend-item"><span class="legend-color" style="background: #4361ee"></span>Google</span>
                    <span class="legend-item"><span class="legend-color" style="background: #7209b7"></span>LinkedIn</span>
                    <span class="legend-item"><span class="legend-color" style="background: #06d6a0"></span>Instagram</span>
                </div>
            </div>
            <div class="chart-body">
                <div class="pie-chart-container">
                    <!-- Donut chart statique -->
                    <div class="donut-chart">
                        <svg viewBox="0 0 100 100" class="donut-svg">
                            <circle cx="50" cy="50" r="40" fill="transparent" stroke="#e2e8f0" stroke-width="12" />
                            <circle cx="50" cy="50" r="40" fill="transparent" stroke="#4361ee" stroke-width="12" stroke-dasharray="251.2" stroke-dashoffset="62.8" transform="rotate(-90 50 50)" />
                            <circle cx="50" cy="50" r="40" fill="transparent" stroke="#7209b7" stroke-width="12" stroke-dasharray="251.2" stroke-dashoffset="138.16" transform="rotate(-90 50 50)" />
                            <circle cx="50" cy="50" r="40" fill="transparent" stroke="#06d6a0" stroke-width="12" stroke-dasharray="251.2" stroke-dashoffset="188.4" transform="rotate(-90 50 50)" />
                        </svg>
                        <div class="donut-center">
                            <span class="donut-total">1,284</span>
                            <span class="donut-label">Total leads</span>
                        </div>
                    </div>
                    <div class="pie-stats">
                        <div class="pie-stat-item">
                            <span class="stat-source">Google Ads</span>
                            <span class="stat-percent">45%</span>
                            <span class="stat-count">578 leads</span>
                        </div>
                        <div class="pie-stat-item">
                            <span class="stat-source">LinkedIn</span>
                            <span class="stat-percent">30%</span>
                            <span class="stat-count">385 leads</span>
                        </div>
                        <div class="pie-stat-item">
                            <span class="stat-source">Instagram</span>
                            <span class="stat-percent">20%</span>
                            <span class="stat-count">257 leads</span>
                        </div>
                        <div class="pie-stat-item">
                            <span class="stat-source">Autres</span>
                            <span class="stat-percent">5%</span>
                            <span class="stat-count">64 leads</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Évolution mensuelle -->
        <div class="chart-card full-width">
            <div class="chart-header">
                <h3>Évolution des leads</h3>
                <div class="chart-legend">
                    <span class="legend-item"><span class="legend-color" style="background: #4361ee"></span>Nouveaux</span>
                    <span class="legend-item"><span class="legend-color" style="background: #06d6a0"></span>Convertis</span>
                </div>
            </div>
            <div class="chart-body">
                <div class="bar-chart">
                    <div class="bar-chart-container">
                        <!-- Mois -->
                        <div class="bar-group">
                            <div class="bar-label">Jan</div>
                            <div class="bars">
                                <div class="bar blue" style="height: 80px" title="145 leads">
                                    <span class="bar-value">145</span>
                                </div>
                                <div class="bar green" style="height: 45px" title="32 leads">
                                    <span class="bar-value">32</span>
                                </div>
                            </div>
                        </div>
                        <div class="bar-group">
                            <div class="bar-label">Fév</div>
                            <div class="bars">
                                <div class="bar blue" style="height: 95px" title="168 leads">
                                    <span class="bar-value">168</span>
                                </div>
                                <div class="bar green" style="height: 52px" title="38 leads">
                                    <span class="bar-value">38</span>
                                </div>
                            </div>
                        </div>
                        <div class="bar-group">
                            <div class="bar-label">Mar</div>
                            <div class="bars">
                                <div class="bar blue" style="height: 110px" title="195 leads">
                                    <span class="bar-value">195</span>
                                </div>
                                <div class="bar green" style="height: 65px" title="47 leads">
                                    <span class="bar-value">47</span>
                                </div>
                            </div>
                        </div>
                        <div class="bar-group">
                            <div class="bar-label">Avr</div>
                            <div class="bars">
                                <div class="bar blue" style="height: 135px" title="234 leads">
                                    <span class="bar-value">234</span>
                                </div>
                                <div class="bar green" style="height: 78px" title="56 leads">
                                    <span class="bar-value">56</span>
                                </div>
                            </div>
                        </div>
                        <div class="bar-group">
                            <div class="bar-label">Mai</div>
                            <div class="bars">
                                <div class="bar blue" style="height: 120px" title="212 leads">
                                    <span class="bar-value">212</span>
                                </div>
                                <div class="bar green" style="height: 71px" title="51 leads">
                                    <span class="bar-value">51</span>
                                </div>
                            </div>
                        </div>
                        <div class="bar-group">
                            <div class="bar-label">Juin</div>
                            <div class="bars">
                                <div class="bar blue" style="height: 98px" title="178 leads">
                                    <span class="bar-value">178</span>
                                </div>
                                <div class="bar green" style="height: 62px" title="44 leads">
                                    <span class="bar-value">44</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribution par chaleur -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Répartition par chaleur</h3>
            </div>
            <div class="chart-body">
                <div class="distribution-list">
                    <div class="dist-item">
                        <div class="dist-label">
                            <span class="dist-color" style="background: #ef476f"></span>
                            <span>Froid</span>
                        </div>
                        <div class="dist-bar-container">
                            <div class="dist-bar" style="width: 25%; background: #ef476f"></div>
                        </div>
                        <span class="dist-value">321</span>
                    </div>
                    <div class="dist-item">
                        <div class="dist-label">
                            <span class="dist-color" style="background: #ffb703"></span>
                            <span>Tiède</span>
                        </div>
                        <div class="dist-bar-container">
                            <div class="dist-bar" style="width: 35%; background: #ffb703"></div>
                        </div>
                        <span class="dist-value">449</span>
                    </div>
                    <div class="dist-item">
                        <div class="dist-label">
                            <span class="dist-color" style="background: #06d6a0"></span>
                            <span>Chaud</span>
                        </div>
                        <div class="dist-bar-container">
                            <div class="dist-bar" style="width: 27%; background: #06d6a0"></div>
                        </div>
                        <span class="dist-value">342</span>
                    </div>
                    <div class="dist-item">
                        <div class="dist-label">
                            <span class="dist-color" style="background: #4361ee"></span>
                            <span>Vendu</span>
                        </div>
                        <div class="dist-bar-container">
                            <div class="dist-bar" style="width: 10%; background: #4361ee"></div>
                        </div>
                        <span class="dist-value">128</span>
                    </div>
                    <div class="dist-item">
                        <div class="dist-label">
                            <span class="dist-color" style="background: #64748b"></span>
                            <span>Mort</span>
                        </div>
                        <div class="dist-bar-container">
                            <div class="dist-bar" style="width: 3%; background: #64748b"></div>
                        </div>
                        <span class="dist-value">44</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance des canaux -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Performance par canal</h3>
            </div>
            <div class="chart-body">
                <div class="channels-stats">
                    <div class="channel-item">
                        <div class="channel-icon" style="background: #4361ee15; color: #4361ee">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="channel-details">
                            <span class="channel-name">Email</span>
                            <span class="channel-rate">68% taux d'ouverture</span>
                            <div class="channel-bar">
                                <div class="channel-bar-fill" style="width: 68%; background: #4361ee"></div>
                            </div>
                        </div>
                    </div>
                    <div class="channel-item">
                        <div class="channel-icon" style="background: #06d6a015; color: #06d6a0">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="channel-details">
                            <span class="channel-name">WhatsApp</span>
                            <span class="channel-rate">82% taux de réponse</span>
                            <div class="channel-bar">
                                <div class="channel-bar-fill" style="width: 82%; background: #06d6a0"></div>
                            </div>
                        </div>
                    </div>
                    <div class="channel-item">
                        <div class="channel-icon" style="background: #7209b715; color: #7209b7">
                            <i class="fab fa-linkedin"></i>
                        </div>
                        <div class="channel-details">
                            <span class="channel-name">LinkedIn</span>
                            <span class="channel-rate">45% taux d'acceptation</span>
                            <div class="channel-bar">
                                <div class="channel-bar-fill" style="width: 45%; background: #7209b7"></div>
                            </div>
                        </div>
                    </div>
                    <div class="channel-item">
                        <div class="channel-icon" style="background: #ffb70315; color: #ffb703">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="channel-details">
                            <span class="channel-name">Téléphone</span>
                            <span class="channel-rate">38% taux de joignabilité</span>
                            <div class="channel-bar">
                                <div class="channel-bar-fill" style="width: 38%; background: #ffb703"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau récent des leads -->
    <div class="recent-leads-card">
        <div class="recent-leads-header">
            <h3>Derniers leads ajoutés</h3>
            <a href="#" class="view-all-link">Voir tous <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="recent-leads-table">
            <table>
                <thead>
                    <tr>
                        <th>Lead</th>
                        <th>Source</th>
                        <th>Chaleur</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="recent-lead-info">
                                <div class="recent-avatar" style="background: linear-gradient(135deg, #4361ee, #7209b7)">JD</div>
                                <div>
                                    <div class="recent-name">Jean Dupont</div>
                                    <div class="recent-company">TechCorp SAS</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge source">Google Ads</span></td>
                        <td><span class="badge chaleur chaud">Chaud</span></td>
                        <td><span class="badge statut encours">En cours</span></td>
                        <td>15/02/2025</td>
                        <td><button class="table-action-btn"><i class="fas fa-eye"></i></button></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="recent-lead-info">
                                <div class="recent-avatar" style="background: linear-gradient(135deg, #f093fb, #f5576c)">MM</div>
                                <div>
                                    <div class="recent-name">Marie Martin</div>
                                    <div class="recent-company">Design Studio</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge source">LinkedIn</span></td>
                        <td><span class="badge chaleur tiede">Tiède</span></td>
                        <td><span class="badge statut relance">À relancer</span></td>
                        <td>14/02/2025</td>
                        <td><button class="table-action-btn"><i class="fas fa-eye"></i></button></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="recent-lead-info">
                                <div class="recent-avatar" style="background: linear-gradient(135deg, #06d6a0, #0b8a6b)">PL</div>
                                <div>
                                    <div class="recent-name">Pierre Laurent</div>
                                    <div class="recent-company">Innovation Lab</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge source">Instagram</span></td>
                        <td><span class="badge chaleur chaud">Chaud</span></td>
                        <td><span class="badge statut encours">En cours</span></td>
                        <td>13/02/2025</td>
                        <td><button class="table-action-btn"><i class="fas fa-eye"></i></button></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="recent-lead-info">
                                <div class="recent-avatar" style="background: linear-gradient(135deg, #ffb703, #f57c00)">SD</div>
                                <div>
                                    <div class="recent-name">Sophie Dubois</div>
                                    <div class="recent-company">Consulting Pro</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge source">Recommandation</span></td>
                        <td><span class="badge chaleur tiede">Tiède</span></td>
                        <td><span class="badge statut devis">R1 prix</span></td>
                        <td>12/02/2025</td>
                        <td><button class="table-action-btn"><i class="fas fa-eye"></i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Reset et variables */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary: #4361ee;
        --primary-dark: #3a56d4;
        --secondary: #7209b7;
        --success: #06d6a0;
        --warning: #ffb703;
        --danger: #ef476f;
        --dark: #1e293b;
        --light: #f8fafc;
        --gray: #64748b;
        --border: #e2e8f0;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: #f1f5f9;
    }

    /* Dashboard container */
    .dashboard-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 24px;
    }

    /* Header */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 28px;
    }

    .dashboard-header h1 {
        font-size: 32px;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 8px 0;
        letter-spacing: -0.5px;
    }

    .dashboard-header p {
        color: var(--gray);
        font-size: 15px;
        margin: 0;
    }

    .period-selector {
        display: flex;
        gap: 8px;
        background: white;
        padding: 4px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .period-btn {
        padding: 10px 20px;
        border-radius: 12px;
        border: none;
        background: transparent;
        color: var(--gray);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .period-btn.active {
        background: var(--primary);
        color: white;
    }

    /* KPI Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .kpi-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        border: 1px solid var(--border);
        transition: all 0.3s;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.05);
    }

    .kpi-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .kpi-content {
        flex: 1;
    }

    .kpi-label {
        display: block;
        font-size: 13px;
        color: var(--gray);
        margin-bottom: 6px;
        font-weight: 500;
    }

    .kpi-value {
        display: block;
        font-size: 28px;
        font-weight: 700;
        color: var(--dark);
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .kpi-trend {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 20px;
        background: var(--light);
    }

    .kpi-trend.positive {
        color: var(--success);
    }

    .kpi-trend i {
        font-size: 10px;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    .chart-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        border: 1px solid var(--border);
    }

    .chart-card.full-width {
        grid-column: 1 / -1;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }

    .chart-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--dark);
    }

    .chart-actions {
        display: flex;
        gap: 8px;
        background: var(--light);
        padding: 4px;
        border-radius: 12px;
    }

    .chart-btn {
        padding: 6px 14px;
        border-radius: 10px;
        border: none;
        background: transparent;
        color: var(--gray);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .chart-btn.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .chart-legend {
        display: flex;
        gap: 16px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--gray);
    }

    .legend-color {
        width: 10px;
        height: 10px;
        border-radius: 4px;
    }

    /* Funnel Chart */
    .funnel-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 8px 0;
    }

    .funnel-stage {
        transition: all 0.3s;
    }

    .stage-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: 13px;
        color: var(--gray);
    }

    .stage-value {
        font-weight: 600;
        color: var(--dark);
    }

    .stage-bar {
        width: 100%;
        border-radius: 12px;
        position: relative;
        margin-bottom: 8px;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
    }

    .stage-percent {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 12px;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    /* Pie Chart */
    .pie-chart-container {
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .donut-chart {
        position: relative;
        width: 160px;
        height: 160px;
    }

    .donut-svg {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }

    .donut-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .donut-total {
        display: block;
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
        line-height: 1.2;
    }

    .donut-label {
        font-size: 11px;
        color: var(--gray);
    }

    .pie-stats {
        flex: 1;
    }

    .pie-stat-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        padding: 8px 12px;
        background: var(--light);
        border-radius: 12px;
    }

    .stat-source {
        flex: 1;
        font-size: 13px;
        color: var(--dark);
        font-weight: 500;
    }

    .stat-percent {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary);
    }

    .stat-count {
        font-size: 12px;
        color: var(--gray);
    }

    /* Bar Chart */
    .bar-chart-container {
        display: flex;
        justify-content: space-around;
        align-items: flex-end;
        gap: 16px;
        padding: 20px 0;
        min-height: 240px;
    }

    .bar-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .bar-label {
        font-size: 12px;
        color: var(--gray);
        font-weight: 500;
    }

    .bars {
        display: flex;
        gap: 8px;
        width: 100%;
        justify-content: center;
    }

    .bar {
        width: 30px;
        border-radius: 8px 8px 0 0;
        position: relative;
        transition: all 0.3s;
        cursor: pointer;
    }

    .bar.blue {
        background: linear-gradient(180deg, var(--primary), var(--secondary));
    }

    .bar.green {
        background: linear-gradient(180deg, var(--success), #0b8a6b);
    }

    .bar-value {
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        font-weight: 600;
        color: var(--gray);
        opacity: 0;
        transition: opacity 0.2s;
    }

    .bar:hover .bar-value {
        opacity: 1;
    }

    /* Distribution list */
    .distribution-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .dist-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dist-label {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 80px;
        font-size: 13px;
        color: var(--dark);
    }

    .dist-color {
        width: 10px;
        height: 10px;
        border-radius: 4px;
    }

    .dist-bar-container {
        flex: 1;
        height: 8px;
        background: var(--light);
        border-radius: 20px;
        overflow: hidden;
    }

    .dist-bar {
        height: 100%;
        border-radius: 20px;
        transition: width 0.3s;
    }

    .dist-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--dark);
        min-width: 40px;
        text-align: right;
    }

    /* Channels stats */
    .channels-stats {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .channel-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px;
        border-radius: 12px;
        transition: all 0.2s;
    }

    .channel-item:hover {
        background: var(--light);
    }

    .channel-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .channel-details {
        flex: 1;
    }

    .channel-name {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .channel-rate {
        display: block;
        font-size: 11px;
        color: var(--gray);
        margin-bottom: 6px;
    }

    .channel-bar {
        height: 6px;
        background: var(--light);
        border-radius: 10px;
        overflow: hidden;
    }

    .channel-bar-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s;
    }

    /* Recent leads card */
    .recent-leads-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        border: 1px solid var(--border);
    }

    .recent-leads-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .recent-leads-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--dark);
    }

    .view-all-link {
        color: var(--primary);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .recent-leads-table {
        overflow-x: auto;
    }

    .recent-leads-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .recent-leads-table th {
        text-align: left;
        padding: 12px 8px;
        font-size: 12px;
        font-weight: 600;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border);
    }

    .recent-leads-table td {
        padding: 16px 8px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .recent-lead-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .recent-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
    }

    .recent-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--dark);
    }

    .recent-company {
        font-size: 11px;
        color: var(--gray);
    }

    .badge.source {
        background: var(--light);
        color: var(--dark);
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge.chaleur.chaud {
        background: #fee2e2;
        color: #991b1b;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge.chaleur.tiede {
        background: #fff3cd;
        color: #856404;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge.statut.encours {
        background: #cfe2ff;
        color: #052c65;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge.statut.relance {
        background: #fff3cd;
        color: #856404;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge.statut.devis {
        background: #e9d5ff;
        color: #6b21a8;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .table-action-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: white;
        color: var(--gray);
        cursor: pointer;
        transition: all 0.2s;
    }

    .table-action-btn:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 16px;
        }

        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .period-selector {
            width: 100%;
            overflow-x: auto;
            padding: 6px;
        }

        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .pie-chart-container {
            flex-direction: column;
            align-items: center;
        }

        .bar-chart-container {
            overflow-x: auto;
            justify-content: flex-start;
        }

        .bar-group {
            min-width: 60px;
        }
    }

    @media (max-width: 480px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .chart-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .chart-legend {
            flex-wrap: wrap;
        }
    }
</style>

<!-- Pas de JavaScript nécessaire pour les graphiques statiques -->
@endsection