{{-- components/testimonial-card.blade.php --}}
@props(['name', 'text', 'rating' => null])

<article class="home-review h-100">
    @if ($rating)
        <div class="home-review__rating" aria-label="Valutazione: {{ $rating }} su 5">
            @for ($star = 1; $star <= 5; $star++)
                <i class="bi {{ $star <= round($rating) ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>
            @endfor
        </div>
    @endif

    <blockquote>&ldquo;{{ $text }}&rdquo;</blockquote>
    <p>{{ $name }}</p>
</article>
