<?php

namespace App\Http\Controllers\Snippet;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\SnippetRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SnippetController extends ApiController
{
    //

    public function createSnippet(SnippetRequest $request)
    {

        try{

            $body = [
                "url" => route("getSnippet",$request->name),
                "name" => $request->name,
                "expires_at" => Carbon::now()->addSeconds($request->expires_in),
                "snippet" => $request->snippet
            ];

            $response = $this->saveResponseToFile($request->name,$body);


            if($response){
                return $this->successResponse($body,Response::HTTP_CREATED);
            }else{
                return $this->errorResponse("Snippet could not be created at the moment",Response::HTTP_BAD_REQUEST);
            }
        }catch (\Exception $e){
            return $this->errorResponse("Something went wrong",Response::HTTP_INTERNAL_SERVER_ERROR);
        }



    }

    public function getSnippet($name){

        try{
            $body = $this->getResponseFromFile($name);


            if(Carbon::parse($body["expires_at"])->diffInSeconds(Carbon::now()) > 30){
                return $this->errorResponse("",Response::HTTP_NOT_FOUND);
            }

            $body["expires_at"] = Carbon::parse($body["expires_at"])->addSeconds(30);

            $formattedData = json_encode($body,JSON_PRETTY_PRINT);

            $response = $this->saveResponseToFile($name,$formattedData);
            if($response){
                return $this->successResponse($body,Response::HTTP_CREATED);
            }
        }catch (\Exception $e){
            return $this->errorResponse("Something went wrong",Response::HTTP_INTERNAL_SERVER_ERROR);
        }



      //  return Carbon::parse($data["expires_at"])->diffInSeconds(Carbon::now());


//        if(Carbon::parse($data["expires_at"])->diffInSeconds(Carbon::now()) > 30){
//            return $this->errorResponse("",Response::HTTP_NOT_FOUND);
//        }
//
//        Storage::delete("snippets/".$fileName);
//        $data["expires_at"] = Carbon::parse($data["expires_at"])->addSeconds(30);
//        $response = Storage::disk("local")->put("snippets/".$fileName,json_encode($data,JSON_PRETTY_PRINT));
//
//        if($response){
//            return $this->successResponse($data,Response::HTTP_OK);
//        }

    }

    private function saveResponseToFile($fileName,$body): bool
    {
        $fileName = $fileName . ".txt";
        $data = json_encode($body,JSON_PRETTY_PRINT);

        return Storage::disk("local")->put("snippets/".$fileName,$data);
    }

    private  function getResponseFromFile($fileName)
    {
        $fileName = $fileName . ".txt";

        $snippet = Storage::get("snippets/".$fileName);

         return json_decode($snippet,true);
    }
}
