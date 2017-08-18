<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use DB;
use Schema;

class Quicksand
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct()
    {
//         $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    
	    
	    
	    
	    
	    // BYPASSED!!!
	    return $next($request);
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    if(!file_exists(config('database.connections.sqlite.database'))) file_put_contents(config('database.connections.sqlite.database'), '');
	    
	    if (!Schema::connection('sqlite')->hasTable('quicksand_data')) {
// 		    Schema::connection('sqlite')->drop('quicksand_data');
			Schema::connection('sqlite')->create('quicksand_data', function ($table) {
			    $table->increments('id');
			    $table->string('email');
			    $table->string('ip')->nullable()->unique();
			    $table->string('session_token')->nullable();
			    $table->string('action')->default('allow');
			    $table->nullableTimestamps();
			});
		}

		$whitelist = DB::connection('sqlite')->table('quicksand_data')->get();
	    
	    date_default_timezone_set('America/New_York');
	    
	    $cookie_expiration = is_null($request->cookie('quicksand_expiration')) ? time() : $request->cookie('quicksand_expiration');
		$cookie = $request->cookie('quicksand');
		$anger = 1;
		if($cookie) $anger = $cookie < 10 ? $cookie + 1 : 10;
	    $gamble = rand(0, 20) > 19;
	    $luckyday = rand(0, 20) > 12;
	    $shakeup = rand(0, 100) < 2;
	   
	    $bizhours = (int)date('G') >= 8 && (int)date('G') <= 18;  
	    
	    $quicksand_session_token = $request->session()->get('quicksand_session_token', false);
	    $whitelisted = $quicksand_session_token ?  DB::connection('sqlite')->table('quicksand_data')->where('quicksand_session_token', $quicksand_session_token)->value('action') : false;  
// 	    dd($quicksand_session_token);
	    
	    
	    $anger_messages = [
		    "Please, be patient.",
		    "This message will go away momentarily, please do not refresh the page. Revisit again after " . date('g:i', $cookie_expiration),
		    //"You'll have to come back if you can't resist hitting refresh",
	    ];
	    
	    $anger_message = isset($anger_messages[$anger]) ? $anger_messages[$anger] : $anger_messages[array_rand($anger_messages)]; 
	    $purgatory = response()->view('errors.503', ['anger' => $anger, 'gamble' => $gamble, 'anger_message' => $anger_message])->withCookie(cookie('quicksand', $anger, $anger))->withCookie(cookie('quicksand_expiration', time() + ($anger * 60), $anger));
	    
        if ($luckyday) {
	        return $next($request);
        }
        
        
        
        if($bizhours && !$shakeup){
	        // Make ok for the day
	        if(isset($_SERVER['REMOTE_ADDR']))
		        if( DB::connection('sqlite')->table('quicksand_data')->where('ip', $_SERVER['REMOTE_ADDR'])->first() )
				        DB::connection('sqlite')->table('quicksand_data')->where('ip', $_SERVER['REMOTE_ADDR'])->delete();
		        
	        $newsessiontoken = str_random(128);
	        session(['quicksand_session_token' => $newsessiontoken]);
	        DB::connection('sqlite')->table('quicksand_data')->insert(['email' => 'tech@jmbdc.com', 'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null , 'session_token' => $newsessiontoken]);
	        return $next($request);
        }
        if(!$bizhours && !$shakeup){
	        return $purgatory;
        }
        if ($anger > 3) {
	        return $purgatory;
        }
        else if($gamble){
	        return $purgatory;
        }

        return $next($request);
    }
}
