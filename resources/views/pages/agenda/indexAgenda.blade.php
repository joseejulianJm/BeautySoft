@extends('layouts.app')

@section('title')
    Agenda
@endsection

@section('content')
    <!-- calendario -->
    <div class="container">
        <div class="row px-3 py-2">
            <div class="col p-3 bg-white">
                <div id="calendar">

                </div>
            </div>
        </div>
    </div>

    <style>
div {
  margin-bottom: 10px;
  position: relative;
}

input[type="number"] {
  width: 100px;
}

input + span {
    
  padding-right: 30px;
}


input:invalid+span:after {
  position: absolute;
  content: '✖';
  padding-left: 5px;
}

input:valid+span:after {
  position: absolute;
  content: '✓';
  padding-left: 5px;
}

</style>

    <!-- Modal Crear -->
    <div class="modal fade" id="Crear" tabindex="-1" aria-labelledby="CrearLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content ">
                {{-- <div class="modal-header"> --}}
                <div class="row">
                    <div class="col-12 pt-3 text-center">
                        {{-- <h4 class="modal-title  text-secondary" id="CrearLabel"></h4> --}}
                        <h3 class="text-center"> <strong style="color: rgba(2, 93, 113, 1);">Agendar cita</strong></h3>
                    </div>
                </div>
                {{-- </div> --}}
                <div class="modal-body ">
                    <form class="d-flex align-content-around justify-content-around " action="" id="formulario-Crear">
                        @csrf
                        <div class="row  p-4 "> 
                            {{-- CLIENTE FORM --}}
                            <div class="col-12  col-md-12 col-lg-6">
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <select name="cliente_id"
                                            class="js-example-basic-single form-control @error('cliente_id') is-invalid @enderror "
                                            style="width: 100%">
                                            <option value="" selected>Cliente</option>
                                            @foreach ($clientes as $value)
                                                @if($value->state != 0)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
    
                                        @error('cliente_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
    
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="date" id="fechaC" class="form-control @error('fecha') is-invalid @enderror"
                                            name="date" readonly>
                                        @error('fecha')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
    
                                    <div class="col-12 col-lg-4  form-group">
                                        <input type="time" id="horaC"  min="09:00" max="19:30"class="form-control @error('hora') is-invalid @enderror"
                                            name="hourI">
                                            <span class="validity"></span>
                                        @error('hora')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-4  form-group">
                                        <input type="number" id="tiempo" class="form-control " placeholder="Duración*">
    
                                    </div>
                                    <div class="col-12 col-lg-4 form-group">
                                        <input type="text" id="direc"
                                            class="form-control @error('direccion') is-invalid @enderror" name="direction"
                                            placeholder="Dirección">
                                        @error('direccion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 form-group mb-5">
                                        <textarea name="description" id="descri" class="form-control @error('descripcion') is-invalid @enderror "
                                            placeholder="Descripción"></textarea>
                                        @error('descripcion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
    
                                    </div>
                                    
                                    <div class="col-12 col-lg-6  form-group">
    
                                        <select name="servicio_id" id="servicio"
                                            class=" form-control @error('servicio') is-invalid @enderror"
                                            onchange="precio_total()">
                                            <option value="">Servicios</option>
                                            @foreach ($servicios as $value)
                                                @if($value->state != 0)
                                                    <option precio="{{ $value->price }}" value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
    
                                        @error('servicio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
    
                                    </div>
    
                                    <div class="col-12 col-lg-6  form-group">
    
                                        <input type="text" id="precio"
                                            class="form-control @error('precio') is-invalid @enderror" name="precio" 
                                            placeholder="Precio de Servicio" readonly>
    
                                        @error('precio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
    
                                    </div>
                                    
                                    <div class="col-12 form-group d-flex justify-content-end">
                                        <button type="button" onclick="agregar_Servicio()" data-bs-toggle="tooltip"
                                            data-bs-placement="left" title="Agregar producto"
                                            class="btn principal-color text-white"><i class="fas fa-plus"></i>
                                            <span> Agregar Servicio</span> </button>
                                    </div>
    
                                </div>
                            </div>
                            {{-- SERVICIO FORM --}}
                            <div class="col-12 col-md-12 col-lg-6">
                                <div class=" row">
                                    <div class="col-12  form-group">
                                        <input type="text" id="preciototal"
                                            class="form-control @error('precio') is-invalid @enderror text-center" name="price"
                                            placeholder="Precio Total" readonly>
    
                                        @error('precio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
    
                                    </div>
                                    
    
                                    <div class="col-12 mt-2 tbl2_scroll table-responsive ">
                                        <table id="TadaBaseCitas w-100" class="table  table-bordered">
                                            <thead>
                                                <tr class="text-center">
                                                    <th scope="col">Servicio</th>
                                                    <th scope="col">Precio</th>
                                                    <th scope="col">Accion</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbalaServicio">
    
                                            </tbody>
                                        </table>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                       
                </div>
                <div class="row py-4 px-3 justify-content-end">
                    <div class="col-6 col-md-4 col-lg-3">
                        {{-- <button type="submit"  class="btn btn-success">crear</button> --}}

                        <button type="button" onclick="CrearCita()" class="btn principal-color text-white w-100"
                            id="btnCrear">Agendar</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a  onclick="limpiar()" class="btn btn-outline-dark btn-block"
                            data-bs-dismiss="modal">Volver
                        </a>
                    </div>



                </div>
                </form>
            </div>

        </div>
    </div>
    </div>

    {{-- MODAL OPCIONES --}}
    <div class="modal" id="Opciones"  id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
               
            <h3 class="text-center"> <strong style="color: rgba(2, 93, 113, 1);">Opciones de la cita</strong></h3>

                
                <div class="modal-body" id="select" >
                    <select name="estado"id="estado" onchange="cambio()"class="js-example-basic-single form-control @error('cliente_id') is-invalid @enderror " style="width: 100%">
                        <option value="" selected>Estados de la cita</option>
                        
                    </select>
                    
                </div>
                <div class="col-6 col-md-4 col-lg-6 center"  id="cambio-btn"style="margin-left:120px;">
                    <a href="" class="btn principal-color text-white w-100" id="cambio" data-bs-toggle="tooltip" data-bs-placement="left" title="Cambiar Estado"> cambio estado</a>
                </div>
                <div class="row py-4 px-3 justify-content-around " >
                        
                        <div class="col-6 col-md-4 col-lg-2" id="edit">   
                                <a class="btn  btn-warning btn-ms btn-block" id="opcionesEditar" href="" data-bs-toggle="tooltip" data-bs-placement="left" title="Editar Cita"><i
                                    class="glyphicon glyphicon-edit"></i>Editar</a>
                        </div>
                        <div class="col-3 col-md-4 col-lg-2" >
                            <a class="btn  btn-primary btn-ms btn-block" id="opcionesDetalle" href="" data-bs-toggle="tooltip" data-bs-placement="left" title="Ver Detalle"><i
                                    class="glyphicon glyphicon-edit"></i>Detalle</a>
                        </div>
                        <div class="col-3 col-md-4 col-lg-2">
                            <button type="button" onclick="limpiar()" class="btn btn-outline-dark btn-block"
                            data-bs-dismiss="modal" data-bs-toggle="tooltip" data-bs-placement="left" title="Regresar">Volver</button>
                        </div>
                </div>
            </div>

        </div>
    </div>
    
@endsection
@section('js-alert')

    <script src="/js/calendar.js "></script>
    <script src="/plugins/fullcalendar-5.10.1/moment/moment.min.js "></script>
    <script src="/plugins/fullcalendar-5.10.1/lib/main.min.js"></script>
    <script src="/plugins/fullcalendar-5.10.1/lib/locales-all.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        //  CAMBIO ESTADO  
        function cambio(){
            let id = $("#estado option:selected").attr("Cita_id");
            let estado =$("#estado option:selected").val();
            
            $("#cambio").prop('href','/agenda/'+id+'/'+estado);

        }   
        // window.onload = autoState;
        // //ESTADO AUTOMATICO
        // function autoState() {
        //     let date = new Date();
        //     let output =date.toISOString().split('T')[0];
            
        //     @foreach($cita as $value)
        //     dateServer = String.valueOf();
            
        //     console.log({{$value->date}});
        //     @endforeach
            
        // }
        // SERVICIO FORM
        function precio_total() {

            let precio = $("#servicio option:selected").attr("precio");
            
            $("#precio").val(precio);

        }

        function agregar_Servicio() {
            
            let servicio = $("#servicio option:selected").text();
            
            let precio = $("#servicio option:selected").attr("precio");
            
            let id = $("#servicio option:selected").val()
           
            if (precio > 0) {
                $('#tbalaServicio').append(`
                    <tr id="tr-${id}"  class="sr">
                        <td>
                        <input type="hidden" name="servicios_id[]" value="${id}"/>
                        ${servicio}</td>
                        <td>${precio}</td>
                        <td class="text-center">
                            <button  type="button" class="btn btn-danger" onclick="Eliminar(${id},${precio})"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
        
                `);

                let precioTotal = $("#preciototal").val() || 0;
                $("#preciototal").val(parseInt(precioTotal) + parseInt(precio));
            } else {
                
               
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: '!Ocurrio un Error¡',
                    text: 'Por favor seleccione un producto o coloque una cantidad mayor a 0 ',
                    showConfirmButton: false,
                    timer: 2500
                })
            

            }
        }

        function Eliminar($id, $precio) {
            console.log($id);
            $("#tr-" + $id).remove();
            let precioTotal = $("#preciototal").val() || 0;
            $("#preciototal").val(parseInt(precioTotal) - parseInt($precio));
        }
       

       
     
    </script>
@endsection
