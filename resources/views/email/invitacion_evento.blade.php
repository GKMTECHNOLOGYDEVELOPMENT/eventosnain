<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9f9f9; padding: 20px; border-radius: 10px; color: #333;">
  <h2 style="color: #2c3e50;">¡Hola {{ $usuario->name }}! 👋</h2>

  <p style="font-size: 18px;">🚀 ¡Buenas noticias! Has sido invitado a un evento que no te puedes perder:</p>

  <h3 style="color: #2980b9;">🎉 {{ $evento->titulo }}</h3>

  <p><strong>📝 Descripción:</strong> {{ $evento->descripcion }}</p>
  <p><strong>📅 Fecha de inicio:</strong> {{ $evento->fechainicio }}</p>
  <p><strong>📍 Ubicación:</strong> {{ $evento->ubicacion }}</p>

  <p style="margin-top: 20px;">🔔 Revisa los detalles en tu calendario para no perdértelo. ¡Va a estar épico!</p>

  <p style="margin-top: 30px;">🙌 ¡Nos vemos pronto!</p>
</div>
