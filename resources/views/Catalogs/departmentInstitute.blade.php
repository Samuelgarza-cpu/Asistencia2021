@extends('base.forms')
@section('cssForm')
@endsection

@section('titleTable')
  Relacionar Departamentos con Instituto
@endsection

@section('headerTable')
  <th data-field="number" data-sortable="true">#</th>
  <th data-field="department" data-sortable="true">Departamento</th>
  <th data-field="institute" data-sortable="true">Instituto</th>
  <th data-field="actions" data-events="operateEvents" data-width="80"></th>
@endsection

@section('contentForm')
{{-- Modal Para Agregar/Modificar uno nuevo --}}
<div class="row">
  <div class="col-md-12">
      <div class="modal fade" tabindex="-1" role="dialog" id="modal-register">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                    <form method="POST" action="{{Request::url()}}" id="frm" class="needs-validation" enctype="multipart/form-data" novalidate>
                        @csrf
                            <input type="hidden" name="action" id="action" value=""/>
                            <input type="hidden" name="id" id="id" value="0">
                            <div class="form-group">
                            <label for="institutes_id">Instituto:</label>
                            <select type="text" class="form-control" id="institutes_id" name="institutes_id">
                                @if(isset($institutes))
                                    @foreach($institutes as $element)
                                    <option value="{{$element['id']}}">{{$element['name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="departments_id">Departamento:</label>
                            <select type="text" class="form-control" id="departments_id" name="departments_id">
                                @if(isset($departments))
                                    @foreach($departments as $element)
                                    <option value="{{$element['id']}}">{{$element['name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group files-div">
                        <span class="file stamp-img">
                            <input type="file" accept="image/*" name="stamp" id="stamp" class="form-control">
                            <div class="invalid-feedback">
                            Favor de ingresar la imagen del sello del departamento
                        </div>
                        </span>
                        <label for="stamp" class="label-button">
                            <span id="title-dept">Subir el sello del departamento</span>
                        </label>  
                        </div>
                            <div class="form-group files-div">
                            <img id="imagenPrevisualizacion">
                        </div>
                        <div class="form-group files-div">
                            <span class="file image-img">
                                <input type="file" accept="image/*" name="image" id="image" class="form-control" required>
                                <div class="invalid-feedback">
                                Favor de ingresar la imagen del departamento
                                </div>
                            </span>
                            <label for="image" class="label-button">
                                <span>Subir la imagen del departamento</span>
                            </label>  
                        </div>
                        <div class="form-group files-div">
                            <img id="imagenPrevisualizacion2">
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

@section('jsForm')
  <script src="../assets/js/DepartmentInstitute.js"></script>
@endsection