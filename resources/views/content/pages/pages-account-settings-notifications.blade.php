@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Detalles /</span> Notificaciones
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
      <li class="nav-item"><a class="nav-link" href="{{ route('client.status', $cliente->id) }}"><i class="bx bx-user me-1"></i> Cliente</a></li>
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-bell me-1"></i>
          Notificaciones</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-connections')}}"><i class="bx bx-link-alt me-1"></i> Connections</a></li>
    </ul>
    <div class="card">
      <!-- Notifications -->
      <h5 class="card-header">Actividad</h5>
      <div class="card-body">
        <span>En esta parte se mustran los detalles del seguimiento del cliente <span class="notificationRequest"><span class="fw-medium"></span></span></span>
        <div class="error"></div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-borderless border-bottom">
          <thead>
            <tr>
              <th class="text-nowrap">Seguimiento</th>
              <th class="text-nowrap text-center">✉️ Correo</th>
              <th class="text-nowrap text-center">🖥 Whatsapp</th>
              <th class="text-nowrap text-center">👩🏻‍💻 Llamada</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-nowrap">Envio de Datos</td>
              <td>
                <div class="form-check d-flex justify-content-center">
                  <input class="form-check-input" type="checkbox" id="defaultCheck1" checked />
                </div>
              </td>
              <td>
                <div class="form-check d-flex justify-content-center">
                  <input class="form-check-input" type="checkbox" id="defaultCheck2" checked />
                </div>
              </td>
              <td>
                <div class="form-check d-flex justify-content-center">
                  <input class="form-check-input" type="checkbox" id="defaultCheck3" checked />
                </div>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-borderless border-bottom">
          <thead>
            <tr>
              <th class="text-nowrap">SEGUIMIENTO</th>
              <th class="text-nowrap text-center">🖥 Llamada</th>
              <th class="text-nowrap text-center">🖥 Fecha</th>
              <th class="text-nowrap text-center">👩🏻‍💻 Observacion</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-nowrap">Llamada a cliente</td>
              <td>
                <div class="form-check d-flex justify-content-center">

                  <input class="form-check-input" type="checkbox" id="defaultCheck1" checked />
                </div>
              </td>
              <td>

                <div class="form-text d-flex justify-content-center">

                  <input class="form-date-input" type="date" id="" />
                </div>
              </td>
              <td>
                <div class="form-check d-flex justify-content-center">
                  <input class="form-text-input" type="text" id="defaultCheck3" />
                </div>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-borderless border-bottom">
          <thead>
            <tr>
              <th class="text-nowrap">Observaciones</th>
              <th class="text-nowrap text-center">🖥 Reunion</th>
              <th class="text-nowrap text-center">🖥 Contrato</th>
              <th class="text-nowrap text-center">👩🏻‍💻 Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-nowrap">Reunion o Contrato</td>
              <td>
                <div class="form-check d-flex justify-content-center">

                  <input class="form-check-input" type="checkbox" id="defaultCheck1" checked />
                </div>
              </td>
              <td>

                <div class="form-check d-flex justify-content-center">

                  <input class="form-date-input" type="date" id="" />
                </div>
              </td>
              <td>
                <div class="form-check d-flex justify-content-center">
                  <input class="form-text-input" type="text" id="defaultCheck3" />
                </div>
              </td>
            </tr>

          </tbody>
        </table>
      </div>
      <div class="card-body">
        <h6>When should we send you notifications?</h6>
        <form action="javascript:void(0);">
          <div class="row">
            <div class="col-sm-6">
              <select id="sendNotification" class="form-select" name="sendNotification">
                <option selected>Only when I'm online</option>
                <option>Anytime</option>
              </select>
            </div>
            <div class="mt-4">
              <button type="submit" class="btn btn-primary me-2">Save changes</button>
              <button type="reset" class="btn btn-outline-secondary">Discard</button>
            </div>
          </div>
        </form>
      </div>
      <!-- /Notifications -->
    </div>
  </div>
</div>
@endsection