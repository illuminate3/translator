@extends('backend._template')

@section('title')Dashboard @stop

@section('page-css')

@stop

@section('page-title') <h3><i class="fa fa-dashboard"></i> Dashboard</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>Dashboard</h5>
            </header>
        </div><!-- /.box -->
    </div>
    <div class="col col-xs-12">
        <div class="row">
            <div class="text-center">
                <h2><i class="fa fa-tasks"></i> Pages</h2>
                <a class="quick-btn" href="{{ URL::to('dashboard/pages') }}">
                    <i class="fa fa-tasks fa-2x"></i>
                    <span>All Pages</span> 
                    <span class="label label-danger">{{ $numbers[0] }}</span> 
                </a> 
                <a class="quick-btn" href="{{ URL::to('dashboard/pages/published') }}">
                    <i class="fa fa-bolt fa-2x"></i>
                    <span>Published</span> 
                    <span class="label label-success">{{ $numbers[1] }}</span> 
                </a> 
                <a class="quick-btn" href="{{ URL::to('dashboard/pages/drafts') }}">
                    <i class="fa fa-exclamation-triangle fa-2x"></i>
                    <span>Drafts</span> 
                    <span class="label label-warning">{{ $numbers[2] }}</span> 
                </a> 
                <a class="quick-btn" href="{{ URL::to('dashboard/pages/trash') }}">
                    <i class="fa fa-trash-o fa-2x"></i>
                    <span>Deleted</span> 
                    <span class="label label-default">{{ $numbers[3] }}</span> 
                </a>                 
            </div>
            <hr>
        </div>
        <div class="row">
            <div class="text-center">
                <h2><i class="fa fa-quote-left"></i> Posts</h2>
                <a class="quick-btn" href="{{ URL::to('dashboard/blog') }}">
                    <i class="fa fa-quote-left fa-2x"></i>
                    <span>All Posts</span> 
                    <span class="label label-danger">{{ $numbers[4] }}</span> 
                </a> 
                <a class="quick-btn" href="{{ URL::to('dashboard/blog/posts/published') }}">
                    <i class="fa fa-bolt fa-2x"></i>
                    <span>Published</span> 
                    <span class="label label-success">{{ $numbers[5] }}</span> 
                </a> 
                <a class="quick-btn" href="{{ URL::to('dashboard/blog/posts/drafts') }}">
                    <i class="fa fa-exclamation-triangle fa-2x"></i>
                    <span>Drafts</span> 
                    <span class="label label-warning">{{ $numbers[6] }}</span> 
                </a> 
                <a class="quick-btn" href="{{ URL::to('dashboard/blog/posts/trash') }}">
                    <i class="fa fa-trash-o fa-2x"></i>
                    <span>Deleted</span> 
                    <span class="label label-default">{{ $numbers[7] }}</span> 
                </a>                 
            </div>
            <hr>
        </div>  
        <div class="row">
            <div class="text-center">
                <h2><i class="fa fa-group"></i> Users</h2>
                @foreach ($numbers[8] as $aUser) 
                    <div class="row">
                        <a class="quick-btn" href="#">
                            <i class="fa fa-user fa-2x"></i>
                            <span>{{ $aUser->first_name . ' ' . Str::upper( Str::limit($aUser->last_name, 1 ,'') ) }}</span> 
                        </a>
<!--                         <a class="quick-btn" href="#">
                            <i class="fa fa-sign-in fa-2x"></i>
                            <span>Last Login</span> 
                            <span class="label label-danger">{{ $aUser->last_login }}</span> 
                        </a>   -->                       
                        <a class="quick-btn" href="#">
                            <i class="fa fa-tasks fa-2x"></i>
                            <span>Pages</span> 
                            <span class="label label-danger">{{ count($aUser->pages) }}</span> 
                        </a> 
                        <a class="quick-btn" href="#">
                            <i class="fa fa-quote-left fa-2x"></i>
                            <span>Posts</span> 
                            <span class="label label-danger">{{ count($aUser->posts) }}</span> 
                        </a> 
                    </div> 
                @endforeach                
            </div>
            <hr>
        </div>               
    </div> 
@stop

@section('page-js')

@stop