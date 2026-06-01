@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Recensione'" />
@endsection

@section('inner')
    <style>
        .review-stars {
            display: inline-flex;
            gap: .35rem;
        }

        .review-star {
            border: 0;
            background: transparent;
            color: #adb5bd;
            font-size: 2rem;
            line-height: 1;
            padding: .25rem;
            transition: color .15s ease, transform .15s ease;
        }

        .review-star:hover,
        .review-star:focus,
        .review-star.is-active {
            color: #ffc107;
            transform: translateY(-1px);
        }
    </style>

    <script>
        let currentRating = @json($rating);

        function renderStars(rating) {
            document.querySelectorAll('[data-review-star]').forEach((star) => {
                const value = Number(star.dataset.reviewStar);
                star.classList.toggle('is-active', value <= rating);
            });
        }

        async function invia_feefback(punteggio) {
            currentRating = punteggio;
            renderStars(currentRating);

            await fetch("{{ route('student.feedback.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                body: `punteggio=${encodeURIComponent(punteggio)}`,
            });
        }

        function countChar(field) {
            document.getElementById('current').innerHTML = field.value.length;
        }

        async function storeReview(testo) {
            const response = await fetch("{{ route('student.review.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                body: `testo=${encodeURIComponent(testo)}`,
            });

            document.getElementById('recensione').value = await response.text();
            countChar(document.getElementById('recensione'));
            document.getElementById('review-status').classList.remove('d-none');
        }

        window.addEventListener('DOMContentLoaded', () => {
            const reviewField = document.getElementById('recensione');

            renderStars(currentRating);
            countChar(reviewField);
        });
    </script>

    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <x-ui.card>
                    <div class="mb-4 text-center">
                        <h4 class="fw-bold mb-3">
                            Valutazione
                        </h4>

                        <div class="review-stars" id="stars" aria-label="Valutazione">
                            @for ($value = 1; $value <= 5; $value++)
                                <button
                                    type="button"
                                    class="review-star"
                                    data-review-star="{{ $value }}"
                                    onclick="invia_feefback({{ $value }})"
                                    aria-label="{{ $value }} stelle"
                                >
                                    <i class="bi bi-star-fill"></i>
                                </button>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="recensione">
                            Recensione
                        </label>

                        <textarea
                            id="recensione"
                            name="recensione"
                            rows="6"
                            maxlength="500"
                            class="form-control rounded-4"
                            onkeyup="countChar(this)"
                        >{{ $review }}</textarea>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div class="text-muted small">
                            <span id="current">0</span><span id="maximum">/500</span>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <span id="review-status" class="text-success small d-none">
                                Recensione salvata.
                            </span>

                            <x-ui.primary-button id="storeReview" onclick="storeReview(document.getElementById('recensione').value)">
                                Invia
                            </x-ui.primary-button>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
