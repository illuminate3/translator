@extends('backend._template')

@section('title')New Category @stop

@section('page-css')
    <style>
        h5 a, h5 a:visited {
            color: #FFFFFF;
        }
    </style>
@stop

@section('page-title') <h3><i class="fa fa-tasks"></i> Blog</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>New Category</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat disabled" href="{{URL::to('dashboard/blog/categories/create')}}"><span class="fa fa-pencil"></span> New Category</a>
                </div>                
            </header>
        </div><!-- /.box -->
    </div>
    <div class="col-md-12">
        {{Form::open(['url' => 'dashboard/blog/categories/create', 'class' => 'form-horizontal'])}}  
            <div class="col-md-9">                 
                <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">
                    {{ Form::label('name', $errors->first('name'), ['class' => 'control-label']) }}
                    {{ Form::text('name', Input::old('name'), ['id' => 'name', 'class' => 'form-control input-lg'])}}
                </div> 
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-metis-5 btn-grad btn-rect btn-lg">Save</button>
                </div>
            </div>
        {{Form::close()}}        
    </div>
@stop

@section('page-js')

@stop