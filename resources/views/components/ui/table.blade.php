@props(['striped' => true])

<div class="table-container">
    <table {{ $attributes->merge(['class' => 'table']) }} role="table">
        {{ $slot }}
    </table>
</div>
