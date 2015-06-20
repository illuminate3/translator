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
        .versions-margin {
            margin: 10px 0;
        }
        .opacity {
            opacity: 0.3 !important;
        }
    </style>
@stop

@section('scripts')
@stop

@section('inline-scripts')
jQuery(document).ready(function($) {
	var latestVersion = $('.selectedVersion[checked]').val();
	$('.selectedVersion[checked]').prop('checked', true);

	$('.selectedVersion').change( function() {
		$this = $(this);

		if ( $this.val() != latestVersion ) {
			$('#select-version').text('Revert to Version ' + $this.attr('rel') ).removeClass('disabled').removeClass('opacity');
		}

		else {
			$('#select-version').text('Select Version').addClass('disabled').addClass('opacity');
		}

	});

	// $('.preview-link').click(function(event) {
	//     event.preventDefault();
	//     window.open($(this).attr('href'), '_blank');
	// });
});
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
	{{ Lang::choice('kotoba::cms.version', 2) }}
	<hr>
</h1>
</div>



    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>Archived Page Versions</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat" href="{{URL::to('admin/pages/create')}}"><span class="fa fa-pencil"></span> New Page</a>
                </div>
            </header>
        </div><!-- /.box -->
    </div>

    <div class="col-md-12 versions-margin">
        @if(Session::has('success'))
            <div class="alert alert-dismissable alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('success') }}
            </div>
        @endif

        {!! Form::open(['url' => 'admin/pages/'.$page->id.'/select-version', 'class' => 'form-horizontal']) !!}
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Version</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Select Version</th>
                    </tr>
                </thead>
                <tbody>
                   {!! $versionsHtml !!}
                </tbody>
            </table>
        </div>

        <div class="form-group" style="margin-left: 5px;">
            <button type="submit" class="btn btn-metis-5 btn-grad btn-rect btn-lg disabled opacity" id="select-version" >Select Version</button>
        </div>
        {!! Form::close() !!}
    </div>

@stop
