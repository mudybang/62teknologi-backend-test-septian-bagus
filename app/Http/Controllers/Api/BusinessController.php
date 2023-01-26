<?php

namespace App\Http\Controllers\Api;

use App\Models\Business;
use App\Models\Category;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\BusinessResource;

class BusinessController extends BaseController{
    var $lat,$lon;
    public function __construct(){
        $this->lat=40.730610;
        $this->lon=-73.935242;
    }

    public function index(Request $request){
        $data=Business::selectRaw("*,ST_Distance_Sphere(point(".$this->lon.", ".$this->lat."),point(longitude, latitude)) as distance");
		if($request->name){
            $data->where('name','LIKE','%'.$request->name.'%');
        }
        if($request->distance){
            $data->whereRaw("ST_Distance_Sphere(point(?, ?),point(longitude, latitude)) < ".$request->distance."", [
                $this->lon,
                $this->lat,
            ]);
        }
        if($request->category_id){
            $data->whereRaw("? IN (SELECT category_id FROM business_categories WHERE business_id=id)", [
                $request->category_id
            ]);
        }
        if($request->category){
            $data->whereRaw('"'.$request->category.'" IN (select title from business_categories LEFT JOIN categories ON business_categories.category_id=categories.id WHERE business_id=businesses.id)');
        }
        $result['total'] = $data->count();

		$page = $request->page ? intval($request->page) : 1;
        $rows = $request->rows ? intval($request->rows) : 10;
        $sort = $request->sort ? strval($request->sort) : 'id';
        if($sort=='star_rating')$sort='rating';
        if($request->order=='desc'){
            $data->orderByDesc($sort);
        }else{
            $data->orderBy($sort);
        }
        $offset = ($page-1) * $rows;

        $data->skip($offset);
        $data->take($rows);

        $data_=[];
        foreach($data->get() as $row){
            $row['ck']=$row['id'];
            $row['logo']=$row['image_url']?'<img class="img-thumbnail" style="width:30px;height:30px;padding:0;" src="'.$row['image_url'].'"/>':'';
            $row['star_rating']=fivestar_rating($row->rating);
            $row['latlon']=$row['latitude'].', '.$row['longitude'];
            $row['display_address']=$row['address1'].' '.$row['address1'].' '.$row['city'].', '.$row['state'].' '.$row['zip_code'];
            $row['categories']=BusinessCategory::select('categories.alias', 'categories.title')
                ->join('businesses', 'businesses.id', '=', 'business_categories.business_id')
                ->join('categories', 'categories.id', '=', 'business_categories.category_id')
                ->where('business_id',$row['id'])->get();
            $data_[]=$row;
        }

        $results=array_merge($result,array('rows'=>$data_,'sql'=>$data->toSql()));
		return $this->sendResponse(BusinessResource::collection($data_), $data->toSql());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:App\Models\Business,name',
            'review_count' => 'required',
            'rating' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'errorMessages'=>$validator->messages()
            ],400);
        }
        if($business=Business::create([
            'name' => $request->name,
            'alias' => \Str::slug($request->title),
            'image_url'=> $request->image_url,
            'review_count' => $request->review_count,
            'rating' => $request->rating,
            'price' => $request->price,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
            'country' => $request->country,
            'state' => $request->state,
            'phone' => $request->phone,
        ])){
            $categories='';
            foreach($request->categories as $category){
                $rowcat=Category::where('alias',$category)->first();
                BusinessCategory::create([
                    'business_id'=>$business->id,
                    'category_id'=>$rowcat->id
                ]);

            }
        }
        $business->categories=$categories;
        return $this->sendResponse(new BusinessResource($business), 'Business created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function edit(Business $business)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Business $business)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'review_count' => 'required',
            'rating' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'errorMessages'=>$validator->messages()
            ],400);
        }
        $business->name = $request->name;
        $business->alias = \Str::slug($request->name);
        $business->image_url = $request->image_url;
        $business->review_count = $request->review_count;
        $business->rating = $request->rating;
        $business->latitude = $request->latitude;
        $business->longitude = $request->longitude;
        $business->price = $request->price;
        $business->address1 = $request->address1;
        $business->address2 = $request->address2;
        $business->city = $request->city;
        $business->zip_code = $request->zip_code;
        $business->country = $request->country;
        $business->state = $request->state;
        $business->phone = $request->phone;
        if($business->save()){
            BusinessCategory::where('business_id',$business->id)->delete();
            foreach(Category::get() as $category){
                $param='categories_'.clearstr($category->alias);
                if($request->{$param}){
                    BusinessCategory::create([
                        'business_id'=>$business->id,
                        'category_id'=>$category->id
                    ]);
                }
            }
        }
        return $this->sendResponse(new BusinessResource($business), 'Business updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        $business->delete();
        return $this->sendResponse(new BusinessResource($business), 'Business deleted successfully.');
    }
}
