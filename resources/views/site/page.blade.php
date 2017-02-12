@extends('site.basic')

@section('content')

    @include('site.pages.top')

    @yield('page.content')

@endsection

@section('scripts')

    @yield('page.scripts')

@endsection