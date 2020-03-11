<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller {

    public function homeCategories()
    {
        $categories = Category::where( 'in_homepage', '!=', '0' )
            ->get();

        $response = [];
        foreach ($categories as $category)
        {
            $response[] = [
                'id'     => $category->id,
                'name'   => $category->name,
                'slug'   => $category->slug
            ];
        }

        return $response;
    }

    public function categoryProducts( $slug, Request $request )
    {
        $wishList = [];
        $user = Auth::guard('api')->user();
        if($user)
        {
            //get user wishList
            $wishList = $user->wishLists->pluck('product_id')->toArray();
        }

        $category = Category::where( 'slug', $slug )->first();

        $filterOptions = $request->query();
//        dd($filterOptions);
        if(isset($filterOptions['cat']))
        {
            $filterCategories = explode(',',$filterOptions['cat']);
            $categoriesIds = Category::whereIn( 'slug', $filterCategories )->pluck('id');

        }

        if(!isset($filterOptions['cat']))
        {
            $categoriesIds = $category->subCategories()
                ->pluck('id');
            $categoriesIds->push($category->id);
        }

        $query = Product::whereHas('categories', function ($query) use ($categoriesIds){
            $query->whereIn('id', $categoriesIds->toArray());
        });

        if(isset($filterOptions['term']))
        {
            $query->where('name_en', 'LIKE', '%'. $filterOptions['term'] .'%')
                ->orWhere('name_ar', 'LIKE', '%'. $filterOptions['term'] .'%');
        }

        if(isset($filterOptions['color']))
        {
            $filterColors = explode(',',$filterOptions['color']);

            $query->whereHas('productAttributeValues', function ($query) use ($filterColors){
                $query->whereIn('attribute_value_id', $filterColors);
            });
        }

        if(isset($filterOptions['discount']))
        {
            if(in_array('upTo30',$filterOptions['discount'] )){
                $query->whereHas('prices', function ($query) use ($filterOptions, $user){

                    if($user->type == User::TYPE_CORPORATE)
                    {
//                        $query->where('percentage_discount', '<=',  $filterOptions['min']);
//                        $query->where('corporate_unit_price', '<=',  $filterOptions['max']);
                    }
                    else
                    {
//                        $query->where('individual_unit_price', '>=',  $filterOptions['min']);
//                        $query->where('individual_unit_price', '<=',  $filterOptions['max']);
                    }
                });
            }
            if(in_array('upTo50',$filterOptions['discount'] )){
//                dd($filterOptions['discount']);
            }
            if(in_array('upTo60',$filterOptions['discount'] )){
//                dd($filterOptions['discount']);
            }
            if(in_array('moreThan60',$filterOptions['discount'] )){
//                dd($filterOptions['discount']);
            }
        }

        if(isset($filterOptions['min']) || isset($filterOptions['max']))
        {
            $query->whereHas('prices', function ($query) use ($filterOptions, $user){

                if($user->type == User::TYPE_CORPORATE)
                {
                    $query->where('corporate_unit_price', '>=',  $filterOptions['min']);
                    $query->where('corporate_unit_price', '<=',  $filterOptions['max']);
                }
                else
                {
                    $query->where('individual_unit_price', '>=',  $filterOptions['min']);
                    $query->where('individual_unit_price', '<=',  $filterOptions['max']);
                }
            });
        }

        if(isset($filterOptions['sort']))
        {
            $products = $query->orderBy( 'name_en', $filterOptions['sort'] )->paginate(9);
        }
        else
        {
            $products = $query->orderBy( 'created_at', 'desc' )->paginate(9);
        }


        if ( $category )
        {
            $productModel = new Product();

            // make sure filter attributes not changed after apply any of filters on category
            if(empty($filterOptions))
            {
                $filterAttributes = $productModel->getProductsFilterAttributes($products);
            }

            $products = $productModel->getProductsRatingColors($products);
            $products = $this->addWishListedProducts($products, $wishList);

            $response = [
                'category' => [
                    'name_en'        => $category->name_en,
                    'description_en' => $category->description_en,
                    'banner'         => $category->banner,
                    'image'          => $category->image,
                    'slug'           => $category->slug,
                ],
                'products' => $products,
            ];

            if(empty($filterOptions))
            {
                $response['filterAttributes'] = $filterAttributes;
            }
            return response()->json( $response );
        }

        return [];
    }

    public function addWishListedProducts($products, $wishList)
    {
        foreach ($products as $product)
        {
            if(in_array($product->id, $wishList))
            {
                $product->whishlisted = 1;
            }
            else
            {
                $product->whishlisted = 0;
            }
        }

        return $products;
    }

    public function listFilterCategories($slug)
    {
        if($slug)
        {
            $category = Category::where( 'slug', $slug )->first();

            $children = $category->subCategories;

            return [
              'singleCategories' => $children
            ];

        }

        $categories = Category::all();

        return [
            'singleCategories' => $categories
        ];
    }
}
