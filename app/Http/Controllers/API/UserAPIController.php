<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAPIController extends Controller
{
    public function getData(Request $request)
    {
        return UserRepository::listData($request);
    }

    public function saveData(Request $request)
    {
        $payload = $request->post();
        try {
            DB::beginTransaction();
            $result = UserRepository::insertData($payload);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function updateData($id, Request $request)
    {
        $payload = $request->post();
        try {
            DB::beginTransaction();
            $result = UserRepository::updateData($id, $payload);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function deleteData(Request $request)
    {
        $id = $request->id;
        try {
            DB::beginTransaction();
            $result = UserRepository::deleteData($id);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
}
