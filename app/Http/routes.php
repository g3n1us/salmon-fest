<?php


function tidy($string){
	return ucwords(str_replace('_', ' ', $string));	
}


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'auth'], function () {
	
	Route::get('/', 'MapController@getIndex');
	Route::get('home', function(){
		return redirect('/');
	});
	
	Route::get('configure', function(){
		$request = Request::all();
		if(!empty($request)) {
			if(isset($request['save_preset'])) {
				$preset_id = array_get($request, 'preset_id', 9999999999999999999);
				if(!$preset = \App\Preset::find($preset_id))
					$preset = new \App\Preset;
				$preset->title = array_get($request, 'preset_title', '');
				$preset->save();
				$request['preset_id'] = $preset->id;
				$preset->query = http_build_query(array_except($request, ['save_preset']));
				
				if($preset->save()) return redirect("configure/?" . $preset->query);
			}
			if(isset($request['delete_preset'])){
				$preset_id = array_get($request, 'preset_id', 9999999999999999999);
				$preset = \App\Preset::find($preset_id);
					if($preset->delete()) return redirect(url("configure"));
			}
		}
		return view('configure');
	});
	
	Route::get('admin/spreadsheet/{id}', function($id){
		$pa = \App\PurchaseAward::find($id);
		$rows = [];
		$i = 0;
		foreach($pa->data_points as $p){
	
			$p = collect($p);
			if($i == 0) $rows[] = $p->keys()->implode("\t");
			$rows[] = $p->implode("\t");
			$i++;
		}
		return response(implode("\n", $rows))->header('Content-Type', 'application/csv')->header('Content-Disposition', 'attachment; filename="'.$pa->solicitation_number.'.csv"');
		
	});
	
	// Route::controller('admin', 'MapController');
	
});


Route::get('slideshow', function(){
	$data['pas'] = \App\PurchaseAward::all();
    return view('slideshow', $data);	
});

Route::get('guest_login', function(){
	$tokens = explode(',', env('guest_tokens', ''));
	$token = Request::input('token');
	if(!in_array($token, $tokens)) abort(500);
	else{
		$user = \App\User::find(999);
		Auth::loginUsingId(999);
		return redirect('/');
	}
});


Route::get('admin/map/{id?}', 'MapController@getMap');


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


Route::get('test/{id}', function($id){
	$pa = \App\PurchaseAward::find($id);
	return $pa->total_cases;
});



Route::get('pdf/{mapname}', function($mapname){
	return response(Storage::get("original_pdfs/$mapname"))->header('Content-type', 'application/pdf');
});
