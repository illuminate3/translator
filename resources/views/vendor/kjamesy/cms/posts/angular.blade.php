@extends('cms::_template')

@section('title')Posts @stop

@section('page-css')
    <style>
        table {
            font-size: 13px;
        }
        .more-options {
            margin-top: 5px;
        }
        .visibility {
            visibility: hidden;
        }
        a.red {
            color: #D54E21;
        }
        a:hover {
            color: #D54E21;
            text-decoration: none;
        }
        .page-options {
            margin: 10px 0;
        }
        .opacity {
            opacity: 0.3;
        }

    </style>
    {!! HTML::style('packages/kjamesy/cms/angular-modules/jquery-ui-1.11.2.custom/jquery-ui.min.css') !!}
    {!! HTML::style('packages/kjamesy/cms/angular-modules/jquery-ui-1.11.2.custom/jquery-ui.theme.min.css') !!}
    {!! HTML::style('packages/kjamesy/cms/angular-css/animate.min.css') !!}
    {!! HTML::script('packages/kjamesy/cms/js/jQuery-1.10.2.min.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-1.2.9/angular.min.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-modules/jquery-ui-1.11.2.custom/jquery-ui.min.js') !!}
@stop

@section('page-title') <h3><i class="fa fa-quote-left"></i> Posts</h3> @stop

@section('page')

    <div data-ng-app="postsApp" data-ng-controller="PostsController"
         data-ng-init="laravel_token = '<?= csrf_token(); ?>'; ckEditorLight = '<?= asset("packages/kjamesy/cms/ckeditor4.4.3/ckeditor-light-config.js"); ?>'">
        <div data-ui-view="">

        </div>
    </div>
@stop

@section('page-js')
    {!! HTML::script('packages/kjamesy/cms/ckfinder2.4/ckfinder.js') !!}
    {!! HTML::script('packages/kjamesy/cms/ckeditor4.4.3/ckeditor.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-modules/angular-ui-router-0.2.13.min.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-1.2.9/angular-resource.min.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-1.2.9/angular-animate.min.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-modules/ngStorage.min.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-modules/angular-locale_en-gb.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-modules/dirPagination.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-modules/angular-ckeditor.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-modules/checklist-model.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-scripts/posts.app.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-scripts/posts.controllers.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-scripts/posts.services.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-scripts/filters.js') !!}
    {!! HTML::script('packages/kjamesy/cms/angular-scripts/directives.js') !!}


@stop

