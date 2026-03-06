@extends('client.layouts.app')

@section('title','SMS')

@section('content')

<div class="card">

    <h2>Communication SMS</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('send.sms') }}">
        @csrf

        <div class="form-group">
            <label>Numéro</label>
            <input type="text" name="phone" class="form-control" placeholder="+261XXXXXXXXX" required>
        </div>

        <div class="form-group">
            <label>Message</label>
            <textarea name="message" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Envoyer SMS
        </button>

    </form>

</div>

@endsection