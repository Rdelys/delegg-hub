@extends('client.layouts.app')

@section('title', 'Envoi mail en masse')

@section('content')
<div class="card">
    @if(session('success'))
    <div style="background:#dcfce7; padding:10px; border-radius:8px; margin-bottom:15px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#fee2e2; padding:10px; border-radius:8px; margin-bottom:15px;">
        {{ session('error') }}
    </div>
@endif
    <h2 style="margin-bottom: 20px;">
        <i class="fa-solid fa-paper-plane text-primary"></i>
        Envoi de mails en masse
    </h2>

<form method="POST" action="{{ route('client.mails.plus.send') }}">        @csrf

        <div style="margin-bottom: 15px;">
            <label>Sujet</label>
            <input type="text" name="subject" 
                   style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Liste des emails (séparés par virgule)</label>
            <textarea name="emails" rows="4"
                      style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;"></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label>Message</label>
            <textarea name="message" rows="6"
                      style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;"></textarea>
        </div>

        <button type="submit" 
                style="background:#4f46e5; color:white; padding:10px 20px; border:none; border-radius:8px; cursor:pointer;">
            <i class="fa-solid fa-paper-plane"></i> Envoyer
        </button>
    </form>
</div>
@endsection