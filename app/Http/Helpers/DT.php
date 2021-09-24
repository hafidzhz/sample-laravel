<?php
namespace App\Http\Helpers;
use Illuminate\Support\Facades\DB;

class DT
{
    static function make ( $request, $columns, $sql, $filter )
    {
        $bindings = array();

        // Data set length after filtering
        $resFilterLength = DB::select("SELECT COUNT(*) as count FROM ($sql) as alias_sub", $filter);
        $recordsFiltered = $resFilterLength[0]->count;

        // Build the SQL query string from the request
        $limit = self::limit( $request, $columns );
        $order = self::order( $request, $columns );


        $sql .= "
				$order
		";

        $sql .= "
				$limit
		";

        $data = DB::select($sql, $filter);

        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request->draw ) ? intval( $request->draw ) : 0,
            "recordsTotal"    => intval( $recordsFiltered ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => self::data_output( $columns, $data )
        );
    }

    static function makeWithoutCount ( $request, $columns, $sql, $filter )
    {
        $bindings = array();

        // Data set length after filtering
        // Build the SQL query string from the request
        $limit = self::limit( $request, $columns );
        $order = self::order( $request, $columns );


        $sql .= "
				$order
		";

        $sql .= "
				$limit
		";

        $data = DB::select($sql, $filter);

        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request->draw ) ? intval( $request->draw ) : 0,
            "recordsTotal"    => 5000000,
            "recordsFiltered" => 5000000,
            "data"            => self::data_output( $columns, $data )
        );
    }

    static function data_output ( $columns, $data )
    {
        $out = array();

        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            $row = array();

            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];

                // Is there a formatter?
                if ( isset( $column['formatter'] ) ) {
                    if(empty($column['field'])){
                        $row[ $column['name'] ] = $column['formatter']( $data[$i] );
                    }
                    else{
                        $row[ $column['name'] ] = $column['formatter']( $data[$i][ $column['field'] ], $data[$i] );
                    }
                }
                else {
                    if(!empty($column['field']))
                    {
                        $row[ $column['name'] ] = $data[$i]->{$columns[$j]['name']};
                    }
                    else
                    {
                        $row[ $column['name'] ] = "";
                    }
                }
            }

            $out[] = $row;
        }

        return $out;
    }

    static function limit ( $request, $columns )
    {
        $limit = '';

        if ( isset($request->start) && $request->length != -1 )
        {
            $limit = "OFFSET ".intval($request->start)." LIMIT ".intval($request->length);
        }

        return $limit;
    }

    static function order ( $request, $columns )
    {
        $order = '';

        if ( isset($request->order) && count($request->order) )
        {
            $orderBy = array();
            $dtColumns = self::pluck( $columns, 'name' );

            for ( $i=0, $ien=count($request->order) ; $i<$ien ; $i++ )
            {
                /* Convert the column index into the column data property */
                $columnIdx = intval($request->order[$i]['column']);
                $requestColumn = $request->columns[$columnIdx];

                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];

                if ( $requestColumn['orderable'] == 'true' )
                {
                    $dir = $request->order[$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';

                    $orderBy[] = ''.$column['field'].' '.$dir;
                }
            }

            if ( count( $orderBy ) )
            {
                $order = 'ORDER BY '.implode(', ', $orderBy);
            }
        }

        return $order;
    }

    static function pluck ( $a, $prop )
    {
        $out = array();

        for ( $i=0, $len=count($a) ; $i<$len ; $i++ )
        {
            if(empty($a[$i][$prop])){
                continue;
            }
            $out[$i] = $a[$i][$prop];
        }

        return $out;
    }

    public static function dbGetOne($sql, $params = [])
    {
        return self::getOne(DB::select($sql, $params));
    }


    public static function getOne($select_result)
    {
        if (!$select_result) return '';
        return head(head($select_result));
    }
}
