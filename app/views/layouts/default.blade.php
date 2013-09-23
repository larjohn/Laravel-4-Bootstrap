<!DOCTYPE html>
<html lang="en" ng-app="metamin">
<head>
    <!-- Basic Page Needs
    ================================================== -->
    <meta charset="utf-8"/>
    <title>
        @section('title')
        {{{$title}}}
        @show
    </title>
    <meta name="keywords" content="DBpedia, errors, validation. debug, power tool"/>
    <meta name="author" content="Lazaros Ioannidis"/>
    <meta name="description"
          content="Lorem ipsum dolor sit amet, nihil fabulas et sea, nam posse menandri scripserit no, mei."/>

    <!-- Mobile Specific Metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS
    ================================================== -->
    <link href="{{{ asset('assets/css/application/application.css') }}}" rel="stylesheet">
    <link href="{{{ asset('assets/css/bootstrap.css') }}}" rel="stylesheet">
    <!--link href="{{{ asset('assets/css/metro-bootstrap.css') }}}" rel="stylesheet"-->
    <link type="text/css" rel="stylesheet" href="{{{ asset('assets/css/style.css')}}}">
    <link type="text/css" rel="stylesheet" href="{{{ asset('assets/css/calendar.css')}}}">
    <link href="{{{ asset('assets/css/jquery-ui.css')}}}" media="screen" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" href="{{{ asset('assets/css/theme.css')}}}">

    <link href="{{{ asset('assets/css/font-awesome/css/font-awesome.css') }}}" rel="stylesheet">

    <style>
        @section('styles')

		@show
    </style>

    <script type="text/javascript">
        appRoot = "{{URL::to('')}}/"
    </script>
    <script type="text/javascript" src="{{{ asset('assets/js/jquery-1.9.0.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('assets/js/underscore-1.4.3.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('assets/js/backbone-0.9.10.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('assets/js/jqueryui/jquery.ui.core.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('assets/js/jqueryui/jquery.ui.widget.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('assets/js/jqueryui/jquery.ui.autocomplete.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('assets/js/jqueryui/jquery.ui.position.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('assets/js/jqueryui/jquery.ui.menu.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('assets/js/application/main.js') }}}"></script>
    <script src="{{{ asset('assets/js/VIE/vie-2.1.0.debug.js') }}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{{ asset('assets/js/application/rdf.js') }}}"></script>
    <script type="text/javascript" src="{{{ asset('assets/js/jsviews/jsviews.js') }}}"></script>
    <script src="{{{ asset('assets/js/jqGrid/jquery.jqGrid.src.js') }}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{{ asset('assets/js/application/ladybug.js') }}}"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Favicons
    ================================================== -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
          href="{{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="{{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
          href="{{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}}">
    <link rel="apple-touch-icon-precomposed" href="{{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}}">
    <link rel="shortcut icon" href="{{{ asset('assets/ico/favicon.png') }}}">
</head>

<body>

<!-- BEGIN WRAP -->
<div id="wrap">


<!-- BEGIN TOP BAR -->
<div id="top">
    <!-- .navbar -->
    <div class="navbar navbar-inverse navbar-static-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="{{URL::to('')}}"><img src="{{{ asset('assets/img/ladybug.png') }}}"/></a>
                <!-- .topnav -->
                <div class="btn-toolbar topnav">
                    <div class="btn-group">
                        <a id="changeSidebarPos" class="btn btn-success" rel="tooltip"
                           data-original-title="Show / Hide Sidebar" data-placement="bottom">
                            <i class="icon-resize-horizontal"></i>
                        </a>
                    </div>

                </div>
                <!-- /.topnav -->
                <div class="nav-collapse collapse">
                    <!-- .nav -->
                    <ul class="nav">
                        <li class="active"><a href="{{URL::to('/tests/latest')}}">Test Dashboard</a></li>
                        <li><a href="{{URL::to('queries')}}">Queries</a></li>
                        <li><a href="{{URL::to('tests')}}">Tests</a></li>

                    </ul>
                    <!-- /.nav -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.navbar -->
</div>
<!-- END TOP BAR -->


<!-- BEGIN HEADER.head -->
<header class="head">

    <!-- ."main-bar -->
    <div class="main-bar">
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <span>
                   <i class="icon-home"></i>

                        {{ Breadcrumbs::render($bread["path"], $bread["params"]) }}
                    </span>
                </div>
            </div>
            <!-- /.row-fluid -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.main-bar -->
</header>
<!-- END HEADER.head -->

<!-- BEGIN LEFT  -->
<div id="left">
    <!-- .user-media -->
 @yield('left')

</div>
<!-- END LEFT -->

<!-- BEGIN MAIN CONTENT -->
<div id="content">
    <!-- .outer -->
    <div class="container-fluid outer">
        <div class="row-fluid">
            <!-- .inner -->
            <div class="span12 inner">
                <!--BEGIN LATEST COMMENT-->
                <!-- .row-fluid -->
                <div class="row-fluid">
                    <!-- .span6 -->

                    <!-- /.span6 -->
                    <!-- .span6 -->
                    <div class="span12">
                        <!-- .box -->
                        <div class="box">
                            <header></header>
                            <!-- .body -->
                            <div class="body">

                                <!-- Notifications -->
                                @include('notifications')
                                <!-- ./ notifications -->

                                <!-- Content -->
                                @yield('content')
                                <!-- ./ content -->
                            </div>
                            <!-- /.body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.span6 -->
                </div>
                <!-- /.row-fluid -->
                <!--END LATEST COMMENT-->
            </div>
            <!-- /.inner -->
        </div>
        <!-- /.row-fluid -->
    </div>
    <!-- /.outer -->
</div>
<!-- END CONTENT -->


<!-- #push do not remove -->
<div id="push"></div>
<!-- /#push -->
</div>
<!-- END WRAP -->

<div class="clearfix"></div>

<!-- BEGIN FOOTER -->
<div id="footer">
    <p>LadyBug <a href="http://dbpedia.org">DBpedia</a> Validation</p>
</div>
<!-- END FOOTER -->

<!-- #helpModal -->
<div id="helpModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel"
     aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="helpModalLabel"><i class="icon-external-link"></i> Help</h3>
    </div>
    <div class="modal-body">
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
            ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
            nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit
            anim id est laborum.
        </p>
    </div>
    <div class="modal-footer">

        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>
<!-- /#helpModal -->

<script src="{{{ asset('assets/js/lib/jquery.mousewheel.js')}}}"></script>
<script src="{{{ asset('assets/js/lib/jquery.sparkline.min.js')}}}"></script>
<script src="{{{ asset('assets/vendor/flot/jquery.flot.js') }}}"></script>
<script src="{{{ asset('assets/vendor/flot/jquery.flot.pie.js') }}}"></script>
<script src="{{{ asset('assets/vendor/flot/jquery.flot.selection.js') }}}"></script>
<script src="{{{ asset('assets/vendor/flot/jquery.flot.resize.js') }}}"></script>
<script src="{{{ asset('assets/vendor/fullcalendar/fullcalendar/fullcalendar.min.js') }}}"></script>
<script src="{{{ asset('assets/js/bootstrap/bootstrap.min.js') }}}"></script>
</body>
</html>
