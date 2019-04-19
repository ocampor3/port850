<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="-1">
  <meta http-equiv="Cache-Control" content="private, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0">

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> @yield('title') </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ URL::asset('/AdminLTE-2.3.7/bootstrap/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ URL::asset('/AdminLTE-2.3.7/dist/css/AdminLTE.min.css') }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ URL::asset('/AdminLTE-2.3.7/dist/css/skins/_all-skins.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('/css/mystyle.css') }}">

    <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{ URL::asset('/AdminLTE-2.3.7/plugins/colorpicker/bootstrap-colorpicker.min.css') }}">

  <!-- TINY MCE -->
  <script src="{{ URL::asset('/css/tinymce/tinymce.min.js') }}"></script>  

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- jQuery 2.2.3 -->
  <script src="{{ URL::asset('/AdminLTE-2.3.7/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
</head>
<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
<!-- the fixed layout is not compatible with sidebar-mini -->
<body class="hold-transition skin-blue fixed sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <input type="hidden" id="refreshed" value="no">

  <header class="main-header">
    <!-- Logo -->
    @if(Auth::user()->UserGroup == "Admin")    
      <a href="/v1/site" class="logo navbar-top">
        <img src="/images/DigiSpace_LefyNavIcon.png">
      </a>    
    @else
      <?php 
        $user = App\User::with('usersite.site')->where('Id', Auth::user()->Id)->first();
        $logosite = $user['usersite'][0]['site'];
      ?>

      <a href="/v1/site/{{ $logosite->SiteCode }}" class="logo navbar-top">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Roche_Logo.svg/2000px-Roche_Logo.svg.png">
      </a>        
    @endif

    
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="no-show-big-screen sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav"> 
            <li class="dropdown user user-menu">

                @if(Auth::guest())

                @else
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">                        
                      <img src="{{ URL::asset('/AdminLTE-2.3.7/dist/img/avatar5.png')}}" class="user-image" alt="User Image">
                                               
                      <span class="hidden-xs">                                                        
                          {{Auth::user()->FullName}}                          
                      </span>
                    </a>
                @endif

                <ul class="dropdown-menu">
                    @if(Auth::guest())

                    @else
                        <!-- User image -->
                        <li class="user-header">
                            
                            <img src="{{ URL::asset('/AdminLTE-2.3.7/dist/img/avatar5.png')}}" class="img-circle" alt="User Image">
                           
                            <p>                                            
                                {{Auth::user()->FullName}}
                                <small>{{Auth::user()->UserGroup}}</small>     
                            </p>
                        </li>
                    @endif    

                    <!-- Menu Footer-->
                    <li class="user-footer">  
                        <div class="pull-left">
                             <a href="{{ route('edit-user') }}" class="btn btn-default btn-flat" role="button"></span> Edit Profile</a>
                        </div>
 
                        <div class="pull-right">
                            <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                Sign Out
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            
                        </div>
                    </li>
                </ul>
            </li> 
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
      <!-- SELECTED THEME -->        
        @if(Session::get('SiteCode'))
          <li class="header">ACTIVE SITE</li>
          <li class="treeview">
            <a href="{{ route('site.show', Session::get('SiteCode')) }}">
              <i class="fa fa-circle-o text-aqua"></i> <span>{{Session::get('SiteTitle')}}</span>
            </a>
          </li>
        @endif
        <!-- MAIN NAVIGATION -->
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-globe"></i> <span>Sites</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(Auth::user()->UserGroup != 'Visitor')
              <li><a onclick="clearCookie();" href="{{route('site.index')}}"><i class="fa fa-circle-o"></i> All Sites</a></li>
            @endif

            @if($limit == "no")
              @foreach($sites as $site)
                @if($site->parentSite == null)
                  <li><a href="{{ route('site.show', $site->SiteCode) }}" id="{{$site->Id}}" class="subclass"><i class="fa fa-plus-circle"></i>{{ $site->Title }}</a>

                    <ul class="treeview-menu">
                      @if(Auth::user()->UserGroup == 'Owner' || Auth::user()->UserGroup == 'Admin')
                        <li><a href="{{ route('theme.show',$site->Id) }}"><i class="fa fa-paint-brush"></i> Theme</a></li>
                      @endif
                      <li><a href="{{ route('category.show',$site->Id) }}"><i class="fa fa-tags"></i> Categories</a></li>
                      <li><a onclick="clearCookie();" href="{{ route('article.show',$site->Id) }}"><i class="fa fa-file-text"></i> Articles</a></li>                  


                      @foreach($site->subsites as $subsite)
                        <li><a href="{{ route('site.show', $subsite->SiteCode) }}" class="{{$site->Id}}-subsubclass subsubclass"><i class="fa fa-plus-circle"></i>{{ $subsite->Title }}</a>

                        <ul class="treeview-menu">
                          @if(Auth::user()->UserGroup == 'Owner' || Auth::user()->UserGroup == 'Admin')
                            <li><a href="{{ route('theme.show',$subsite->Id) }}"><i class="fa fa-paint-brush"></i> Theme</a></li>
                          @endif
                          <li><a href="{{ route('category.show',$subsite->Id) }}"><i class="fa fa-tags"></i> Categories</a></li>
                          <li><a onclick="clearCookie();" href="{{ route('article.show',$subsite->Id) }}"><i class="fa fa-file-text"></i> Articles</a></li>                  

                        </ul>
                      @endforeach
                    </ul>
                  </li>
                @endif
              @endforeach
            @else
              @foreach($sites['usersite'] as $usersite)
                <?php $site = $usersite['site']; ?>
                @if($usersite['site']->parentSite == null)
                  <li><a href="{{ route('site.show', $site->SiteCode) }}" id="{{$site->Id}}" class="subclass"><i class="fa fa-plus-circle"></i>{{ $site->Title }}</a>

                    <ul class="treeview-menu">
                      @if(Auth::user()->UserGroup == 'Owner' || Auth::user()->UserGroup == 'Admin')
                        <li><a href="{{ route('theme.show',$site->Id) }}"><i class="fa fa-paint-brush"></i> Theme</a></li>
                      @endif
                      <li><a href="{{ route('category.show',$site->Id) }}"><i class="fa fa-tags"></i> Categories</a></li>
                      <li><a onclick="clearCookie();" href="{{ route('article.show',$site->Id) }}"><i class="fa fa-file-text"></i> Articles</a></li>                  


                      @foreach($site->subsites as $subsite)
                        <li><a href="{{ route('site.show', $subsite->SiteCode) }}" class="{{$site->Id}}-subsubclass subsubclass"><i class="fa fa-plus-circle"></i>{{ $subsite->Title }}</a>

                        <ul class="treeview-menu">
                          @if(Auth::user()->UserGroup == 'Owner' || Auth::user()->UserGroup == 'Admin')
                            <li><a href="{{ route('theme.show',$subsite->Id) }}"><i class="fa fa-paint-brush"></i> Theme</a></li>
                          @endif
                          <li><a href="{{ route('category.show',$subsite->Id) }}"><i class="fa fa-tags"></i> Categories</a></li>
                          <li><a onclick="clearCookie();" href="{{ route('article.show',$subsite->Id) }}"><i class="fa fa-file-text"></i> Articles</a></li>                  

                        </ul>
                      @endforeach
                    </ul>
                  </li>
                @endif
              @endforeach
            @endif
          </ul>
        </li>
        @if(Auth::user()->UserGroup == 'Owner' || Auth::user()->UserGroup == 'Admin')
          <li><a href="{{ route('user.index') }}"><i class="fa fa-users"></i> <span>Users</span></a></li>  
        @endif
        @if(Auth::user()->UserGroup == 'Admin')
          <li><a href="{{ route('securitygroup.index') }}"><i class="fa fa-user-secret"></i> <span>Security Group</span></a></li>  
        @endif
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    @if(Session::has('flash_message'))
        <div class="row">
            <div class="col-md-6">
                <div class="box-body">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        {{ Session::get('flash_message') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if(Session::has('error_message'))
        <div class="row">
            <div class="col-md-6">
                <div class="box-body">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                        {{ Session::get('error_message') }}
                    </div>
                </div>  
            </div>
        </div>

    @endif

    <section class="content-header">
      <h1 style="word-wrap: break-word;">@yield('page-title')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.1.0
        </div>
        <strong>Copyright © 2019 DigiSpace.</strong> All rights
        reserved.
    </footer>
</div>
<!-- ./wrapper -->


<!-- Bootstrap 3.3.6 -->
<script src="{{ URL::asset('/AdminLTE-2.3.7/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ URL::asset('/AdminLTE-2.3.7/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ URL::asset('/AdminLTE-2.3.7/plugins/fastclick/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ URL::asset('/AdminLTE-2.3.7/dist/js/app.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ URL::asset('/AdminLTE-2.3.7/dist/js/demo.js') }}"></script>

<script src="{{ URL::asset('/AdminLTE-2.3.7/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ URL::asset('/AdminLTE-2.3.7/plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>

<script type="text/javascript">

    $('.subclass').click(function () {
        $('.subclass').not(this).find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
        $(this).find('i').toggleClass('fa-minus-circle');

        // toggle to plus icon if subsubclass of unfocused site
        var id = $(this).attr('id');
        $('.subsubclass').not('.'+id+'-subsubclass').find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
    });

    $('.subsubclass').click(function () {
        $('.subsubclass').not(this).find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
        $(this).find('i').toggleClass('fa-minus-circle');
    });

    function clearCookie()
    {
        $.cookie("article_categoryfilter","All");       
    }   

    // refresh page on back button
    window.addEventListener( "pageshow", function ( event ) {
      var historyTraversal = event.persisted || ( typeof window.performance != "undefined" && window.performance.navigation.type === 2 );
      if ( historyTraversal ) {
        // Handle page restore.
        window.location.reload();
      }
    });

</script>

</body>
</html>
