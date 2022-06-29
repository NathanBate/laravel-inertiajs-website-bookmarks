@component('mail::message')
# Please activate your new email address

Please activate the new email address you just entered on your profile. If you did not add a new email address, please
contact your website administrator.

@component('mail::button', ['url' => $url])
Verify Email Address
@endcomponent

If the button link does not work, you can can copy and paste this link instead:<br>
{{ $url }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
