@extends('layouts.adminbsb')

@section('content')

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if (session('status'))
    <div class="alert alert-info">
        {{ session('status') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<magang-component  :data-konstruktor="{{$konstruktor}}" :data-biodata="{{$biodata ? $biodata: '{}'}}" :data-magang="{{$magang ? $magang: '{}'}}"></magang-component>
@endsection


