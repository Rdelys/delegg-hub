@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')

{{-- ===== KPI CARDS ===== --}}
<div class="dashboard-grid">

    <div class="kpi-card">
        <div class="kpi-icon bg-blue">
            <i class="fas fa-building"></i>
        </div>
        <div class="kpi-info">
            <h3>{{ $clientsCount }}</h3>
            <p>Clients</p>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon bg-purple">
            <i class="fas fa-key"></i>
        </div>
        <div class="kpi-info">
            <h3>{{ $licencesCount }}</h3>
            <p>Licences</p>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon bg-green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="kpi-info">
            <h3>{{ \App\Models\Licence::where('status','actif')->count() }}</h3>
            <p>Licences actives</p>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-icon bg-red">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="kpi-info">
            <h3>{{ \App\Models\Licence::where('status','expire')->count() }}</h3>
            <p>Licences expirées</p>
        </div>
    </div>

</div>

{{-- ===== ALERTES ===== --}}
<div class="card">
    <h2 class="section-title">
        <i class="fas fa-bell"></i>
        Alertes importantes
    </h2>

    @php
        $expiringSoon = \App\Models\Licence::where('status','actif')
            ->whereDate('end_date', '<=', now()->addDays(7))
            ->count();
    @endphp

    @if($expiringSoon > 0)
        <div class="alert alert-warning">
            <i class="fas fa-clock"></i>
            <strong>{{ $expiringSoon }}</strong> licence(s) arrivent à expiration sous 7 jours.
        </div>
    @else
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            Aucune licence proche de l’expiration.
        </div>
    @endif
</div>

{{-- ===== DERNIÈRES LICENCES ===== --}}
<div class="card">
    <h2 class="section-title">
        <i class="fas fa-history"></i>
        Dernières licences
    </h2>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Entreprise</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Licence::latest()->take(5)->get() as $licence)
                    <tr>
                        <td>{{ $licence->client?->company }}</td>
                        <td>{{ \Carbon\Carbon::parse($licence->start_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($licence->end_date)->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $licence->status }}">
                                {{ ucfirst($licence->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<style>
.dashboard-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.kpi-card{
    background:white;
    border-radius:18px;
    padding:24px;
    display:flex;
    align-items:center;
    gap:20px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
    transition:.3s;
}

.kpi-card:hover{
    transform:translateY(-6px);
    box-shadow:0 18px 45px rgba(0,0,0,.1);
}

.kpi-icon{
    width:56px;
    height:56px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:22px;
}

.bg-blue{ background:linear-gradient(135deg,#2563eb,#3b82f6); }
.bg-purple{ background:linear-gradient(135deg,#7c3aed,#a78bfa); }
.bg-green{ background:linear-gradient(135deg,#16a34a,#4ade80); }
.bg-red{ background:linear-gradient(135deg,#dc2626,#f87171); }

.kpi-info h3{
    font-size:28px;
    margin:0;
    font-weight:800;
}

.kpi-info p{
    margin:4px 0 0;
    color:#64748b;
    font-weight:500;
}

.section-title{
    font-size:20px;
    font-weight:700;
    margin-bottom:20px;
    display:flex;
    align-items:center;
    gap:10px;
}

.alert{
    padding:16px 20px;
    border-radius:14px;
    display:flex;
    align-items:center;
    gap:12px;
    font-weight:600;
}

.alert-warning{
    background:rgba(245,158,11,.15);
    color:#b45309;
}

.alert-success{
    background:rgba(22,163,74,.15);
    color:#166534;
}

.dashboard-table{
    width:100%;
    border-collapse:collapse;
}

.dashboard-table th,
.dashboard-table td{
    padding:14px;
    border-bottom:1px solid #e5e7eb;
}

.dashboard-table th{
    background:#f8fafc;
    text-align:left;
    font-weight:600;
}

@media(max-width:768px){
    .dashboard-grid{
        grid-template-columns:1fr;
    }
}
</style>

@endsection


