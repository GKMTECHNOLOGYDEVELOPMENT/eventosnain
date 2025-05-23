@extends('layouts.blankLayout')

@section('title', 'Login - Pages')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Login -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <h4 class="mb-2"> {{ config('variables.templateName') }} </h4>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Bienvenido a {{ config('variables.templateName') }}! 👋</h4>
                    <p class="mb-4">Inicie sesión en su cuenta y comience la aventura.</p>

                    <form id="formAuthentication" class="mb-3" action="{{ url('auth/login-basic') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo</label>
                            <input type="email" class="form-control @error('login') is-invalid @enderror" id="email"
                                name="email" placeholder="Introduce tu correo electrónico" value="{{ old('email') }}"
                                required autofocus>
                            @error('login')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Contraseña</label>
                                <a href="{{ url('auth/forgot-password-basic') }}">
                                    <small>¿Has olvidado tu contraseña?</small>
                                </a>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password"
                                    class="form-control @error('login') is-invalid @enderror" name="password"
                                    placeholder="Ingresa tu contraseña" required />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('login')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                                <label class="form-check-label" for="remember-me">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Iniciar Sesion</button>
                        </div>
                    </form>

                    <!-- 
                    <p class="text-center">
                        <span>New on our platform?</span>
                        <a href="{{ url('auth/register-basic') }}">
                            <span>Create an account</span>
                        </a>
                    </p> -->
                </div>
            </div>
        </div>
        <!-- /Login -->
    </div>
</div>
@endsection