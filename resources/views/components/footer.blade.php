<footer class="border-top bg-white py-3 mt-auto">
    <div class="container d-flex justify-content-between small text-muted">

        <div>
            © {{ date('Y') }} Lezioni Informatica
        </div>

        <div class="d-flex gap-3">
            <a href="{{ route('privacy-policy') }}" class="text-muted text-decoration-none">Privacy</a>
            <a href="{{ route('cookie-policy') }}" class="text-muted text-decoration-none">Cookie</a>
        </div>

    </div>
</footer>
