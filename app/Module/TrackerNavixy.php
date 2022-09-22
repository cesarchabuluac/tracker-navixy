<?php

namespace App\Module;

use App\Models\User;
use App\Traits\ExternalServices;
use Exception;
use Illuminate\Support\Facades\Log;

class TrackerNavixy
{
    use ExternalServices;

    protected $baseUri;
    protected $hashNavixy;

    public function __construct()
    {
        $this->baseUri = env('API_NAVIXY');
        $this->hashNavixy = session()->get('hash_token_navixy');
    }

    /**
     * [loginNavixy description]
     *
     * @return  [type]  [return description]
     */
    public function loginNavixy()
    {
        $client = new \GuzzleHttp\Client(['base_uri' => env('API_NAVIXY')]);

        $response = $client->request('POST', 'user/auth', [
            'json' => [
                'login' => env('API_NAVIXY_LOGIN'),
                'password' => env('API_NAVIXY_PASSWORD')
            ],
            'headers' => [
                'Accept' => 'application/json'
            ],
            'http_errors' => false
        ]);
        return json_decode($response->getBody()->getContents(), true);
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
            'hash' =>  session()->get('hash_token_navixy'),
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
     * [listVehiclesNavixy description]
     *
     * @return  [type]  [return description]
     */
    public function listVehiclesNavixy () {

        return $this->makeRequest(
            "GET",
            "vehicle/list/",
            [
                'hash' =>  session()->get('hash_token_navixy'),
            ],
            [],
            $isJsonRequest = true
        );
    }

    /**
     * [listTracking description]
     *
     * @param   [type]  $trackers  [$trackers description]
     *
     * @return  [type]             [return description]
     */
    public function listTracking($trackers) {
        return $this->makeRequest(
            "GET",
            "tracker/get_states",
            [
                'hash' =>  session()->get('hash_token_navixy'),
                'trackers' => json_encode($trackers),
            ],
            [],
            $isJsonRequest = true
        );
    }
}
