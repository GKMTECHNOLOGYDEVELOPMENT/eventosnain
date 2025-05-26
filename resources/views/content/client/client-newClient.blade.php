@extends('layouts/contentNavbarLayout')

@section('title', 'New Cliente - Formulario')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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



<h4 class="py-3 mb-4"><span class="text-muted fw-light">Formulario/</span> Nuevo Cliente</h4>

<!-- Basic Layout & Basic with Icons -->
<div class="row">
    <div class="col-xxl">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Formulario</h5> <small class="text-muted float-end">Nuevo Cliente</small>
            </div>
            <div class="card-body">
                <!-- Cambiar el action a la ruta para el m��todo store -->
                <form id="client-form" action="{{ route('client.store') }}" method="POST">
                    @csrf
                    <!-- Nombre -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="nombre">Nombre</label>
                        <div class="col-sm-10">
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                id="nombre" placeholder="John Doe" value="{{ old('nombre') }}" />
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Empresa -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="empresa">Empresa</label>
                        <div class="col-sm-10">
                            <input type="text" name="empresa"
                                class="form-control @error('empresa') is-invalid @enderror" id="empresa"
                                placeholder="ACME Inc." value="{{ old('empresa') }}" />
                            @error('empresa')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Número de Documento -->
                    <div class="row mb-3">
                        <label for="documento" class="col-sm-2 col-form-label">N° Documento</label>
                        <div class="col-sm-10">
                            <input type="text" name="documento" id="documento"
                                class="form-control @error('documento') is-invalid @enderror"
                                placeholder="Ingrese DNI, RUC, etc." value="{{ old('documento') }}">
                            @error('documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tel��fono -->
                    <div class="row mb-3">
                        <label class="col-sm-2 form-label" for="telefono">Telefono No</label>
                        <div class="col-sm-10">
                            <input type="text" name="telefono"
                                class="form-control @error('telefono') is-invalid @enderror" id="telefono"
                                placeholder="658 799 8941" value="{{ old('telefono') }}" />
                            @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="email">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" placeholder="john.doe@example.com" value="{{ old('email') }}" />
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>



                    <!-- Servicios -->
                    <div class="row mb-3">
                        <label for="servicios" class="col-sm-2 col-form-label">Servicios</label>
                        <div class="col-sm-10">
                            <select name="servicios" id="servicios" required
                                class="form-select @error('servicios') is-invalid @enderror">
                                <option value="" disabled selected>Seleccione</option>
                                <option value="MODULO" {{ old('servicios') == 'MODULO' ? 'selected' : '' }}>MODULO
                                </option>
                            </select>
                            @error('servicios')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Mensaje -->
                    <div class="row mb-3" id="observacion-container" style="display: none;">
                        <label for="mensaje" class="col-sm-2 col-form-label">OBSERVACION</label>
                        <div class="col-sm-10">
                            <textarea name="mensaje" id="mensaje"
                                class="form-control @error('mensaje') is-invalid @enderror"
                                placeholder="Ingrese su observacion">{{ old('mensaje') }}</textarea>
                            @error('mensaje')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>



                    <!-- Script para mostrar/ocultar el campo de observaciones -->
                    <script>
                        document.getElementById('servicios').addEventListener('change', function() {
                            var observacionContainer = document.getElementById('observacion-container');
                            if (this.value === 'OTROS') {
                                observacionContainer.style.display = 'block';
                            } else {
                                observacionContainer.style.display = 'none';
                            }
                        });
                    </script>

                    <!-- Evento -->
                    <div class="row mb-3">
                        <label class="col-sm-2 form-label" for="events_id">Evento</label>
                        <div class="col-sm-10">
                            <select name="events_id" class="form-select @error('events_id') is-invalid @enderror"
                                required id="events_id">
                                <option value="">Seleccione un Evento</option>
                                @foreach ($events as $event)
                                <option value="{{ $event->id }}" {{ old('events_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                                @endforeach
                            </select>
                            @error('events_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <!-- Campos ocultos -->
                    <div class="row mb-3" style="display: none;">
                        <label class="col-sm-2 form-label" for="status">Status</label>
                        <div class="col-sm-10">
                            <input type="text" name="status" class="form-control" id="status" value="PENDIENTE" />
                        </div>
                    </div>

                    <div class="row mb-3" style="display: none;">
                        <label class="col-sm-2 form-label" for="correo">Correo</label>
                        <div class="col-sm-10">
                            <input type="text" name="correo" class="form-control" id="correo" value="NO" />
                        </div>
                    </div>

                    <div class="row mb-3" style="display: none;">
                        <label class="col-sm-2 form-label" for="whatsapp">Whatsapp</label>
                        <div class="col-sm-10">
                            <input type="text" name="whatsapp" class="form-control" id="whatsapp" value="NO" />
                        </div>
                    </div>

                    <div class="row mb-3" style="display: none;">
                        <label class="col-sm-2 form-label" for="reunion">Reuni��n</label>
                        <div class="col-sm-10">
                            <input type="text" name="reunion" class="form-control" id="reunion" value="NO" />
                        </div>
                    </div>

                    <div class="row mb-3" style="display: none;">
                        <label class="col-sm-2 form-label" for="contrato">Contrato</label>
                        <div class="col-sm-10">
                            <input type="text" name="contrato" class="form-control" id="contrato" value="NO" />
                        </div>
                    </div>

                    <!-- Bot��n de guardar -->
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>
@endsection