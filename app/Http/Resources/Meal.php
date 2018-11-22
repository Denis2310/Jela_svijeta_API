<?php

namespace App\Http\Resources;

use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Tag as TagResource;
use App\Http\Resources\Ingredient as IngredientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Meal extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if ($request->filled('with')) {

            $with = trim($request->with);
            $with = strtolower($with);
            $with = explode(',', $with);
        } else {

            $with = array();
        }

        return [

            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'category' => new CategoryResource($this->category),
            'tags' => TagResource::collection($this->tags),
            'ingredients' => IngredientResource::collection($this->ingredients),
        ];
    }
}


function with_category($with){

    return in_array('category', $with)? true : false;
};

function with_tags($with){

    return in_array('tags', $with)? true : false;
};

function with_ingredients($with){

    return in_array('ingredients', $with)? true : false;
};