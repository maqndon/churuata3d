@extends('layouts.app')

@section('content')

    {{ $product->slug }}
    @include('components.most-downloaded')

@endsection