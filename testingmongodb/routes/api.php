<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\DB;

// added to have access to Models\Post from within our API
use App\Models\CustomerMongo;
use App\Models\CustomerSQL;

use MongoDB\Laravel\Document;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//check for connection establishment
Route::get('/test_mongodb/', function (Request $request) {

    $connection = DB::connection('mongodb');
    $msg = 'MongoDB is accessible!';
    try {
        $connection->command(['ping' => 1]);
    } catch (\Exception $e) {
        $msg =  'MongoDB is not accessible. Error: ' . $e->getMessage();
    }

    return ['msg' => $msg];
});

//insert into collection
Route::get('/create_mongo/', function(Request $request){
    try{
        $success = CustomerMongo::create([
            'guid' => 'cust_1',
            'first_name' => 'John',
            'family_name' => 'Doe',
            'email' => 'j.doe@gmail.com',
            'address' => '123 is my address'
        ]);
        $msg = "OK";

    }catch(\Exception $e){
        $msg = 'Error while creating user: ' . $e->getMessage();


    }
    return ['status' => 'executed', 'data'=>$msg];
});


//find
Route::get('/find_customer/', function (Request $request){
 $customer = CustomerMongo::where('guid','cust_1')->get();
 return ['status' => 'executed', 'data' => $customer];
});


//update
Route::get('/update_customer/', function (Request $request){
   $result = CustomerMongo::where('guid','cust_1')->update(
    ['first_name' => 'Mike']
   ); 

   return [ 'status'=>'executed', 'data'=>$result];
});

//delete
Route::delete('/delete_customer/', function (Request $request){
    $res = CustomerMongo::where('guid','cust_1')->delete();
    return [ 'status'=>'executed', 'data'=>$result];

});

//prosiri
Route::get('/create_nested/', function (Request $request) {
    $message = "executed";
    $success = null;

    $address = new stdClass;
    $address->street = '123 my street name';
    $address->city   = 'my city';
    $address->zip    = '12345';

    $emails = ['j.doe@gmail.com', 'j.doe@work.com'];

    try {
        $customer = new CustomerMongo();
        $customer->guid         = 'cust_2';
        $customer->first_name   = 'John';
        $customer->family_name  = 'Doe';
        $customer->email        = $emails;
        $customer->address      = $address;
        $success = $customer->save();       // save() returns 1 or 0
    }
    catch (\Exception $e) {
        $message = $e->getMessage();
    }

    return ['status' => $message, 'data' => $success];
});


