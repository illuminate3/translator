           
<div class="col-md-9">                 
    <div class="form-group {{ $errors->first('title') ? 'has-error' : '' }}">
        {{ Form::label('title', $errors->first('title'), ['class' => 'control-label']) }}
        {{ Form::text('title', Input::old('title'), ['id' => 'title', 'class' => 'form-control'])}}
    </div>
    <div class="form-group {{ $errors->first('summary') ? 'has-error' : '' }}">
        {{ Form::label('summary', $errors->first('summary'), ['class' => 'control-label']) }}
        {{ Form::textarea('summary', Input::old('title'), ['id' => 'summary', 'class' => 'form-control', 'rows' => '3'])}}
    </div>
    <div class="form-group {{ $errors->first('content') ? 'has-error' : '' }}">
        {{ Form::label('content', $errors->first('content'), ['class' => 'control-label']) }}
        {{ Form::textarea('content', Input::old('content'), ['id' => 'content', 'class' => 'form-control', 'rows' => '3'])}}
    </div>  
</div>
<div class="col-md-2 col-md-offset-1">
    <div class="form-group">
        {{ Form::label('parent_id', 'Page Parent', ['class' => 'control-label']) }}
        {{ Form::select('parent_id', $pagelist, Input::old('parent_id'), ['class' => 'form-control', 'id' => 'parent_id']) }}
    </div> 
    <div class="form-group">
        {{ Form::label('is_online', 'Draft/Publish', ['class' => 'control-label']) }}
        {{ Form::select('is_online', [0 => 'Draft', 1 => 'Publish'], Input::old('is_online'), ['class' => 'form-control', 'id' => 'is_online']) }}
    </div>     
    <div class="form-group {{ $errors->first('order') ? 'has-error' : '' }}">
        {{ Form::label('order', $errors->first('order'), ['class' => 'control-label']) }}
        {{ Form::text('order', Input::old('order'), ['id' => 'order', 'class' => 'form-control']) }}
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