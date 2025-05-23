@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Configuracion de cuenta /</span> Cuenta
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Cuenta</a></li>
    </ul>
    <div class="card mb-4">
      <h5 class="card-header">Profile Details</h5>
      <div class="card-body">
        <form action="{{ route('update-account') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')
          <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
            <img src="{{ asset($user->photo ? 'storage/' . $user->photo : 'assets/img/avatars/1.png') }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
            <div class="button-wrapper">
              <label for="photo" class="btn btn-primary me-2 mb-4" tabindex="0">
                <span class="d-none d-sm-block">Upload new photo</span>
                <i class="bx bx-upload d-block d-sm-none"></i>
                <input type="file" id="photo" name="photo" class="account-file-input" accept="image/png, image/jpeg" />
              </label>
              <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                <i class="bx bx-reset d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Reset</span>
              </button>
              <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
            </div>
          </div>
          <div class="row">
            <div class="mb-3 col-md-6">
              <label for="name" class="form-label">Nombre</label>
              <input class="form-control" type="text" id="name" name="name" value="{{ $user->name }}" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="email" class="form-label">E-mail</label>
              <input class="form-control" type="text" id="email" name="email" value="{{ $user->email }}" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="current_password" class="form-label">Contraseña actual</label>
              <input class="form-control" type="password" id="current_password" name="current_password" />
              @error('current_password')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label for="password" class="form-label">Nueva contraseña</label>
              <input class="form-control" type="password" id="password" name="password" />
            </div>
            <div class="mb-3 col-md-6">
              <label for="password_confirmation" class="form-label">Confirmar Nueva contraseña</label>
              <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" />
            </div>
          </div>
          <div class="mt-2">
            <button type="submit" class="btn btn-primary me-2">Guardar</button>
            <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
