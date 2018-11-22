<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FilterRequest;

use App\Meal;
use App\Category;
use App\Tag;
use App\Ingredient;
use App\MyClasses\Filter;
use App\Http\Resources\Meals as MealsResource;

//use Illuminate\Support\Facades\Session;
//use Illuminate\Pagination\Paginator;
//use Illuminate\Support\Carbon;

class FilterController extends Controller {


    public function index(Request $request){
         

        $filtered_data = Filter::get_data($request);

       /* if(!$filtered_data) {

        	return response()->json(null,204);
        }*/

    	return $filtered_data;
    }

}



/******Moj način riješavanja pronalaska jela prema više tagova*****/

//Kreiranje prazne kolekcije, provjera za svako jelo da li sadrzava sve tagove, ako ne sadrzava varijabla $contains postavlja se u false i navedeno jelo ne sprema se u kolekciju. Navedeno riješenje sam napravio prije pronalaska boljeg rješenja na stack overflow.


//Kreiranje prazne kolekcije za spremanje jela koja zadovoljavaju uvjet
	/*$filtered_meals = collect();
	$result = array();
	$message = "Tags: ";
	
	//Jela koja zadovoljavaju prvi tag
	
	$meals = Meal::whereHas('tags', function ($tags) use($tag) {
    $tags->where('meal_tag.tag_id', $tag[0]);
	})->get();

	$arr_length = count($tag);

	//Pregledavanje svih jela koja zadovoljavaju prvi tag, da li zadovoljavaju i ostale
	
	foreach($meals as $meal){
		$contains = true;
		for($i=1; $i<$arr_length; $i++){
			
		if($meal->tags->contains($tag[$i]) == false){

				$contains = false;
			}
		}

		if($contains == true){ $filtered_meals->push($meal); }

	}*/

