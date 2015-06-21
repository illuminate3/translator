@extends('app')

{{-- Web site Title --}}
@section('title')
{{ Lang::choice('kotoba::cms.page', 2) }} :: @parent
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
	{{ trans('kotoba::general.command.edit') }}
	<hr>
</h1>
</div>


<div class="row">
	{!! Form::model($page, ["url" => "admin/pages/$page->id/update", 'class' => 'form-horizontal']) !!}
		@include('nifty.backend.partials.page-form')
	{!! Form::close() !!}
</div>

<div class="row">

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

</div>

@stop
