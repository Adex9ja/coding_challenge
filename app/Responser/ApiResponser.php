<?php


namespace App\Responser;


trait ApiResponser
{

    public function  successResponse($body,$code){
        return response()->json($body,$code);
    }


    public function  errorResponse($message,$code): \Illuminate\Http\JsonResponse
    {

        return response()->json($message,$code);
    }
}
