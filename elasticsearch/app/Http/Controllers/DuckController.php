<?php

namespace App\Http\Controllers;

use App\Duck;
use Illuminate\Http\Request;

class DuckController extends Controller
{
    public function searchForDuck(){
        if($query = \Request::get('query')){
            $results = Duck::search($query)->get();
            dd($results);
            return view('duck_search' , ['query' => $query , 'results' => $results] ); 
        }else{
            return view('duck_search' , ['query' => null] );
        }

       
    }
}
