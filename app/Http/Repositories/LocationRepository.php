<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\LocationInterface;
use App\Http\Resources\API\V1\Calendar\IndexResource;
use App\Http\Traits\JSONResponseTrait;
use App\Models\Calendar;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Request;

class LocationRepository implements LocationInterface
{
    use JSONResponseTrait;

    // Mode Driving, Bicycling, Transit, Walking
    private $type = 'driving';
    private $key = 'AIzaSyAThu0exNmJ1hx42iCJMqDUqPO2RMJU7ic';
    private $api = 'https://maps.googleapis.com/maps/api/distancematrix/json';
    private $parameters,
            $response;

    protected $distance,
              $duration,
              $lastRecord;


    public function distance($currentLocation, $targetLocation)
    {
        $this->parameters = [
            'origins' => $currentLocation,
            'destinations' => $targetLocation,
            'mode' => $this->type,
            'key' => $this->key,
            'language' => 'en',
            'sensor' => false
        ];

        //$this->api .= http_build_query($this->parameters, '', '&');

        $this->lastRecord = Carbon::now()->format('Y-m-d H:i:s');

        if(!is_null($this->lastRecord())) {
            $this->lastRecord = $this->lastRecord()->turnaround_time;
        }

        return $this;
    }

    public function calculate($mode = NULL) {

        if (!is_null($mode)) {
            $this->type = $mode;
        }

        $client = new Client([
            'verify' => false
        ]);

        $response = $client->request('GET', $this->api, [
            'query' =>  $this->parameters
        ]);

        if($response->getStatusCode() == '200') {
            $this->response = json_decode($response->getBody()->getContents())->rows[0]->elements[0];
            if($this->response->status != 'NOT_FOUND') {
                $this->distance =  $this->response->distance->value;
                $this->duration =  $this->response->duration->value;
            }
        }

        return $this;
    }

    public function create($parameters = []) {

        if(is_null($this->distance) || is_null($this->duration)) {
            return $this->failed(['Whoops. Calculation failed']);
        }

        /**
         * Gidilecek mesafenin KM Hesabı
         */
        $parameters['distance'] = number_format($this->distance / 1000, 0);

        /**
         *  Turn Around Time
         *  Gidiş ve Geliş Süresi Hesaplanmıştır.
         *  Ayrıca 1 Saat'te üzerine ekstradan konulmuştur.
         */
        $parameters['turnaround_time'] = Carbon::parse($this->lastRecord)->addSeconds(($this->duration * 2) + 3600)->format('Y-m-d H:i:s');
        /**
         *  Emlak Danışmanın Son Randevusu
        */
        $parameters['arrive_time'] = $this->lastRecord;
        /**
         *  Hangi Danışman Olduğu
        */
        $parameters['consultant'] = auth()->user()->id;
        /*
         *  Randevunun Durumu
         */
        $parameters['status'] = 'waiting';

        $create = Calendar::create($parameters);

        if($create) {
            return $this->success(new IndexResource($create));
        }

        return $this->unknownError();
    }

    public function update($calendar, $parameters = []) {
        if(!is_null($this->distance)) {
            $parameters['distance'] = number_format($this->distance / 1000, 0);
        }

        if(!is_null($this->duration)) {
            $parameters['turnaround_time'] = Carbon::parse($calendar->arrive_time)->addSeconds(($this->duration * 2) + 3600)->format('Y-m-d H:i:s');
        }

        if($calendar->update($parameters)) {
            return $this->success(new IndexResource($calendar));
        }

        return $this->unknownError();
    }

    private function lastRecord() {
        return Calendar::where('consultant', auth()->user()->id)->orderByDesc('turnaround_time')->first();
    }

    public function delete($calendar) {
        if($calendar->delete()) {
            return $this->success(['id' => $calendar->id]);
        }

        return $this->unknownError();
    }
}