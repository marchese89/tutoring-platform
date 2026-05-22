{{-- <div class="card border-0 shadow-sm h-100 rounded-4 transition-card">
    <div class="card-body p-4 d-flex flex-column">
        {{ $slot }}
    </div>
</div> --}}
{{-- resources/views/components/ui/card.blade.php --}}

@props([
    'class' => '',
])

<div {{ $attributes->merge([
    'class' => 'card border-0 shadow-sm rounded-4 h-100 ' . $class,
]) }}>

    <div class="card-body p-4 d-flex flex-column">
        {{ $slot }}
    </div>

</div>
