@extends('app')

{{-- Web site Title --}}
@section('title')
{{ $type }} {{ Lang::choice('kotoba::cms.pages', 2) }} :: @parent
@stop

@section('styles')
	<link href="{{ asset('assets/vendors/DataTables-1.10.5/plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}" rel="stylesheet">
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
@stop

@section('scripts')
	<script src="{{ asset('assets/vendors/DataTables-1.10.5/media/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/vendors/DataTables-1.10.5/plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
@stop

@section('inline-scripts')
$(document).ready(function() {
oTable =
	$('#table').DataTable({
	});
});
@stop


{{-- Content --}}
@section('content')

<div class="row">
<h1>
	<p class="pull-right">
	<a href="/admin/pages/create" class="btn btn-primary" title="{{ trans('kotoba::button.new') }}">
		<i class="fa fa-plus fa-fw"></i>
		{{ trans('kotoba::button.new') }}
	</a>
	</p>
	<i class="fa fa-tasks fa-lg"></i>
		{{ Lang::choice('kotoba::cms.page', 2) }}
	<hr>
</h1>
</div>

{{-- section('page') --}}
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>{{ $type }} Pages</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat" href="{{URL::to('admin/pages/create')}}"><span class="fa fa-pencil"></span> New Page</a>
                </div>
            </header>
        </div><!-- /.box -->
    </div>

    <div class="col-md-12">
        <div class="btn-group page-options">
            <a class="btn btn-metis-5 btn-rect {{ $type == 'All' ? 'disabled' : 'btn-line' }}" role="button" href="{{ URL::to('admin/pages') }}">All ({{ $nums['allNotDeletedNum'] }})</a>
            <a class="btn btn-metis-5 btn-rect {{ $type == 'Published' ? 'disabled' : 'btn-line' }}" role="button" href="{{ URL::to('admin/pages/published') }}">Published ({{ $nums['publishedNum'] }})</a>
            <a class="btn btn-metis-5 btn-rect {{ $type == 'Drafts' ? 'disabled' : 'btn-line' }}" role="button" href="{{ URL::to('admin/pages/drafts') }}">Drafts ({{ $nums['draftsNum'] }})</a>
            <a class="btn btn-metis-5 btn-rect btn-line" href="{{ URL::to('admin/pages/trash') }}">Trash ({{ $nums['deletedNum'] }})</a>
        </div>
    </div>

    <div class="col-md-12 optionsDiv opacity">
        {!! Form::open(['url' => '#', 'id' => 'bulk-options-form']) !!}
        <div class="row">
            <div class="col-sm-3 col-md-2">
                <div class="form-group">
                    <select name="bulk-options" id="bulk-options" class="form-control" disabled="disabled">
                        <option value=''>Select Option</option>
                        @if ( $type == 'All' )
                            <option value='1'>Publish</option>
                            <option value='2'>Draft</option>
                            <option value='0'>Trash</option>
                        @elseif ( $type == 'Published' )
                            <option value='2'>Draft</option>
                            <option value='0'>Trash</option>
                        @elseif ( $type == 'Drafts' )
                            <option value='1'>Publish</option>
                            <option value='0'>Trash</option>
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" id="bulkPublishUrl" value = "{{ URL::to('admin/pages/bulk-publish') }}" />
                    <input type="hidden" id="bulkDeleteUrl" value = "{{ URL::to('admin/pages/bulk-delete') }}" />
                    <input type="hidden" id="bulkDraftUrl" value = "{{ URL::to('admin/pages/bulk-draft') }}" />
                    <div class="appendTarget"></div>
                </div>
            </div>
            <div class="col-sm-3 col-md-2">
                <div class="form-group">
                    <button type="submit" class="btn btn-default btn-rect" id="bulk-submit" disabled="disabled">Submit</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div class="col-md-12">
        @if(Session::has('success'))
            <div class="alert alert-dismissable alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('success') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><input type='checkbox' id="checkAll" name='allposts'></th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Version</th>
                        <th>Created</th>
                        <th>Updated</th>
                    </tr>
                </thead>
                <tbody>
                   {!! $pagesHtml !!}
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-12">
        {{-- $links --}}
    </div>
@stop

@section('page-js')
    <script>
        function unHideOptions() {
            $('.optionsDiv').removeClass('opacity');
            $('#bulk-options').removeAttr('disabled');
            var html = '';
            $(':checkbox.acheckbox:checked').each(function() {
                html += "<input type='checkbox' name='pages[]' value='" + $(this).val() + "' class='hidden' checked='checked'>";
            });
            $('#bulk-options-form .form-group .appendTarget').html(html);
        }

        function handleOption(option) {
            switch(option) {
                case "" :
                    $('#bulk-options-form').attr('action', '#');
                    $('#bulk-submit').attr('disabled', 'disabled').removeClass().addClass('btn btn-default btn-rect').text('Submit');
                    break;
                case "0" :
                    $('#bulk-options-form').attr( 'action', $('#bulkDeleteUrl').val() );
                    $('#bulk-submit').removeAttr('disabled').removeClass().addClass('btn btn-default btn-rect btn-metis-1').text('Trash ' + $(':checkbox.acheckbox:checked').size());
                    break;
                case "1" :
                    $('#bulk-options-form').attr( 'action', $('#bulkPublishUrl').val() );
                    $('#bulk-submit').removeAttr('disabled').removeClass().addClass('btn btn-default btn-rect btn-metis-2').text('Publish ' + $(':checkbox.acheckbox:checked').size());
                    break;
                case "2" :
                    $('#bulk-options-form').attr( 'action', $('#bulkDraftUrl').val() );
                    $('#bulk-submit').removeAttr('disabled').removeClass().addClass('btn btn-default btn-rect btn-metis-5').text('Draft ' + $(':checkbox.acheckbox:checked').size());
                    break;
            }
        }
    </script>
@stop
