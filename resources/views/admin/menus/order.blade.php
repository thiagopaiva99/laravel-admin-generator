@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="box box-success">
            <div class="box-header with-border text-center">
                            <span class="pull-left">
                                @include("admin.util._back")
                            </span>

                <h1 class="box-title">Ordenar menus</h1>
            </div>

            <div class="box-body">
                <div class="col-md-12">
                    @include('flash::message-admin')
                </div>

                {!! Form::open(['url' => url('admin/menus/order')]) !!}
                    {{-- Start menus display --}}
                    <ul class="list-group" id="sortable">
                        @foreach( $menus as $menu )
                            <li class="list-group-item"><i class="{{ $menu->icon }}"></i> - {{ $menu->menu }}</li>
                        @endforeach
                    </ul>
                    {{-- Finish menus display --}}

                    {!! Form::submit('Alterar ordem', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    {{--temporario--}}
    <script>
        $("#sortable")
            .sortable()
            .disableSelection();
    </script>
@endsection