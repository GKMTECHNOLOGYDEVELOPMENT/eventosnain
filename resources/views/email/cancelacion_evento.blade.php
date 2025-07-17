<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #fff8f8; padding: 20px; border-radius: 10px; color: #333;">
  <h2 style="color: #e61d06ff;">Hola {{ $usuario->name }},</h2>

  <p style="font-size: 18px;">😔 Te informamos que el evento al que estabas invitado ha sido cancelado:</p>

  <h3 style="color: #e61d06ff;">🗑️ {{ $evento->titulo }}</h3>

  <p><strong>📅 Se iba a realiza el:</strong> {{ $evento->fechainicio }}</p>
  <p><strong>📍 Ubicación:</strong> {{ $evento->ubicacion }}</p>

  <p style="margin-top: 20px;">Disculpa las molestias. Si tienes dudas, contacta al organizador.</p>

  <p style="margin-top: 30px;">Gracias por tu comprensión 🙏</p>
</div>
