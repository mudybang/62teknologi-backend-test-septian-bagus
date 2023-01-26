<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    use HasUlids;
    protected $fillable = ['name','alias','categories','image_url,','is_closed','review_count','rating','latitude','longitude','price',
        'address1','address2','city','zip_code','country','state','phone'];
}
