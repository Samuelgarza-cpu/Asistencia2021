@extends('base.base')
@section('cssDashboard')
@endsection

@section('text')
Dashboard
@endsection

@section('content')
<!-- Solicitudes Pendientes de Autorizar -->

<div class="col-xl-1"></div>

<div class="col-xl-2 col-md-6 mb-3">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Solicitudes Pendientes de Autorizar</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$sPA}}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-fw fa-file-alt fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Solicitudes Entregadas Pendientes de Autorizar -->
<div class="col-xl-2 col-md-6 mb-3">
    <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Solicitudes Entregadas Pendientes de Autorizar</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$sEPA}}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-fw fa-file-alt fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Solicitudes Rechazadas -->
<div class="col-xl-2 col-md-6 mb-3">
    <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Solicitudes Rechazadas</div>
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$sR}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-fw fa-file-alt fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Solicitudes Autorizadas -->
<div class="col-xl-2 col-md-6 mb-3">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Solicitudes Autorizadas</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$sA}}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-fw fa-file-alt fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Solicitudes Finalizadas -->
<div class="col-xl-2 col-md-12 mb-3">
    <div class="card border-left-secondary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Solicitudes Finalizadas</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$sF}}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-fw fa-file-alt fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Diagrama de solicitudes Mensual -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Solicitudes Mensuales</h6>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-pie pt-4 pb-2">
                <canvas id="myPieChart"></canvas>
            </div>
            <div class="mt-4 text-center small">
                <span class="mr-2">
                    <i class="fas fa-circle text-warning"></i> Pendiente de autorización
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-info"></i> Entregada pero pendiente de autorización
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-danger"></i> Rechazadas
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-success"></i> Autorizadas pendiente de entrega
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-secondary"></i> Finalizadas
                </span>
            </div>
        </div>
    </div>
 <!-- Tipo de solicitud -->
 <div class="col-xl-6 col-lg-6">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tipo de solicitudes Mensual</h6>
        </div>
    </div>
    <div class="card-body">
        <div class="chart-pie pt-4 pb-2">
            <canvas id="myPieChart2"></canvas>
        </div>
        <div class="mt-4 text-center small">
            <span class="mr-2">
                <i class="fas fa-circle text-primary"></i>Producto
            </span>
            <span class="mr-2">
                <i class="fas fa-circle text-success"></i>Vale
            </span>
        </div>
    </div>
 </div>

@endsection


@section('jsDashboard')
<script>
    var sPA = {{$sPA}};
    var sEPA = {{$sEPA}};
    var sR = {{$sR}};
    var sA = {{$sA}};
    var sF = {{$sF}};
    var sT = {{$sT}};
    var sT1 = {{$sT1}};
    var sT2 = {{$sT2}};
</script>
<script src="../assets/js/Dashboard.js"></script>
@endsection