<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="email" name="email" value="{{ old('email', $email) }}" required>
    <!-- Campos adicionales para la nueva contraseña y confirmación -->
    <button type="submit">Restablecer contraseña</button>
</form>