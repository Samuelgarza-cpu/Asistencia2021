<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="">
  <style>
  .borders {
    border: 1px solid black;
  }
  table {
  border-collapse: collapse;
  width: 100%;
  }
  </style>
</head>

<body id="page-top">
    <div>
     <main>
            <!-- El contenido de tu PDF aquí -->
            <div>
              <table>
                <tr>
                  <td>
                    <img src="{{$requisition->public_path.$requisition->image}}"/>
                  </td>
                  <td>
                    <table style="text-align: center; border-style:solid;">
                      <tr>
                        <td>
                          PETICIÓN DE APOYO DE ASISTENCIA SOCIAL
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <p>{{$requisition->date}}</p>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <p id="folio">FOLIO: {{$requisition->folio}}</p>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <br/>
              <table class="borders">
                <tr>
                  <td class="borders">
                    <p>NOMBRE DEL SOLICITANTE: </p>
                  </td>
                  <td class="borders">
                    <p id="namePetitioner">{{$requisition->petitioner}}</p>
                  </td>
                </tr>
                <tr>
                  <td class="borders">
                    <p>BENEFICIARIO(S):</p>
                  </td>
                  <td class="borders">
                    @foreach($requestPersonalData as $element)
                      <p id="{{'nameBeneficiary'.$element->id}}">Beneficiario: {{$element->name.' '.$element->lastName.' '.$element->secondLastName}}</p>
                      <p id="{{'ageBeneficiary'.$element->id}}">Edad: {{$element->age}}</p>
                    @endforeach
                  </td>
                </tr>
                <tr>
                  <td class="borders">
                    <p>APOYO:</p>
                  </td>
                  <td class="borders">
                    @if($requestSupplierProducts->count() > 0)
                    @foreach($requestSupplierProducts as $element)
                      <p id="{{'productInfo'.$element->id}}">{{$element->qty.' '.$element->productName.' Precio Unitario:'.$element->price.' Total:'.$element->total}}</p>
                    @endforeach
                    @endif
                  </td>
                </tr>
                <tr>
                  <td class="borders">
                    <p>CASO:</p>
                  </td>
                  <td class="borders">
                      <p id="{{'case'.$requisition->id}}">{{$requisition->description}}</p>
                  </td>
                </tr>
                <tr>
                  <td class="borders">
                    <p>AUTORIZADO:</p>
                  </td>
                  <td class="borders">
                    @if($requisition->status_id == 6 || $requisition->status_id == 4)
                      <img src="{{$requisition->mainPublic_path.$userAuth->stamp}}"/>
                    @endif
                  </td>
                </tr>
              </table>
              <p style="page-break-after: always;"></p>

              <table>
                <tr>
                  <td>
                    <img style="height:100px" src="{{$requisition->images_path.'DIF.jpg'}}"/>
                  </td>
                  <td>
                    <h2 style="text-align: center;">SISTEMA PARA EL DESARROLLO INTEGRAL DE LA FAMILIA</h2>
                  </td>
                  <td style="text-align: right">
                    <p>{{$requisition->date}}</p>
                  </td>
                </tr>
              </table>

              <table>
                <tr>
                  <td colspan="2">
                    <table>
                      <tr>
                        <td style="text-align: right;">
                          <span>SOLICITA:</span>
                          @if($requestSupplierProducts->count() > 0)
                          @foreach($requestSupplierProducts as $element)
                          <span style="text-decoration: underline;" id="{{'productInfo'.$element->id}}">{{$element->qty.' '.$element->productName}}</span>
                          @endforeach
                          @endif
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <br>
              <br>

              <table>
                <tr>
                  @if($requisition->beneficiary == 1)
                    <td style="width: 100%;" colspan="2">NOMBRE: <span style="text-decoration: underline;">{{$requisition->petitioner}}</span></td>
                  @else
                    <td style="width: 80%">NOMBRE: <span style="text-decoration: underline;">{{$requestPersonalData[0]->name.' '.$requestPersonalData[0]->lastName.' '.$requestPersonalData[0]->secondLastName}}</span></td>
                    <td style="width: 20%">EDAD: <span style="text-decoration: underline;">{{$requestPersonalData[0]->age}}</span></td>
                  @endif
                </tr>
                <tr>
                  <td colspan="2">
                    DOMICILIO:
                    {{-- <p>{{$address['state']->name}}</p> --}}
                    <span style="text-decoration: underline;">{{$address->street.' #'.$address->externalNumber.' '.$address->internalNumber}}</span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <table>
                      <tr>
                        <td>
                          COLONIA:
                          {{-- <p>{{$address['state']->name}}</p> --}}
                          <span style="text-decoration: underline;">{{$address['community']->type.' '.$address['community']->name}}</span>
                        </td>
                        <td>
                          CIUDAD:
                          {{-- <p>{{$address['state']->name}}</p> --}}
                          <span style="text-decoration: underline;">{{$address['municipality']->name.', '.$address['state']->name}}</span>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <table>
                      <tr>
                        <td>
                          EDO. CIVIL:
                          {{-- <p>{{$address['state']->name}}</p> --}}
                          <span style="text-decoration: underline;">{{$requestPersonalData[0]->civilStatus}}</span>
                        </td>
                        <td>
                          ESCOLARIDAD:
                          {{-- <p>{{$address['state']->name}}</p> --}}
                          <span style="text-decoration: underline;">{{$requestPersonalData[0]->scholarShip}}</span>
                        </td>
                        <td>
                          OCUPACION:
                          {{-- <p>{{$address['state']->name}}</p> --}}
                          <span style="text-decoration: underline;">{{$requestPersonalData[0]->employmentName}}</span>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <br>
              <H2 STYLE="text-align: center;">SITUACIÓN FAMILIAR</H2>
              <table>
                <tr>
                  <th>NOMBRE</th>
                  <th>EDAD</th>
                  <th>PARENTESCO</th>
                  <th>EDO. CIVIL</th>
                  <th>OCUPACIÓN</th>
                  <th>ESCOLARIDAD</th>
                </tr>
                @for($i = 0; $i < $requestPersonalData->count(); $i++)
                  @if($requestPersonalData[$i]->beneficiary)
                    <tr>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;">{{$requestPersonalData[$i]->name.' '.$requestPersonalData[$i]->lastName.' '.$requestPersonalData[$i]->secondLastName}}</span>
                      </td>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;">{{$requestPersonalData[$i]->age}}</span>
                      </td>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;"></span>
                      </td>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;">{{$requestPersonalData[$i]->civilStatus}}</span>
                      </td>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;">{{$requestPersonalData[$i]->employmentName}}</span>
                      </td>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;">{{$requestPersonalData[$i]->scholarShip}}</span>
                      </td>
                    </tr>
                  @endif
                @endfor
                @if($familySituation->count() > 0)
                @foreach($familySituation as $element)
                    <tr>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;">{{$element->name.' '.$element->lastName.' '.$element->secondLastName}}</span>
                      </td>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;">{{$element->age}}</span>
                      </td>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;">{{$element->relationship}}</span>
                      </td>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;">{{$element->civilStatus}}</span>
                      </td>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;">{{$element->employmentName}}</span>
                      </td>
                      <td STYLE="text-align: center;">
                        <span style="text-decoration: underline;">{{$element->scholarship}}</span>
                      </td>
                    </tr>
                @endforeach
                @endif
              </table>
              <br>
              <h2 STYLE="text-align: center;">CONDICIONES DE VIDA</h2>
              <TABLE>
                <tr>
                  <td style="text-align: center;">
                    TIPO DE CASA:
                    <span style="text-decoration: underline;">{{$lifeCondition[0]->typeHouse}}</span>
                  </td>
                  <td style="text-align: center;">
                    NÚMERO DE CUARTOS
                    <span style="text-decoration: underline;">{{$lifeCondition[0]->number_rooms}}</span>
                  </td>
                </tr>
              </TABLE>
              <br>
              <TABLE>
                <TR>
                  <TD>
                    <TABLE>
                      <tr>
                        <TH>MUEBLES</TH>
                      </tr>
                      @foreach($requestFurnitures as $element)
                      <TR>
                        <TD style="text-align: center;">
                          <span style="text-decoration: underline;">{{$element->name}}</span>
                        </TD>
                      </TR>
                      @endforeach
                    </TABLE>
                  </TD>
                  <TD>
                    <table>
                      <TR>
                        <TH>SERVICIOS</TH>
                      </TR>
                      @foreach($requestServices as $element)
                      <TR>
                        <TD style="text-align: center;">
                          <span style="text-decoration: underline;">{{$element->name}}</span>
                        </TD>
                      </TR>
                      @endforeach
                    </table>
                  </TD>
                  <TD>
                    <TABLE>
                      <TR>
                        <TH>MATERIAL DE CONTRUCCIÓN</TH>
                      </TR>
                      @foreach($requestBuildingMaterial as $element)
                      <TR>
                        <TD style="text-align: center;">
                          <span style="text-decoration: underline;">{{$element->name}}</span>
                        </TD>
                      </TR>
                      @endforeach
                    </TABLE>
                  </TD>
                </TR>
              </TABLE>
              <br>
              <H3 style="text-align: center;">INGRESOS ECONÓMICOS</H3>
              <TABLE>
                <TR>
                  <TD style="text-align: center;">INGRESOS: <span style="text-decoration: underline;">${{$economicData[0]->income}}</span></TD>
                  <td style="text-align: center;">
                    EGRESOS: <span style="text-decoration: underline;">${{$economicData[0]->expense}}</span>
                  </td>
                </tr>
              </TABLE>
              <br>
              <table>
                <tr>
                  <tD>
                    <table>
                      <tr>
                        <td style="text-align: center; height: 150px;">
                          <img style="height: 150px;" src="{{$requisition->mainPublic_path.$user->signature}}"/>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: center">
                          USUARIO: <span style="text-decoration: underline;">{{$user->owner}}</span></tD>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td>
                    <table>
                      <tr>
                        <td  style="text-align: center; height: 150px;">
                          @if($requisition->status_id == 4 || $requisition->status_id == 6)
                            <img style="height: 150px;" src="{{$requisition->mainPublic_path.$user->signature}}"/>
                          @endif
                        </td>
                      </tr>
                      <tr>
                        <tD style="text-align: center">AUTORIZA: <span style="text-decoration: underline;">{{$userAuth->owner}}</span></tD>
                      </tr>
                    </table>
                  </td>

                </tr>
                <tr>
                  <td colspan="2"><SPAN STYLE="font-size: 14PX">BLVD. EJERCITO MEXICANO 528 TELEFONOS: 714-21-24 Y 714-21-27 GÓMEZ PALACIO, DGO.</SPAN></td>
                </tr>
              </table>
              <P STYLE="page-break-after: always"></P>
              <table>
                <tr>
                  <td>
                    <p style="font-weight: bold;">Lic. Laura Maria Vitela Rodríguez</p>
                  </td>
                  <td style="text-align: right;">
                    <span style="font-weight: bold;">{{$requisition->date}}</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Presidenta DIF Municipal Gómez Palacio, Durango</p>
                    <p>Presente.-</p>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <p>A sus amables atenciones me dirijo, para solicitarle tenga a bien apoyarme con:</p>
                    <p>{{$requisition->description}}</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    Para: <span style="text-decoration: underline;">{{$requestPersonalData[0]->name.' '.$requestPersonalData[0]->lastName.' '.$requestPersonalData[0]->secondLastName}}</span></td>
                  </td>
                  <td>EDAD: <span style="text-decoration: underline;">{{$requestPersonalData[0]->age}}</span></td>
                </tr>
                <tr>
                  <td>
                    DOMICILIO:
                    {{-- <p>{{$address['state']->name}}</p> --}}
                    <span style="text-decoration: underline;">{{$address->street.' #'.$address->externalNumber.' '.$address->internalNumber}}</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    COLONIA:
                    {{-- <p>{{$address['state']->name}}</p> --}}
                    <span style="text-decoration: underline;">{{$address['community']->type.' '.$address['community']->name}}</span>
                  </td>
                  <td>
                    CIUDAD:
                    {{-- <p>{{$address['state']->name}}</p> --}}
                    <span style="text-decoration: underline;">{{$address['municipality']->name.', '.$address['state']->name}}</span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <p>Lo anterior debido a que:</p>
                    <br>
                    <br>
                    <input style="width: 100%;" type="text"/>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <p>Agradezco su invaluable apoyo:</p>
                    <br>
                    <br>
                    <input style="width: 100%;" type="text"/>
                    <p style="font-size: 14px;">FIRMA, NOMBRE, DOMICILIO(CALLE Y NUMERO INT Y EXT. COLONIA/EJIDO) DEL SOLICITANTE</p>
                  </td>
                </tr>
              </table>
              <br/>
              <br>
              <table>
                <tr>
                  <td>
                    <p style="font-weight: bold;">Lic. Laura Maria Vitela Rodríguez</p>
                  </td>
                  <td style="text-align: right;">
                    <span style="font-weight: bold;">{{$requisition->date}}</span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <p>Presidenta DIF Municipal Gómez Palacio, Durango</p>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <p>Solicito su valiso apoyo con
                      @if($requestSupplierProducts->count() > 0)
                      @foreach($requestSupplierProducts as $element)
                        <span id="{{'productInfo'.$element->id}}">{{$element->qty.' '.$element->productName}}</span>
                      @endforeach
                      @endif
                    para mi ya que no cuento con los medios económicos para solventar el gasto ya que lo necesito.</p>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" style="text-align: right;">
                    <p>____________________________________</p>
                    <p style="font-weight:bold">{{$requestPersonalData[0]->name.' '.$requestPersonalData[0]->lastName.' '.$requestPersonalData[0]->secondLastName}}</p>
                  </td>
                </tr>
              </table>
              <p style="page-break-after: always;"></p>
              <table>
                <tr>
                  <td>
                    <img style="height:100px" src="{{$requisition->images_path.'DIF.jpg'}}"/>
                  </td>
                  <td>
                    <h2 style="text-align: center;">TRABAJO SOCIAL</h2>
                  </td>
                  <td style="text-align: right;">
                    <p>{{$requisition->date}}</p>
                  </td>
                </tr>
              </table>
              <br>
              <table>
                <tr>
                  <td>
                    <span>INSTITUCIÓN: </span><span></span>
                  </td>
                </tr>
                <tr>
                  <td>
                    <span>DOMICILIO: </span><span></span>
                  </td>
                </tr>
                <tr>
                  <td>
                    <span>COLONIA: </span><span></span>
                  </td>
                </tr>
              </table>
              <br>
              <table>
                <TR>
                  <TD>
                    FAVOR DE CARGAR A NUESTRA CUENTA LA CANTIDAD DE <span style="text-decoration: underline;"></span>
                    SON: <input type="text" width="150PX"/>
                  </TD>
                </TR>
                <tr>
                  <td>
                    POR CONCEPTO DE <SPAN></SPAN>
                  </td>
                </tr>
                <TR>
                  <TD>
                    PARA EL/LA C. NOMBRE: <span style="text-decoration: underline;">{{$requestPersonalData[0]->name.' '.$requestPersonalData[0]->lastName.' '.$requestPersonalData[0]->secondLastName}}</span>
                    QUIEN CUENTA CON <span style="text-decoration: underline;">{{$requestPersonalData[0]->age}}</span> AÑOS DE EDAD.
                  </TD>
                </TR>
                <TR>
                  <TD>
                    Y TIENE SU DOMICILIO EN <span style="text-decoration: underline;">{{$address->street.' #'.$address->externalNumber.' '.$address->internalNumber.' '.$address['community']->type.' '.$address['community']->name.', '.$address['municipality']->name.', '.$address['state']->name}}</span>
                  </TD>
                </TR>
              </table>
              <br>
              <TABLE>
                <TR>
                  <TD>
                    <table>
                      <tr>
                        <td style="text-align: right;">
                          <SPAN>ATENTAMENTE</SPAN>
                        </td>
                      </tr>
                      <tr>
                        <td style="text-align: right;">
                          @if($requisition->status_id == 4 || $requisition->status_id == 6)
                            <img style="height: 150px;" src="{{$requisition->mainPublic_path.$user->signature}}"/>
                          @endif
                        </td>
                      </tr>
                      <tr>
                        <tD style="text-align: right;">AUTORIZA: <span style="text-decoration: underline;">{{$userAuth->owner}}</span></tD>
                      </tr>
                    </table>
                  </TD>
                </TR>
                <tr>
                  <td style="text-align: right;">
                    SISTEMA PARA EL DESARROLLO INTEGRAL DE LA FAMILIA
                  </td>
                </tr>
                <TR>
                  <TD style="text-align: right;">
                    Blvd. Ejercito Mexicano No. 528 Parque Industrial Gómez Palacio, Dgo. Tel: 714-21-24
                  </TD>
                </TR>
              </TABLE>
              <hr>
              <p style="page-break-after: always;"></p>
              {{-- //foliado --}}
              <table>
                <tr>
                  <td>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td style="text-align: right;">
                    {{$requisition->folio}}
                  </td>
                </tr>
                <tr>
                  <td>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td style="text-align: right;">
                    @if($requestSupplierProducts->count() > 0)
                    <p>{{$requestSupplierProducts[0]->total}}</p>
                    @endif
                  </td>
                </tr>
                <tr>
                  <td>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td>
                    @if($requestSupplierProducts->count() >0)
                    {{$requestSupplierProducts[0]->qty.' '.$requestSupplierProducts[0]->productName}}
                    @endif
                  </td>
                </tr>
                <tr>
                  <td>
                    BENEFICIARIO: {{$requestPersonalData[0]->name.' '.$requestPersonalData[0]->lastName.' '.$requestPersonalData[0]->secondLastName}}
                    CON {{$requestPersonalData[0]->age}} AÑOS DE EDAD.
                  </td>
                </tr>
                <tr>
                  <td>
                    DOMICILIO EN <span style="text-decoration: underline;">{{$address->street.' #'.$address->externalNumber.' '.$address->internalNumber.' '.$address['community']->type.' '.$address['community']->name.', '.$address['municipality']->name.', '.$address['state']->name}}</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td>
                    <br>
                  </td>
                </tr>
                <TR>
                  <TD>
                    <p>{{$requisition->date}}</p>
                  </TD>
                </TR>
              </table>
        </main>
    </div>
</body>
</html>
