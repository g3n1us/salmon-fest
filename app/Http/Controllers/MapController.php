<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage;
use DB;
use pQuery;

class MapController extends Controller
{
	
	public function __construct(){
		$this->middleware('quicksand');
	}
	
	public function getMap(Request $request, $id = false){
		
		if($id === false && $request->has('id')){
			$id = $request->input('id');
		}
		else if($id === false) {
			$data['showing_most_recent'] = true;
			$id = [\App\PurchaseAward::count() - 1];
		}
		else if(!is_array($id)){
			$id = [$id];
		}
		
		// config items
		foreach(config('app.defaults') as $defaultkey => $defaultvalue ){
			$data[$defaultkey] = $request->has($defaultkey) ? $request->input($defaultkey) : $defaultvalue;
			$$defaultkey = $data[$defaultkey];
		}
		if($vendor === false) $vendor = \App\Vendor::get()->pluck('id')->all();

		if($request->has('jpg')) $ext = 'jpg';
		
		if($ext == 'jpg' || $request->has('output')){			
			
			if($request->has('output')) $ext = $request->input('output');
			
			if(in_array($ext, ['jpg', 'pdf'])){
				$mime = $ext == 'pdf' ? 'application/pdf' : 'image/jpg';
				$shellexecurl = $request->url() . "?" . http_build_query(array_except($request->all(), ['jpg', 'output']));
				
				$outputpath = public_path('tmpfiles');
				$rand = rand();
				$params = $request->all();
				ksort($params);
				$queryhash = base64_encode(http_build_query($params));
				$ogfilename = $request->has('download_filename') ? $request->input('download_filename') . ".$ext" : "$rand.$ext";
				$filename = "$outputpath/$ogfilename";
				
				if(file_exists($filename)) unlink($filename);
				if(file_exists($filename . ".svg")) unlink($filename . ".svg");
				if($ext == 'jpg'){
// 					dd($shellexecurl);
					$jpgres = config('app.jpg_sizes')[$jpg_size];
					shell_exec("convert -density $jpgres '$shellexecurl' '$filename'");
// 					dd("convert -density $jpgres '$shellexecurl' '$filename'");
				}
				else if($ext == 'pdf'){
					shell_exec("wget -O '$filename.svg' '$shellexecurl'");
					shell_exec("rsvg-convert -f pdf -o '$filename' '$filename.svg'");
				}
				$f = @file_get_contents($filename);
				return response($f ?: null)
					->header('Content-Type', $mime)
					->header('Cache-Control', 'max-age=' . 30 * 24 * 60 * 60);
			}
			
		}
		
		// options cases, dollars, combined
		$data['report_type'] = 'cases';
		$data['is_dollars'] = false;
		if($request->has('type')){
			$data['report_type'] = $request->input('type');
		}
		if($data['report_type'] == 'cases'){
			$data['subtitle'] = 'Total Cases per Recipient City';
			$data['val_label'] = 'Total number of cases:';
		}
		else if($data['report_type'] == 'dollars'){
			$data['subtitle'] = 'Total Dollars Spent per Recipient City';
			$data['val_label'] = 'Total dollar amount:';
			$data['is_dollars'] = true;
		}
		$data['pa'] = \App\PurchaseAward::with('purchases', 'purchases.vendor', 'purchases.purchase_award')->whereIn('id', $id)->get();
		$items = collect([]);
		
		$data['pa']->each(function($item, $key) use($items){
			$items[] = $item->data_points;
		});
		$items = $items->collapse();
		if($combined){
			$items = \App\DataPoint::all();
			$data['pa'] = \App\PurchaseAward::with('purchases', 'purchases.vendor', 'purchases.purchase_award')->get();			
		}
		
		if($kosher < 2){
			$items = $items->filter(function ($dp) use($kosher){
			    return $dp->purchase->kosher == $kosher;
			});		
		}
		
		if($species != 2){
			$items = $items->filter(function ($dp) use($species){
				return str_contains(strtolower($dp->purchase->product), $species);
			});		
		}
		
		if($request->has('vendor')){
			$items = $items->filter(function ($dp) use($vendor){
			    return in_array($dp->purchase->vendor->id, $vendor);
			});		
		}
		
		
		
		
		
		$items = $items->sortByDesc('quantity');
		$maxcases = $data['maxcases'] = $items->pluck('quantity')->max();
		$mincases = $data['mincases'] = $items->pluck('quantity')->min();
		$moneycollection = [];
		foreach($items as $item){
			$moneycollection[] = $item->quantity * $item->price;
		}
		$moneycollection = collect($moneycollection);
		$maxdollars = $data['maxdollars'] = $moneycollection->max();
		$mindollars = $data['mindollars'] = $moneycollection->min();

// 		$data['total_dollars'] = $data['pa']->sum('total_amount');
		$data['total_dollars'] = $items->reduce(function ($carry, $item) {
		    return $carry + ($item->quantity * $item->price);
		});		
		$data['total_cases'] = $data['pa']->sum('total_cases');
		$data['total_cases'] = $items->sum('quantity');
		
		
		$data['dots'] = '';
		$data['key'] = '';
		$time = time();
		foreach($items as $itemindex => $item){
			$items[$itemindex]->purchase_award = $item->purchase_award;
			if($data['report_type'] == 'cases')
				$r = $item->quantity/$maxcases * $base_dot_size;
			else
				$r = $item->quantity * $item->price/$maxdollars * $base_dot_size;
				
			$dotfill = $dot_fill;
			if(str_contains(strtolower($item->purchase->product), 'red')) $dotfill = $red_salmon_dot_fill;
			$kosherstring = '';
			$roffset = (int)round(($r * 1.5) * 0.33);
			$rvoffset = (int)round(($r * 1.5) * 0.38);
			// dd($roffset);
			if($item->purchase->kosher) $kosherstring = '<text font-family="Helvetica" x="-'. $roffset .'" y="'. $rvoffset .'" font-size="'. $r * 1.5 .'" fill="#ffffff">K<title>' . $item->city . ', ' . $item->state  . ' - ' . number_format($item->quantity) . ' cases - $' . number_format($item->quantity * $item->price) .' - Kosher</title></text>';
				
			$xy = \App\SvgCoords::where('city', strtolower($item->city))->where('state', strtolower($item->state))->first();
			if($xy) $xy = array_values(array_only($xy->toArray(), ['x', 'y']));
			else{
				Storage::append("finderlogs/$time", $item->city . ', ' . $item->state);
				continue;

			}
			
			if($xy){
				$koshernotkosher = empty($kosherstring) ? 'Non-Kosher' : 'Kosher';
				$data['dots'] .= '<g transform="translate('.$xy[0].', '.$xy[1].')"><circle r="'.$r.'" stroke="'.$data['dot_stroke'].'" stroke-width="'.$data['dot_stroke_width'].'" fill="'.$dotfill.'"><title>' . $item->city . ', ' . $item->state  . ' - ' . number_format($item->quantity) . ' cases - $' . number_format($item->quantity * $item->price) .' - '. $koshernotkosher .'</title></circle>'.$kosherstring.'  </g>';
				
				
// 				$data['dots'] .= '<circle cx="'.$xy[0].'" cy="'.$xy[1].'" r="'.$r.'" stroke="'.$data['dot_stroke'].'" stroke-width="'.$data['dot_stroke_width'].'" fill="'.$dotfill.'"><title>' . $item->city . ', ' . $item->state  . ' - ' . number_format($item->quantity) . ' cases - $' . number_format($item->quantity * $item->price) .'</title>'.$kosherstring.'</circle>';
				$data['key'] .= '<circle r="'.$r.'" stroke="'.$data['dot_stroke'].'" stroke-width="'.$data['dot_stroke_width'].'" fill="'.$dotfill.'" />';				
			}
		}
// 		dd($data['pa']);
// 		dd($items[0]->purchase_award->total_cases);
		$collecteditems = collect($items->toArray());
		foreach($data['pa'] as $paindex => $pa){
// 			dd( $collecteditems->where('purchase_award_id', $pa->id)->pluck('quantity')->sum()  );
			$data['pa'][$paindex]->total_cases = $collecteditems->where('purchase_award_id', $pa->id)->pluck('quantity')->sum();
			
			
		}
		
			
		
		
		if($request->has('json')) {
			$data['items'] = $items->toArray();
			return response()->view('excel', array_only($data, ['items', 'total_cases', 'maxdollars', 'mindollars', 'maxcases', 'mincases', 'total_dollars', 'report_type', 'subtitle', 'pa']));
		}
		
		return response()->view('map', $data)
			->header('Content-Type', 'image/svg+xml')
			->header('Cache-Control', 'max-age=' . 30 * 24 * 60 * 60);
	}
	// http://maps.googleapis.com/maps/api/geocode/json?address=tampa,%20fl&sensor=true
	
	
	public function getSearch(Request $request, $city, $state){
		
	}
	
	
	private function getSvgCoords($city, $state, $dom = false){
		$xy = \App\SvgCoords::where('city', strtolower($city))->where('state', strtolower($state))->first();
		if($xy) return array_values(array_only($xy->toArray(), ['x', 'y']));

		else{
			
			return false;
			
		}


		
		$url = strtolower('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode("$city, $state") . '&sensor=true');
// 		$resp = file_get_contents($url);
		
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url,
		    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		
		
		if(empty(json_decode( $resp )->results)) {
			
			$zips = DB::table('zipcodes')->where('city', strtoupper($city))->where('state', strtoupper($state))->get();
		}
		else{
			$coords = json_decode( $resp )->results[0]->geometry->location;
			
	 		$zips = DB::table('zipcodes')->whereBetween('latitude', [$coords->lat - .01, $coords->lat +.01])->whereBetween('longitude', [$coords->lng - .01, $coords->lng + .01])->get();
			
		}
 		$zip = empty($zips) ? false : $zips[0]->zipcode; 
 		if(!$zip) {
	 		Storage::put("missing_from_zips/$city-$state", $city);
	 		return $city;
 		}
		$state = strtoupper($state);
		$fip = DB::table('FIPStoZIPS')->where('zipcode', $zip)->first();
		if(!$fip) $fip = DB::table('FIPStoZIPS')->where('city', strtoupper($zips[0]->city))->where('state', $state)->first();
		$county = ucwords(strtolower($fip->county));
// 		dd($county . ' ' . $state . " | $city");
		
		if($dom == false) $dom = pQuery::parseFile(storage_path() . '/usa_map.svg');		
		$tag = $dom->query('[label="'.$county.', '.$state.'"]');
		
		if(!$tag[0]) {
			Storage::put("county_missing_from_map/$county", $county);
			return $county;
		}

		$d = $tag[0]->attr('d');
		$dd = preg_match('/M(.*?)L/', $d, $matches);
		$ddd = trim(str_replace(['L', 'M'], '', $matches[0]));
		$xy = explode(",", $ddd);
		$added = new \App\SvgCoords;
		$added->x = $xy[0];
		$added->y = $xy[1];
		$added->city = strtolower($city);
		$added->state = strtolower($state);
		$added->save();
		return $xy;
	}
	
	
    public function getIndex(){
	    $data['pas'] = \App\PurchaseAward::all();
	    return view('list', $data);
	    
    }
    
    public function getRefresh(){
	    $maps = Storage::files('original_pdfs');
	    foreach($maps as $map){
		    $makepa = \App\PurchaseAward::firstOrCreate(['filename' => basename($map)]);
		    unset($makepa);
	    }
	    return redirect()->back();
    }
    
    
    
    
    public function getPurchaseAward($id){
	    $pa = \App\PurchaseAward::with('purchases', 'purchases.vendor')->findOrFail($id);
	    return view('purchase-award', $pa);
    }



    
    public function postPurchaseAward(Request $request, $id){
	    $pa = \App\PurchaseAward::with('purchases', 'purchases.vendor')->findOrFail($id);
	    $pa->update($request->all());
	    return redirect()->back();
    }
    
    
    
    
    public function getPurchase(Request $request, $purchase_id = null){
	    if(is_null($purchase_id) && $request->has('purchase_award_id')){
		    $pa = \App\PurchaseAward::find($request->input('purchase_award_id'));
		    $pa->purchases()->create([
			    'product' => $pa->title,
			]);
			return redirect()->back();
	    }
		$data['purchase'] = \App\Purchase::with('data_points')->find($purchase_id);
		if(!$data['purchase']) $data['purchase'] = new \App\Purchase;
		
		
	    $fullpath = storage_path('app/original_pdfs') . "/" . $data['purchase']->purchase_award->filename;
	    $data['pdftext'] = shell_exec("pdftotext -nopgbrk '$fullpath' -");		    
		
		return view('purchase', $data);
    }


    
    
    public function postPurchase(Request $request, $purchase_id = null){
	    
		if(empty($purchase_id)) {
			
			$purchase = \App\Purchase::create($request->all());
			$purchase->save();
		}
		else {
			$purchase = \App\Purchase::find($purchase_id);
			
			if($request->has('bulktext')){
				$rows = $this->convert_pdf_text_to_data($request->input('bulktext'));
			    foreach($rows as $row){
				    $dbrow = new \App\DataPoint;
				    $dbrow->bid_inv_item = trim($row[0]);
				    $citystate = explode(",", $row[1]);
				    $dbrow->city = trim($citystate[0]);
				    $dbrow->state = trim($citystate[1]);
				    $dbrow->quantity = trim( str_replace([',', 'CS', ' '], '', $row[2]));
				    $dbrow->price = trim( str_replace(['$',' '], '', $row[3]));
				    $dbrow->purchase_id = $purchase->id;
				    $dbrow->vendor_id = $purchase->vendor->id;
				    $dbrow->save();
			    }
				
			}
			
			$purchase->update( array_except($request->all(), ['bulktext']) );
		}
		
		return redirect('/admin/purchase/' . $purchase->id);
    }    
    
    
    public function getDeleteDatapoint(Request $request, $id){
	    \App\DataPoint::find($id)->delete();
	    return redirect()->back();
    }
    
    public function getDeleteallpurchasedatapoints(Request $request, $id){
	    \App\Purchase::find($id)->data_points()->delete();
	    return redirect()->back();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function getShow($mapname){
		 if(!Storage::has("original_texts/$mapname.txt")) return redirect("/map/$mapname");
		 
		 $pdftext = Storage::get("original_texts/$mapname.txt");
		 $data['mapname'] = $mapname;
		 $data['data'] = $this->convert_pdf_text_to_data($pdftext);
		 $this->insert_database($mapname, $data['data']);
		 return view('show', $data);	    
    }
    
    
    

    
    
    
    public function maptrim($mapname){
	    $data['mapname'] = $mapname;
	    if(Storage::has("original_texts/$mapname.txt") && trim(Storage::get("original_texts/$mapname.txt")) ) $data['pdftext'] = Storage::get("original_texts/$mapname.txt");
	    else{
		    $fullpath = storage_path('app/original_pdfs') . "/$mapname";
		    $data['pdftext'] = shell_exec("pdftotext -nopgbrk '$fullpath' -");		    
	    }
	    return view('trim', $data);
	    
    }
    
    
    public function savetrim(Request $request, $mapname){
	    $text = $request->input('trimmedtext');
	    $title = $request->input('title');
// 	    dd($request);
	    $mapdata = \App\MapData::firstOrNew(['filename', $mapname]);
	    	    

	    $mapdata->trimmedtext = $text;
	    $mapdata->title = $title;
	    
	    if(Storage::put("original_texts/$mapname.txt", $text) && $mapdata->save()) return redirect()->back();
    }
    
    
    public function insert_database($mapname, $rows){
	    foreach($rows as $row){
		    $dbrow = \App\DataPoint::firstOrNew(['filename', $mapname]);
		    $dbrow->bid_inv_item = trim($row[0]);
		    $citystate = explode(",", $row[1]);
		    $dbrow->city = trim($citystate[0]);
		    $dbrow->state = trim($citystate[1]);
		    $dbrow->quantity = trim( str_replace([',', 'CS', ' '], $row[2]));
		    $dbrow->price = trim( str_replace(['$',' '], $row[3]));
		    $dbrow->save();
		    
	    }
    }
    
    
    public function convert_pdf_text_to_data($contents){
		
		$contents = trim($contents);			
		$contents = str_replace("\r\n", "\n", $contents);
		$contents = str_replace("\n\n", "\n", $contents);
		$contents = str_replace("$\n", "$", $contents);
			
		$contents = str_replace("\n", " ", $contents);
// 		$contents = preg_replace("/PCA(.*?)Quantity(.*?)UOM(.*?)Price/si", "", $contents); // trim this off manually
		$contents = trim($contents);
		$contents = preg_replace("/Page(.*?)Price/si", "", $contents);
		$contents = preg_replace("/Subtotal(.*?)OZ/si", "", $contents);
		
		$contents = preg_replace_callback("/\\$.[0-9|\.]*[0]*\\s/", function($matches){
			return $matches[0] . "\n";
		}, $contents);
		$contents = preg_replace("/\\n(\\s*)/", "\n", $contents);
		
		
		$response = array();
		$rows = explode("\n", $contents);
		foreach($rows as $i => $row){
			$rows[$i] = $row = trim(str_replace("\t", "",$row));
			$row = preg_replace('/\\s/', "\t", $row, 1);
			$row = preg_replace_callback('/\\s([0-9|\,|\.|\\s]*)CS/i', function($matches){
				return "\t" . $matches[0] . "\t";
			}, $row, 1);
			$cells = explode("\t", $row);
			foreach($cells as $ci => $cell){
				$cells[$ci] = trim($cell);
			}
			
			$rows[$i] = $cells;
		}
		
		return collect($rows);
	}
    
	    
	    
}
    
    

