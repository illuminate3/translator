@extends('app')

{{-- Web site Title --}}
@section('title')
{{ Lang::choice('kotoba::general.content', 2) }} :: @parent
@stop

@section('styles')
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
	<a href="/admin/contents" class="btn btn-default" title="{{ trans('kotoba::button.back') }}">
		<i class="fa fa-chevron-left fa-fw"></i>
		{{ trans('kotoba::button.back') }}
	</a>
	</p>
	<i class="fa fa-edit fa-lg"></i>
	{{ trans('kotoba::general.command.create') }}
	<hr>
</h1>
</div>


<div class="row">
{!! Form::open([
	'url' => 'admin/contents',
	'method' => 'POST',
	'class' => 'form-horizontal'
]) !!}


<div class="col-sm-9">

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

	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#content_{{$properties['locale']}}" aria-controls="content" role="tab" data-toggle="tab">{{ trans('kotoba::cms.content') }}</a></li>
		<li role="presentation"><a href="#meta_{{$properties['locale']}}" aria-controls="meta" role="tab" data-toggle="tab">{{ trans('kotoba::cms.meta') }}</a></li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="content_{{$properties['locale']}}">

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
		<div role="tabpanel" class="tab-pane" id="meta_{{$properties['locale']}}">

			<div class="form-group">
				<label for="title">{{ trans('kotoba::general.slug') }}</label>
				<input type="text" class="form-control" name="{{ 'slug_'. $properties['id'] }}" id="{{ 'slug_'. $properties['id'] }}" placeholder="{{ trans('kotoba::general.slug') }}">
			</div>

			<div class="form-group">
				<label for="title">{{ trans('kotoba::cms.meta_title') }}</label>
				<input type="text" class="form-control" name="{{ 'meta_title_'. $properties['id'] }}" id="{{ 'meta_title_'. $properties['id'] }}" placeholder="{{ trans('kotoba::cms.meta_title') }}">
			</div>

			<div class="form-group">
				<label for="title">{{ trans('kotoba::cms.meta_keywords') }}</label>
				<input type="text" class="form-control" name="{{ 'meta_keywords_'. $properties['id'] }}" id="{{ 'meta_keywords_'. $properties['id'] }}" placeholder="{{ trans('kotoba::cms.meta_keywords') }}">
			</div>

			<div class="form-group">
				<label for="title">{{ trans('kotoba::cms.meta_title') }}</label>
				<input type="text" class="form-control" name="{{ 'meta_description_'. $properties['id'] }}" id="{{ 'meta_description_'. $properties['id'] }}" placeholder="{{ trans('kotoba::cms.meta_description') }}">
			</div>

		</div>

	</div>

	</div><!-- ./ panel -->
	@endforeach
	</div>
	@endif

</div><!-- ./ col -->
<div class="col-sm-3">

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
{{--
	<div class="form-group">
		{!! Form::label('featured_image', Lang::choice('kotoba::cms.image', 1), ['class' => 'control-label']) !!}
		<div class="imageTarget" rel="{{ $thumbnailPath }}"></div>
		{!! Form::hidden('featured_image', Input::old('featured_image'), ['id' => 'featured_image', 'class' => 'form-control hidden']) !!}
	</div>
	<div class="form-group">
		<a class="btn btn-default btn-rect btn-grad" id="changeFeaturedImage" data-toggle="modal" data-target="#featuredImageModal">{{ trans('kotoba::general.change') }}</a>
		<a class="btn btn-metis-3 btn-rect btn-grad" id="clearFeaturedImage">{{ trans('kotoba::general.clear') }}</a>
	</div>
--}}

</div><!-- ./ col -->


<hr>


<div class="form-group">
	<input class="btn btn-success btn-block" type="submit" value="{{ trans('kotoba::button.save') }}">
</div>

<div class="row">
<div class="col-sm-6">
	<a href="/admin/contents" class="btn btn-default btn-block" title="{{ trans('kotoba::button.cancel') }}">
		<i class="fa fa-times fa-fw"></i>
		{{ trans('kotoba::button.cancel') }}
	</a>
</div>

<div class="col-sm-6">
	<input class="btn btn-default btn-block" type="reset" value="{{ trans('kotoba::button.reset') }}">
</div>
</div>


{!! Form::close() !!}


</div> <!-- ./ row -->
@stop
