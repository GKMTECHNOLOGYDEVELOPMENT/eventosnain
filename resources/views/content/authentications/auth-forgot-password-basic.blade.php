@extends('layouts.blankLayout')

@section('title', 'Forgot Password - Pages')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Forgot Password Card -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros', ["width" => 25, "withbg" =>
                                'var(--bs-primary)'])</span>
                            <span
                                class="app-brand-text demo text-body fw-bold">{{ config('variables.templateName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">¿Olvidaste tu contraseña?</h4>
                    <p class="mb-4">Le enviaremos un correo electrónico con un enlace para restablecer su contraseña.
                    </p>

                    <form method="POST" action="{{ route('send-reset-link') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter your email" required>
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary d-grid w-100">Enviar enlace de Recuperacion</button>
                    </form>

                    @if (session('status'))
                    <div class="alert alert-success mt-3">
                        {{ session('status') }}
                    </div>
                    @endif
                </div>
            </div>
            <!-- Forgot Password Card -->
        </div>
    </div>
</div>
@endsection