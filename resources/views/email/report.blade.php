@component('mail::message')

{{ $title }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
