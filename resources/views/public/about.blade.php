@extends('layouts.layout-bootstrap')

@section('content')

<div class="container"  style="text-align: center;width:60%;min-height:900px">
    @foreach ($certificates as $item)
        <div class="container" style="text-align: center;">

            <h4>{{$item->name}}</h4>
            <br>
            <iframe
				width="90%"
                @if ($item->file_path != null)
                src="{{$item->file_path}}#view=FitH"
                @endif
                style="min-height:1000px">
            </iframe>
            <br>
            <br>

        </div>
    @endforeach
</div>
@endsection
