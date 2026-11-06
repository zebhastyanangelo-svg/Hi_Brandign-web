@extends('layouts.app')

@section('title', 'Acceso Privado Dirección')

@section('content')
<main id="main-content" class="admin-login-page">
    @include('partials.admin-login-card', ['loginContext' => 'page'])

    <p class="admin-login-back">
        <a href="{{ route('home') }}" class="text-link"><span aria-hidden="true">←</span> Volver al sitio</a>
    </p>
</main>
@endsection
