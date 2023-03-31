<?php

namespace App\Http\Controllers\Api\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStationProductRequest;
use App\Http\Requests\UpdateStationProductRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponseTrait;
use App\Models\Stations_petrol_type;
use App\Models\User;
use Exception;

class StationsProductsController extends Controller
{
    use ApiResponseTrait;

    // add station new petrol product by supervisors only
    public function store(StoreStationProductRequest $request,$station) {
        $station = User::Where('is_admin', 0)->find($station);
        try {
            if($station) {
                Stations_petrol_type::create([
                    'user_id' => $station->id,
                    'petrol_type' => $request->input('petrol_type'),
                    'storage_num' => $request->input('storage_num'),
                    'storage_capacity' => $request->input('storage_capacity'),
                ]);

                return $this->apiResponse(new UserResource($station), 200, 'تم إضافة منتج جديد للمحطة');
            }
            else {
                return $this->responseErrorMsg(404, 'المحطة غير مسجلة');
            }
        } catch(Exception $e) {
            return $this->responseErrorMsg(400, 'Erorr: '.$e->getMessage());
        }
    }

    // update station petrol product by supervisors only
    public function update(UpdateStationProductRequest $request, $station, $product) {
        try {
            $station = User::Where('is_admin', 0)->find($station);
            if($station) {
                $petrol_type = $station->stations_petrol_types->find($product);
                if($petrol_type) {
                    $petrol_type->storage_num = $request->input('storage_num');
                    $petrol_type->storage_capacity = $request->input('storage_capacity');
                    if($petrol_type->isDirty()){
                        $petrol_type->save();
                        return $this->apiResponse(new UserResource($station), 200, 'تم تعديل المنتج للمحطة');
                    }
                    else {
                        return $this->responseErrorMsg(401, 'لا يوجد تعديلات لتسجيلها');
                    }
                }
                else {
                    return $this->responseErrorMsg(404, 'المنتج غير مسجل للمحطة');
                }
            }
            else {
                return $this->responseErrorMsg(404, 'المحطة غير مسجلة');
            }

        } catch(Exception $e) {
            return $this->responseErrorMsg(400, 'Erorr: '.$e->getMessage());
        }
    }

    // delete station petrol product by supervisors only
    public function destroy($station, $product) {
        try {
            $station = User::Where('is_admin', 0)->find($station);
            if($station) {
                $petrol_type = $station->stations_petrol_types->find($product);
                if($petrol_type) {
                    $petrol_type->delete();
                    return $this->responseSuccessMsg(200, 'تم حذف المنتج');
                }
                else {
                    return $this->responseErrorMsg(404, 'المنتج غير مسجل للمحطة');
                }
            }
            else {
                return $this->responseErrorMsg(404, 'المحطة غير مسجلة');
            }
        } catch(Exception $e) {
            return $this->responseErrorMsg(400, 'Erorr: '.$e->getMessage());
        }
    }
}
