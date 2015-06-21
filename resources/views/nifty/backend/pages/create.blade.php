@extends('app')

{{-- Web site Title --}}
@section('title')
{{ Lang::choice('kotoba::cms.menu', 2) }} :: @parent
@stop

@section('styles')
    <style>
        h5 a, h5 a:visited {
            color: #FFFFFF;
        }
        img.img-thumbnail {
            cursor: pointer;
            margin-right: 10px;
        }
    </style>
@stop

@section('scripts')
	{{ HTML::script('assets/ckfinder2.4/ckfinder.js') }}
	{{ HTML::script('assets/ckeditor4.3/ckeditor.js') }}
@stop

@section('inline-scripts')
var editor = CKEDITOR.replace('content',
	{
		// width: 600,
		// height: 450
	});

CKFinder.setupCKEditor(editor, '{{asset("assets/ckfinder2.4")}}');

jQuery(document).ready(function($) {
	var thumbPath = $('.imageTarget').attr('rel');

	if ( $('#featured_image').val().length > 0 ) {
		$('.imageTarget').html( "<img src='" + thumbPath + '/' + $('#featured_image').val() + "' alt='featured image'>" );
	}

	$('#featuredImageModal').on('shown.bs.modal', function (e) {
		$this = $(this);
	   var url = $this.attr('rel');
		$.get(url, function(data) {
			if ( data.success ) {
				var images = data.success;
				var html = '';
				$.each(images, function(index, val) {
					var filename = val.substring(val.lastIndexOf("/") + 1, val.length);
					html += "<img class='img-thumbnail' src='" + val + "' rel='" + filename + "'>";
				});

				$this.find('.modal-body').html(html);

				$('.img-thumbnail').click(function(event) {
					event.preventDefault();
					$image = $(this);
					$('#featured_image').val($image.attr('rel'));
					$('.imageTarget').fadeIn('slow', function() {
						$(this).html("<img src='" + data.thumbnailPath + '/' + $image.attr('rel') + "' alt='featured image'>");
					});
					$this.modal('hide');
				});
			}

			if ( data.error ) {
				$this.find('.modal-body').html(data.error);
			}
		});

	});

	$('#clearFeaturedImage').click(function(event) {
		event.preventDefault();
		$('#featured_image').val('');
		$('.imageTarget').fadeOut('slow', function() {
			$(this).html('');
		});
	});

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
	{{ trans('kotoba::general.command.create') }}
	<hr>
</h1>
</div>


<div class="row">
	{!! Form::open(['url' => 'admin/pages/create', 'class' => 'form-horizontal']) !!}
		@include('nifty.backend.partials.page-form')
	{!! Form::close() !!}
</div>


<div class="col-sm-6">

	@if (count($locales))

	<ul class="nav nav-tabs">
		@foreach( $locales as $locale => $properties)
			<li class="@if ($locale == $lang)active @endif">
				<a href="#{{ $properties['id'] }}" data-target="#{{ $properties['id'] }}" data-toggle="tab">{{{ $properties['native'] }}}</a>
			</li>
		@endforeach
	</ul>

	<div class="tab-content padding-lg margin-bottom-xl">

	@foreach( $locales as $locale => $properties)
		<div role="tabpanel" class="tab-pane fade @if ($locale == $lang)in active @endif" id="{{{ $properties['id'] }}}">

			<div class="form-group">
				<label for="title">{{ trans('kotoba::general.title') }}</label>
				<input type="text" class="form-control" name="{{ 'title_'. $properties['id'] }}" id="{{ 'title_'. $properties['id'] }}" placeholder="{{ trans('kotoba::general.title') }}">
			</div>

			<div class="form-group">
				<label for="summary">{{ trans('kotoba::cms.summary') }}</label>
				<textarea class="form-control" rows="3" name="{{ 'summary_'. $properties['id'] }}" id="{{ 'summary_'. $properties['id'] }}" placeholder="{{ trans('kotoba::cms.summary') }}"></textarea>
			</div>

			<div class="form-group">
				<label for="content">{{ trans('kotoba::cms.content') }}</label>
				<textarea class="form-control" rows="3" name="{{ 'content_'. $properties['id'] }}" id="{{ 'content_'. $properties['id'] }}" placeholder="{{ trans('kotoba::cms.content') }}"></textarea>
			</div>

		</div>
	@endforeach

	</div>

	@endif

</div>
<div class="col-sm-6">

	<div class="form-group">
	<div class="input-group">
		<span class="input-group-addon"><i class="fa fa-tag fa-fw"></i></span>
			<input type="text" id="name" name="name" placeholder="{{ trans('kotoba::account.name') }}" class="form-control" autofocus="autofocus">
	</div>
	</div>


	<div class="form-group">
	<div class="input-group">
		<span class="input-group-addon"><i class="fa fa-css3 fa-fw"></i></span>
			<input type="text" id="class" name="class" placeholder="{{ trans('kotoba::cms.class') }}" class="form-control">
	</div>
	</div>

	<div class="form-group">
		{!! Form::label('parent_id', trans('kotoba::cms.parent'), ['class' => 'control-label']) !!}
		{!! Form::select('parent_id', $pagelist, Input::old('parent_id'), ['class' => 'form-control', 'id' => 'parent_id']) !!}
	</div>
	<div class="form-group">
		{!! Form::label('is_online', Lang::choice('kotoba::general.status', 1), ['class' => 'control-label']) !!}
		{!! Form::select('is_online', [0 => Lang::choice('kotoba::cms.draft', 1), 1 => trans('kotoba::cms.publish')], Input::old('is_online'), ['class' => 'form-control', 'id' => 'is_online']) !!}
	</div>
	<div class="form-group {{ $errors->first('order') ? 'has-error' : '' }}">
		{!! Form::label('order', trans('kotoba::cms.position'), $errors->first('order'), ['class' => 'control-label']) !!}
		{!! Form::text('order', Input::old('order'), ['id' => 'order', 'class' => 'form-control']) !!}
	</div>
	<div class="form-group {{ $errors->first('link') ? 'has-error' : '' }}">
		{!! Form::label('link', Lang::choice('kotoba::cms.link', 1), $errors->first('link'), ['class' => 'control-label']) !!}
		{!! Form::text('link', Input::old('link'), ['id' => 'link', 'class' => 'form-control', 'placeholder' => 'http://...']) !!}
	</div>
	<div class="form-group">
		{!! Form::label('featured_image', Lang::choice('kotoba::cms.image', 1), ['class' => 'control-label']) !!}
		<div class="imageTarget" rel="{{ $thumbnailPath }}"></div>
		{!! Form::hidden('featured_image', Input::old('featured_image'), ['id' => 'featured_image', 'class' => 'form-control hidden']) !!}
	</div>
	<div class="form-group">
		<a class="btn btn-default btn-rect btn-grad" id="changeFeaturedImage" data-toggle="modal" data-target="#featuredImageModal">{{ trans('kotoba::general.change') }}</a>
		<a class="btn btn-metis-3 btn-rect btn-grad" id="clearFeaturedImage">{{ trans('kotoba::general.clear') }}</a>
	</div>



</div>


<hr>


<div class="form-group">
	<input class="btn btn-success btn-block" type="submit" value="{{ trans('kotoba::button.save') }}">
</div>

<div class="row">
<div class="col-sm-6">
	<a href="/admin/menus" class="btn btn-default btn-block" title="{{ trans('kotoba::button.cancel') }}">
		<i class="fa fa-times fa-fw"></i>
		{{ trans('kotoba::button.cancel') }}
	</a>
</div>

<div class="col-sm-6">
	<input class="btn btn-default btn-block" type="reset" value="{{ trans('kotoba::button.reset') }}">
</div>
</div>










<!-- Modal -->
<div class="modal fade" id="featuredImageModal" tabindex="-1" role="dialog" aria-labelledby="featuredImageModalLabel" aria-hidden="true" rel="{{ URL::to('admin/select-featured-image') }}">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="featuredImageModalLabel">Click on Image to Select</h4>
			</div>
			<div class="modal-body">
				<img src="{{asset('assets/images/ajax-loader.gif')}}" alt="Loading">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-metis-3 btn-grad btn-rect" data-dismiss="modal">Close</button>
				<!-- <button type="button" class="btn btn-metis-6 btn-grad btn-rect">Select</button> -->
			</div>
		</div>
	</div>
</div>

@stop
