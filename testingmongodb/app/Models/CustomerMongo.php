<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

//use MongoDB\Laravel\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
class CustomerMongo extends Model
{
    use HasFactory;


    protected $connection = 'mongodb';
    protected $collection = 'laravelcoll';
    protected $fillable = ['guid','first_name','family_name','email','address'];

}
