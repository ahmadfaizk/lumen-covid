<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CovidController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function indonesia() {
        $client = new Client();
        $response = $client->get('https://services5.arcgis.com/VS6HdKS0VfIhv8Ct/arcgis/rest/services/Statistik_Perkembangan_COVID19_Indonesia/FeatureServer/0/query?where=1%3D1&outFields=*&outSR=4326&f=json');
        $body = collect(json_decode($response->getBody(), true));
        $data = collect([]);
        foreach ($body['features'] as $attributes) {
            if ($attributes['attributes']['Jumlah_Kasus_Kumulatif'] == null) {
                break;
            }
            $data->push($attributes['attributes']);
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function prov() {
        $client = new Client();
        $response = $client->get('https://services5.arcgis.com/VS6HdKS0VfIhv8Ct/arcgis/rest/services/COVID19_Indonesia_per_Provinsi/FeatureServer/0/query?where=1%3D1&outFields=*&outSR=4326&f=json');
        $body = collect(json_decode($response->getBody(), true));
        $data = collect([]);
        foreach ($body['features'] as $attributes) {
            $data->push($attributes['attributes']);
        }
        $data->pop();
        return response()->json([
            'data' => $data
        ]);
    }
}
