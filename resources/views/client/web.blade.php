@extends('client.layouts.app')

@section('title', 'Web Scraper')

@section('content')
<div class="card">

    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
        <div>
            <h1 style="margin-bottom:6px;">Web Scraper</h1>
            <p style="color:#64748b; margin:0;">
                Extraction automatique d’emails depuis un site web
            </p>
        </div>
    </div>

    <!-- Success -->
    @if(session('success'))
        <div style="
            background:#ecfdf5;
            border:1px solid #34d399;
            color:#065f46;
            padding:14px 18px;
            border-radius:12px;
            margin-bottom:24px;
            font-weight:600;
        ">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Scraper Form -->
    <form method="POST" action="{{ route('client.web.scrape') }}"
          style="
            background:#f8fafc;
            border:1px solid #e2e8f0;
            border-radius:16px;
            padding:24px;
            margin-bottom:40px;
        ">
        @csrf

        <label style="font-weight:600; margin-bottom:8px; display:block;">
            URL du site à analyser
        </label>

        <div style="display:flex; gap:14px;">
            <input
                type="url"
                name="url"
                required
                placeholder="https://exemple.com"
                style="
                    flex:1;
                    padding:14px 16px;
                    border-radius:12px;
                    border:1px solid #cbd5f5;
                    font-size:14px;
                "
            >

            <button
                type="submit"
                style="
                    background:#4f46e5;
                    color:#fff;
                    border:none;
                    border-radius:12px;
                    padding:0 22px;
                    font-weight:600;
                    cursor:pointer;
                "
            >
                <i class="fa-solid fa-play"></i>
                Lancer
            </button>
        </div>

        <p style="margin-top:10px; color:#94a3b8; font-size:13px;">
            Les pages contact, mentions légales et à propos sont analysées automatiquement.
        </p>
    </form>

    <!-- Results -->
    <h2 style="margin-bottom:16px;">Résultats</h2>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f1f5f9;">
                    <th style="padding:14px; text-align:left;">Nom</th>
                    <th style="padding:14px; text-align:left;">Email</th>
                    <th style="padding:14px; text-align:left;">Source</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $row)
                    <tr style="border-bottom:1px solid #e5e7eb;">
                        <td style="padding:14px; font-weight:600;">
                            {{ $row->name ?? '—' }}
                        </td>
                        <td style="padding:14px; color:#2563eb;">
                            {{ $row->email }}
                        </td>
                        <td style="padding:14px;">
                            <a href="{{ $row->source_url }}"
                               target="_blank"
                               style="
                                   color:#4f46e5;
                                   text-decoration:none;
                                   font-weight:600;
                               ">
                                Ouvrir
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding:20px; text-align:center; color:#94a3b8;">
                            Aucun résultat pour le moment
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
