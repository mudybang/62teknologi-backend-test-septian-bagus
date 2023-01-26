<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller{
    var $lat,$lon;
    public function __construct(){
        $this->middleware('auth');
        $this->lat=40.730610;
        $this->lon=-73.935242;
    }
    public function index(){
        return view('business',[
            'title'=>'Business',
            'categories'=>Category::get()
        ]);
    }

    public function get_data(Request $request){
        $data=Business::selectRaw("*,ST_Distance_Sphere(point(".$this->lon.", ".$this->lat."),point(longitude, latitude)) as distance");
		if($request->sname){
            $data->where('name','LIKE','%'.$request->sname.'%');
        }
        if($request->sdistance){
            $data->whereRaw("ST_Distance_Sphere(point(?, ?),point(longitude, latitude)) < ".$request->sdistance."", [
                $this->lon,
                $this->lat,
            ]);
        }
        if($request->scategory_id){
            $data->whereRaw("? IN (SELECT category_id FROM business_categories WHERE business_id=id)", [
                $request->scategory_id
            ]);
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
            $row['categories']='';
            $categories=BusinessCategory::select('business_categories.*', 'categories.alias', 'businesses.name', 'categories.title')
                ->join('businesses', 'businesses.id', '=', 'business_categories.business_id')
                ->join('categories', 'categories.id', '=', 'business_categories.category_id')
                ->where('business_id',$row['id']);
            foreach($categories->get() as $category){
                $row['categories'].=$category->title.', ';
                $row['categories_'.clearstr($category->alias)]=1;
            }
            $data_[]=$row;
        }

        $results=array_merge($result,array('rows'=>$data_,'sql'=>$data->toSql()));
		return response()->json($results);
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

        return response()->json(['success'=>true]);
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

        return response()->json(['success'=>true]);
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
        return response()->json(['success'=>true]);
    }
    public function testget(Request $request){
        return response()->json(BusinessCategory::where('business_id','01gqhzwxfnhfhws5kq2wpe03cb')->get());

    }
    public function bulk_insert(){
        $data=json_decode($this->json_data());
        foreach($data as $row){
            Business::create([
                'name' => $row->name,
                'alias' => $row->alias,
                'image_url'=> $row->image_url??'',
                'is_closed'=> $row->is_closed?1:0,
                'review_count' => $row->review_count??'',
                'rating' => $row->rating??'',
                'price' => $row->price??'',
                'latitude' => $row->coordinates->latitude??'',
                'longitude' => $row->coordinates->longitude??'',
                'address1' => $row->location->address1??'',
                'address2' => $row->location->address2??'',
                'city' => $row->location->city??'',
                'zip_code' => $row->location->zip_code??'',
                'country' => $row->location->country??'',
                'state' => $row->location->state??'',
                'phone' => $row->phone??'',
            ]);
        }
    }
    public function json_data(){
        return '[
              {
                "id": "H4jJ7XB3CetIr1pg56CczQ",
                "alias": "levain-bakery-new-york",
                "name": "Levain Bakery",
                "image_url": "https://s3-media3.fl.yelpcdn.com/bphoto/DH29qeTmPotJbCSzkjYJwg/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/levain-bakery-new-york?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 9428,
                "categories": [
                  {
                    "alias": "bakeries",
                    "title": "Bakeries"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.779961,
                  "longitude": -73.980299
                },
                "transactions": [],
                "price": "$$",
                "location": {
                  "address1": "167 W 74th St",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10023",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "167 W 74th St",
                    "New York, NY 10023"
                  ]
                },
                "phone": "+19174643769",
                "display_phone": "(917) 464-3769",
                "distance": 8115.903194093832
              },
              {
                "id": "V7lXZKBDzScDeGB8JmnzSA",
                "alias": "katzs-delicatessen-new-york",
                "name": "Katzs Delicatessen",
                "image_url": "https://s3-media1.fl.yelpcdn.com/bphoto/1_2gtvgqMyuSgVJoCP6BQw/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/katzs-delicatessen-new-york?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 14595,
                "categories": [
                  {
                    "alias": "delis",
                    "title": "Delis"
                  },
                  {
                    "alias": "sandwiches",
                    "title": "Sandwiches"
                  },
                  {
                    "alias": "soup",
                    "title": "Soup"
                  }
                ],
                "rating": 4,
                "coordinates": {
                  "latitude": 40.722237,
                  "longitude": -73.9875259
                },
                "transactions": [
                  "delivery",
                  "pickup"
                ],
                "price": "$$",
                "location": {
                  "address1": "205 E Houston St",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10002",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "205 E Houston St",
                    "New York, NY 10002"
                  ]
                },
                "phone": "+12122542246",
                "display_phone": "(212) 254-2246",
                "distance": 1836.553222671626
              },
              {
                "id": "44SY464xDHbvOcjDzRbKkQ",
                "alias": "ippudo-ny-new-york-7",
                "name": "Ippudo NY",
                "image_url": "https://s3-media1.fl.yelpcdn.com/bphoto/zF3EgqHCk7zBUwD2B3WTEA/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/ippudo-ny-new-york-7?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 10538,
                "categories": [
                  {
                    "alias": "ramen",
                    "title": "Ramen"
                  }
                ],
                "rating": 4,
                "coordinates": {
                  "latitude": 40.73092,
                  "longitude": -73.99015
                },
                "transactions": [
                  "delivery",
                  "pickup"
                ],
                "price": "$$",
                "location": {
                  "address1": "65 4th Ave",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10003",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "65 4th Ave",
                    "New York, NY 10003"
                  ]
                },
                "phone": "+12123880088",
                "display_phone": "(212) 388-0088",
                "distance": 2820.7453024396
              },
              {
                "id": "xEnNFXtMLDF5kZDxfaCJgA",
                "alias": "the-halal-guys-new-york-2",
                "name": "The Halal Guys",
                "image_url": "https://s3-media1.fl.yelpcdn.com/bphoto/MYnXprCKOS0JlpQJRMOR7Q/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/the-halal-guys-new-york-2?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 10254,
                "categories": [
                  {
                    "alias": "foodstands",
                    "title": "Food Stands"
                  },
                  {
                    "alias": "mideastern",
                    "title": "Middle Eastern"
                  },
                  {
                    "alias": "halal",
                    "title": "Halal"
                  }
                ],
                "rating": 4,
                "coordinates": {
                  "latitude": 40.761861,
                  "longitude": -73.979306
                },
                "transactions": [
                  "delivery",
                  "pickup"
                ],
                "price": "$",
                "location": {
                  "address1": "W 53rd Street And 6th Ave",
                  "address2": null,
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10019",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "W 53rd Street And 6th Ave",
                    "New York, NY 10019"
                  ]
                },
                "phone": "+13475271505",
                "display_phone": "(347) 527-1505",
                "distance": 6102.744807076076
              },
              {
                "id": "KFnr0CGsHQ2ABFHbLNtobQ",
                "alias": "central-park-conservancy-new-york",
                "name": "Central Park Conservancy",
                "image_url": "https://s3-media4.fl.yelpcdn.com/bphoto/RcCKWSjELHgHRmFJ1iwKGg/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/central-park-conservancy-new-york?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 2733,
                "categories": [
                  {
                    "alias": "parks",
                    "title": "Parks"
                  }
                ],
                "rating": 5,
                "coordinates": {
                  "latitude": 40.764266,
                  "longitude": -73.971656
                },
                "transactions": [],
                "location": {
                  "address1": "14 E 60th St",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10022",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "14 E 60th St",
                    "New York, NY 10022"
                  ]
                },
                "phone": "+12123106600",
                "display_phone": "(212) 310-6600",
                "distance": 6404.887575550017
              },
              {
                "id": "jVncyqXwlx_D9f2xZn05tg",
                "alias": "the-metropolitan-museum-of-art-new-york-3",
                "name": "The Metropolitan Museum of Art",
                "image_url": "https://s3-media1.fl.yelpcdn.com/bphoto/NcHMjAQ1mgaPKwQEEOLM_A/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/the-metropolitan-museum-of-art-new-york-3?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 3318,
                "categories": [
                  {
                    "alias": "artmuseums",
                    "title": "Art Museums"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.779449,
                  "longitude": -73.963245
                },
                "transactions": [],
                "location": {
                  "address1": "1000 Fifth Ave",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10028",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "1000 Fifth Ave",
                    "New York, NY 10028"
                  ]
                },
                "phone": "+12125357710",
                "display_phone": "(212) 535-7710",
                "distance": 8169.582982339193
              },
              {
                "id": "jjJc_CrkB2HodEinB6cWww",
                "alias": "lovemama-new-york",
                "name": "LoveMama",
                "image_url": "https://s3-media1.fl.yelpcdn.com/bphoto/bLlFKTlVuLfmF-lIDGIjZA/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/lovemama-new-york?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 6321,
                "categories": [
                  {
                    "alias": "thai",
                    "title": "Thai"
                  },
                  {
                    "alias": "malaysian",
                    "title": "Malaysian"
                  },
                  {
                    "alias": "vietnamese",
                    "title": "Vietnamese"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.730408722512074,
                  "longitude": -73.98612673033213
                },
                "transactions": [
                  "delivery",
                  "restaurant_reservation",
                  "pickup"
                ],
                "price": "$$",
                "location": {
                  "address1": "174 2nd Ave",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10003",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "174 2nd Ave",
                    "New York, NY 10003"
                  ]
                },
                "phone": "+12122545370",
                "display_phone": "(212) 254-5370",
                "distance": 2670.2440958288694
              },
              {
                "id": "WHRHK3S1mQc3PmhwsGRvbw",
                "alias": "bibble-and-sip-new-york-2",
                "name": "Bibble & Sip",
                "image_url": "https://s3-media1.fl.yelpcdn.com/bphoto/qVGATBDmFAaXL9l5Yzv-ww/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/bibble-and-sip-new-york-2?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 5533,
                "categories": [
                  {
                    "alias": "coffee",
                    "title": "Coffee & Tea"
                  },
                  {
                    "alias": "bakeries",
                    "title": "Bakeries"
                  },
                  {
                    "alias": "desserts",
                    "title": "Desserts"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.7628355,
                  "longitude": -73.98518009478293
                },
                "transactions": [
                  "delivery",
                  "pickup"
                ],
                "price": "$$",
                "location": {
                  "address1": "253 W 51st St",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10019",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "253 W 51st St",
                    "New York, NY 10019"
                  ]
                },
                "phone": "+16466495116",
                "display_phone": "(646) 649-5116",
                "distance": 6231.588282026839
              },
              {
                "id": "jnEv25Y2DosTq2sNnvmC9g",
                "alias": "los-tacos-no-1-new-york",
                "name": "Los Tacos No.1",
                "image_url": "https://s3-media2.fl.yelpcdn.com/bphoto/FU44TYl3PzXsE06G4W5aog/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/los-tacos-no-1-new-york?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 3656,
                "categories": [
                  {
                    "alias": "tacos",
                    "title": "Tacos"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.7422546402357,
                  "longitude": -74.0059581
                },
                "transactions": [
                  "delivery"
                ],
                "price": "$$",
                "location": {
                  "address1": "75 9th Ave",
                  "address2": "",
                  "address3": "Chelsea Market",
                  "city": "New York",
                  "zip_code": "10011",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "75 9th Ave",
                    "Chelsea Market",
                    "New York, NY 10011"
                  ]
                },
                "phone": "",
                "display_phone": "",
                "distance": 4525.079539589976
              },
              {
                "id": "j1S3NUrkB3BVT49n_e76NQ",
                "alias": "best-bagel-and-coffee-new-york",
                "name": "Best Bagel & Coffee",
                "image_url": "https://s3-media2.fl.yelpcdn.com/bphoto/Cp9antpXYQMxLur0oi6tPw/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/best-bagel-and-coffee-new-york?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 4320,
                "categories": [
                  {
                    "alias": "bagels",
                    "title": "Bagels"
                  },
                  {
                    "alias": "coffee",
                    "title": "Coffee & Tea"
                  },
                  {
                    "alias": "breakfast_brunch",
                    "title": "Breakfast & Brunch"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.7522683,
                  "longitude": -73.9910861
                },
                "transactions": [
                  "delivery",
                  "pickup"
                ],
                "price": "$",
                "location": {
                  "address1": "225 W 35th St",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10001",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "225 W 35th St",
                    "New York, NY 10001"
                  ]
                },
                "phone": "+12125644409",
                "display_phone": "(212) 564-4409",
                "distance": 5134.919514264648
              },
              {
                "id": "B3_K2kUVbYOU0VaLcj_LTw",
                "alias": "thai-villa-new-york-2",
                "name": "Thai Villa",
                "image_url": "https://s3-media4.fl.yelpcdn.com/bphoto/xmFtvhWJIJH_BDFRo5epaA/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/thai-villa-new-york-2?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 4483,
                "categories": [
                  {
                    "alias": "thai",
                    "title": "Thai"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.73902,
                  "longitude": -73.99065
                },
                "transactions": [
                  "delivery",
                  "pickup"
                ],
                "price": "$$",
                "location": {
                  "address1": "5 E 19th St",
                  "address2": "G Floor",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10003",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "5 E 19th St",
                    "G Floor",
                    "New York, NY 10003"
                  ]
                },
                "phone": "+12128029999",
                "display_phone": "(212) 802-9999",
                "distance": 3690.593442744935
              },
              {
                "id": "zj8Lq1T8KIC5zwFief15jg",
                "alias": "prince-street-pizza-new-york-2",
                "name": "Prince Street Pizza",
                "image_url": "https://s3-media2.fl.yelpcdn.com/bphoto/I4gm7i1zoamgAk1hmOKbKw/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/prince-street-pizza-new-york-2?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 4731,
                "categories": [
                  {
                    "alias": "pizza",
                    "title": "Pizza"
                  },
                  {
                    "alias": "italian",
                    "title": "Italian"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.72308755605564,
                  "longitude": -73.99453001177575
                },
                "transactions": [
                  "delivery",
                  "pickup"
                ],
                "price": "$",
                "location": {
                  "address1": "27 Prince St",
                  "address2": null,
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10012",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "27 Prince St",
                    "New York, NY 10012"
                  ]
                },
                "phone": "+12129664100",
                "display_phone": "(212) 966-4100",
                "distance": 2209.3116178604555
              },
              {
                "id": "UA2M9QFZghe-9th2KwLoWQ",
                "alias": "burger-and-lobster-flatiron-nyc-new-york",
                "name": "Burger & Lobster - Flatiron NYC",
                "image_url": "https://s3-media1.fl.yelpcdn.com/bphoto/2Z3Cn2sDxitAmWJYTdrkpA/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/burger-and-lobster-flatiron-nyc-new-york?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 5833,
                "categories": [
                  {
                    "alias": "seafood",
                    "title": "Seafood"
                  },
                  {
                    "alias": "burgers",
                    "title": "Burgers"
                  },
                  {
                    "alias": "newamerican",
                    "title": "American (New)"
                  }
                ],
                "rating": 4,
                "coordinates": {
                  "latitude": 40.74007,
                  "longitude": -73.99344
                },
                "transactions": [
                  "delivery",
                  "pickup"
                ],
                "price": "$$",
                "location": {
                  "address1": "39 W 19th St",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10011",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "39 W 19th St",
                    "New York, NY 10011"
                  ]
                },
                "phone": "+16468337532",
                "display_phone": "(646) 833-7532",
                "distance": 3867.2454876885745
              },
              {
                "id": "4yPqqJDJOQX69gC66YUDkA",
                "alias": "peter-luger-brooklyn-2",
                "name": "Peter Luger",
                "image_url": "https://s3-media1.fl.yelpcdn.com/bphoto/d0XSKED0U0sTgFWhCQdY7w/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/peter-luger-brooklyn-2?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 6674,
                "categories": [
                  {
                    "alias": "steak",
                    "title": "Steakhouses"
                  }
                ],
                "rating": 4,
                "coordinates": {
                  "latitude": 40.709945,
                  "longitude": -73.962478
                },
                "transactions": [],
                "price": "$$$$",
                "location": {
                  "address1": "178 Broadway",
                  "address2": "",
                  "address3": "",
                  "city": "Brooklyn",
                  "zip_code": "11211",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "178 Broadway",
                    "Brooklyn, NY 11211"
                  ]
                },
                "phone": "+17183877400",
                "display_phone": "(718) 387-7400",
                "distance": 1446.5330245620685
              },
              {
                "id": "SULHf6nGQ8sK0UpG1XU30w",
                "alias": "los-tacos-no-1-new-york-3",
                "name": "Los Tacos No.1",
                "image_url": "https://s3-media4.fl.yelpcdn.com/bphoto/5wEe4FCwda16knmBHSsX0Q/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/los-tacos-no-1-new-york-3?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 2588,
                "categories": [
                  {
                    "alias": "tacos",
                    "title": "Tacos"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.7575067,
                  "longitude": -73.9877717
                },
                "transactions": [
                  "delivery"
                ],
                "price": "$",
                "location": {
                  "address1": "229 W 43rd St",
                  "address2": "",
                  "address3": null,
                  "city": "New York",
                  "zip_code": "10036",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "229 W 43rd St",
                    "New York, NY 10036"
                  ]
                },
                "phone": "+12125744696",
                "display_phone": "(212) 574-4696",
                "distance": 5664.931997000446
              },
              {
                "id": "veq1Bl1DW3UWMekZJUsG1Q",
                "alias": "gramercy-tavern-new-york",
                "name": "Gramercy Tavern",
                "image_url": "https://s3-media2.fl.yelpcdn.com/bphoto/f14WAmWETi0cu2f6rUBj-Q/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/gramercy-tavern-new-york?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 3266,
                "categories": [
                  {
                    "alias": "newamerican",
                    "title": "American (New)"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.73844,
                  "longitude": -73.98825
                },
                "transactions": [
                  "delivery"
                ],
                "price": "$$$$",
                "location": {
                  "address1": "42 E 20th St",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10003",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "42 E 20th St",
                    "New York, NY 10003"
                  ]
                },
                "phone": "+12124770777",
                "display_phone": "(212) 477-0777",
                "distance": 3588.814433200449
              },
              {
                "id": "JION8hhg7q6zyayHYwhxIw",
                "alias": "the-high-line-new-york",
                "name": "The High Line",
                "image_url": "https://s3-media2.fl.yelpcdn.com/bphoto/8dWtYbMkHKNgyKe5S1DZkA/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/the-high-line-new-york?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 2526,
                "categories": [
                  {
                    "alias": "parks",
                    "title": "Parks"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.7396039,
                  "longitude": -74.00847657515718
                },
                "transactions": [],
                "location": {
                  "address1": "820 Washington St",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10014",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "820 Washington St",
                    "New York, NY 10014"
                  ]
                },
                "phone": "+12122069922",
                "display_phone": "(212) 206-9922",
                "distance": 4387.905588292356
              },
              {
                "id": "16ZnHpuaaBt92XWeJHCC5A",
                "alias": "olio-e-più-new-york-7",
                "name": "Olio e Più",
                "image_url": "https://s3-media3.fl.yelpcdn.com/bphoto/Nn4I1SG0pYmqCyJPlArYOQ/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/olio-e-pi%C3%B9-new-york-7?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 3985,
                "categories": [
                  {
                    "alias": "pizza",
                    "title": "Pizza"
                  },
                  {
                    "alias": "italian",
                    "title": "Italian"
                  },
                  {
                    "alias": "cocktailbars",
                    "title": "Cocktail Bars"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.733798036104304,
                  "longitude": -73.99977392649927
                },
                "transactions": [
                  "pickup",
                  "delivery"
                ],
                "price": "$$",
                "location": {
                  "address1": "3 Greenwich Ave",
                  "address2": null,
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10014",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "3 Greenwich Ave",
                    "New York, NY 10014"
                  ]
                },
                "phone": "+12122436546",
                "display_phone": "(212) 243-6546",
                "distance": 3450.228657989248
              },
              {
                "id": "nU4XBdvxDABXqZ6CnB8Dig",
                "alias": "clinton-street-baking-company-new-york-5",
                "name": "Clinton Street Baking Company",
                "image_url": "https://s3-media2.fl.yelpcdn.com/bphoto/cX6_cvryWi7ri2GKc-ASTg/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/clinton-street-baking-company-new-york-5?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 5653,
                "categories": [
                  {
                    "alias": "bakeries",
                    "title": "Bakeries"
                  },
                  {
                    "alias": "newamerican",
                    "title": "American (New)"
                  },
                  {
                    "alias": "breakfast_brunch",
                    "title": "Breakfast & Brunch"
                  }
                ],
                "rating": 4,
                "coordinates": {
                  "latitude": 40.721128,
                  "longitude": -73.983933
                },
                "transactions": [
                  "delivery",
                  "pickup"
                ],
                "price": "$$",
                "location": {
                  "address1": "4 Clinton St",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10002",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "4 Clinton St",
                    "New York, NY 10002"
                  ]
                },
                "phone": "+16466026263",
                "display_phone": "(646) 602-6263",
                "distance": 1623.4880246742232
              },
              {
                "id": "lWOkeS-wV4no8qqA9OwwEg",
                "alias": "doughnut-plant-new-york-6",
                "name": "Doughnut Plant",
                "image_url": "https://s3-media1.fl.yelpcdn.com/bphoto/hLWKXsLv6hyltNSilBy8-g/o.jpg",
                "is_closed": false,
                "url": "https://www.yelp.com/biz/doughnut-plant-new-york-6?adjust_creative=DSj6I8qbyHf-Zm2fGExuug&utm_campaign=yelp_api_v3&utm_medium=api_v3_business_search&utm_source=DSj6I8qbyHf-Zm2fGExuug",
                "review_count": 3412,
                "categories": [
                  {
                    "alias": "donuts",
                    "title": "Donuts"
                  },
                  {
                    "alias": "coffee",
                    "title": "Coffee & Tea"
                  }
                ],
                "rating": 4.5,
                "coordinates": {
                  "latitude": 40.71632,
                  "longitude": -73.98848
                },
                "transactions": [
                  "delivery",
                  "pickup"
                ],
                "price": "$$",
                "location": {
                  "address1": "379 Grand St",
                  "address2": "",
                  "address3": "",
                  "city": "New York",
                  "zip_code": "10002",
                  "country": "US",
                  "state": "NY",
                  "display_address": [
                    "379 Grand St",
                    "New York, NY 10002"
                  ]
                },
                "phone": "+12125053700",
                "display_phone": "(212) 505-3700",
                "distance": 1310.0243148054626
              }
            ]';
    }
}
