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
                      <table class="table table-striped" id="table-1">
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
                                        <th></th>
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
                                        <td>
                                            <div class="buttons">
                                                @if (!$car->deleted_at)
                                                <a href="{{route('cars.edit', $car->id)}}" class="btn btn-block btn-primary">Editar</a>    
                                                @endif
                                                
                                                @if ($car->deleted_at)
                                                <button data-id="{{$car->id}}" data-status="activar" type="button" class="btn btn-block btn-warning btn-disabled">Activar</button>
                                                @else
                                                <button data-id="{{$car->id}}" data-status="desactivar" type="button" class="btn btn-block btn-danger btn-disabled">Desactivar</button>
                                                @endif
                                                
                                            </div>
                                        </td>

                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="22"></td>
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0-alpha.1/axios.min.js" integrity="sha512-xIPqqrfvUAc/Cspuj7Bq0UtHNo/5qkdyngx6Vwt+tmbvTLDszzXM0G6c91LXmGrRx8KEPulT+AfOOez+TeVylg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('.btn-disabled').on('click', function(e) {
        const id = $(this).data('id')
        const status = $(this).data('status')

        Swal.fire({
            title: ` ¿Deseas ${status} el vehículo?`,
            text: "",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Si, ${status}!`,
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                let url = `{{route('cars.destroy', ':id')}}`
                url = url.replace(':id', id)
                const { data } = await axios.delete(url)
                if (data.success) {
                    Swal.fire({
                        title: 'Éxito',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                    }).then(() => {
                        window.location.reload()
                    })
                }
            },
            allowOutsideClick: () => !Swal.isLoading(),
        })
    })
</script>


@endpush