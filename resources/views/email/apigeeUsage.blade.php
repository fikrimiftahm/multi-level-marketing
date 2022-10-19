@component('mail::message')
{{ $title }}

{{ $message }}

<table width="100%" border="1" cellpadding="1" cellspacing="1" bgcolor="#f4f6f6">
<tr>
<th>Month</th>
@foreach($apigeeTotalTraffic as $key => $item)
<th><center>{{ $key }}</center></th>
@endforeach
</tr>

<tr>
<td>Usage (Hit)</td>
@foreach($apigeeTotalTraffic as $key => $item)
<td><center>{{ $item }}</center></td>
@endforeach
</tr>

<tr>
<td>Total</td>
<td colspan="{{ $months + 1 }}"><center>{{ $totalTraffic }}</center></td>
</tr>
</table>

<br>

Thanks, <br>
{{ config('app.name') }}
@endcomponent
