<?php

namespace App\Http\Controllers\Api\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponseTrait;
use App\Models\Stations_petrol_type;
use App\Models\User;
use Exception;

class UsersController extends Controller
{
    use ApiResponseTrait;

    // create new user by supervisors only
    public function store(StoreUserRequest $request) {
        try {
            DB::beginTransaction();
                $user = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'is_admin' => $request->input('user_type'),
                ]);

                if($user->is_admin == 0) {
                    foreach($request->petrol_types as $petrol_type){
                        Stations_petrol_type::create([
                            'user_id' => $user->id,
                            'petrol_type' => $petrol_type['type'],
                            'storage_num' => $petrol_type['storage_num'],
                            'storage_capacity' => $petrol_type['storage_capacity'],
                        ]);
                    }
                }
            DB::commit();

            return $this->apiResponse(new UserResource($user), 200, 'تم انشاء مستخدم جديد');
        } catch(Exception $e) {
            DB::rollBack();
            return $this->responseErrorMsg(400, 'Erorr: '.$e->getMessage());
        }
    }

    // update users by supervisors only
    public function update(UpdateUserRequest $request, $id) {
        try {
            $user = User::find($id);
            if($user && $user->is_admin < 2) {
                $user->name = $request->input('name');
                if($user->isDirty()) {
                    $user->save();
                    return $this->apiResponse(new UserResource($user), 200, 'تم تعديل المستخدم');
                }
                else {
                    return $this->responseErrorMsg(401, 'لا يوجد تعديلات لتسجيلها');
                }
            }
            else {
                return $this->responseErrorMsg(404, 'المستخدم غير مسجل');
            }
        } catch(Exception $e) {
            return $this->responseErrorMsg(400, 'Erorr: '.$e->getMessage());
        }
    }

    // delete users by supervisors only
    public function destroy($id) {
        try {
            $user = User::find($id);
            if($user) {
                if($user->is_admin == 2) {
                    return $this->responseErrorMsg(404, 'لا يمكن حذف المستخدم');
                }
                else {
                    DB::beginTransaction();
                        $user->delete();
                        $user->stations_petrol_types->each->delete();
                    DB::commit();

                    return $this->responseSuccessMsg(200, 'تم حذف المستخدم');
                }
            }
            else {
                return $this->responseErrorMsg(404, 'المستخدم غير مسجل');
            }
        } catch(Exception $e) {
            DB::rollBack();
            return $this->responseErrorMsg(400, 'Erorr: '.$e->getMessage());
        }
    }
}
