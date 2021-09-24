<?php

namespace App\Http\Repository;

use Illuminate\Support\Facades\DB;
use App\Http\Helpers\DT;

class UserRepository {
    public static function listData($request)
    {
        $id         = $request->id;
        $name       = $request->nama;
        $hp         = $request->hp;
        $bindings   = [
            'name'  => "%{$name}%",
            'hp'    => "%{$hp}%"
        ];
        $sql        = "SELECT * FROM user WHERE lower(nama) LIKE lower(:name) AND hp LIKE :hp";

        if ($id) {
            $sql .= " AND id = :id";
            $bindings['id'] = $id;
        }

        $columns    = [
            ['field' => 'id', 'name' => 'id'],
            ['field' => 'nama', 'name' => 'nama'],
            ['field' => 'hp', 'name' => 'hp'],
        ];

        return DT::make($request, $columns, $sql, $bindings);
    }

    public static function insertData($payload)
    {
        return DB::table('user')
                ->insertGetId($payload, 'id');
    }

    public static function updateData($id, $payload)
    {
        return DB::table('user')
                ->where('id', $id)
                ->update($payload);
    }

    public static function getData($id)
    {
        return DB::selectOne("SELECT * FROM user WHERE id = :id", [$id]);
    }

    public static function deleteData($id)
    {
        return DB::table('user')
            ->where('id', $id)
            ->delete();
    }
}
