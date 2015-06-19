@extends('frontends._template')

@section('page-title')
    Home
@stop

@section('page-css')

@stop

@section('page-content')
    <div class="col-sm-12">
        <div class="editor-content"> 
            {{ $page->content }}
        </div>
    </div>
@stop

@section('page-js')

@stop