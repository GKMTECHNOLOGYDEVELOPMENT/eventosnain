<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f8ff; padding: 20px; border-radius: 12px; color: #2c3e50;">
  <h2 style="color: #34495e;">¡Hola {{ $usuario->name }}! 👋</h2>

  <p style="font-size: 18px;">🔄 El evento <strong style="color: #2980b9;">{{ $evento->titulo }}</strong> ha sido <strong>actualizado</strong>.</p>

  <p><strong>📝 Nueva descripción:</strong> {{ $evento->descripcion }}</p>
  <p><strong>📅 Nueva fecha de inicio:</strong> {{ $evento->fechainicio }}</p>
  <p><strong>📍 Nueva ubicación:</strong> {{ $evento->ubicacion }}</p>

  <p style="margin-top: 20px;">🔔 Te recomendamos revisar los cambios directamente en tu calendario para estar al tanto de todo.</p>

  <p style="margin-top: 30px;">Gracias por estar siempre al día 🙌</p>
</div>
