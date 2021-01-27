<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  {{-- Imagen del sistema --}}
  <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">
  {{-- Fonts --}}
  <link href="../assets/fonts/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"/>
  {{-- CSS --}}
  <link href="../assets/css/alertify.min.css" rel="stylesheet"/>
  <link href="../assets/css/alertify.rtl.min.css" rel="stylesheet"/>
  @yield('cssDashboard')
  <link href="../assets/css/mainstyles.css" rel="stylesheet"/>
  <title>SISTEMA DE ASISTENCIA</title>
</head>

<body id="page-top">
  <div id="wrapper">
    {{-- Menú --}}
    <ul class="navbar-nav bg-gradient-main sidebar sidebar-dark accordion" id="accordionSidebar">
      {{-- Titulo --}}
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Sistema de Asistencia</div>
      </a>
      {{-- Divisor --}}
      <hr class="sidebar-divider my-0">
      {{-- Encabezado --}}
      @if (session('user_agent')=='Admin'|| session('user_agent')=='CoordiAsSo'||session('user_agent')=='DirGen'||session('user_agent')=='Cont'||session('user_agent') == 'SuperAsSo')
      <div class="sidebar-heading">
          <i class="fas fa-cubes"></i>
        Modulos
      </div>
      {{-- Lista de módulos en el menú --}}
      <li class="nav-item">
        <a class="nav-link" href="dashboard">
            <i class="fas fa-fw fa-home"></i>
          <span>Principal</span>
        </a>
      </li>
      @endif
      @if (session('user_agent')=='Admin'|| session('user_agent')=='CoordiAsSo'||session('user_agent')=='DirGen'||session('user_agent')=='Cont'||session('user_agent')=='TrSo'||session('user_agent') == 'SuperAsSo')
      <li class="nav-item">
        <a class="nav-link" href="solicitudes">
            <i class="fas fa-fw fa-file-alt"></i>
          <span>Solicitudes</span>
        </a>
      </li>

       <!-- Divider -->
       <hr class="sidebar-divider">
       @endif
       @if (session('user_agent')=='Admin')
       {{-- Encabezado --}}
      <div class="sidebar-heading">
        <i class="fas fa-Book"></i>
      Catalogos
      </div>
    {{-- Lista de módulos en el menú --}}

      <li class="nav-item">
        <a class="nav-link" href="institutos">
            <i class="fas fa-fw fa-university"></i>
            <span>Instituciones</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="departamentos">
            <i class="fas fa-fw fa-building"></i>
            <span>Departamentos</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="estados_solicitud">
          <i class="fas fa-fw fa-check-square"></i>
          <span>Estados de Solicitud</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="roles">
          <i class="fas fa-fw fa-user-cog"></i>
          <span>Roles</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="ocupaciones">
          <i class="fas fa-fw fa-user-tie"></i>
          <span>Ocupaciones</span>
        </a>
      </li>
      @endif
      @if (session('user_agent')=='Admin'||session('user_agent')=='TrSo'||session('user_agent')=='Cont'||session('user_agent')=='DirGen'||session('user_agent') == 'SuperAsSo')
      <li class="nav-item">
        <a class="nav-link" href="proveedores">
          <i class="fas fa-fw fa-user-secret"></i>
          <span>Proveedores</span>
        </a>
      </li>
      @endif
      @if (session('user_agent')=='Admin')
      <li class="nav-item">
        <a class="nav-link" href="usuarios">
          <i class="fas fa-fw fa-user"></i>
          <span>Usuarios</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="apoyos">
          <i class="fas fa-fw fa-hands-helping"></i>
          <span>Apoyos</span>
        </a>
      </li>
      @endif
      @if (session('user_agent')=='Admin'||session('user_agent')=='Cont'||session('user_agent')=='DirGen')
      <li class="nav-item">
        <a class="nav-link" href="productos">
          <i class="fas fa-fw fa-inbox"></i>
          <span>Productos</span>
        </a>
      </li>
      @endif
      @if (session('user_agent')=='Admin')
      <li class="nav-item">
        <a class="nav-link" href="categorias">
          <i class="fas fa-fw fa-inbox"></i>
          <span>Categorias</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="materialesConstruccion">
          <i class="fas fa-fw fa-inbox"></i>
          <span>Materiales de Construcción</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="servicios">
          <i class="fas fa-fw fa-inbox"></i>
          <span>Servicios</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="muebles">
          <i class="fas fa-fw fa-inbox"></i>
          <span>Muebles</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="categoria-diagnostico">
          <i class="fas fa-fw fa-inbox"></i>
          <span>Categoria del Diagnostico </span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="diagnostico">
          <i class="fas fa-fw fa-inbox"></i>
          <span>Diagnostico</span>
        </a>
      </li>

      {{-- Divisor --}}
      <hr class="sidebar-divider">
      {{-- Encabezado --}}
      <div class="sidebar-heading">
          <i class="fa fa-chart-bar"></i>
        Relaciones
      </div>
      {{-- Lista de reportes en el menú --}}
      <li class="nav-item">
        <a class="nav-link" href="productos_apoyos">
            <i class="fas fa-fw fa-poll"></i>
            <span>Productos del Apoyo</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="apoyos_departamento">
              <i class="fas fa-fw fa-poll"></i>
              <span>Apoyos del Departamento</span>
            </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="instituto_departamento">
              <i class="fas fa-fw fa-poll"></i>
              <span>Departamentos del Instituto</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        @endif
        @if (session('user_agent')=='Admin'||session('user_agent')=='Soport'||session('user_agent') == 'SuperAsSo')

         {{-- Encabezado --}}
      {{-- <div class="sidebar-heading">
        <i class="fa fa-chart-bar"></i>
      Bitácoras
    </div> --}}
    {{-- Lista de reportes en el menú --}}
    {{-- <li class="nav-item">
        <a class="nav-link" href="bitacora_errores">
            <i class="fas fa-fw fa-poll"></i>
            <span>Errores</span>
          </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="bitacora_correos">
            <i class="fas fa-fw fa-poll"></i>
            <span>Correos</span>
          </a>
      </li> --}}
      {{-- <hr class="sidebar-divider"> --}}
      @endif
      @if (session('user_agent')=='Admin'||session('user_agent')=='Cont'||session('user_agent')=='DirGen'||session('user_agent')=='TrSo'||session('user_agent') == 'SuperAsSo')
      {{-- Encabezado --}}
      {{-- <div class="sidebar-heading">
          <i class="fa fa-chart-bar"></i>
        Reportes
      </div> --}}
      {{-- Lista de reportes en el menú --}}
      {{-- <li class="nav-item">
          <a class="nav-link" href="reportes">
              <i class="fas fa-fw fa-poll"></i>
              <span>Reportes</span>
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a class="nav-link" href="R-EstadoCuenta">
            <i class="fas fa-fw fa-money-check-alt"></i>
            <span>Estado de cuenta con Proveedores</span>
            </a>
        </li> --}}
      <!-- Divisor -->
      {{-- <hr class="sidebar-divider d-none d-md-block"> --}}
      @endif
      {{-- Reducir Tamaño Menú --}}
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    {{-- Fin de Menú --}}
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          {{-- Icono Menú en línea de navegación --}}
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <ul class="navbar-nav ml-auto">
           @yield('notificaciones')



            <!-- Nav Item - Alerts -->
            {{-- <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <div class="small text-gray-500">
                  @if (auth()->user()->unreadNotifications())
                     <span class="badge badge-warning" id="nNotifications">{{auth()->user()->unreadNotifications()->count()}}</span>
                  @else
                   <span class="badge badge-warning" id="nNotifications">{{auth()->user()->unreadNotifications()->count()}}</span>

                  @endif

                </div> --}}


              {{-- </a> --}}
              <!-- Dropdown - Alerts -->
              {{-- <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Alerts Center
                </h6>

                  <div class="mr-3">
                    <div class="icon-circle bg-primary">
                      <i class="fas fa-file-alt text-white"></i>
                    </div>
                  </div>
                  <div>

                  </div>

                    <span class="badge badge-light">
                   @if(auth()->user()->unreadNotifications()->count()>0)
                    @foreach (auth()->user()->unreadNotifications()->get() as $datos)
                    <a class="dropdown-item d-flex align-items-center" id={{$datos->id}} href={{'/leidas/'.$datos->id}}>
                    <p> {{$datos->data['Notificacion']}}</p>

                    <span class="pull-right text-muted">{{$datos->created_at->diffForHumans()}}</span>
                    </a>
                    @endforeach

                   @else
                   <span class="badge badge-warning">No tienes Notifaciones</span>
                   @endif

                    </span>

              <a class="dropdown-item text-center small text-gray-500" href="{{route('leidas')}}">Show All Alerts</a>
              </div>
            </li>


            <div class="topbar-divider d-none d-sm-block"></div> --}}
            {{-- <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">3+</span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-primary">
                      <i class="fas fa-file-alt text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 12, 2019</div>
                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-success">
                      <i class="fas fa-donate text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 7, 2019</div>
                    $290.29 has been deposited into your account!
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-warning">
                      <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 2, 2019</div>
                    Spending Alert: We've noticed unusually high spending for your account.
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
              </div>
            </li> --}}

            {{-- <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">7</span>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  Message Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div class="font-weight-bold">
                    <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/AU4VPcFN4LE/60x60" alt="">
                    <div class="status-indicator"></div>
                  </div>
                  <div>
                    <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
                    <div class="small text-gray-500">Jae Chun · 1d</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/CS2uCrpNzJY/60x60" alt="">
                    <div class="status-indicator bg-warning"></div>
                  </div>
                  <div>
                    <div class="text-truncate">Last month's report looks great, I am very happy with the progress so far, keep up the good work!</div>
                    <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div>
                    <div class="text-truncate">Am I a good boy? The reason I ask is because someone told me that people say this to all dogs, even if they aren't good...</div>
                    <div class="small text-gray-500">Chicken the Dog · 2w</div>
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div> --}}

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{session('user')}}</span>
                <img class="img-profile rounded-circle" src="{{'../storage/'.session('userImage')}}">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{'perfil/'.session('user_id')}}">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Perfil
                </a>
                {{-- <div class="dropdown-divider"></div> --}}
                <a class="dropdown-item" href="logout" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Cerrar sesión
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div id="title-header" class="d-sm-flex align-items-center justify-content-between mb-4">
            @yield('text')
            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
          </div>

          <!-- Content Row -->
          <div class="row">
            @yield('content')
          </div>



        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Pie de Página -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Presidencia Municipal de Gómez Palacio 2019-2022</span>
          </div>
        </div>
      </footer>
      <!-- Fin Pie de Página -->

    </div>
  </div>

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" id="modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cerrar sesión</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">¿Está usted seguro de querer cerrar la sesión?</div>
        <div class="modal-footer">
          <a class="btn btn-primary" href="/logout">Salir</a>
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>

        </div>
      </div>
    </div>
  </div>

  {{-- Jquery //Necesario para usar bootstrap 4 o inferior --}}
  <script src="../assets/js/jquery.min.js"></script>
  {{-- Bootstrap --}}
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  {{-- Externos Plugins --}}
  <script src="../assets/js/jquery.easing.min.js"></script>
  <script src="../assets/js/alertify.min.js"></script>

  <script src="../assets/js/jquery.mask.min.js"></script>
  <script src="../assets/js/Chart.min.js"></script>
  {{-- Base de otras páginas JS --}}
  @yield('jsDashboard')
  <script src="../assets/js/main.js"></script>
  @yield('script')

</body>
</html>
