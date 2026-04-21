@props(['right' => false, 'scope' => 'col'])

<th scope="{{ $scope }}" {{ $attributes->merge(['class' => $right ? 'th-right' : 'th']) }}>
    {{ $slot }}
</th>
