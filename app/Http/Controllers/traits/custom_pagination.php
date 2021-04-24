<?php

namespace App\Http\Controllers\traits;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

trait custom_pagination
{
    private function custom_paginate($request, $array, $perPage){

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $itemCollection = collect($array);
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
            $paginatedItems->setPath($request->url());
            
            if(empty(Input::get('page'))){
                $page = 0;
            }else{
                $page = Input::get('page');
            }

            return array($paginatedItems,$page);        
    	}
}
