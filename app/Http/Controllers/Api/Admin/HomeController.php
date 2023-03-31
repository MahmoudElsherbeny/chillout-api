<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StationsReportRequest;
use App\Http\Resources\StationsSalesResource;
use App\Traits\ApiResponseTrait;
use App\Models\Stations_sale;

class HomeController extends Controller
{
    use ApiResponseTrait;

    public function index(StationsReportRequest $request) {
        if($request->input('from') || $request->input('to')) {
            $from = $request->input('from');
            $to = $request->input('to');
        }
        else {
            $from = date('Y-m-d');
            $to = date('Y-m-d');
        }
        // StationsSalesResource::collection(Stations_sale::all())

        $sales = $request->input('station') == 'all'
            ? Stations_sale::whereDate('updated_at','>=',$from)
                           ->whereDate('updated_at','<=',$to)
                           ->get()
            : Stations_sale::whereDate('updated_at','>=',$from)
                           ->whereDate('updated_at','<=',$to)
                           ->find($request->input('station'));

        return count($sales) > 0
            ? $this->apiResponse(StationsSalesResource::collection($sales), 200, 'stations sales')
            : $this->responseErrorMsg(404, 'no stations sales found');
    }
}
