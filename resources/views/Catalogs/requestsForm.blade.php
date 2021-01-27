@extends('base.base')
@section('cssDashboard')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>
<style type="text/css">
  img {
  display: block;
  max-width: 100%;
  }
  .preview {
  overflow: hidden;
  width: 160px;
  height: 160px;
  margin: 10px;
  border: 1px solid red;
  }
  .modal-lg{
  max-width: 1000px !important;
  }
  </style>
@endsection
{{-- @section('text')
@if (session('message'))
    <div class="alert alert-warning">
        {{ session('message') }}
    </div>
@endif
@endsection --}}

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 text-align-center">
        <span class="m-0 font-weight-bold text-primary title-table">Solicitud de apoyo</span>
      </div>
    <div class="card-body">
      <form method="POST" id="formRequest" action="{{Request::url()}}" class="needs-validation" novalidate>
        @csrf
        <input type="hidden" name="action" id="action" value="{{$action}}"/>
        <input type="hidden" name="id" id="id" value="0">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="requestGeneralData-tab" data-toggle="tab" href="#requestGeneralData" role="tab" aria-controls="requestGeneralData" aria-selected="true">Datos generales de solicitud</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="beneficiaryGeneralData-tab" data-toggle="tab" href="#beneficiaryGeneralData" role="tab" aria-controls="beneficiaryGeneralData" aria-selected="false">Datos generales de beneficiario</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="familySituation-tab" data-toggle="tab" href="#familySituation" role="tab" aria-controls="familySituation" aria-selected="false">Situación familiar</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="lifeConditions-tab" data-toggle="tab" href="#lifeConditions" role="tab" aria-controls="lifeConditions" aria-selected="false">Condiciones de vida</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="economicData-tab" data-toggle="tab" href="#economicData" role="tab" aria-controls="economicData" aria-selected="false">Ingresos económicos</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="requestGeneralData" role="tabpanel" aria-labelledby="requestGeneralData-tab">
          <br>
          <div class="form-row">
            <div class="form-group col-md-4 files-div">
              @if(isset($petitioner->petitionerImage))
                <span class="file petitionerImage">
                  <input type="file" name="petitionerImage" id="petitionerImage" class="form-control imagePetitioner" accept="image/*">
                </span>
                <label for="petitionerImage" class="label-img">
                  <span>{{$petitioner['petitionerImage']}}</span>
                </label>
              @else
                <span class="file petitionerImage">
                  <input type="file" name="petitionerImage" accept="image/*" id="petitionerImage" class="form-control imagePetitioner" required>
                  <div class="invalid-feedback">
                    Favor de ingresar la imagen del solicitante
                  </div>
                </span>
                <label for="petitionerImage" class="label-img">
                  <span>Subir la imagen del solicitante</span>
                </label>
              @endif
            </div>
            <div class="form-group col-md-6 files-div">
              @if(isset($petitioner->imageSRC))
                <img id="imagenPrevisualizacion" src="{{$petitioner['imageSRC']}}" class="file-img">
              @else
                <img id="imagenPrevisualizacion" class="file-img">
              @endif
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-2">
              <label for="date">Fecha de solicitud</label>
              @if(isset($requisition->date))
                <input type="date" class="form-control" value="{{$requisition->date}}" id="date" name="date" required>
              @else
                <input type="date" class="form-control" value="{{date("Y-m-d")}}" id="date" name="date" required>
              @endif
            </div>
            <div class="form-group col-md-4">
              <label for="petitioner">Solicitante</label>
              @if(isset($petitioner->petitioner))
                <input type="text" class="form-control" id="petitioner" name="petitioner" placeholder="Ingrese el nombre del solicitante" required value="{{$petitioner->petitioner}}">
              @else
                <input type="text" class="form-control" id="petitioner" name="petitioner" placeholder="Ingrese el nombre del solicitante" required>
              @endif
              <div class="invalid-feedback">
                  Favor de ingresar el nombre del solicitante
              </div>
            </div>
            <div class="form-group col-md-6">
              <label for="curpPetitioner">Curp del solicitante</label>
              <div class="input-group">
                @if(isset($petitioner->curpPetitioner))
                  <input type="text" class="form-control" id="curpPetitioner1" name="curpPetitioner1" data-mask="SSSS000000SSSSSSAA" placeholder="Ingrese la curp del solicitante" required value="{{$petitioner->curpPetitioner}}">
                @else
                  <input type="text" class="form-control" id="curpPetitioner1" name="curpPetitioner1" data-mask="SSSS000000SSSSSSAA" placeholder="Ingrese la curp del solicitante" required>
                @endif
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="verifyCurpPetitioner(this)" id="check-1">Verificar</button>
                </div>
                <div class="invalid-feedback">
                  Favor de ingresar la CURP del solicitante
                </div>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-2">
              <label for="type">Forma del apoyo</label>
              <select id="type" name="type" class="form-control" required>
                @if(isset($requisition->type))
                  <option value="" {{"" == $request->type ? 'selected' : '' }}>selecciona...</option>
                  <option value="especie" {{"especie" == $request->type ? 'selected' : '' }}>Producto</option>
                  <option value="efectivo" {{"efectivo" == $request->type ? 'selected' : '' }}>Vale</option>
                @else
                  <option value="">selecciona...</option>
                  <option value="especie">Producto</option>
                  <option value="efectivo">Vale</option>
                  {{-- <option value="descuento">Descuento</option> --}}
                @endif
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="supports_id">Categoría del apoyo</label>
              <select id="supports_id" name="supports_id" class="form-control" disabled required>
                <option value="">selecciona...</option>
                @if(isset($requisition->supports_id))
                  @if(isset($supports))
                    @foreach($supports as $element)
                      <option value="{{$element['id']}}" {{$element['id'] == $requisition->supports_id ? 'selected' : ''}}>{{$element['name']}}</option>
                    @endforeach
                  @endif
                @else
                  @if(isset($supports))
                    @foreach($supports as $element)
                      <option value="{{$element['id']}}">{{$element['name']}}</option>
                    @endforeach
                  @endif
                @endif
              </select>
            </div>
            <div class="form-group col-md-4">
              <label for="categories_id">Categoría de los productos</label>
              <select id="categories_id" disabled name="categories_id" class="form-control" required>
                <option value="">selecciona...</option>
                @if(isset($requisition->categories_id))
                  @if(isset($categories))
                    @foreach($categories as $element)
                      <option value="{{$element['id']}}" {{$element['id'] == $requisition->categories_id ? 'selected' : ''}}>{{$element['name']}}</option>
                    @endforeach
                  @endif
                @endif
              </select>
            </div>
            <div class="form-group col-md-4">
              <label for="reason">Caso</label>
              @if(isset($requisition->case))
                <input type="text" class="form-control" id="reason" name="reason" placeholder="Ingrese la razón de la solicitud" required value="{{$requisition->case}}">
              @else
                <input type="text" class="form-control" id="reason" name="reason" placeholder="Ingrese la razón de la solicitud" required>
              @endif
              <div class="invalid-feedback">
                Favor de ingresar el caso de la solicitud
              </div>
            </div>
          </div>

          <div>
            {{-- <span class="m-0 font-weight-bold text-primary title-table">Productos Solicitantes
                <button type="button" id="addProduct" class="btn btn-primary float-right">Agregar</button>
            </span> --}}
            @if(isset($request))
              <input type="hidden" name="countProduct" id="countProduct" value="{{$request['countProduct']}}">
              <input type="hidden" name="countTotalP" id="countTotalP" value="{{$request['countProduct']}}">
              <input type="hidden" name="fieldsProducts" id="fieldsProducts" value="{{$request['countProduct']}}">
            @else
              <input type="hidden" name="countProduct" id="countProduct" value="1">
              <input type="hidden" name="countTotalP" id="countTotalP" value="1">
              <input type="hidden" name="fieldsProducts" id="fieldsProducts" value="1">
            @endif
          </div>

          {{-- <div class="headerAppend">Producto Principal</div> --}}
          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="suppliers_id1">Proveedor</label>
              <select id="suppliers_id1" name="suppliers_id1" disabled class="form-control" required>
                <option value="">Selecciona...</option>
                  @if(isset($requisition->suppliers_id1))
                    @if(isset($suppliers))
                      @foreach($suppliers as $element)
                        <option value="{{$element['id']}}" {{$element['id'] == $requisition['suppliers_id1'] ? 'selected' : '' }}>{{$element['companyname']}}</option>
                      @endforeach
                    @else
                    @foreach($suppliers as $element)
                      <option value="{{$element['id']}}">{{$element['companyname']}}</option>
                    @endforeach
                  @endif
                @endif
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="products_id1">Producto</label>
              <select id="products_id1" name="products_id1" disabled class="form-control" required>
                <option value="">seleccione...</option>
                @if(isset($requisition->products_id1))
                  @if(isset($products))
                    @foreach($products as $element)
                      <option value="{{$element['id']}}" {{$element['id'] == $requisition['products_id1'] ? 'selected' : '' }}>{{$element['name']}}</option>
                    @endforeach
                  @else
                    @foreach($products as $element)
                      <option value="{{$element['id']}}">{{$element['name']}}</option>
                    @endforeach
                  @endif
                @endif
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="unitPrice1">Precio Unitario</label>
              @if(isset($requisition->unitPrice1))
                <input type="text" class="form-control" disabled id="unitPrice1" name="unitPrice1" placeholder="Ingresar el precio del producto" required value="{{$supplier['unitPrice1']}}">
              @else
                <input type="text" class="form-control" id="unitPrice1" disabled name="unitPrice1" placeholder="Ingresar el precio del producto" required value="0">
              @endif
              <div class="invalid-feedback">
                Favor de ingresar el precio del producto
              </div>
            </div>
            <div class="form-group col-md-2">
              <label for="qty1">Cantidad</label>
              @if(isset($requisition->qty1))
                <input type="text" class="form-control" disabled id="qty1" name="qty1" placeholder="Ingresar la cantidad de productos" required value="{{$supplier['qty1']}}">
              @else
                <input type="text" class="form-control" id="qty1" disabled name="qty1" placeholder="Ingresar la cantidad de productos" required value="0">
              @endif
            </div>
            <div class="form-group col-md-2">
              <label for="totalPrice1">Costo Total</label>
              <div class="input-group">
                @if(isset($requisition->totalPrice1))
                  <input type="text" class="form-control" id="totalPrice1" name="totalPrice1" disabled required value="{{$supplier['totalPrice1']}}">
                @else
                  <input type="text" class="form-control" id="totalPrice1" name="totalPrice1" disabled required value="0">
                @endif
              </div>
            </div>
          </div>
          @if(isset($request))
            @for($i = 2 ; $i <= $request['countProduct'];$i++)
              <div id="{{'fDP-'.$i}}">
                <hr>
                <div class="headerAppend">Producto {{$i}}
                  <button type="button" id="{{'deleteProduct-'.$i}}" class="btn float-right" onclick="deleteProduct(this)">
                    <i class="fas fa-trash-alt fa-2x colorIcon"></i>
                  </button>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label for="{{'suppliers_id'.$i}}">Proveedor</label>
                    <select id="{{'suppliers_id'.$i}}" disabled name="{{'suppliers_id'.$i}}" class="form-control" required>
                      <option value="">Selecciona...</option>
                        @if(isset($suppliers))
                          @foreach($suppliers['suppliers'.$i] as $element)
                            <option value="{{$element['id']}}" {{$element['id'] == $requisition['suppliers_id'.$i] ? 'selected' : '' }}>{{$element['companyname']}}</option>
                          @endforeach
                        @else
                          @foreach($suppliers as $element)
                            <option value="{{$element['id']}}">{{$element['companyname']}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="{{'products_id'.$i}}">Producto</label>
                    <select id="{{'products_id'.$i}}" disabled name="{{'products_id'.$i}}" class="form-control" required>
                      <option value="">seleccione...</option>
                      @if(isset($products))
                          @foreach($products['product'.$i] as $element)
                            <option value="{{$element['id']}}" {{$element['id'] == $requisition['products_id'.$i] ? 'selected' : '' }}>{{$element['name']}}</option>
                          @endforeach
                      @else
                          @foreach($products as $element)
                            <option value="{{$element['id']}}">{{$element['name']}}</option>
                          @endforeach
                      @endif
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="{{'unitPrice'.$i}}">Precio Unitario</label>
                    @if(isset($requisition->products))
                      <input type="text" class="form-control" id="{{'unitPrice'.$i}}" disabled name="{{'unitPrice'.$i}}" placeholder="Ingresar el precio del producto" required value="{{$requisition['unitPrice'.$i]}}">
                    @else
                      <input type="text" class="form-control" id="{{'unitPrice'.$i}}" disabled name="{{'unitPrice'.$i}}" placeholder="Ingresar el precio del producto" required value="0">
                    @endif
                    <div class="invalid-feedback">
                      Favor de ingresar el precio del producto
                    </div>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="{{'qty'.$i}}">Cantidad</label>
                    @if(isset($requisition->products))
                      <input type="text" class="form-control" id="{{'qty'.$i}}" disabled name="{{'qty'.$i}}" placeholder="Ingresar la cantidad de productos" required value="{{$requisition['qty'.$i]}}">
                    @else
                      <input type="text" class="form-control" id="{{'qty'.$i}}" disabled name="{{'qty'.$i}}" placeholder="Ingresar la cantidad de productos" required value="0">
                    @endif
                  </div>
                  <div class="form-group col-md-2">
                    <label for="{{'totalPrice'.$i}}">Costo Total</label>
                    @if(isset($requisition->products))
                      <input type="text" class="form-control" id="{{'totalPrice'.$i}}" disabled name="{{'totalPrice'.$i}}" disabled required value="{{$requisition['totalPrice'.$i]}}">
                    @else
                      <input type="text" class="form-control" id="{{'totalPrice'.$i}}" disabled name="{{'totalPrice'.$i}}" disabled required value="0">
                    @endif
                    <div class="invalid-feedback">
                      Favor de ingresar el total del costo
                    </div>
                  </div>
                </div>
              </div>
            @endfor
          @endif
          <div id="products"></div>
          <hr>
          <a href="solicitudes"  class="btn btn-primary float-right">Cancelar</a>
          <button type="button" id="requestGeneralData-1"  onclick="nextNavTab(this)" class="btn btn-primary float-right" style="margin-right: 3px;">Siguiente</button>
          <button type="button" onclick="saveAfter()" class="btn btn-primary float-right" style="margin-right: 3px;">Guardar para después</button>
        </div>

        <div class="tab-pane fade" id="beneficiaryGeneralData" role="tabpanel" aria-labelledby="beneficiaryGeneralData-tab">
          <br>
          <div>
            <span class="m-0 font-weight-bold text-primary title-table">Beneficiarios
                <button type="button" id="addBeneficiary" class="btn btn-primary float-right">Agregar</button>
            </span>
            @if(isset($request))
              <input type="hidden" name="countBeneficiary" id="countBeneficiary" value="{{$request['countBeneficiary']}}">
              <input type="hidden" name="countTotalB" id="countTotalB" value="{{$request['countBeneficiary']}}">
            @else
              <input type="hidden" name="countBeneficiary" id="countBeneficiary" value="1">
              <input type="hidden" name="countTotalB" id="countTotalB" value="1">
            @endif
          </div>
          <hr>
          <div class="headerAppend">Beneficiario Principal</div>
          <div class="form-row">
            <div class="form-group col-md-7">
              <label for="curpbeneficiary1">Curp del beneficiario</label>
              <div class="input-group">
                @if(isset($requisition->beneficiaries))
                  <input type="text" class="form-control" id="curpbeneficiary1" name="curpbeneficiary1" data-mask="SSSS000000SSSSSSAA" placeholder="Ingrese la curp del beneficiario" required value="{{$requisition['beneficiaries']->curpbeneficiary1}}">
                @else
                  <input type="text" class="form-control" id="curpbeneficiary1" name="curpbeneficiary1" data-mask="SSSS000000SSSSSSAA" placeholder="Ingrese la curp del beneficiario" required>
                @endif
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="verifyCurp(this)" id="check-1">Verificar</button>
                </div>
                <div class="invalid-feedback">
                  Favor de ingresar la CURP del beneficiario
                </div>
              </div>
            </div>
            <div class="form-group col-md-2">
                <label for="agebeneficiary1">Edad</label>
                @if(isset($requisition->beneficiaries))
                  <input type="text" class="form-control" id="agebeneficiary1" data-mask="000" name="agebeneficiary1" placeholder="Ingrese la edad del beneficiario" required value="{{$requisition['beneficiaries']->agebeneficiary1}}">
                @else
                  <input type="text" class="form-control" id="agebeneficiary1" data-mask="000" name="agebeneficiary1" placeholder="Ingrese la edad del beneficiario" required>
                @endif
            </div>
            <div class="form-group col-md-3">
              <label for="phonenumber1">Número telefónico</label>
                @if(isset($requisition->beneficiaries))
                  <input type="text" class="form-control" id="phonenumber1" name="phonenumber1" placeholder="Ingresar número telefónico del beneficiario" data-mask="000-000-0000" value="{{$requisition['beneficiaries']->phonenumber1}} required>
                @else
                  <input type="text" class="form-control" id="phonenumber1" name="phonenumber1" placeholder="Ingresar número telefónico del beneficiario" data-mask="000-000-0000" required>
                @endif
                  <div class="invalid-feedback">
                      Favor de ingresar el número telefónico del beneficiario
                  </div>
            </div>
            {{-- <div class="form-group col-md-2">
              <label for="statusBeneficiary1">¿Es Familiar?</label>
              <div class="custom-control custom-switch">
                @if(isset($requisition->beneficiaries))
                  <input type="checkbox" class="custom-control-input" name="statusBeneficiary1" id="statusBeneficiary1" {{$requisition["beneficiaries"]->statusBeneficiary1 == 1 ? 'checked' : ''}}>
                @else
                  <input type="checkbox" class="custom-control-input" name="statusBeneficiary1" id="statusBeneficiary1">
                @endif
                <label class="custom-control-label" for="statusBeneficiary1">No/Si</label>
              </div>
            </div> --}}
          </div>
         <div class="form-row">
          <div class="form-group col-md-4">
            <label for="namebeneficiary1">Nombre(s)</label>
            @if(isset($requisition->beneficiaries))
              <input type="text" class="form-control" id="namebeneficiary1" name="namebeneficiary1" value="{{$requisition['beneficiaries']->namebeneficiary1}}" placeholder="Ingrese el nombre del beneficiario" required>
            @else
              <input type="text" class="form-control" id="namebeneficiary1" name="namebeneficiary1" placeholder="Ingrese el nombre del beneficiario" required>
            @endif
            <div class="invalid-feedback">
                Favor de ingresar el nombre del beneficiario
            </div>
          </div>
          <div class="form-group col-md-4">
            <label for="lastNamebeneficiary1">Apellido paterno</label>
            @if(isset($requisition->beneficiaries))
              <input type="text" class="form-control" id="lastNamebeneficiary1" name="lastNamebeneficiary1" value="{{$requisition['beneficiaries']->lastNamebeneficiary1}}" placeholder="Ingrese el apellido paterno del beneficiario" required>
            @else
              <input type="text" class="form-control" id="lastNamebeneficiary1" name="lastNamebeneficiary1" placeholder="Ingrese el apellido paterno del beneficiario" required>
            @endif
            <div class="invalid-feedback">
                Favor de ingresar el apellido paterno del beneficiario
            </div>
          </div>
          <div class="form-group col-md-4">
            <label for="secondLastNamebeneficiary1">Apellido materno</label>
            @if(isset($requisition->beneficiaries))
              <input type="text" class="form-control" id="secondLastNamebeneficiary1" name="secondLastNamebeneficiary1" placeholder="Ingrese el apellido Materno del beneficiario" value="{{$requisition['beneficiaries']->secondLastName1}}" required>
            @else
              <input type="text" class="form-control" id="secondLastNamebeneficiary1" name="secondLastNamebeneficiary1" placeholder="Ingrese el apellido Materno del beneficiario" required>
            @endif
            <div class="invalid-feedback">
                Favor de ingresar el apellido materno del beneficiario
            </div>
          </div>
         </div>
         <div class="form-row">
          <div class="form-group col-md-4">
            <label for="civilStatusbeneficiary1">Edo. Civil</label>
              <select id="civilStatusbeneficiary1" name="civilStatusbeneficiary1" class="form-control" required>
              @if(isset($requisition->beneficiaries))
                  <option value="" {{$requisition['beneficiaries']->civilStatusbeneficiary1 == ""}}>Selecciona...</option>
                  <option value="soltero(a)" {{$requisition['beneficiaries']->civilStatusbeneficiary1 == "soltero(a)"}}>Soltero(a)</option>
                  <option value="casado(a)" {{$requisition['beneficiaries']->civilStatusbeneficiary1 == "casado(a)"}}>Casado(a)</option>
                  <option value="divorciado(a)" {{$requisition['beneficiaries']->civilStatusbeneficiary1 == "divorciado(a)"}}>Divorciado(a)</option>
                  <option value="viudo(a)" {{$requisition['beneficiaries']->civilStatusbeneficiary1 == "viudo(a)"}}>Viudo(a)</option>
                  <option value="unionLibre" {{$requisition['beneficiaries']->civilStatusbeneficiary1 == "unionLibre"}}>Unión libre</option>
              @else
                  <option value="">Selecciona...</option>
                  <option value="soltero(a)">Soltero(a)</option>
                  <option value="casado(a)">Casado(a)</option>
                  <option value="divorciado(a)">Divorciado(a)</option>
                  <option value="viudo(a)">Viudo(a)</option>
                  <option value="unionLibre">Unión libre</option>
              @endif
              </select>
            </div>
          <div class="form-group col-md-4">
            <label for="scholarShipbeneficiary1">Escolaridad</label>
                <select id="scholarShipbeneficiary1" name="scholarShipbeneficiary1" class="form-control" required>
                  <option value="">selecciona...</option>
                  @if(isset($requisition->beneficiaries))
                    <option value="sinEstudios" {{$requisition['beneficiaries']->scholarShipbeneficiary1 == "sinEstudios"}}>Sin estudios</option>
                    <option value="primaria" {{$requisition['beneficiaries']->scholarShipbeneficiary1 == "primaria"}}>Primaria</option>
                    <option value="secundaria" {{$requisition['beneficiaries']->scholarShipbeneficiary1 == "secundaria"}}>Secundaria</option>
                    <option value="bachillerato/tecnico" {{$requisition['beneficiaries']->scholarShipbeneficiary1 == "bachillerato/tecnico"}}>Bachillerato/Técnico</option>
                    <option value="licenciatura/profesional" {{$requisition['beneficiaries']->scholarShipbeneficiary1 == "licenciatura/profesional"}}>Licenciatura/Profesional</option>
                    <option value="posgrado" {{$requisition['beneficiaries']->scholarShipbeneficiary1 == "posgrado"}}>Posgrado</option>
                  @else
                    <option value="sinEstudios">Sin estudios</option>
                    <option value="primaria">Primaria</option>
                    <option value="secundaria">Secundaria</option>
                    <option value="bachillerato/tecnico">Bachillerato/Técnico</option>
                    <option value="licenciatura/profesional">Licenciatura/Profesional</option>
                    <option value="posgrado">Posgrado</option>
                  @endif
                </select>
          </div>
          <div class="form-group col-md-4">
            <label for="employments_idbeneficiary1">Ocupación</label>
            <select id="employments_idbeneficiary1" name="employments_idbeneficiary1" class="form-control" required>
                <option value="">selecciona...</option>
                @if(isset($requisition->beneficiaries))
                  @if(isset($employments))
                    @foreach($employments as $element)
                      <option value="{{$element['id']}}" {{$requisition['beneficiaries']->employments_idbeneficiary1 == $element['id']}}>{{$element['name']}}</option>
                    @endforeach
                  @endif
                @else
                  @if(isset($employments))
                    @foreach($employments as $element)
                      <option value="{{$element['id']}}">{{$element['name']}}</option>
                    @endforeach
                  @endif
                @endif
            </select>
          </div>
         </div>
         <div>
          <span class="m-0 font-weight-bold text-primary headerAppend">Diagnostico de Beneficiario
              <button type="button" id="addDiagnosticBeneficiary1" class="btn btn-primary float-right">Agregar</button>
          </span>
          @if(isset($request))
            <input type="hidden" name="countDiagnosticBeneficiary1" id="countDiagnosticBeneficiary1" value="{{$request['countDiagnosticBeneficiary1']}}">
            <input type="hidden" name="countTotalDB1" id="countTotalDB1" value="{{$request['countDiagnosticBeneficiary1']}}">
          @else
            <input type="hidden" name="countDiagnosticBeneficiary1" id="countDiagnosticBeneficiary1" value="1">
            <input type="hidden" name="countTotalDB1" id="countTotalDB1" value="1">
          @endif
        </div>
         <div class="form-row">
          <div class="form-group col-md-6">
            <label for="disabilitycategories1_1">Categoria del Diagnostico</label>
              <select id="disabilitycategories1_1" name="disabilitycategories1_1" class="form-control" required>
                <option value="">selecciona...</option>
                @if(isset($categotydisability))
                @foreach($categotydisability as $element)
                  <option value="{{$element['id']}}">{{$element['name']}}</option>
                @endforeach
              @endif
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="disability1_1">Diagnostico</label>
                <select id="disability1_1" name="disability1_1" disabled class="form-control" required>
                  <option value="">selecciona...</option>
                  @if(isset($disabilities))
                  @foreach($disabilities as $element)
                    <option value="{{$element['id']}}">{{$element['name']}}</option>
                  @endforeach
                @endif
                </select>
              </div>
          </div>
          <div id="requestDiagnostic1"></div>



          @if(isset($requisition))
          @for($i = 2 ; $i <= $requisition['countBeneficiary'];$i++)
            <div id="{{'fDB-'.$i}}">
              <hr/>
                <button type="button" id="{{'deleteB-'.$i}}" class="btn float-right" onclick="deleteB(this)">
                  <i class="fas fa-trash-alt fa-2x colorIcon"></i>
                </button>
                <div class="form-row">
                  <div class="form-group col-md-5">
                    <label for="{{'curpbeneficiary'.$i}}">Curp del beneficiario</label>
                    <div class="input-group">
                      @if(isset($requisition->beneficiaries))
                        <input type="text" class="form-control" id="{{'curpbeneficiary'.$i}}" name="{{'curpbeneficiary'.$i}}" data-mask="SSSS000000SSSSSSAA" placeholder="Ingrese la curp del beneficiario" required value="{{$requisition['beneficiaries']['curpbeneficiary'.$i]}}">
                      @else
                        <input type="text" class="form-control" id="{{'curpbeneficiary'.$i}}" name="{{'curpbeneficiary'.$i}}" data-mask="SSSS000000SSSSSSAA" placeholder="Ingrese la curp del beneficiario" required>
                      @endif
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="verifyCurp(this)" id="{{'check-'.$i}}">Verificar</button>
                      </div>
                      <div class="invalid-feedback">
                        Favor de ingresar la CURP del beneficiario
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-2">
                      <label for="{{'agebeneficiary'.$i}}">Edad</label>
                      @if(isset($requisition->beneficiaries))
                        <input type="text" class="form-control" id="{{'agebeneficiary'.$i}}" data-mask="000" name="{{'agebeneficiary'.$i}}" placeholder="Ingrese la edad del beneficiario" required value="{{$requisition['beneficiaries']['agebeneficiary'.$i]}}">
                      @else
                        <input type="text" class="form-control" id="{{'agebeneficiary'.$i}}" data-mask="000" name="{{'agebeneficiary'.$i}}" placeholder="Ingrese la edad del beneficiario" required>
                      @endif
                  </div>
                  <div class="form-group col-md-3">
                    <label for="{{'phonenumber'.$i}}">Número telefónico</label>
                      @if(isset($requisition->beneficiaries))
                        <input type="text" class="form-control" id="{{'phonenumber'.$i}}" name="{{'phonenumber'.$i}}" placeholder="Ingresar número telefónico del beneficiario" data-mask="000-000-0000" value="{{$requisition['beneficiaries']['phonenumber'.$i]}}" required>
                      @else
                        <input type="text" class="form-control" id="{{'phonenumber'.$i}}" name="{{'phonenumber'.$i}}" placeholder="Ingresar número telefónico del beneficiario" data-mask="000-000-0000" required>
                      @endif
                        <div class="invalid-feedback">
                            Favor de ingresar el número telefónico del beneficiario
                        </div>
                  </div>
                  {{-- <div class="form-group col-md-2">
                    <label for="{{'statusBeneficiary'.$i}}">¿Es Familiar?</label>
                    <div class="custom-control custom-switch">
                      @if(isset($requisition->beneficiaries))
                        <input type="checkbox" class="custom-control-input" name="{{'statusBeneficiary'.$i}}" id="{{'statusBeneficiary'.$i}}" {{$requisition["beneficiaries"]['statusBeneficiary'.$i] == 1 ? 'checked' : ''}}>
                      @else
                        <input type="checkbox" class="custom-control-input" name="{{'statusBeneficiary'.$i}}" id="{{'statusBeneficiary'.$i}}">
                      @endif
                      <label class="custom-control-label" for="{{'statusBeneficiary'.$i}}">No/Si</label>
                    </div>
                  </div> --}}
                </div>

                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="{{'namebeneficiary'.$i}}">Nombre(s)</label>
                    @if(isset($requisition->beneficiaries))
                      <input type="text" class="form-control" id="{{'namebeneficiary'.$i}}" name="{{'namebeneficiary'.$i}}" value="{{$requisition['beneficiaries']['namebeneficiary'.$i]}}" placeholder="Ingrese el nombre del beneficiario" required>
                    @else
                      <input type="text" class="form-control" id="{{'namebeneficiary'.$i}}" name="{{'namebeneficiary'.$i}}" placeholder="Ingrese el nombre del beneficiario" required>
                    @endif
                    <div class="invalid-feedback">
                        Favor de ingresar el nombre del beneficiario
                    </div>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="{{'lastNamebeneficiary'.$i}}">Apellido paterno</label>
                    @if(isset($requisition->beneficiaries))
                      <input type="text" class="form-control" id="{{'lastNamebeneficiary'.$i}}" name="{{'lastNamebeneficiary'.$i}}" value="{{$requisition['beneficiaries']['lastNamebeneficiary'.$i]}}" placeholder="Ingrese el apellido paterno del beneficiario" required>
                    @else
                      <input type="text" class="form-control" id="{{'lastNamebeneficiary'.$i}}" name="{{'lastNamebeneficiary'.$i}}" placeholder="Ingrese el apellido paterno del beneficiario" required>
                    @endif
                    <div class="invalid-feedback">
                        Favor de ingresar el apellido paterno del beneficiario
                    </div>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="{{'secondLastNamebeneficiary'.$i}}">Apellido materno</label>
                    @if(isset($requisition->beneficiaries))
                      <input type="text" class="form-control" id="{{'secondLastNamebeneficiary'.$i}}" name="{{'secondLastNamebeneficiary'.$i}}" placeholder="Ingrese el apellido Materno del beneficiario" value="{{$requisition['beneficiaries']['secondLastName'.$i]}}" required>
                    @else
                      <input type="text" class="form-control" id="{{'secondLastNamebeneficiary'.$i}}" name="{{'secondLastNamebeneficiary'.$i}}" placeholder="Ingrese el apellido Materno del beneficiario" required>
                    @endif
                    <div class="invalid-feedback">
                        Favor de ingresar el apellido materno del beneficiario
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="{{'civilStatusbeneficiary'.$i}}">Edo. Civil</label>
                      <select id="{{'civilStatusbeneficiary'.$i}}" name="{{'civilStatusbeneficiary'.$i}}" class="form-control" required>
                      @if(isset($requisition->beneficiaries))
                          <option value="" {{$requisition['beneficiaries']['civilStatusbeneficiary'.$i] == ""}}>Selecciona...</option>
                          <option value="soltero(a)" {{$requisition['beneficiaries']['civilStatusbeneficiary'.$i] == "soltero(a)"}}>Soltero(a)</option>
                          <option value="casado(a)" {{$requisition['beneficiaries']['civilStatusbeneficiary'.$i] == "casado(a)"}}>Casado(a)</option>
                          <option value="divorciado(a)" {{$requisition['beneficiaries']['civilStatusbeneficiary'.$i] == "divorciado(a)"}}>Divorciado(a)</option>
                          <option value="viudo(a)" {{$requisition['beneficiaries']['civilStatusbeneficiary'.$i] == "viudo(a)"}}>Viudo(a)</option>
                          <option value="unionLibre" {{$requisition['beneficiaries']['civilStatusbeneficiary'.$i] == "unionLibre"}}>Unión libre</option>
                      @else
                          <option value="">Selecciona...</option>
                          <option value="soltero(a)">Soltero(a)</option>
                          <option value="casado(a)">Casado(a)</option>
                          <option value="divorciado(a)">Divorciado(a)</option>
                          <option value="viudo(a)">Viudo(a)</option>
                          <option value="unionLibre">Unión libre</option>
                      @endif
                      </select>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="{{'scholarShipbeneficiary'.$i}}">Escolaridad</label>
                        <select id="{{'scholarShipbeneficiary'.$i}}" name="{{'scholarShipbeneficiary'.$i}}" class="form-control" required>
                          @if(isset($requisition->beneficiaries))
                            <option value="" {{$requisition['beneficiaries']['scholarShipbeneficiary'.$i] == ""}}>selecciona...</option>
                            <option value="sinEstudios" {{$requisition['beneficiaries']['scholarShipbeneficiary'.$i] == "sinEstudios"}}>Sin estudios</option>
                            <option value="primaria" {{$requisition['beneficiaries']['scholarShipbeneficiary'.$i] == "primaria"}}>Primaria</option>
                            <option value="secundaria" {{$requisition['beneficiaries']['scholarShipbeneficiary'.$i] == "secundaria"}}>Secundaria</option>
                            <option value="bachillerato/tecnico" {{$requisition['beneficiaries']['scholarShipbeneficiary'.$i] == "bachillerato/tecnico"}}>Bachillerato/Técnico</option>
                            <option value="licenciatura/profesional" {{$requisition['beneficiaries']['scholarShipbeneficiary'.$i] == "licenciatura/profesional"}}>Licenciatura/Profesional</option>
                            <option value="posgrado" {{$requisition['beneficiaries']['scholarShipbeneficiary'.$i] == "posgrado"}}>Posgrado</option>
                          @else
                            <option value="">selecciona...</option>
                            <option value="sinEstudios">Sin estudios</option>
                            <option value="primaria">Primaria</option>
                            <option value="secundaria">Secundaria</option>
                            <option value="bachillerato/tecnico">Bachillerato/Técnico</option>
                            <option value="licenciatura/profesional">Licenciatura/Profesional</option>
                            <option value="posgrado">Posgrado</option>
                          @endif
                        </select>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="{{'employments_idbeneficiary'.$i}}">Ocupación</label>
                    <select id="{{'employments_idbeneficiary'.$i}}" name="{{'employments_idbeneficiary'.$i}}" class="form-control" required>
                        <option value="">selecciona...</option>
                        @if(isset($requisition->beneficiaries))
                          @if(isset($employments))
                            @foreach($employments as $element)
                              <option value="{{$element['id']}}" {{$requisition['beneficiaries']['employments_idbeneficiary'.$i] == $element['id']}}>{{$element['name']}}</option>
                            @endforeach
                          @endif
                        @else
                          @if(isset($employments))
                            @foreach($employments as $element)
                              <option value="{{$element['id']}}">{{$element['name']}}</option>
                            @endforeach
                          @endif
                        @endif
                    </select>
                 </div>
              </div>


            </div>
          @endfor
         @endif
         <div id="requests"></div>

         <hr/>
         <div class="headerAppend">
          Dirección
         </div>
         <div class="form-row">
          <div class="form-group col-md-3">
            <label for="street">Calle</label>
            @if(isset($requisition->street))
              <input type="text" class="form-control" id="street" name="street" placeholder="Ingresar la calle" value="{{$requisition->street}}" required>
            @else
              <input type="text" class="form-control" id="street" name="street" placeholder="Ingresar la calle" required>
            @endif
            <div class="invalid-feedback">
              Favor de ingresar la calle de la dirección del beneficiario
            </div>
          </div>
          <div class="form-group col-md-3">
            <label for="externalNumber">Número externo</label>
            @if(isset($requisition->externalNumber))
              <input type="text" class="form-control" id="externalNumber" name="externalNumber" placeholder="Ingresar el número externo" value="{{$requisition->externalNumber}}" required>
            @else
              <input type="text" class="form-control" id="externalNumber" name="externalNumber" placeholder="Ingresar el número externo" required>
            @endif
            <div class="invalid-feedback">
              Favor de ingresar el número externo de la dirección del beneficiario
            </div>
          </div>
          <div class="form-group col-md-3">
            <label for="internalNumber">Número interno</label>
            @if(isset($requisition->internalNumber))
              <input type="text" class="form-control" id="internalNumber" name="internalNumber" value="{{$requisition->internalNumber}}" placeholder="Ingresar el número interno">
            @else
              <input type="text" class="form-control" id="internalNumber" name="internalNumber" placeholder="Ingresar el número interno">
            @endif
          </div>
          <div class="form-group col-md-3">
            <label for="postalCode1">Código Postal</label>
            <div class="input-group">
              @if(isset($requisition->postalCode))
                <input type="text" class="form-control" id="postalCode1" name="postalCode1" placeholder="Ingresa tú numero postal" required data-mask="00000" value="{{$requisition->postalCode}}">
              @else
                <input type="text" class="form-control" id="postalCode1" name="postalCode1" placeholder="Ingresa tú numero postal" required data-mask="00000">
              @endif
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="filterCP(this)" id="filter-1">Filtrar</button>
              </div>
              <div class="invalid-feedback">
                Favor de ingresar el código postal de la dirección del proveedor
              </div>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="communities_id1">Colonia</label>
            <select id="communities_id1" name="communities_id1" disabled class="form-control" required>
              <option value="">Selecciona...</option>
              @if(isset($requisition->communities))
                @foreach($requisition->communities as $element)
                  <option value="{{$element['id']}}" {{$element['id'] == $requisition['communities_id'] ? 'selected' : '' }}>{{$element['name']}}</option>
                @endforeach
              @endif
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="municipalities_id1">Municipio</label>
            <select id="municipalities_id1" name="municipalities_id1" class="form-control" disabled required>
              <option value="">Selecciona...</option>
              @if(isset($requisition->municipalities_id))
                  <option value="{{$requisition->municipalities_id}}">{{$requisition->municipalities_name}}</option>
              @endif
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="states_id1">Estado</label>
            <select id="states_id1" name="states_id1" class="form-control" disabled required>
              <option value="">Selecciona...</option>
              @if(isset($requisition->states_id))
                  <option value="{{$requisition->states_id}}">{{$requisition->states_name}}</option>
              @endif
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="area">Área</label>
            @if(isset($requisition->area))
              <input type="text" class="form-control" id="area" name="area" placeholder="Ingresar el número del área" value="{{$requisition->area}}" required>
            @else
              <input type="text" class="form-control" id="area" name="area" placeholder="Ingresar el número del área" required>
            @endif
            <div class="invalid-feedback">
              Favor de ingresar el número del área
            </div>
          </div>
        </div>
        <a href="solicitudes"  class="btn btn-primary float-right">Cancelar</a>
        <button type="button" id="beneficiaryGeneralData-1"  onclick="nextNavTab(this)" class="btn btn-primary float-right" style="margin-right: 3px;">Siguiente</button>
        <button type="button" id="beneficiaryGeneralData-2"  onclick="nextNavTab(this)" class="btn btn-primary float-right" style="margin-right: 3px;">Anterior</button>
        <button type="button" onclick="saveAfter()" class="btn btn-primary float-right" style="margin-right: 3px;">Guardar para después</button>
      </div>
      <div class="tab-pane fade" id="familySituation" role="tabpanel" aria-labelledby="familySituation-tab">
        <hr>
        <div>
          <span class="m-0 font-weight-bold text-primary title-table">No. de Miembros que viven en el Hogar
            <button type="button" id="addMH" class="btn btn-primary float-right">Agregar</button>
          </span>
          @if(isset($requisition))
            <input type="hidden" name="countMH" id="countMH" value="{{$requisition['countMH']}}">
            <input type="hidden" name="countTotalMH" id="countTotalMH" value="{{$requisition['countMH']}}">
          @else
            <input type="hidden" name="countMH" id="countMH" value="1">
            <input type="hidden" name="countTotalMH" id="countTotalMH" value="1">
          @endif
        </div>
        <hr>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="name1">Nombre(s)</label>
            @if(isset($requisition->MH))
              <input type="text" class="form-control" id="name1" name="name1" placeholder="Ingresa nombre de la persona" value="{{$requisition->MH['name1']}}">
            @else
              <input type="text" class="form-control" id="name1" name="name1" placeholder="Ingresa nombre de la persona">
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="lastName1">Apellido paterno</label>
            @if(isset($requisition->MH))
              <input type="text" class="form-control" id="lastName1" name="lastName1" placeholder="Ingresa el apellido paterno de la persona" value="{{$requisition->MH['lastName1']}}">
            @else
              <input type="text" class="form-control" id="lastName1" name="lastName1" placeholder="Ingresa el apellido paterno de la persona">
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="secondLastName1">Apellido materno</label>
              @if(isset($requisition->MH))
                <input type="text" class="form-control" id="secondLastName1" name="secondLastName1" placeholder="Ingresa el apellido materno de la persona" value="{{$requisition->MH['secondLastName1']}}">
              @else
                <input type="text" class="form-control" id="secondLastName1" name="secondLastName1" placeholder="Ingresa el apellido materno de la persona">
              @endif
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="age1">Edad</label>
            @if(isset($requisition->MH))
              <input type="text" class="form-control" id="age1" name="age1" data-mask="000" placeholder="Ingresa la edad de la persona" value="{{$requisition->MH['age1']}}">
            @else
              <input type="text" class="form-control" id="age1" name="age1" data-mask="000" placeholder="Ingresa la edad de la persona">
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="relationship1">Parentesco</label>
              <select id="relationship1" name="relationship1" class="form-control">
                @if(isset($requisition->MH))
                  <option value="" {{"" == $requisition->MH['relationship1'] ? 'selected' : ''}}>selecciona...</option>
                  <option value="padre" {{"padre" == $requisition->MH['relationship1'] ? 'selected' : ''}}>Padre</option>
                  <option value="madre" {{"madre" == $requisition->MH['relationship1'] ? 'selected' : ''}}>Madre</option>
                  <option value="hermano(a)" {{"hermano(a)" == $requisition->MH['relationship1'] ? 'selected' : ''}}>Hermano(a)</option>
                  <option value="tio(a)" {{"tio(a)" == $requisition->MH['relationship1'] ? 'selected' : ''}}>Tio(a)</option>
                  <option value="primo(a)" {{"primo(a)" == $requisition->MH['relationship1'] ? 'selected' : ''}}>Primo(a)</option>
                  <option value="hijo(a)" {{"hijo(a)" == $requisition->MH['relationship1'] ? 'selected' : ''}}>Hijo(a)</option>
                  <option value="abuelo(a)" {{"abuelo(a)" == $requisition->MH['relationship1'] ? 'selected' : ''}}>Abuelo(a)</option>
                  <option value="otros" {{"otros" == $requisition->MH['relationship1'] ? 'selected' : ''}}>Otros</option>
                @else
                  <option value="">selecciona...</option>
                  <option value="padre">Padre</option>
                  <option value="madre">Madre</option>
                  <option value="hermano(a)">Hermano(a)</option>
                  <option value="tio(a)">Tio(a)</option>
                  <option value="primo(a)">Primo(a)</option>
                  <option value="hijo(a)">Hijo(a)</option>
                  <option value="abuelo(a)">Abuelo(a)</option>
                  <option value="otros">Otros</option>
                @endif
              </select>
            </div>
            <div class="form-group col-md-4">
              <label for="civilStatus1">Edo. Civil</label>
              <select id="civilStatus1" name="civilStatus1" class="form-control">
                @if(isset($requisition->MH))
                  <option value="" {{"" == $requisition->MH['civilStatus1'] ? 'selected' : ''}}>selecciona...</option>
                  <option value="soltero(a)" {{"soltero(a)" == $requisition->MH['civilStatus1'] ? 'selected' : ''}}>Soltero(a)</option>
                  <option value="casado(a)" {{"casado(a)" == $requisition->MH['civilStatus1'] ? 'selected' : ''}}>Casado(a)</option>
                  <option value="divorciado(a)" {{"divorciado(a)" == $requisition->MH['civilStatus1'] ? 'selected' : ''}}>Divorciado(a)</option>
                  <option value="viudo(a)" {{"viudo(a)" == $requisition->MH['civilStatus1'] ? 'selected' : ''}}>Viudo(a)</option>
                  <option value="unionLibre" {{"unionLibre" == $requisition->MH['civilStatus1'] ? 'selected' : ''}}>Unión libre</option>
                @else
                  <option value="">selecciona...</option>
                  <option value="soltero(a)">Soltero(a)</option>
                  <option value="casado(a)">Casado(a)</option>
                  <option value="divorciado(a)">Divorciado(a)</option>
                  <option value="viudo(a)">Viudo(a)</option>
                  <option value="unionLibre">Unión libre</option>
                @endif
              </select>
            </div>
         </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="scholarShip1">Escolaridad</label>
            <select id="scholarShip1" name="scholarShip1" class="form-control">
              @if(isset($requisition->MH))
                <option value="" {{"" == $requisition->MH['scholarShip1'] ? 'selected' : ''}}>selecciona...</option>
                <option value="sinEstudios" {{"sinEstudios" == $requisition->MH['scholarShip1'] ? 'selected' : ''}}>Sin estudios</option>
                <option value="primaria" {{"primaria" == $requisition->MH['scholarShip1'] ? 'selected' : ''}}>Primaria</option>
                <option value="secundaria" {{"secundaria" == $requisition->MH['scholarShip1'] ? 'selected' : ''}}>Secundaria</option>
                <option value="bachillerato/tecnico" {{"bachillerato/tecnico" == $requisition->MH['scholarShip1'] ? 'selected' : ''}}>Bachillerato/Técnico</option>
                <option value="licenciatura/profesional" {{"licenciatura/profesional" == $requisition->MH['scholarShip1'] ? 'selected' : ''}}>Licenciatura/Profesional</option>
                <option value="posgrado" {{"posgrado" == $requisition->MH['scholarShip1'] ? 'selected' : ''}}>Posgrado</option>
              @else
                <option value="">selecciona...</option>
                <option value="sinEstudios">Sin estudios</option>
                <option value="primaria">Primaria</option>
                <option value="secundaria">Secundaria</option>
                <option value="bachillerato/tecnico">Bachillerato/Técnico</option>
                <option value="licenciatura/profesional">Licenciatura/Profesional</option>
                <option value="posgrado">Posgrado</option>
              @endif
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="employments_id1">Ocupación</label>
            <select id="employments_id1" name="employments_id1" class="form-control">
                <option value="">selecciona...</option>
                @if(isset($requisition->MH))
                  @if(isset($employments))
                    @foreach($employments as $element)
                      <option value="{{$element['id']}}" {{$element['id'] == $requisition->MH['employments_id1'] ? 'selected' : ''}}>{{$element['name']}}</option>
                    @endforeach
                  @endif
                @else
                  @if(isset($employments))
                    @foreach($employments as $element)
                      <option value="{{$element['id']}}">{{$element['name']}}</option>
                    @endforeach
                  @endif
                @endif
            </select>
          </div>
        </div>
        @if(isset($requisition))
          @for($i = 2 ; $i <= $requisition['countMH'];$i++)
            <div id="{{'fDMH-'.$i}}">
              <hr/>
              <button type="button" id="{{'deleteMH-'.$i}}" class="btn float-right" onclick="deleteMH(this)">
                <i class="fas fa-trash-alt fa-2x colorIcon"></i>
              </button>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="{{'name'.$i}}">Nombre(s)</label>
                  @if(isset($requisition->MH))
                    <input type="text" class="form-control" id="{{'name'.$i}}" name="{{'name'.$i}}" placeholder="Ingresa nombre de la persona" value="{{'name'.$i}}">
                  @else
                    <input type="text" class="form-control" id="{{'name'.$i}}" name="{{'name'.$i}}" placeholder="Ingresa nombre de la persona">
                  @endif
                </div>
                <div class="form-group col-md-4">
                  <label for="{{'lastName'.$i}}">Apellido paterno</label>
                  @if(isset($requisition->MH))
                    <input type="text" class="form-control" id="{{'lastName'.$i}}" name="{{'lastName'.$i}}" placeholder="Ingresa el apellido paterno de la persona" value="{{$requisition->MH['lastName'.$i]}}">
                  @else
                    <input type="text" class="form-control" id="{{'lastName'.$i}}" name="{{'lastName'.$i}}" placeholder="Ingresa el apellido paterno de la persona">
                  @endif
                </div>
                <div class="form-group col-md-4">
                  <label for="{{'secondLastName'.$i}}">Apellido materno</label>
                  @if(isset($requisition->MH))
                    <input type="text" class="form-control" id="{{'secondLastName'.$i}}" name="{{'secondLastName'.$i}}" placeholder="Ingresa el apellido materno de la persona" value="{{$requisition->MH['secondLastName'.$i]}}">
                  @else
                    <input type="text" class="form-control" id="{{'secondLastName'.$i}}" name="{{'secondLastName'.$i}}" placeholder="Ingresa el apellido materno de la persona">
                  @endif
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="{{'age'.$i}}">Edad</label>
                  @if(isset($requisition->MH))
                    <input type="number" class="form-control" id="{{'age'.$i}}" name="{{'age'.$i}}" placeholder="Ingresa la edad de la persona" value="{{$requisition->MH['age'.$i]}}">
                  @else
                    <input type="number" class="form-control" id="{{'age'.$i}}" name="{{'age'.$i}}" placeholder="Ingresa la edad de la persona">
                  @endif
                </div>
                <div class="form-group col-md-4">
                  <label for="{{'relationship'.$i}}">Parentesco</label>
                  <select id="{{'relationship'.$i}}" name="{{'relationship'.$i}}" class="form-control">
                    @if(isset($requisition->MH))
                      <option value="" {{"" == $requisition->MH['relationship'.$i]? 'selected' : ''}}>selecciona...</option>
                      <option value="padre" {{"padre" == $requisition->MH['relationship'.$i] ? 'selected' : ''}}>Padre</option>
                      <option value="madre" {{"madre" == $requisition->MH['relationship1'.$i] ? 'selected' : ''}}>Madre</option>
                      <option value="hermano(a)" {{"hermano(a)" == $requisition->MH['relationship'.$i] ? 'selected' : ''}}>Hermano(a)</option>
                      <option value="tio(a)" {{"tio(a)" == $requisition->MH['relationship1'.$i] ? 'selected' : ''}}>Tio(a)</option>
                      <option value="primo(a)" {{"primo(a)" == $requisition->MH['relationship'.$i] ? 'selected' : ''}}>Primo(a)</option>
                      <option value="hermano(a)" {{"hermano(a)" == $requisition->MH['relationship'.$i] ? 'selected' : ''}}>Hermano(a)</option>
                      <option value="hijo(a)" {{"hijo(a)" == $requisition->MH['relationship'.$i] ? 'selected' : ''}}>Hijo(a)</option>
                      <option value="abuelo(a)" {{"abuelo(a)" == $requisition->MH['relationship'.$i] ? 'selected' : ''}}>Abuelo(a)</option>
                      <option value="otros" {{"otros" == $requisition->MH['relationship'.$i] ? 'selected' : ''}}>Otros</option>
                    @else
                      <option value="">selecciona...</option>
                      <option value="padre">Padre</option>
                      <option value="madre">Madre</option>
                      <option value="hermano(a)">Hermano(a)</option>
                      <option value="tio(a)">Tio(a)</option>
                      <option value="primo(a)">Primo(a)</option>
                      <option value="hermano(a)">Hermano(a)</option>
                      <option value="hijo(a)">Hijo(a)</option>
                      <option value="abuelo(a)">Abuelo(a)</option>
                      <option value="otros">Otros</option>
                    @endif
                  </select>
                </div>
                <div class="form-group col-md-4">
                      <label for="{{'civilStatus'.$i}}">Edo. Civil</label>
                      <select id="{{'civilStatus'.$i}}" name="{{'civilStatus'.$i}}" class="form-control">
                        @if(isset($requisition->MH))
                          <option value="" {{"" == $requisition->MH['civilStatus'.$i] ? 'selected' : ''}}>selecciona...</option>
                          <option value="soltero(a)" {{"soltero(a)" == $requisition->MH['civilStatus'.$i] ? 'selected' : ''}}>Soltero(a)</option>
                          <option value="casado(a)" {{"casado(a)" == $requisition->MH['civilStatus'.$i] ? 'selected' : ''}}>Casado(a)</option>
                          <option value="divorciado(a)" {{"divorciado(a)" == $requisition->MH['civilStatus'.$i] ? 'selected' : ''}}>Divorciado(a)</option>
                          <option value="viudo(a)" {{"viudo(a)" == $requisition->MH['civilStatus'.$i] ? 'selected' : ''}}>Viudo(a)</option>
                          <option value="unión libre" {{"unión libre" == $requisition->MH['civilStatus'.$i] ? 'selected' : ''}}>Unión libre</option>
                        @else
                          <option value="">selecciona...</option>
                          <option value="soltero(a)">Soltero(a)</option>
                          <option value="casado(a)">Casado(a)</option>
                          <option value="divorciado(a)">Divorciado(a)</option>
                          <option value="viudo(a)">Viudo(a)</option>
                          <option value="unión libre">Unión libre</option>
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="{{'scholarShip'.$i}}">Escolaridad</label>
                      <select id="{{'scholarShip'.$i}}" name="{{'scholarShip'.$i}}" class="form-control">
                        @if(isset($requisition->MH))
                          <option value="" {{"" == $requisition->MH['scholarShip'.$i] ? 'selected' : ''}}>selecciona...</option>
                          <option value="sin estudios" {{"sin estudios" == $requisition->MH['scholarShip'.$i] ? 'selected' : ''}}>Sin estudios</option>
                          <option value="primaria" {{"primaria" == $requisition->MH['scholarShip'.$i] ? 'selected' : ''}}>Primaria</option>
                          <option value="secundaria" {{"secundaria" == $requisition->MH['scholarShip'.$i] ? 'selected' : ''}}>Secundaria</option>
                          <option value="bachillerato/tecnico" {{"bachillerato/tecnico" == $requisition->MH['scholarShip'.$i] ? 'selected' : ''}}>Bachillerato/Técnico</option>
                          <option value="licenciatura/profesional" {{"licenciatura/profesional" == $requisition->MH['scholarShip'.$i] ? 'selected' : ''}}>Licenciatura/Profesional</option>
                          <option value="posgrado" {{"posgrado" == $requisition->MH['scholarShip'.$i] ? 'selected' : ''}}>Posgrado</option>
                        @else
                          <option value="">selecciona...</option>
                          <option value="sin estudios">Sin estudios</option>
                          <option value="primaria">Primaria</option>
                          <option value="secundaria">Secundaria</option>
                          <option value="bachillerato/tecnico">Bachillerato/Técnico</option>
                          <option value="licenciatura/profesional">Licenciatura/Profesional</option>
                          <option value="posgrado">Posgrado</option>
                        @endif
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="{{'employments_id'.$i}}">Ocupación</label>
                      <select id="{{'employments_id'.$i}}" name="{{'employments_id'.$i}}" class="form-control">
                          <option value="">selecciona...</option>
                          @if(isset($requisition->MH))
                            @if(isset($employments))
                              @foreach($employments as $element)
                                <option value="{{$element['id']}}" {{$element['id'] == $requisition->MH['employments_id'.$i] ? 'selected' : ''}}>{{$element['name']}}</option>
                              @endforeach
                            @endif
                          @else
                            @if(isset($employments))
                              @foreach($employments as $element)
                                <option value="{{$element['id']}}">{{$element['name']}}</option>
                              @endforeach
                            @endif
                          @endif
                      </select>
                    </div>
                  </div>
                </div>
              @endfor
            @endif
            <div id="MHs"></div>
            <hr>
          <a href="solicitudes"  class="btn btn-primary float-right">Cancelar</a>
          <button type="button" id="familySituation-1"  onclick="nextNavTab(this)" class="btn btn-primary float-right" style="margin-right: 3px;">Siguiente</button>
          <button type="button" id="familySituation-2"  onclick="nextNavTab(this)" class="btn btn-primary float-right" style="margin-right: 3px;">Anterior</button>
          <button type="button" onclick="saveAfter()" class="btn btn-primary float-right" style="margin-right: 3px;">Guardar para después</button>
        </div>

        <div class="tab-pane fade" id="lifeConditions" role="tabpanel" aria-labelledby="lifeConditions-tab">
          <br>
          <div class="form-row">
            <div class="form-group col-md-5">
              <label for="typeHouse">La casa del beneficiario es:</label>
                  <select id="typeHouse" name="typeHouse" class="form-control" required>
                    @if(isset($requisition->typeHouse))
                      <option value="" {{"" == $requisition->typeHouse ? "selected" : ""}}>selecciona...</option>
                      <option value="propia" {{"propia" == $requisition->typeHouse ? "selected" : ""}}>Propia</option>
                      <option value="rentada" {{"rentada" == $requisition->typeHouse ? "selected" : ""}}>Rentada</option>
                      <option value="prestada" {{"prestada" == $requisition->typeHouse ? "selected" : ""}}>Prestada</option>
                      <option value="invadida" {{"invadida" == $requisition->typeHouse ? "selected" : ""}}>Invadida</option>
                    @else
                      <option value="">selecciona...</option>
                      <option value="propia">Propia</option>
                      <option value="rentada">Rentada</option>
                      <option value="prestada">Prestada</option>
                      <option value="invadida">Invadida</option>
                    @endif
                  </select>
            </div>
            <div class="form-group col-md-5">
                <label for="number_rooms">Número de cuartos</label>
                @if(isset($requisition->number_rooms))
                  <input type="text" class="form-control" id="number_rooms" name="number_rooms" value="{{$requisition->number_rooms}}" data-mask="00" placeholder="Ingresar la cantidad de cuartos" required>
                @else
                  <input type="text" class="form-control" id="number_rooms" name="number_rooms" placeholder="Ingresar la cantidad de cuartos" data-mask="00" required>
                @endif
            </div>
        </div>
        <hr>
        <div>
          <span class="m-0 font-weight-bold text-primary title-table">Muebles
              <button type="button" id="addFurniture" class="btn btn-primary float-right">Agregar</button>
          </span>
          @if(isset($requisition))
            <input type="hidden" name="countFurniture" id="countFurniture" value="{{$requisition['countFurniture']}}">
            <input type="hidden" name="countTotalF" id="countTotalF" value="{{$requisition['countFurniture']}}">
          @else
            <input type="hidden" name="countFurniture" id="countFurniture" value="1">
            <input type="hidden" name="countTotalF" id="countTotalF" value="1">
          @endif
        </div>
        <hr>
        <div class="headerAppend">Mueble Principal</div>
        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="furnitures_id1">Mueble</label>
            <select id="furnitures_id1" name="furnitures_id1" class="form-control" required>
              <option value="">selecciona...</option>
                @if(isset($furnitures))
                  @if(isset($requisition->furnitures_id1))
                    @foreach($furnitures as $element)
                      <option value="{{$element['id']}}" {{$element['id'] == $requisition['furnitures_id1'] ? 'selected' : '' }}>{{$element['name']}}</option>
                    @endforeach
                  @else
                  @foreach($furnitures as $element)
                    <option value="{{$element['id']}}">{{$element['name']}}</option>
                  @endforeach
                @endif
              @endif
            </select>
          </div>
        </div>
        @if(isset($requisition))
            @for($i = 2 ; $i <= $requisition['countFurniture'];$i++)
              <div id="{{'fDF-'.$i}}">
                <hr>
                <div class="headerAppend">Mueble {{$i}}
                  <button type="button" id="{{'deleteFurniture-'.$i}}" class="btn float-right" onclick="deleteFurniture(this)">
                    <i class="fas fa-trash-alt fa-2x colorIcon"></i>
                  </button>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="{{'furnitures_id'.$i}}">Mueble</label>
                    <select id="{{'furnitures_id'.$i}}" disabled name="{{'furnitures_id'.$i}}" class="form-control" required>
                      <option value="">seleccione...</option>
                        @if(isset($furnitures))
                          @if(isset($requisition))
                            @foreach($furnitures as $element)
                              <option value="{{$element['id']}}" {{$element['id'] == $requisition['furnitures_id'.$i] ? 'selected' : '' }}>{{$element['name']}}</option>
                            @endforeach
                          @else
                            @foreach($furnitures as $element)
                              <option value="{{$element['id']}}">{{$element['name']}}</option>
                            @endforeach
                          @endif
                        @endif
                    </select>
                  </div>
                </div>
              </div>
            @endfor
          @endif
          <div id="furnitures"></div>
          <hr>
          <div>
            <span class="m-0 font-weight-bold text-primary title-table">Materiales de construcción
                <button type="button" id="addBuildingMaterial" class="btn btn-primary float-right">Agregar</button>
            </span>
            @if(isset($requisition))
              <input type="hidden" name="countBuildingMaterial" id="countBuildingMaterial" value="{{$requisition['countBuildingMaterial']}}">
              <input type="hidden" name="countTotalBM" id="countTotalBM" value="{{$requisition['countBuildingMaterial']}}">
            @else
              <input type="hidden" name="countBuildingMaterial" id="countBuildingMaterial" value="1">
              <input type="hidden" name="countTotalBM" id="countTotalBM" value="1">
            @endif
          </div>
          <hr>
          <div class="headerAppend">Material de Construcción Principal</div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="buildingMaterials_id1">Material de Construcción</label>
              <select id="buildingMaterials_id1" name="buildingMaterials_id1" class="form-control" required>
                <option value="">selecciona...</option>
                  @if(isset($buildingMaterials))
                    @if(isset($requisition->buildingMaterials_id1))
                      @foreach($buildingMaterials as $element)
                        <option value="{{$element['id']}}" {{$element['id'] == $requisition['buildingMaterials_id1'] ? 'selected' : '' }}>{{$element['name']}}</option>
                      @endforeach
                    @else
                    @foreach($buildingMaterials as $element)
                      <option value="{{$element['id']}}">{{$element['name']}}</option>
                    @endforeach
                  @endif
                @endif
              </select>
            </div>
          </div>
          @if(isset($requisition))
              @for($i = 2 ; $i <= $requisition['countBuildingMaterial'];$i++)
                <div id="{{'fDBM-'.$i}}">
                  <hr>
                  <div class="headerAppend">Mueble {{$i}}
                    <button type="button" id="{{'deleteBuildingMaterial-'.$i}}" class="btn float-right" onclick="deleteBuildingMaterial(this)">
                      <i class="fas fa-trash-alt fa-2x colorIcon"></i>
                    </button>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="{{'buildingMaterials_id'.$i}}">Mueble</label>
                      <select id="{{'buildingMaterials_id'.$i}}" disabled name="{{'buildingMaterials_id'.$i}}" class="form-control" required>
                        <option value="">seleccione...</option>
                          @if(isset($buildingMaterials))
                            @if(isset($requisition))
                              @foreach($buildingMaterials as $element)
                                <option value="{{$element['id']}}" {{$element['id'] == $requisition['buildingMaterials_id'.$i] ? 'selected' : '' }}>{{$element['name']}}</option>
                              @endforeach
                            @else
                              @foreach($buildingMaterials as $element)
                                <option value="{{$element['id']}}">{{$element['name']}}</option>
                              @endforeach
                            @endif
                          @endif
                      </select>
                    </div>
                  </div>
                </div>
              @endfor
            @endif
            <div id="buildingMaterials"></div>
            <hr>
          <div>
            <span class="m-0 font-weight-bold text-primary title-table">Servicios
                <button type="button"  id="addService" class="btn btn-primary float-right">Agregar</button>
            </span>
            @if(isset($requisition))
              <input type="hidden" name="countService" id="countService" value="{{$requisition['countService']}}">
              <input type="hidden" name="countTotalS" id="countTotalS" value="{{$requisition['countService']}}">
            @else
              <input type="hidden" name="countService" id="countService" value="1">
              <input type="hidden" name="countTotalS" id="countTotalS" value="1">
            @endif
          </div>
          <hr>
          <div class="headerAppend">Servicio Principal</div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="services_id1">Servicio</label>
              <select id="services_id1" name="services_id1" class="form-control" required>
                <option value="">selecciona...</option>
                  @if(isset($services))
                    @if(isset($requisition->services_id1))
                      @foreach($services as $element)
                        <option value="{{$element['id']}}" {{$element['id'] == $requisition['services_id1'] ? 'selected' : '' }}>{{$element['name']}}</option>
                      @endforeach
                    @else
                    @foreach($services as $element)
                      <option value="{{$element['id']}}">{{$element['name']}}</option>
                    @endforeach
                  @endif
                @endif
              </select>
            </div>
          </div>
          @if(isset($requisition))
              @for($i = 2 ; $i <= $requisition['countService'];$i++)
                <div id="{{'fDS-'.$i}}">
                  <hr>
                  <div class="headerAppend">Servicio {{$i}}
                    <button type="button" id="{{'deleteService-'.$i}}" class="btn float-right" onclick="deleteService(this)">
                      <i class="fas fa-trash-alt fa-2x colorIcon"></i>
                    </button>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="{{'services_id'.$i}}">Servicio</label>
                      <select id="{{'services_id'.$i}}" disabled name="{{'services_id'.$i}}" class="form-control" required>
                        <option value="">seleccione...</option>
                          @if(isset($services))
                            @if(isset($requisition))
                              @foreach($services as $element)
                                <option value="{{$element['id']}}" {{$element['id'] == $requisition['services_id'.$i] ? 'selected' : '' }}>{{$element['name']}}</option>
                              @endforeach
                            @else
                              @foreach($services as $element)
                                <option value="{{$element['id']}}">{{$element['name']}}</option>
                              @endforeach
                            @endif
                          @endif
                      </select>
                    </div>
                  </div>
                </div>
              @endfor
            @endif
            <div id="services"></div>
            <hr>
        <a href="solicitudes"  class="btn btn-primary float-right">Cancelar</a>
          <button type="button" id="lifeConditions-1"  onclick="nextNavTab(this)" class="btn btn-primary float-right" style="margin-right: 3px;">Siguiente</button>
          <button type="button" id="lifeConditions-2"  onclick="nextNavTab(this)" class="btn btn-primary float-right" style="margin-right: 3px;">Anterior</button>
          <button type="button" onclick="saveAfter()" class="btn btn-primary float-right" style="margin-right: 3px;">Guardar para después</button>
        </div>
        {{-- economicData --}}
          <div class="tab-pane fade" id="economicData" role="tabpanel" aria-labelledby="economicData-tab">
            <br>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="income">Ingresos</label>
                @if(isset($requisition->economicData))
                  <input type="text" class="form-control" id="income" name="income" placeholder="Ingresa los ingresos mensuales del beneficiario" required value="{{$requisition->economicData['income']}}">
                @else
                  <input type="text" class="form-control" id="income" name="income" placeholder="Ingresa los ingresos mensuales del beneficiario" required>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="expense">Egresos</label>
                @if(isset($requisition->economicData))
                  <input type="text" class="form-control" id="expense" name="expense" placeholder="Ingresa los egresos mensuales del beneficiario" value="{{$requisition->economicData['expense']}}">
                @else
                  <input type="text" class="form-control" id="expense" name="expense" placeholder="Ingresa los egresos mensuales del beneficiario">
                @endif
              </div>
            </div>
            <a href="solicitudes"  class="btn btn-primary float-right">Cancelar</a>
            <button type="submit" class="btn btn-primary float-right" style="margin-right: 3px;">Guardar</button>
            <button type="button" id="economicData-1"  onclick="nextNavTab(this)" class="btn btn-primary float-right" style="margin-right: 3px;">Anterior</button>
            <button type="button" onclick="saveAfter()" class="btn btn-primary float-right" style="margin-right: 3px;">Guardar para después</button>
          </div>
        </div>
      </form>
    </div>
</div>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Recorte de imagen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="img-container">
          <div class="row">
            <div class="col-md-8">
              <img id="imageCrop" src="">
            </div>
            <div class="col-md-4">
              <div class="preview"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="crop">recortar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

{{-- Modal de seleccion de producto --}}
<div class="row">
  <div class="col-md-12">
      <div class="modal fade" tabindex="-1" role="dialog" id="modal-select-product">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-product-title"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                      <form method="POST" action="{{Request::url()}}" id="frm" class="needs-validation" novalidate>
                        @csrf
                          <input type="hidden" name="action" id="actionMP" value=""/>
                          <input type="hidden" name="id" id="id" value="0">
                          <div class="form-group">
                            <label for="suppliers_id">Proveedor:</label>
                            <select type="text" class="form-control" id="suppliers_id" name="suppliers_id">
                                <option value="">Selecciona...</option>
                                @if(isset($suppliers))
                                    @foreach($suppliers as $supplier)
                                      <option value="{{$supplier['id']}}">{{$supplier['companyname']}}</option>
                                    @endforeach
                                @endif
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="products_id">Product:</label>
                            <select type="text" class="form-control" id="products_id" name="products_id">
                                <option value="0">Seleccione...</option>
                                @if(isset($products))
                                    @foreach($products as $product)
                                    <option value="{{$product['id']}}">{{$product['name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                          </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" id="save">Guardar</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  </div>
                </form>
              </div>
          </div>
      </div>
  </div>
</div>



@endsection

@section('jsDashboard')
  <script src="../assets/js/mainForm.js"></script>
  <script src="../assets/js/RequestForm.js"> </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
@endsection
