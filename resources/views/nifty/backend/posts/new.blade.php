@extends('backend._template')

@section('title')New Post @stop

@section('page-css')
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

@section('page-title') <h3><i class="fa fa-tasks"></i> Blog</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>New Post</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat disabled" href="{{URL::to('dashboard/blog/posts/create')}}"><span class="fa fa-pencil"></span> New Post</a>
                </div>                
            </header>
        </div><!-- /.box -->
    </div>
    <div class="col-md-12">
        {{Form::open(['url' => 'dashboard/blog/posts/create', 'class' => 'form-horizontal'])}}  
            <div class="col-md-9">                 
                <div class="form-group {{ $errors->first('title') ? 'has-error' : '' }}">
                    {{ Form::label('title', $errors->first('title'), ['class' => 'control-label']) }}
                    {{ Form::text('title', Input::old('title'), ['id' => 'title', 'class' => 'form-control']) }}
                </div>
                <div class="form-group {{ $errors->first('summary') ? 'has-error' : '' }}">
                    {{ Form::label('summary', $errors->first('summary'), ['class' => 'control-label']) }}
                    {{ Form::textarea('summary', Input::old('title'), ['id' => 'summary', 'class' => 'form-control', 'rows' => '3']) }}
                </div>
                <div class="form-group {{ $errors->first('content') ? 'has-error' : '' }}">
                    {{ Form::label('content', $errors->first('content'), ['class' => 'control-label']) }}
                    {{ Form::textarea('content', Input::old('content'), ['id' => 'content', 'class' => 'form-control', 'rows' => '3']) }}
                </div>  
            </div>
            <div class="col-md-2 col-md-offset-1">
                <div class="form-group">
                    <strong>Categories</strong>
                    @foreach ($categorylist as $id => $name)
                        <div class="checkbox">
                            <label>
                                {{ Form::checkbox('categories[]', $id) }} {{ $name }}
                            </label>
                        </div>
                    @endforeach  
                </div> 
                <div class="form-group {{ $errors->first('order') ? 'has-error' : '' }}">
                    {{ Form::label('order', $errors->first('order'), ['class' => 'control-label']) }}
                    {{ Form::text('order', Input::old('order'), ['id' => 'order', 'class' => 'form-control']) }}
                </div>                
                <div class="form-group">
                    {{ Form::label('is_online', 'Draft/Publish', ['class' => 'control-label']) }}
                    {{ Form::select('is_online', [0 => 'Draft', 1 => 'Publish'], Input::old('is_online'), ['class' => 'form-control', 'id' => 'is_online']) }}
                </div> 
                <div class="form-group {{ $errors->first('link') ? 'has-error' : '' }}">
                    {{ Form::label('link', $errors->first('link'), ['class' => 'control-label']) }}
                    {{ Form::text('link', Input::old('link'), ['id' => 'link', 'class' => 'form-control', 'placeholder' => 'http://...']) }}
                </div>                
                <div class="form-group">
                    {{ Form::label('featured_image', 'Featured Image', ['class' => 'control-label']) }}
                    <div class="imageTarget" rel="{{ $thumbnailPath }}"></div> 
                    {{ Form::hidden('featured_image', Input::old('featured_image'), ['id' => 'featured_image', 'class' => 'form-control hidden']) }}
                </div>    
                <div class="form-group">
                    <a class="btn btn-default btn-rect btn-grad" id="changeFeaturedImage" data-toggle="modal" data-target="#featuredImageModal">Change</a>
                    <a class="btn btn-metis-3 btn-rect btn-grad" id="clearFeaturedImage">Clear</a>
                </div>                               
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-metis-5 btn-grad btn-rect btn-lg">Save</button>
                </div>
            </div>
        {{Form::close()}}

        <!-- Modal -->
        <div class="modal fade" id="featuredImageModal" tabindex="-1" role="dialog" aria-labelledby="featuredImageModalLabel" aria-hidden="true" rel="{{ URL::to('dashboard/select-featured-image') }}">
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

@section('page-js')
    {{ HTML::script('assets/ckfinder2.4/ckfinder.js') }}
    {{ HTML::script('assets/ckeditor4.3/ckeditor.js') }}
    <script>
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
    </script>
@stop