@component('mail::message')
{{ $title }}

{{ $message }}

@component('mail::table')
| Work Order       | Value         | 
| ------------- |:-------------:| 
| Partner      | {{ $partner }}      | 
| Work Order Number      | {{ $woNumber }} | 
| Work Order Product      | {{ $product }} | 
| Work Order Activity      | {{ $activity }} | 
@endcomponent

@component('mail::button', ['url' => $url])
Open
@endcomponent

Thanks, <br>
{{ config('app.name') }}
@endcomponent
