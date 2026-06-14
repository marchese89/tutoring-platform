{{-- components/service-card.blade.php --}}
@props(['icon', 'title', 'description'])

<article class="home-service h-100">
    <span class="home-service__icon" aria-hidden="true">
        <i class="bi {{ $icon }}"></i>
    </span>
    <h3>{{ $title }}</h3>
    <p>{{ $description }}</p>
</article>
