<?php

namespace App\CentralLogics;

use App\Models\Category;
use App\Models\Food;
use App\Models\Restaurant;

class CategoryLogic
{
    public static function parents()
    {
        return Category::where('position', 0)->get();
    }

    public static function child($parent_id)
    {
        return Category::where(['parent_id' => $parent_id])->get();
    }

    public static function products( $category_id,int $limit,int $offset, $type)
    {
        $paginator = Food::whereHas('category',function($q)use($category_id){
            return $q->whereId($category_id)->orWhere('parent_id', $category_id);
        })
        ->active()->type($type)->latest()->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }
    public static function productsbyrestorandcategory($category_id, $resto_id, $limit, $offset, $type)
    {
        $paginator = Food::whereHas('category', function ($q) use ($category_id) {
            return $q->whereId($category_id)->orWhere('parent_id', $category_id);
        })
        ->where('resto_id', $resto_id) // Add this line to filter by restaurant ID
        ->active()
        ->type($type)
        ->latest()
        ->paginate($limit, ['*'], 'page', $offset);
    
        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items(),
        ];
    }

    public static function restaurants(int $category_id, int $limit,int $offset, $type,$longitude=0,$latitude=0)
    {
        $paginator = Restaurant::whereHas('foods.category', function($query)use($category_id){
            return $query->whereId($category_id)->orWhere('parent_id', $category_id);
        })
        ->active()->type($type)->latest()->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'restaurants' => $paginator->items()
        ];
    }


    public static function all_products($id)
    {
        $cate_ids=[];
        array_push($cate_ids,(int)$id);
        foreach (CategoryLogic::child($id) as $ch1){
            array_push($cate_ids,$ch1['id']);
            foreach (CategoryLogic::child($ch1['id']) as $ch2){
                array_push($cate_ids,$ch2['id']);
            }
        }
        return Food::whereIn('category_id', $cate_ids)->get();
    }
    public static function all_productsbyrestorandcategory($id, $restoid)
    {
        // $cate_ids = [];
        // array_push($cate_ids, (int)$id);
    
        // foreach (CategoryLogic::child($id) as $ch1) {
        //     array_push($cate_ids, $ch1['id']);
    
        //     foreach (CategoryLogic::child($ch1['id']) as $ch2) {
        //         array_push($cate_ids, $ch2['id']);
        //     }
        // }
    
        return Food::where('restaurant_id', $restoid) // Add this line to filter by restaurant ID
          //  ->where('category_id', $id)
            ->get();
    }
    

    public static function export_categories($collection){
        $data = [];
        foreach($collection as $key=>$item){
            $data[] = [
                'Id'=>$item->id,
                'Name'=>$item->name,
                'Image'=>$item->image,
                'ParentId'=>$item->parent_id,
                'Position'=>$item->position,
                'Priority'=>$item->priority,
                'Status'=>$item->status == 1 ? 'active' : 'inactive',
            ];
        }
        return $data;
    }
}
