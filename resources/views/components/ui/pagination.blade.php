@props(['paginator'])

@if (method_exists($paginator, 'links') && $paginator->hasPages())
    <div class="mt-4">
        {{ $paginator->links() }}
    </div>
@endif
