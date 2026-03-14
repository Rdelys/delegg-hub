@extends('client.layouts.app')

@section('title','WhatsApp')

@section('content')

<div class="card">
    <h2>Envoyer message WhatsApp</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('send.whatsapp') }}">
        @csrf

        <div>
            <label>Numéro</label>
            <input type="text" name="phone" placeholder="+261XXXXXXXXX" required>
        </div>

        <div>
            <label>Message</label>
            <textarea name="message" required></textarea>
        </div>

        <button type="submit">Envoyer</button>
    </form>
</div>

@endsection