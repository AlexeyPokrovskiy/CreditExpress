<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class ExchengeController extends Controller
{
    public function currentExchenge(){

        $client = new Client();
        $res = $client->request('GET', 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5');

        return $res->getBody();
    }

    public function currentArhive(Request $request){

        if(!$request->date){
            $result = array(
                'messages' => "Не верно указана дата."
            );
            return response()->json($result);
        }

        $format_date = date_format(date_create($request->date), 'd.m.Y');

        $client = new Client();
        $res = $client->request('GET', "https://api.privatbank.ua/p24api/exchange_rates?json&date=$format_date");

        return $res->getBody();
    }
}
