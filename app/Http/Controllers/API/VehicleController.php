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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class VehicleController extends BaseController
{

    private function setHashToken($token)
    {
        auth()->user()->update(['hash_token_navixy' => $token]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //Service Navixy
        $service = app()->make(TrackerNavixy::class);
        $user = User::find(auth()->user()->id);

        if ($user->hash_token_navixy) {

            $response = $service->listVehiclesNavixy();
            $response = json_decode($response, true);
            if (boolval($response['success'])) {

                //Create Vehicles on database local
                $data = [];
                collect($response['list'])->each(function ($item) use (&$data) {
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
                        $vehicle->save();
                    }
                });

                if (!empty($data)) {
                    Vehicle::insert($data);
                }

                //Get tracking       
                $response = $service->listTracking($request->trackers);
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
                    return $this->sendResponse($vehicles, "Tracking Vehicles retrieved successfully");
                } else {
                    return $this->sendError($response['status']['description'], $response['errors'], 500);
                }
            } else {
                $response = $service->loginNavixy();
                if (boolval($response['success'])) {
                    $this->setHashToken($response['hash']);
                } else {
                    return $this->sendError($response['status']['description'], $response['errors'], 500);
                }
            }
        } else {
            $response = $service->loginNavixy();
            if (boolval($response['success'])) {
                $this->setHashToken($response['hash']);
            } else {
                return $this->sendError($response['status']['description'], $response['errors'], 500);
            }
        }
    }
}
