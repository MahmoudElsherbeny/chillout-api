<?php

namespace App\Http\Controllers\Api\Stations;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchByDateRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreStationSalesRequest;
use App\Http\Requests\UpdateStationSalesRequest;
use App\Http\Resources\StationsSalesResource;
use App\Traits\ApiResponseTrait;
use App\Models\Stations_sale;
use Carbon\Carbon;
use Exception;

class StationSalesController extends Controller
{
    use ApiResponseTrait;

    public function show(SearchByDateRequest $request) {
        if($request->input('from') || $request->input('to')) {
            $from = $request->input('from');
            $to = $request->input('to');
        }
        else {
            $from = date('Y-m-d');
            $to = date('Y-m-d');
        }

        $station_sales = Auth::user()->stations_sales->where('updated_at','>=', $from)
                                             ->where('updated_at','<=', $to);

        $total = [];
        foreach(Auth::user()->stations_petrol_types as $type) {
            //determine stations petrol types in total  with valu 0 to save total value for each type after that
            array_push($total, ['type' => $type->petrol_type, 'value' => 0]);
        }
        foreach($station_sales as $key => $all_sales) { //loop on every daily sales
            foreach($all_sales->sales as $k => $sale) { //loop on every type with value in sales array
                //check if sales type is the same in total array to save the sum of each same product
                if($all_sales->sales[$k]['type'] == $total[$k]['type']) {
                    $total[$k]['value'] += $sale['value'];
                }
            }
        }

        $sales_data = StationsSalesResource::collection($station_sales);
        $sales_with_total = $sales_data->concat([
                'data' => [
                    'total' => $total,
                ]
            ]);
        return count($station_sales) > 0
            ? $this->apiResponse($sales_with_total, 200, 'مبيعات '.Auth::user()->name)
            : $this->responseErrorMsg(404, 'لم يتم العثور على نتائج');
    }

    public function store(StoreStationSalesRequest $request) {
        try {
            $sales_exist = Stations_sale::WhereDate('created_at', '=', Carbon::today())
                                        ->Where('user_id', Auth::user()->id)
                                        ->first();
            if($sales_exist) {
                return $this->responseErrorMsg(400, 'تم إدخال تمام المبيعات اليومى مسبقاً');
            }
            else {
                $station_sales = Stations_sale::create([
                    'user_id' => Auth::user()->id,
                    'sales' => $request->sales,
                    'created_by' => $request->input('created_by'),
                ]);

                return $this->apiResponse(new StationsSalesResource($station_sales), 200, 'تم تسجيل تمام المبيعات اليومى');
            }
        } catch(Exception $e) {
            return $this->responseErrorMsg(400, 'Erorr: '.$e->getMessage());
        }
    }

    public function update(UpdateStationSalesRequest $request, $id) {
        try {
            $station_sales = Stations_sale::Where('user_id', Auth::user()->id)->find($id);
            if($station_sales) {
                if($station_sales->created_at < Carbon::today()) {
                    return $this->responseErrorMsg(401, 'لا يمكن تعديل التمام بعد الوقت المحدد');
                }
                else {
                    $station_sales->sales = $request->sales;
                    $station_sales->created_by = $request->input('created_by');

                    if($station_sales->isDirty()) {
                        $station_sales->save();
                        return $this->apiResponse(new StationsSalesResource($station_sales), 200, 'تم تعديل مبيعات التمام اليومى');
                    }
                    else {
                        return $this->responseErrorMsg(401, 'لا يوجد تعديلات لتسجيلها');
                    }
                }
            }
            else {
                return $this->responseErrorMsg(404, 'لا يوجد تمام مبيعات مسجل');
            }
        } catch(Exception $e) {
            return $this->responseErrorMsg(400, 'Erorr: '.$e->getMessage());
        }
    }

    public function destroy($id) {
        try {
            $station_sales = Stations_sale::Where('user_id', Auth::user()->id)->find($id);
            if($station_sales) {
                if($station_sales->created_at < Carbon::today()) {
                    return $this->responseErrorMsg(401, 'لا يمكن حذف التمام بعد الوقت المحدد');
                }
                else {
                    $station_sales->delete();
                    return $this->responseSuccessMsg(200, 'تم حذف تمام المبيعات اليومى');
                }
            }
            else {
                return $this->responseErrorMsg(404, 'لا يوجد تمام مبيعات مسجل');
            }
        } catch(Exception $e) {
            return $this->responseErrorMsg(400, 'Erorr: '.$e->getMessage());
        }
    }
}
