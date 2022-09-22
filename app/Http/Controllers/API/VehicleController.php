<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Module\TrackerNavixy;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Car;
use App\Models\User;
use App\Models\VehicleTracker;
use App\Module\TrackerSemov;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class VehicleController extends BaseController
{

    /**
     * [setHashToken description]
     *
     * @param   [type]  $token  [$token description]
     *
     * @return  [type]          [return description]
     */
    private function setHashToken($token)
    {
        session()->put('hash_token_navixy', $token);
    }

    /**
     * [setHasSessionSemov description]
     *
     * @param   [type]  $session  [$session description]
     *
     * @return  [type]            [return description]
     */
    private function setHasSessionSemov($session)
    {
        session()->put('hash_session_semov', $session);
    }

    private function getTracking(TrackerNavixy $service, TrackerSemov $serviceSemov, Request $request)
    {
        $url_video = env('API_VIDEO_SEMOV');
        $carsSemov = Car::get();
        $response = $service->listVehiclesNavixy();
        $response = json_decode($response, true);
        try {
            if (boolval($response['success'])) {

                DB::beginTransaction();

                //Create Vehicles on database local
                $data = [];
                $trackersID = [];
                collect($response['list'])->each(function ($item) use (&$data, &$trackersID) {
                    if (!empty($item['tracker_id'])) {
                        $trackersID[] = $item['tracker_id'];
                    }

                    if (!empty($item['tracker_id'])) {
                        $vehicle = Vehicle::where('tracker_id', $item['tracker_id'])->first();
                        if (empty($vehicle)) {
                            $data[] = [
                                'id' => $item['id'],
                                'icon_color' => $item['icon_color'] ?? null,
                                'tracker_id' => $item['tracker_id'] ?? null,
                                'tracker_label' => $item['tracker_label'] ?? null,
                                'label' => $item['label'] ?? null,
                                'model' => $item['model'] ?? null,
                                'type' => $item['type'] ?? null,
                                'subtype' => $item['subtype'] ?? null,
                                'color' => $item['color'] ?? null,
                                'additional_info' => $item['additional_info'] ?? null,
                                'reg_number' => $item['reg_number'] ?? null,
                                'vin' => $item['vin'] ?? null,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        } else {
                            $vehicle->icon_color = $item['icon_color'] ?? null;
                            $vehicle->tracker_id = $item['tracker_id'] ?? null;
                            $vehicle->tracker_label = $item['tracker_label'] ?? null;
                            $vehicle->label = $item['label'] ?? null;
                            $vehicle->model = $item['model'] ?? null;
                            $vehicle->type = $item['type'] ?? null;
                            $vehicle->subtype = $item['subtype'] ?? null;
                            $vehicle->color = $item['color'] ?? null;
                            $vehicle->additional_info = $item['additional_info'] ?? null;
                            $vehicle->reg_number = $item['reg_number'] ?? null;
                            $vehicle->vin = $item['vin'] ?? null;
                            $vehicle->updated_at = Carbon::now();
                            $vehicle->save();
                        }
                    }
                });

                if (!empty($data)) {
                    Vehicle::insert($data);
                }

                //Get tracking       
                $response = $service->listTracking($trackersID);
                $response = json_decode($response, true);
                if (boolval($response['success'])) {

                    //Create Vehicles Tracking on database local
                    $data = [];
                    $vehicleIds = [];
                    collect($response['states'])->each(function ($item, $index) use (&$data, &$vehicleIds) {
                        $vehicle = Vehicle::where('tracker_id', $index)->first();
                        if ($vehicle) {
                            $vehicleIds[] = $vehicle->id;
                            $data[] = [
                                'vehicle_id' => $vehicle->id,
                                'source_id' => $item['source_id'],
                                'lat' => $item['gps']['location']['lat'],
                                'lng' => $item['gps']['location']['lng'],
                                'speed' => $item['gps']['speed'],
                                'alt' => $item['gps']['alt'],
                                'last_updated' => $item['last_update'],
                                'movement_status' => $item['movement_status'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }
                    });

                    VehicleTracker::insert($data);                    

                    //Device Status
                    $userDeviceStatus = $serviceSemov->listDeviceStatus();
                    $userDeviceStatus = json_decode($userDeviceStatus, true);

                    if (!empty($userDeviceStatus)) {
                        foreach ($userDeviceStatus['status'] as $key => $device) {
                            foreach ($carsSemov as $key => $car) {
                                if ($car['imei'] == $device['id'] && empty($car['id_navixy'])) {
                                    $car['Fecha'] = Carbon::parse($device['gt'])->format('d/m/Y');
                                    $car['Hora'] = Carbon::parse($device['gt'])->format('H:i:s');
                                    $car['Latitud'] = $device['lat'];
                                    $car['Longitud'] = $device['lng'];
                                    $car['Velocidad'] = $device['sp'] > 0 ? $device['sp'] / 10 : 0;
                                    if ($car['video']) {
                                        $url_video = str_replace('{IMEI}', $car['imei'], $url_video);
                                        $car['UrlCamara'] = $url_video;
                                    }
                                } else {
                                    $vehicle = Vehicle::with('trackings')->where('tracker_id', $car['id_navixy'])->first();
                                    if (!empty($vehicle)) {
                                        if (!empty($vehicle->trackings)) {
                                            $last = $vehicle->trackings->last();
                                            $car['Fecha'] = Carbon::parse($last['last_updated'])->format('d/m/Y');
                                            $car['Hora'] = Carbon::parse($last['last_updated'])->format('H:i:s');
                                            $car['Latitud'] = $last['lat'];
                                            $car['Longitud'] = $last['lng'];
                                            $car['Velocidad'] = $last['speed'] > 0 ? $last['sp'] / 10 : 0;
                                            $car['Altitud'] = $last['alt'];

                                            if($car['video'] && !empty($car['id_cms'])) {
                                                $url_video = str_replace('{IMEI}', $car['id_cms'], $url_video);
                                                $car['UrlCamara'] = $url_video;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //Device Alarm
                    $userVehicleAlarm = $serviceSemov->listVehicleAlarm();
                    $userVehicleAlarm = json_decode($userVehicleAlarm, true);
                    if (!empty($userVhicleAlarm)) {
                        if (!empty($userVhicleAlarm['alarmlist'])) {
                            foreach ($carsSemov as $key => $car) {
                                foreach ($userVehicleAlarm as $key => $alarm) {
                                    if ($car['imei'] == $alarm['DevIDNO'] && $alarm['type'] == 19) {
                                        $car['alarm'] = true;
                                    }
                                }
                            }
                        }
                    }

                    // Log::info(json_encode($cars));
                    $json = collect($carsSemov)->map(function ($item) {
                        return [
                            // "EsNavixy" => $item['is_navixy'],
                            "NombreProveedor" => $item['provider_name'],
                            "IDEmpresa" => 0,
                            "Empresa" => $item['company'],
                            "Ruta" => $item['route'],
                            "Fecha" => $item['Fecha'],
                            "Hora" => $item['Hora'],
                            "VIN" => $item['vin'],
                            "EcoNumero" => $item['economic_number'],
                            "Placa" => $item['license_plate'],
                            "IMEI" => $item['imei'],
                            "Latitud" => $item['Latitud'] ?? 0,
                            "Longitud" => $item['Longitud'] ?? 0,
                            "Altitud" => $item['Altitud'] ?? 0,
                            "Velocidad" => $item['Velocidad'] ?? 0,
                            "Direccion" => 0,
                            "BotonPanico" => $item['alarm'] ?? false,
                            "UrlCamara" => $item['UrlCamara'],
                            "TipodeUnidad" => $item['unit_type'],
                            "Marca" => $item['brand'],
                            "Submarca" => $item['sub_brand'],
                            "Fechamodelo" => $item['model_date'],
                            "Zona" => $item['zone'],
                            "Delegacion" => $item['delegation'],
                            "Municipio" => $item['municipality'],
                            "Numconsesion" => $item['concession_number'],
                            "Activo" => !empty($item['deleted_at']) ? 1 : 0,
                            "CartaEqui" => "http://www.semov.net:8088/Carta.pdf",
                            "FechaIns" => null,
                            "FechaVen" => null
                        ];
                    });

                    DB::commit();

                    return $json;
                } else {
                    DB::rollBack();
                    return $response;
                }
            } else {
                DB::rollBack();
                return $response;
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->sendError($ex->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            //Service Navixy
            $service = app()->make(TrackerNavixy::class);
            $serviceSemov = app()->make(TrackerSemov::class);
            // $user = User::find(auth()->user()->id);

            $resultNavixy = $service->loginNavixy();
            if (boolval($resultNavixy['success'])) {
                $this->setHashToken($resultNavixy['hash']);
            }

            //Set has semov
            $resultSemov = $serviceSemov->authSemov();
            $result = json_decode($resultSemov, true);
            if (isset($result['jsession'])) {
                $this->setHasSessionSemov($result['jsession']);
            }


            if (session()->get('hash_token_navixy')) {
                $result = $this->getTracking($service, $serviceSemov, $request);
                if (isset($result['success']) && !boolval($result['success'])) {
                    $response = $service->loginNavixy();
                    if (boolval($response['success'])) {
                        $this->setHashToken($response['hash']);
                        $result = $this->getTracking($service, $serviceSemov, $request);
                        return response()->json($result);
                        // return $this->sendResponse($result, "Tracking Vehicles retrieved successfully");
                    } else {
                        return $this->sendError($response['status']['description'], $response['errors'], 500);
                    }
                }

                // return $this->sendResponse($result, "Tracking Vehicles retrieved successfully");
                return response()->json($result);
            } else {
                $response = $service->loginNavixy();
                if (boolval($response['success'])) {
                    $this->setHashToken($response['hash']);
                    $result = $this->getTracking($service, $serviceSemov, $request);
                    // return $this->sendResponse($result, "Tracking Vehicles retrieved successful");
                    return response()->json($result);
                } else {
                    return $this->sendError($response['status']['description'], $response['errors'], 500);
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    public function authSemov(Request $request)
    {
        try {

            //Service Semov
            $service = app()->make(TrackerSemov::class);
            $result = $service->authSemov();
            $result = json_decode($result, true);
            if (isset($result['jsession'])) {
                $this->setHasSessionSemov($result['jsession']);
            }
        } catch (Exception $ex) {
            Log::warning($ex->getMessage());
        }
    }
}
