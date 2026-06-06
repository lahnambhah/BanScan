@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<tr>
<td class="header">
<a href="{{ config('app.url') }}">
    <img src="{{ asset('public/images/banscanlogo.png') }}"
         alt="BanScan"
         style="height:80px;">
</a>
</td>
</tr>
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
