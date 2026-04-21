@props(['right' => false])

<td {{ $attributes->merge(['class' => $right ? 'td-right' : 'td']) }}>
    {{ $slot }}
</td>
