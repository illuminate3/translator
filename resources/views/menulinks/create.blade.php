@extends('app')

{{-- Web site Title --}}
@section('title')
{{ Lang::choice('kotoba::general.menu', 2) }} :: @parent
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
	<a href="/admin/menulinks" class="btn btn-default" title="{{ trans('kotoba::button.back') }}">
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
	'url' => 'admin/menulinks',
	'method' => 'POST',
	'class' => 'form-horizontal'
]) !!}
{{-- Form::hidden('menu_id', $menu_id) --}}


<div class="col-sm-6">

<div class="form-group padding-bottom-xl">
	<label for="inputJobTitle1" class="col-sm-2 control-label">{{ Lang::choice('kotoba::cms.menu', 2) }}:</label>
	<div class="col-sm-10">
		{!!
			Form::select(
				'menu_id',
				$menus,
				Input::old('menu_id'),
				array(
					'class' => 'form-control chosen-select'
				)
			)
		!!}
	</div>
</div>

</div>
<div class="col-sm-6">

	@if (count($locales))

	<ul class="nav nav-tabs">
		@foreach( $locales as $locale => $properties)
			<li class="@if ($locale == $lang)active @endif">
				<a href="#{{ $locale }}" data-target="#{{ $locale }}" data-toggle="tab">{{{ $properties['native'] }}}</a>
			</li>
		@endforeach
	</ul>

	<div class="tab-content padding-lg margin-bottom-xl">

	@foreach( $locales as $locale => $properties)
		<div role="tabpanel" class="tab-pane fade @if ($locale == $lang)in active @endif" id="{{{ $locale }}}">

			<div class="form-group">
				<label class="col-sm-1 control-label">{{ trans('kotoba::general.title') }}</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" name="{{ $locale.'[title]' }}" id="{{ $locale.'[title]' }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label">{{ trans('kotoba::general.url') }}</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" name="{{ $locale.'[url]' }}" id="{{ $locale.'[url]' }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label"></label>
				<div class="col-sm-11">
					<div class="checkbox">
							{{ trans('kotoba::general.enabled') }}
							&nbsp;
							<input type="radio" name="{{ $locale.'[status]' }}"  name="{{ $locale.'[status]' }}" value="1">
							&nbsp;
							{{ trans('kotoba::general.disabled') }}
							&nbsp;
							<input type="radio" name="{{ $locale.'[status]' }}"  name="{{ $locale.'[status]' }}" value="0">
					</div>
				</div>
			</div>

		</div>
	@endforeach

	</div>

	@endif

</div>


<hr>


<div class="form-group">
	<input class="btn btn-success btn-block" type="submit" value="{{ trans('kotoba::button.save') }}">
</div>

{!! Form::close() !!}


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


</div> <!-- ./ row -->
@stop
