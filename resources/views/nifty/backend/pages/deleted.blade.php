@extends('app')

{{-- Web site Title --}}
@section('title')
{{ Lang::choice('kotoba::cms.page', 2) }} :: @parent
@stop

@section('styles')
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
@stop

@section('inline-scripts')
@stop


{{-- Content --}}
@section('content')

<div class="row">
<h1>
	<p class="pull-right">
	<a href="/admin/pages" class="btn btn-default" title="{{ trans('kotoba::button.back') }}">
		<i class="fa fa-chevron-left fa-fw"></i>
		{{ trans('kotoba::button.back') }}
	</a>
	</p>
	<i class="fa fa-tasks fa-lg"></i>
	{{ trans('kotoba::cms.deleted_pages') }}
	<hr>
</h1>
</div>


<div class="row">
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
<li role="presentation" class="">
	<a href="{{ URL::to('admin/pages') }}">
		{{ trans('kotoba::general.all') }}
		&nbsp;
		<span class="badge">
			{{ $nums['allNotDeletedNum'] }}
		</span>
	</a>
</li>
<li role="presentation" class="">
	<a href="{{ URL::to('admin/pages/published') }}">
		{{ trans('kotoba::cms.published') }}
		&nbsp;
		<span class="badge">
			{{ $nums['publishedNum'] }}
		</span>
	</a>
</li>
<li role="presentation" class="">
	<a href="{{ URL::to('admin/pages/drafts') }}">
		{{ Lang::choice('kotoba::cms.draft', 2) }}
		&nbsp;
		<span class="badge">
			{{ $nums['draftsNum'] }}
		</span>
	</a>
</li>
<li role="presentation" class="active">
	<a href="{{ URL::to('admin/pages/trash') }}">
		{{ Lang::choice('kotoba::cms.draft', 2) }}
		&nbsp;
		<span class="badge">
			{{ $nums['deletedNum'] }}
		</span>
	</a>
</li>
</ul>
</div>

    <div class="col-md-12">
        <div class="btn-group page-options">
            <a class="btn btn-metis-5 btn-rect btn-line" role="button" href="{{ URL::to('admin/pages') }}">All ({{ $nums['allNotDeletedNum'] }})</a>
            <a class="btn btn-metis-5 btn-rect btn-line" role="button" href="{{ URL::to('admin/pages/published') }}">Published ({{ $nums['publishedNum'] }})</a>
            <a class="btn btn-metis-5 btn-rect btn-line" role="button" href="{{ URL::to('admin/pages/drafts') }}">Drafts ({{ $nums['draftsNum'] }})</a>
            <a class="btn btn-metis-5 btn-rect disabled" href="{{ URL::to('admin/pages/trash') }}">Trash ({{ $nums['deletedNum'] }})</a>
        </div>
    </div>

    <div class="col-md-12 optionsDiv opacity">
        {!! Form::open(['url' => '#', 'id' => 'bulk-options-form']) !!}
        <div class="row">
            <div class="col-sm-3 col-md-2">
                <div class="form-group">
                    <select name="bulk-options" id="bulk-options" class="form-control" disabled="disabled">
                        <option value=''>Select Option</option>
                        <option value='1'>Restore</option>
                        <option value='0'>Delete Permanently</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" id="bulkRestoreUrl" value = "{{ URL::to('admin/pages/bulk-restore') }}" />
                    <input type="hidden" id="bulkDestroyUrl" value = "{{ URL::to('admin/pages/bulk-destroy') }}" />
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
                    $('#bulk-options-form').attr( 'action', $('#bulkDestroyUrl').val() );
                    $('#bulk-submit').removeAttr('disabled').removeClass().addClass('btn btn-default btn-rect btn-metis-1').text('Permanently Delete ' + $(':checkbox.acheckbox:checked').size());
                    break;
                case "1" :
                    $('#bulk-options-form').attr( 'action', $('#bulkRestoreUrl').val() );
                    $('#bulk-submit').removeAttr('disabled').removeClass().addClass('btn btn-default btn-rect btn-metis-5').text('Restore ' + $(':checkbox.acheckbox:checked').size());
                    break;
            }
        }
    </script>
@stop
