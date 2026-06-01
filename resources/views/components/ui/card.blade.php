@props([
    'class' => '',
    'bodyClass' => 'p-4',
])

<div {{ $attributes->merge([
    'class' => 'card border-0 shadow-sm rounded-4 h-100 ' . $class,
]) }}>

    <div class="card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>

</div>
