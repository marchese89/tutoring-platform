@props([
    'title',
    'requests',
    'emptyText',
])

<x-ui.table-card :title="$title">
    <table class="table align-middle mb-0" data-lesson-request-table>
        <thead class="table-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Titolo</th>
                <th scope="col">Data</th>
                <th scope="col" class="text-center">Stato</th>
                <th scope="col">Operazioni</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($requests as $item)
                <tr>
                    <th scope="row">{{ $item['id'] }}</th>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ $item['date'] }}</td>
                    <td class="text-center">
                        <x-ui.status-dot :variant="$item['status_variant']" :label="$item['status_label']" />
                    </td>
                    <td>
                        <x-ui.primary-button href="{{ $item['show_url'] }}" size="sm">
                            Visualizza
                        </x-ui.primary-button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        {{ $emptyText }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-ui.table-card>
