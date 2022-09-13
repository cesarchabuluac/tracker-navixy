@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{asset('bundles/datatables/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
@endpush
@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
               @component('components.alert')                
               
                <div class="card">
                    <div class="card-header">
                        <h4>Alta de Vehículo</h4>
                    </div>
                    <form method="POST" action="{{ route('cars.store') }}" class="needs-validation" novalidate="">
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="provider_name">Proveedor</label>
                                    <input type="text" class="form-control" id="provider_name" name="provider_name" placeholder="Nombre del Proveedor" value="{{old('provider_name')}}" required>
                                    <div class="invalid-feedback">
                                        Por favor complete el nombre del proveedor
                                    </div>
                                    @error('provider_name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="company">Empresa</label>
                                    <input type="text" class="form-control" id="company" name="company" placeholder="Nombre de la empresa concesionaria" required value="{{old('company')}}">
                                    <div class="invalid-feedback">
                                        Por favor complete el nombre de la empresa
                                    </div>
                                    @error('company')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="route">Ruta</label>
                                    <input type="text" class="form-control" id="route" name="route" placeholder="Número de la ruta" value="{{old('route')}}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="vin">VIN</label>
                                    <input type="text" class="form-control" id="vin" name="vin" placeholder="Serie Vehicular de la unidad" required value="{{old('vin')}}">
                                    <div class="invalid-feedback">
                                        Por favor complete la serie vehicular de la unidad
                                    </div>
                                    @error('vin')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="economic_number">Núm Económico</label>
                                    <input type="text" class="form-control" id="economic_number" name="economic_number" placeholder="Número económico del vehículo" value="{{old('economic_number')}}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="license_plate">Placa</label>
                                    <input type="text" class="form-control" id="license_plate" name="license_plate" placeholder="Placas del vehículo" value="{{old('license_plate')}}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="imei">IMEI</label>
                                    <input type="text" class="form-control" id="imei" name="imei" placeholder="Código IMEI del dispositivo GPS o rastreador" required value="{{old('imei')}}">
                                    <div class="invalid-feedback">
                                        Por favor complete el código IMEI del dispositivo GPS o rastreador
                                    </div>
                                    @error('imei')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="unit_type">Tipo Unidad</label>
                                    <select id="unit_type" name="unit_type" class="form-control" required>
                                        <option value="">Selecciona una opción</option>
                                        <option {{old('unit_type') == 'TAXI' ? 'selected' : ''}} value="TAXI">TAXI</option>
                                        <option {{old('unit_type') == 'VAGONETA' ? 'selected' : ''}} value="VAGONETA">VAGONETA</option>
                                        <option {{old('unit_type') == 'CAMIÓN' ? 'selected' : ''}} value="CAMIÓN">CAMIÓN</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor complete el tipo de unidad
                                    </div>
                                    @error('unit_type')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="brand">Marca</label>
                                    <input type="text" class="form-control" id="brand" name="brand" placeholder="Indicar la marca del vehículo" required value="{{old('brand')}}">
                                    <div class="invalid-feedback">
                                        Por favor complete la marca del vehículo
                                    </div>
                                    @error('brand')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="sub_brand">Submarca</label>
                                    <input type="text" class="form-control" id="sub_brand" name="sub_brand" placeholder="Indicar la submarca del vehículo" required value="{{old('sub_brand')}}">
                                    <div class="invalid-feedback">
                                        Por favor complete la submarca del vehículo
                                    </div>
                                    @error('sub_brand')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="model_date">Modelo</label>
                                    <input type="number" class="form-control" id="model_date" name="model_date" placeholder="Indicar el año del vehículo" required value="{{old('model_date')}}">
                                    <div class="invalid-feedback">
                                        Por favor complete el año del vehículo
                                    </div>
                                    @error('model_date')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="zone">Zona</label>
                                    <input type="text" class="form-control" id="zone" name="zone" placeholder="Indicar la zona donde circula la unidad" required value="{{old('zone')}}">
                                    <div class="invalid-feedback">
                                        Por favor complete la zona donde circula la unidad
                                    </div>
                                    @error('zone')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="delegation">Delegación</label>
                                    <input type="text" class="form-control" id="delegation" name="delegation" placeholder="Indicar la delegación donde circula la unidad" required value="{{old('deletagtion')}}">
                                    <div class="invalid-feedback">
                                        Por favor complete la delegación donde circula la unidad
                                    </div>
                                    @error('delegation')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="municipality">Municipio</label>
                                    <input type="text" class="form-control" id="municipality" name="municipality" placeholder="Indicar el municipio donde circula la unidad" required value="{{old('municipality')}}">
                                    <div class="invalid-feedback">
                                        Por favor complete el municipio donde circula la unidad
                                    </div>
                                    @error('municipality')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="concession_number">Núm. Concesión</label>
                                    <input type="text" class="form-control" id="concession_number" name="concession_number" placeholder="Indicar el número de la concesión" required value="{{old('concession_number')}}">
                                    <div class="invalid-feedback">
                                        Por favor complete el número de la concesión
                                    </div>
                                    @error('concession_number')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="id_navixy">ID Navixy</label>
                                    <input type="number" class="form-control" id="id_navixy" name="id_navixy" placeholder="ID Navixy" value="{{old('id_navixy')}}">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="id_cms">ID CMS</label>
                                    <input type="text" class="form-control" id="id_cms" name="id_cms" placeholder="ID CMS" value="{{old('id_cms')}}">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="phone">Teléfono</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Indicar el teléfono" value="{{old('phone')}}">
                                </div>

                                <div class="form-group col-md-3">
                                    <label class="custom-switch mt-4">
                                        <input type="checkbox" name="video" id="video" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Video</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                            <a href="{{route('cars.index')}}" class="btn btn-primary"><i class="fas fa-arrow-alt-circle-left"></i> Regresar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<!-- JS Libraies -->
<script src="{{asset('bundles/datatables/datatables.min.js')}}"></script>
<script src="{{asset('bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('bundles/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Page Specific JS File -->
<script src="{{asset('js/page/datatables.js')}}"></script>

@endpush