@extends('client.layouts.app')

@section('title', 'Profil')

@section('content')
<div class="card">
    <h2>Mon profil</h2>
    <p>Email : {{ session('client.email') }}</p>
</div>
@endsection
