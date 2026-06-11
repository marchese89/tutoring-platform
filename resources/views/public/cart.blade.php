@extends('layouts.layout-bootstrap')

@section('content')
    <div class="container" style="text-align: center;width:35%">
        <h2>Cart</h2>
    </div>
    <br>
    <div class="container" style="text-align: center;width:80%; height:800px">

        @if (count($items) == 0)
            <br>
            <h3>Cart empty!</h3>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Price</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($items as $item)
                        <tr>

                            <th scope="row">{{ $item['id'] }}</th>
                            <td>
                                {{ $item['name'] }}
                            </td>
                            <td>
                                {{ $item['price'] }} &nbsp;<strong>&euro;</strong>
                            </td>
                            <td>
                                <form id="form-remove-{{ $item['id'] }}-{{ $item['type'] }}" method="POST"
                                    style="display: inline"
                                    action="{{ $item['remove_url'] }}"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-primary">Remove</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="container">
                <h3>Total:
                    {{ $total }}
                    &nbsp;&euro;
                </h3>
            </div>
        @endif
        @if (count($items) != 0)
            <br>
            <br>
            <div>
                <button class="btn btn-primary" onclick="location.href='{{ route('checkout.show') }}'">Buy</button>
            </div>
            <br>
            <br>
        @endif
    </div>
@endsection
