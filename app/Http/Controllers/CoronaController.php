<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;

class CoronaController extends Controller
{ 

    protected $url          = "https://api.kawalcorona.com/";
    protected $ind          = "indonesia";
    protected $prov         = "provinsi";
    protected $positif      = "positif";
    protected $sembuh       = "sembuh";
    protected $meninggal    = "meninggal";


    public function index()
    {
        $url = $this->url;
        $response = CoronaController::getAPI($url); 
        $datas = \json_decode($response);

        return Datatables::of($datas)->make(true);
    }

    public function getAPI($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        curl_close($curl);  
        return $response;
    }

    public function getJumlah($url)
    { 
        $response = CoronaController::getAPI($url); 

        $jmlpositif     = 0;
        $jmlsembuh      = 0;
        $jmlmeninggal   = 0;
        $datas          = \json_decode($response); 

        foreach ($datas as $r) { 
            $jmlpositif     += $r->attributes->Confirmed;
            $jmlsembuh      += $r->attributes->Recovered;
            $jmlmeninggal   += $r->attributes->Deaths;
        }

        $listjml = [$jmlpositif, $jmlsembuh, $jmlmeninggal];
        $json = json_encode($listjml);  
        return $json;
    }


    public function dunia()
    {
        $url = $this->url;
        $response   = CoronaController::getAPI($url); 
        $datas      = \json_decode($response);

        $status     = '["Total Positif", "Total Meninggal", "Total Sembuh"]';
        // $statusjml  = \json_encode($status);  
        $jumlah     = CoronaController::getJumlah($url); 
 

        $dataku = [
                    'data'      => $datas,
                    'status'    => $status,
                    'jml'       => $jumlah,
                ];
        return view('corona.dunia', $dataku);  
        
    }
}
