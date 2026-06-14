{{-- components/price-card.blade.php --}}
@props(['title', 'price', 'unit', 'description'])

<article class="home-price h-100">
    <div>
        <p class="home-price__label">{{ $title }}</p>
        <p class="home-price__description">{{ $description }}</p>
    </div>
    <div class="home-price__amount">
        <strong>{{ $price }}</strong>
        <span>{{ $unit }}</span>
    </div>
</article>
