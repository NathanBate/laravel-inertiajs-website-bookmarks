@component('mail::message')
# Your user profile has been enabled!

Thank you for your patience while your profile was enabled. You may now login.

@component('mail::button', ['url' => $loginUrl])
Login
@endcomponent

Thanks,<br>
Your friends at {{ config('app.name') }}
@endcomponent
