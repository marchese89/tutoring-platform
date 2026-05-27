@foreach ($ordini as $ordine)
    <tr>
        <td class="fw-semibold">{{ $ordine['id'] }}</td>

        <td>{{ $ordine['studente'] }}</td>

        <td>{{ $ordine['data'] }}</td>

        <td class="fw-semibold text-success">
            {{ $ordine['totale'] }}€
        </td>

        <td>
            <a href="{{ url('/admin-ordine-' . $ordine['id']) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                Visualizza
            </a>
        </td>
    </tr>
@endforeach
