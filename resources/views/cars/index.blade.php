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
                        <h4>Vehiculos</h4>
                        <a href="{{route('cars.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="vehicles">
                                <thead>
                                    <tr>
                                        <th>
                                            Nombre Proveedor
                                        </th>
                                        <th>ID Empresa</th>
                                        <th>Empresa</th>
                                        <th>Ruta</th>
                                        <th>VIN</th>
                                        <th>EcoNumero</th>
                                        <th>Placa</th>
                                        <th>IMEI</th>
                                        <th>Tipo Unidad</th>
                                        <th>Marca</th>
                                        <th>Submarca</th>
                                        <th>Fecha Modelo</th>
                                        <th>Zona</th>
                                        <th>Delegación</th>
                                        <th>Municipio</th>
                                        <th>Num Consesión</th>
                                        <th>ID Navixy</th>
                                        <th>ID CMS</th>
                                        <th>Video</th>
                                        <th>Teléfono</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cars as $car)
                                    <tr>
                                        <td>{{$car->provider_name}}</td>
                                        <td>{{$car->company_id}}</td>
                                        <td>{{$car->company}}</td>
                                        <td>{{$car->route}}</td>
                                        <td>{{$car->vin}}</td>
                                        <td>{{$car->economic_number}}</td>
                                        <td>{{$car->license_plate}}</td>
                                        <td>{{$car->imei}}</td>
                                        <td>{{$car->unit_type}}</td>
                                        <td>{{$car->brand}}</td>
                                        <td>{{$car->sub_brand}}</td>
                                        <td>{{$car->model_date}}</td>
                                        <td>{{$car->zone}}</td>
                                        <td>{{$car->delegation}}</td>
                                        <td>{{$car->municipality}}</td>
                                        <td>{{$car->concession_number}}</td>
                                        <td>{{$car->id_navixy}}</td>
                                        <td>{{$car->id_cms}}</td>
                                        <td>
                                            <div class="badge badge-{{$car->video ? 'success':'danger'}} badge-shadow">{{$car->video ? 'Si':'No'}}</div>
                                        </td>
                                        <td>{{$car->phone}}</td>

                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="21"></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
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