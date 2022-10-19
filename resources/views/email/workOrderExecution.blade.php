@component('mail::message')
{{ $title }}

{{ $message }}

@component('mail::table')
| Work Order | Value |
| ------------- |:-------------:|
| Partner | {{ $partner }} |
| Work Order Number | {{ $woNumber }} |
| Work Order Product | {{ $product }} |
| Work Order Activity | {{ $activity }} |
@if($activity == 'Create new wallet')
| Developer App Name | {{ $developerAppName }} |
| Wallet Name | {{ $walletName }} |
| Wallet Balance | {{ $walletBalance }} |
| Expiry Date | {{ $expiryDate }} |
| Consumer Key | {{ $consumerKey }} |
| Consumer Secret | {{ $consumerSecret }} |
| Partner API Dashboard Password | {{ $dashboardPassword }} |
@endif
@endcomponent

@component('mail::button', ['url' => $url])
Open
@endcomponent

Thanks, <br>
{{ config('app.name') }}
@endcomponent
