@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">ENVIO DE COTIZACION / </span> ADMINISTRADOR
</h4>


<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item"><a class="nav-link" href="{{ route('client.proceso', $cliente->id) }}"><i
                        class="bx bx-user me-1"></i> CLIENTE</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.informacion', $cliente->id) }}"><i
                        class="bx bx-bell me-1"></i> LEVANTAMIENTO DE INFORMACION</a></li>
            <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                        class="bx bx-link-alt me-1"></i> COTIZACIONES</a></li>
        </ul>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('enviarCotizacion') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">COTIZACIONES</h5>
                    <div class="card-body demo-vertical-spacing demo-only-element">

                        <!-- Campo para destinatario -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">Email destinatario</span>
                            <input type="email" name="recipient_email" class="form-control"
                                placeholder="Correo del destinatario" required />
                        </div>

                        <!-- Campo para asunto -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">N Cotizacion</span>
                            <input type="text" name="subject" class="form-control" placeholder="Número de cotización"
                                required />
                        </div>

                        <!-- Campo para mensaje -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">Mensaje</span>
                            <textarea name="message" class="form-control" placeholder="Escribe tu mensaje aquí"
                                required></textarea>
                        </div>

                        <!-- Campo para archivo -->
                        <div class="input-group mb-3">
                            <input type="file" name="attachment" class="form-control" />
                        </div>

                        <!-- Campo oculto para cliente_id -->
                        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">

                        <!-- Campo para dinero -->
                        <div class="input-group mb-3">
                            <span class="input-group-text">Monto</span>
                            <input type="number" name="money" class="form-control" placeholder="Monto en dinero"
                                step="0.01" />
                        </div>

                        <!-- Botón de envío -->
                        <button type="submit" class="btn btn-primary mt-3">GUARDAR</button>
                    </div>
                </div>
            </div>
        </form>


    </div>
</div>
@endsection