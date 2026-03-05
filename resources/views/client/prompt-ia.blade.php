@extends('client.layouts.app')

@section('title','Prompt IA')

@section('content')

<div class="card">
    <h2>Prompt IA</h2>

    <p>Générateur de messages marketing avec IA.</p>

    <textarea style="width:100%;height:200px" placeholder="Ecris ton prompt ici..."></textarea>

    <br><br>

    <button class="btn">Générer</button>
</div>

@endsection