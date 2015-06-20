@extends('backend._template')

@section('title')Deleted Pages @stop

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
@stop

@section('page-title') <h3><i class="fa fa-tasks"></i> Pages</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>Deleted Pages</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat" href="{{URL::to('dashboard/pages/create')}}"><span class="fa fa-pencil"></span> New Page</a>
                </div>                  
            </header>
        </div><!-- /.box -->
    </div>

    <div class="col-md-12">
        <div class="btn-group page-options">
            <a class="btn btn-metis-5 btn-rect btn-line" role="button" href="{{ URL::to('dashboard/pages') }}">All ({{ $nums['allNotDeletedNum'] }})</a>
            <a class="btn btn-metis-5 btn-rect btn-line" role="button" href="{{ URL::to('dashboard/pages/published') }}">Published ({{ $nums['publishedNum'] }})</a>
            <a class="btn btn-metis-5 btn-rect btn-line" role="button" href="{{ URL::to('dashboard/pages/drafts') }}">Drafts ({{ $nums['draftsNum'] }})</a>
            <a class="btn btn-metis-5 btn-rect disabled" href="{{ URL::to('dashboard/pages/trash') }}">Trash ({{ $nums['deletedNum'] }})</a>
        </div>
    </div>

    <div class="col-md-12 optionsDiv opacity">
        {{ Form::open(['url' => '#', 'id' => 'bulk-options-form']) }}
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
                    <input type="hidden" id="bulkRestoreUrl" value = "{{ URL::to('dashboard/pages/bulk-restore') }}" />
                    <input type="hidden" id="bulkDestroyUrl" value = "{{ URL::to('dashboard/pages/bulk-destroy') }}" />
                    <div class="appendTarget"></div>
                </div>                
            </div>
            <div class="col-sm-3 col-md-2">                     
                <div class="form-group">
                    <button type="submit" class="btn btn-default btn-rect" id="bulk-submit" disabled="disabled">Submit</button> 
                </div>
            </div>
        </div>
        {{ Form::close() }}
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
                   {{ $pagesHtml }}
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-12">
        {{ $links }}
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