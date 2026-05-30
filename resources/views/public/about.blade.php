@extends('layouts.layout-bootstrap')

@section('content')

<div class="container"  style="text-align: center;width:60%;min-height:900px">
    @php
    $certificates = DB::table('certificates')->select('*')->get();
    @endphp
    @foreach ($certificates as $item)
        <div class="container" style="text-align: center;">

            <h4>{{$item->nome}}</h4>
            <br>
            <iframe
				width="90%"
                @if ($item->percorso_file != null)
                src="{{$item->percorso_file}}#view=FitH"
                @endif
                style="min-height:1000px">
            </iframe>
            <br>
            <br>

        </div>
    @endforeach
</div>
@endsection
