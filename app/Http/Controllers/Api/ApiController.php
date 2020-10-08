<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ApiController extends Controller
{
    public function getDataClient()
    {
        $dataClient = json_decode(file_get_contents('https://agrcf.lib.id/exercice@dev'), true);
        return $dataClient;
    }
}
