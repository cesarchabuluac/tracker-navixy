<?php

namespace App\Module;

use App\Models\User;
use App\Traits\ExternalServices;
use Exception;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Log;

class TrackerSemov
{
    use ExternalServices;

    protected $baseUri;
    protected $hashSemov;

    public function __construct()
    {
        $this->baseUri = env('API_SEMOV');
        $this->hashSemov = session()->get('hash_session_semov');
    }

    public function authSemov()
    {
        return $this->makeRequest(
            "GET",
            "/StandardApiAction_login.action",
            [
                'account' => env('API_ACCOUNT_SEMOV'),
                'password' => env('API_PASSWORD_SEMOV'),
            ],
            [],
            $isJsonRequest = true
        );
    }


    /**
     * [resolveAuthorization description]
     *
     * @param   [type]  $queryParams  [$queryParams description]
     * @param   [type]  $formParams   [$formParams description]
     * @param   [type]  $headers      [$headers description]
     *
     * @return  [type]                [return description]
     */
    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers = array(
            'jsession' => session()->get('hash_session_semov'),
        );
    }

    /**
     * [decodeResponse description]
     *
     * @param   [type]  $response  [$response description]
     *
     * @return  [type]             [return description]
     */
    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    /**
     * [listUserVehicle description]
     *
     * @return  [type]  [return description]
     */
    public function listUserVehicle()
    {
        return $this->makeRequest(
            "GET",
            "/StandardApiAction_queryUserVehicle.action",
            [
                'jsession' => session()->get('hash_session_semov'),
            ],
            [],
            $isJsonRequest = true
        );
    }

    /**
     * [listDeviceStatus description]
     *
     * @param   [type]  $trackers  [$trackers description]
     *
     * @return  [type]             [return description]
     */
    public function listDeviceStatus()
    {
        return $this->makeRequest(
            "GET",
            "/StandardApiAction_getDeviceStatus.action",
            [
                'jsession' => session()->get('hash_session_semov'),
            ],
            [],
            $isJsonRequest = true
        );
    }

    /**
     * [listVehicleAlarm description]
     *
     * @return  [type]  [return description]
     */
    public function listVehicleAlarm()
    {
        return $this->makeRequest(
            "GET",
            "/StandardApiAction_vehicleAlarm.action",
            [
                'jsession' => session()->get('hash_session_semov'),
            ],
            [],
            $isJsonRequest = true
        );
    }
}
