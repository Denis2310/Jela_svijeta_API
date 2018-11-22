<?php

namespace App\MyClasses;
	
use App\Meal;
use App\Category;
use App\Tag;
use App\Ingredient;
use App\Http\Resources\Meal as MealResource;
use App\Http\Resources\MealCollection;
use Validator;
use Illuminate\Support\Facades\Input;

class Filter{

    private static $validation_rules = [

            'per_page' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
            'category' => ['nullable', 'regex: /^(\d+|null|!null)?$/i'],
            'tags' => ['nullable', 'regex: /^(\d+(\s*,\s*\d+)*)?$/'],
            'diff_time' => 'nullable|integer|min:1',
            'with' => 'nullable',
            'lang' => 'nullable|string',
        ];


    public static function get_data($request){

        $per_page = 5; //default
        $requestDate = null;
        $with = null;

        $validator = Validator::make($request->all(), static::$validation_rules);

        if ($validator->fails()) {

            return response()->json($validator->errors());
        } 

        if($request->filled('diff_time')) {
            
               $requestDate = date('Y-m-d', $request->diff_time);
        }


        //2. Postavljanje broja jela po stranici
        if ($request->filled('per_page')) { 

            $per_page = $request->per_page; 
        }
        

        //3. Ako ne postoji zahtjev za kategorijama i tagovima
        if (!$request->filled('category') && !$request->filled('tags')) {

            //Provjera ako postoji samo zahtjev za broj jela po stranici
            if ($requestDate){

                $meals = Meal::withTrashed()->where('created_at', '>=', $requestDate)->where('updated_at', '>=', $requestDate)->where('deleted_at', '=', NULL)->orWhere('deleted_at', '>=', $requestDate)->paginate($per_page);
                

            } else {

                $meals = Meal::paginate($per_page);
            }

        //Postoji samo zahtjev za kategorijom
        } else if ($request->filled('category') && !$request->filled('tags')) {

            $meals = filter_meals_by_category($request->category, $per_page, $requestDate);

        //Postoji samo zahtjev za tagovima
        } else if (!$request->filled('category') && $request->filled('tags')) {

            $meals = filter_meals_by_tags($request->tags, $per_page, $requestDate);

        //Postoji zahtjev za kategorijama i tagovima
        } else {

            $meals = filter_meals_by_categories_and_tags($request->category, $request->tags, $per_page, $requestDate);
        }

        $meals = $meals->appends($request->except('page'));
        return new MealCollection($meals);

    }

}


//Funkcija za dohvaćanje kategorija
function filter_meals_by_category($category, $per_page, $requestDate=null){

    $category = trim($category);
    $category = strtolower($category);
    $result = array();
    
    if (isset($requestDate)){

        $meals = Meal::withTrashed()->where('created_at', '>=', $requestDate)->where('updated_at', '>=', $requestDate)->where('deleted_at', '=', NULL)->orWhere('deleted_at', '>=', $requestDate);

    };

    if ($category == '!null') {
            

            if(isset($meals)){

                $meals = $meals->where('category_id', '!=', NULL)->paginate($per_page);
            }else{

                $meals = Meal::where('category_id','!=', NULL)->paginate($per_page);
            }
                


    } else if ($category == 'null'){
           

            if(isset($meals)){

                $meals = $meals->where('category_id', '=', NULL)->paginate($per_page);
            }else{
                
                $meals = Meal::where('category_id','=', NULL)->paginate($per_page);
            }



        } else {

            
            if(isset($meals)){

                $meals = $meals->where('category_id', '=', $category)->paginate($per_page);
            }else{
                
                $meals = Meal::where('category_id', '=', $category)->paginate($per_page);
            }

        } 


    return $meals; 
}

//Funkcija za dohvaćanje tagova
function filter_meals_by_tags($tags, $per_page, $requestDate=null){

    $tags = trim($tags);
    $tags = explode(',', $tags);

    if (isset($requestDate)){

        $meals = Meal::withTrashed()->where('created_at', '>=', $requestDate)->where('updated_at', '>=', $requestDate)->where('deleted_at', '=', NULL)->orWhere('deleted_at', '>=', $requestDate);
        
        $meals = $meals->when($tags, function($query) use ($tags){
                             
                             foreach($tags as $tag){
                                    
                                 $query->whereHas('tags', function($query) use ($tag){

                                    $query->where('tag_id', $tag);
                                });
                             }
                         });

    } else {

        $meals = Meal::when($tags, function($query) use ($tags){
                     
                     foreach($tags as $tag){
                            
                         $query->whereHas('tags', function($query) use ($tag){

                            $query->where('tag_id', $tag);
                        });
                     }
                 });

    }


    $meals = $meals->paginate($per_page);
    
    return $meals;
}

//Funkcija za dohvaćanje kategorija i tagova
function filter_meals_by_categories_and_tags($category, $tags, $per_page, $requestDate=null){

    $tags = trim($tags);
    $tags = explode(',', $tags);
    $category = trim($category);
    $category = strtolower($category);

    if (isset($requestDate)){

        $meals = Meal::withTrashed()->where('created_at', '>=', $requestDate)->where('updated_at', '>=', $requestDate)->where('deleted_at', '=', NULL)->orWhere('deleted_at', '>=', $requestDate);

         if ($category == '!null') {
                
                $meals = $meals->when($tags, function($query) use ($tags){
                 
                 foreach($tags as $tag){
                     $query->whereHas('tags', function($query) use ($tag){

                        $query->where('tag_id', $tag);
                    });
                 }
                })->where('category_id','!=', NULL);


            } else if ($category == 'null'){

                $meals = $meals->when($tags, function($query) use ($tags){
                 
                 foreach($tags as $tag){
                     $query->whereHas('tags', function($query) use ($tag){

                        $query->where('tag_id', $tag);
                    });
                 }
                })->where('category_id','=', NULL);
                    

            } else {

                $meals = $meals->when($tags, function($query) use ($tags){
                 
                 foreach($tags as $tag){
                        
                     $query->whereHas('tags', function($query) use ($tag){

                        $query->where('tag_id', $tag);
                    });
                 }
                })->where('category_id', $category); 

            }


    } else {

     if ($category == '!null') {

            $meals = Meal::when($tags, function($query) use ($tags){
             
             foreach($tags as $tag){
                 $query->whereHas('tags', function($query) use ($tag){

                    $query->where('tag_id', $tag);
                });
             }
            })->where('category_id','!=', NULL);


        } else if ($category == 'null'){

            $meals = Meal::when($tags, function($query) use ($tags){
             
             foreach($tags as $tag){
                 $query->whereHas('tags', function($query) use ($tag){

                    $query->where('tag_id', $tag);
                });
             }
            })->where('category_id','=', NULL);
                

        } else {

            $meals = Meal::when($tags, function($query) use ($tags){
             
             foreach($tags as $tag){
                    
                 $query->whereHas('tags', function($query) use ($tag){

                    $query->where('tag_id', $tag);
                });
             }
            })->where('category_id', $category); 

        }
    }

     $meals = $meals->paginate($per_page);

     return $meals;
} 





?>