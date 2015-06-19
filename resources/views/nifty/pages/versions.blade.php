@extends('backend._template')

@section('title')Page Archives @stop

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
        .versions-margin {
            margin: 10px 0;
        }
        .opacity {
            opacity: 0.3 !important;
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
                <h5>Archived Page Versions</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat" href="{{URL::to('dashboard/pages/create')}}"><span class="fa fa-pencil"></span> New Page</a>
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

        {{ Form::open(['url' => 'dashboard/pages/'.$page->id.'/select-version', 'class' => 'form-horizontal']) }}
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
                   {{ $versionsHtml }}
                </tbody>
            </table>
        </div>

        <div class="form-group" style="margin-left: 5px;">
            <button type="submit" class="btn btn-metis-5 btn-grad btn-rect btn-lg disabled opacity" id="select-version" >Select Version</button>
        </div> 
        {{ Form::close() }}        
    </div>

@stop

@section('page-js')
    <script>
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
    </script>
@stop