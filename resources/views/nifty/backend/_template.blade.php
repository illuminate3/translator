<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">       
        <meta content="Configure new site" name="description">
        <meta content="James Ilaki" name="author">
        <link href="{{asset('favicon.png')}}" rel="shortcut icon">
        <title>Nifty::@yield('title') </title>
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
        {{ HTML::style('assets/template/css/main.css') }}
        {{ HTML::style('assets/template/css/theme.css') }}
        @yield('page-css')
        <!--[if lt IE 9]>
            {{ HTML::script('assets/bootstrap/js/html5shiv.js') }}
            {{ HTML::script('assets/bootstrap/js/respond.min.js') }}
        <![endif]-->
    </head>
    <body class="padTop53">
        <div id="wrap">
            <div id="top">
                <nav class="navbar navbar-inverse navbar-fixed-top">
                    <header class="navbar-header">
<!--                         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                        </button> -->
                        <a href="{{ URL::to('/') }}" class="navbar-brand">
                            <img src="{{asset('assets/template/img/logo.png')}}" alt="Nifty">
                        </a> 
                    </header>
                    <div class="topnav">
                        <div class="btn-toolbar">
                            <div class="btn-group">
                                <a data-toggle="modal" data-original-title="Settings" data-placement="bottom" class="btn btn-info btn-sm btn-grad" href="#settingsModal">
                                    <i class="fa fa-cogs"></i>
                                </a> 
                            </div>
                            <div class="btn-group">
                                <a href="{{ URL::to('dashboard/logout') }}" data-toggle="tooltip" data-original-title="Logout" data-placement="bottom" class="btn btn-danger btn-sm btn-grad">
                                    <i class="fa fa-power-off"></i>
                                </a> 
                            </div>
                        </div>
                    </div><!-- /.topnav -->
<!--                     <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"> <a href="dashboard.html">Dashboard</a> </li>
                            <li> <a href="table.html">Pages</a>  </li>
                            <li> <a href="file.html">Blog</a>  </li>
                            <li class='dropdown '>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Users
                                    <b class="caret"></b>
                                </a> 
                                <ul class="dropdown-menu">
                                    <li> <a href="form-general.html">General</a>  </li>
                                    <li> <a href="form-validation.html">Validation</a>  </li>
                                    <li> <a href="form-wysiwyg.html">WYSIWYG</a>  </li>
                                    <li> <a href="form-wizard.html">Wizard &amp; File Upload</a>  </li>
                                </ul>
                            </li>
                        </ul>
                    </div> -->
                </nav><!-- /.navbar -->

                <header class="head">
                    <div class="search-bar" style="margin-top: 5px; overflow:hidden">
                        <a data-original-title="Show/Hide Menu" data-placement="bottom" data-tooltip="tooltip" class="accordion-toggle btn btn-primary btn-sm visible-xs" data-toggle="collapse" href="#menu" id="menu-toggle">
                            <i class="fa fa-expand"></i>
                        </a> 
                    </div>
                    <div class="main-bar">
                        @yield('page-title')
                    </div><!-- /.main-bar -->
                </header>
            </div><!-- /#top -->
            <div id="left">
                @include('backend.partials.sidebar')
            </div><!-- /#left -->
            <div id="main-content">
                <div class="outer">
                    <div class="inner">
                        @yield('page')
                    </div><!-- end .inner -->
                </div> <!-- end .outer --> 
            </div> <!-- end #main-content -->
        </div><!-- /#wrap -->
        <div id="footer">
            <p><a href="https://github.com/kJamesy" target="_blank"> {{ date('Y') }} &copy;kJamesy</a></p>
        </div>

        <!-- #helpModal -->
        <div id="settingsModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Settings</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert hidden alert-dismissable" id="settings_feedback">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong></strong>
                        </div>
                        {{ Form::open(['url' => 'dashboard/change-settings', 'class' => 'form-horizontal', 'id' => 'settings_form']) }}                  
                            <div class="form-group" style="margin-left: 0; margin-right: 0;">
                                {{ Form::label('settings_image_path', 'Images Directory (Current: ' . Setting::getImagePath() . '/)', ['class' => 'control-label']) }}
                                {{ Form::text('settings_image_path', '', ['id' => 'settings_image_path', 'class' => 'form-control']) }}
                            </div>
                            <div class="form-group" style="margin-left: 0; margin-right: 0;">
                                {{ Form::label('settings_thumbnail_path', 'Thumbnails Directory (Current: ' . Setting::getThumbnailPath() . '/)', ['class' => 'control-label']) }}
                                {{ Form::text('settings_thumbnail_path', '', ['id' => 'settings_thumbnail_path', 'class' => 'form-control']) }}
                            </div>
                            <!-- <div class="form-group" style="margin-left: 0; margin-right: 0;"> -->
                                {{-- Form::label('settings_contact_email', 'Contact Email (Current: ' . Setting::getThumbnailPath() . '/)', ['class' => 'control-label']) --}}
                                {{-- Form::text('settings_contact_email', '', ['id' => 'settings_contact_email', 'class' => 'form-control']) --}}
                            <!-- </div>                             -->
                            <div class="form-group" style="margin-left: 0; margin-right: 0;">
                                <button type="submit" class="btn btn-metis-5 btn-grad btn-rect btn-lg" id="settings_submit">Save</button>
                            </div>    
                        {{ Form::close() }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal --><!-- /#helpModal -->

        {{ HTML::script('assets/js/jQuery-1.10.2.min.js') }}
        {{ HTML::script('assets/bootstrap/js/bootstrap.min.js') }}

        {{ HTML::script('assets/template/js/main.min.js') }}
        
        <script>
            jQuery(document).ready(function($) {
                $('#settings_form').submit(function(event) {
                    event.preventDefault();
                    var url = $(this).attr('action');
                    var imagePath = $.trim( $('#settings_image_path').val() );
                    var thumbnailPath = $.trim( $('#settings_thumbnail_path').val() );
                    var _token = $('input[name="_token"]').val();

                    $.post(url, {imagePath:imagePath, thumbnailPath:thumbnailPath, _token: _token}, function(data) {
                        if ( data.error ) {
                            $('#settings_feedback').removeClass('alert-success hidden').addClass('alert-danger');
                            $('#settings_feedback').find('strong').text(data.error);
                            $('#settings_form').find('.form-group').removeClass('has-success').addClass('has-error');
                        }
                        if ( data['success'] ) {
                            $('#settings_feedback').removeClass('alert-danger hidden').addClass('alert-success');
                            $('#settings_feedback').find('strong').text(data.success);
                            $('#settings_form').find('.form-group').addClass('has-success').removeClass('has-error');
                        }

                        $('#settingsModal').on('hidden.bs.modal', function (e) {
                            location.reload(true);
                        });

                    });
                });
            });
        </script>

        @yield('page-js')
        {{ HTML::script('assets/js/back.js') }}
    </body>
</html>