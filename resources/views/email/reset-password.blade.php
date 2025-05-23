@component('mail::message')
# Reset Your Password

To reset your password, click the button below:

@component('mail::button', ['url' => route('password.reset', ['token' => $token, 'email' => $email])])
Reset Password
@endcomponent

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent