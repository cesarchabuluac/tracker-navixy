<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Module\TrackerNavixy;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\User;
use App\Models\VehicleTracker;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class VehicleController extends BaseController
{

    private function setHashToken($token)
    {
        auth()->user()->update(['hash_token_navixy' => $token]);
    }

    private function getTracking(TrackerNavixy $service, Request $request)
    {
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
                    $vehicle = Vehicle::find($item['id']);
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
                                'last_updated' => $item['last_update'],
                                'movement_status' => $item['movement_status'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }
                    });

                    VehicleTracker::insert($data);

                    $vehicles = VehicleTracker::with('vehicle')->whereIn('vehicle_id', $vehicleIds)->latest('last_updated')->get()->unique('vehicle_id');

                    DB::commit();

                    return $vehicles;
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
            $user = User::find(auth()->user()->id);

            if ($user->hash_token_navixy) {
                $result = $this->getTracking($service, $request);

                if (isset($result['success']) && !boolval($result['success'])) {
                    $response = $service->loginNavixy();
                    if (boolval($response['success'])) {
                        $this->setHashToken($response['hash']);
                        $result = $this->getTracking($service, $request);
                        return $this->sendResponse($result, "Tracking Vehicles retrieved successfully");
                    } else {
                        return $this->sendError($response['status']['description'], $response['errors'], 500);
                    }
                }

                return $this->sendResponse($result, "Tracking Vehicles retrieved successfully");
            } else {
                $response = $service->loginNavixy();
                if (boolval($response['success'])) {

                    $this->setHashToken($response['hash']);
                    $result = $this->getTracking($service, $request);
                    return $this->sendResponse($result, "Tracking Vehicles retrieved successful");
                } else {
                    return $this->sendError($response['status']['description'], $response['errors'], 500);
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->sendError($ex->getMessage(), 500);
        }
    }
}
