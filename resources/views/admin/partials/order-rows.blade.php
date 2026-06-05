@foreach ($orders as $order)
    <tr>
        <td class="fw-semibold">{{ $order['id'] }}</td>

        <td>{{ $order['student'] }}</td>

        <td>{{ $order['date'] }}</td>

        <td class="fw-semibold text-success">
            {{ $order['total'] }}€
        </td>

        <td>
            <a href="{{ route('admin.orders.show', $order['id']) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                Visualizza
            </a>
        </td>
    </tr>
@endforeach
