@extends('welcome')

@section('main')



<div class="card col-lg-8 col-lg-push-4" style="position: sticky; top: 0;">

	<div class="buttons form-inline">
		<span class="margin-bottom10 margin-top btn btn-default btn-sm" style="height: 50px; visibility: hidden">.</span>
	</div>
	<div class="table-responsive">
		<div class="embed-responsive embed-responsive-4by3">
			<iframe id="showbox" name="showbox" frameborder="0"></iframe>
		</div>
	</div>	
</div>







<div class="col-lg-4  col-lg-pull-8 cards" style="overflow: auto; max-height: 100%; ">

<button type="button" class="btn btn-default btn-sm" style="margin-bottom: 20px;" data-toggle="collapse" data-target="#saved_presets"><i class="fa fa-eye"></i> View Saved Presets</button>
<div id="saved_presets" class="collapse">
	<div class=" margin-bottom40">
	@forelse(\App\Preset::get() as $saved_preset)
		<a class="btn btn-default btn-block" style="margin-bottom: 10px;" href="?{{ $saved_preset->query }}">{{$saved_preset->title}}</a> 
	@empty
	
	<div class="alert alert-warning">No Saved Alerts</div>
	@endforelse
	</div>
</div>

<form action="admin/map" target="showbox">
<div class="collapse spcollapse in">	
	<button type="submit" name="json" data-toggle="collapse" data-target=".spcollapse" value="1" style="max-width: 400px; " class="btn btn-primary btn-block spreadsheetbutton center-block margin-bottom20"><i class="fa fa-file-excel-o"></i> View Spreadsheet</button>
</div>
<div class="collapse spcollapse">
	<button type="submit" data-toggle="collapse" data-target=".spcollapse" style="max-width: 400px;" class="btn btn-default btn-block mapreturnbutton center-block"><i class="fa fa-map-o"></i> View Map</button>
</div>
<!-- 	onclick="$(this).fadeOut()" -->
	
	<p class="preset_name_holder margin-top20" style="{{ Request::has('preset_id') ? '' : 'display: none' }}">
		<input class="form-control noautosubmit" name="preset_title" value="{{ Request::has('preset_title') ? Request::input('preset_title') : '' }}" placeholder="Enter a Name for the Preset">
		@if(Request::has('preset_id'))<input class="noautosubmit" name="preset_id" value="{{ Request::input('preset_id') }}" type="hidden">@endif
		
		
	</p>	
	<button type="button" name="save_preset" value="1" style="max-width: 400px; margin-bottom: 10px;" class="btn btn-info btn-block savepreset center-block margin-bottom20 margin-top20"><i class="fa fa-save"></i> Save{{ Request::has('preset_id') ? '' : ' as a' }} Preset</button>	
	@if(Request::has('preset_id'))<button type="submit" name="delete_preset" value="1" class="btn btn-danger deletepreset btn-block btn-xs center-block margin-bottom20" style="max-width: 400px;"><i class="fa fa-trash-o"></i> Delete Preset</button>@endif

	<div class="form-group">
		<h5 class="form-label">Purchase Awards to Include</h5>
		<div class="pa-list">
			<fieldset class="pa-list-fields">
				<div class="col-count-2">
@foreach(\App\PurchaseAward::all() as $i => $pa)
				<label style="font-weight: normal" class="margin-bottom10">	
				<?php $request_id = []; if(Request::has('id') && !is_array(Request::input('id'))) $request_id = [Request::input('id')]; else if(Request::has('id')) $request_id = Request::input('id');  ?>
				<input name="id[]" {{ in_array($pa->id, $request_id) ? 'checked' : '' }}{{ !Request::has('id') && !$i ? 'checked' : '' }} value="{{$pa->id}}" type="checkbox"> {{$pa->extra1}} - {{$pa->date}}
				</label>
@endforeach
				</div>
			<p class="default-warning alert alert-warning" style="display: none">No purchase awards selected, defaulting to single most recent only</p>
			</fieldset>
		</div>
	</div>
	

	<div class="form-group">
		<h5 class="form-label">Include/Exclude Vendors</h5>
		
			<fieldset class="">
				<div class="col-count-2">
@foreach(\App\Vendor::all() as $i => $vnd)
				<label style="font-weight: normal" class="margin-bottom10">	
<?php $vendor_id = []; if(Request::has('vendor') && !is_array(Request::input('vendor'))) $vendor_id = [Request::input('vendor')]; else if(Request::has('vendor')) $vendor_id = Request::input('vendor');  ?>				
				<input name="vendor[]" {{ in_array($vnd->id, $vendor_id) ? 'checked' : '' }}{{ !Request::has('vendor') ? 'checked' : '' }} value="{{$vnd->id}}" type="checkbox"> {{ucwords(strtolower($vnd->name))}}
				</label>
@endforeach
			</div>
			<p class="default-warning alert alert-warning" style="display: none">No vendors selected, showing all vendors.</p>
			</fieldset>
		
	</div>
	
	
	
@foreach(array_except(config('app.defaults'), ['kosher', 'ext', 'jpg_size', 'species', 'vendor']) as $key => $val)
<?php 
	$checked = '';
	$form_control = 'form-control';
	$checkboxes = ['combined'];
	if(in_array($key, $checkboxes)){
		$form_control = '';
	}	
	if(Request::has($key)) {
		if(in_array($key, $checkboxes)){
			$checked = 'checked';
			$val === false;
		}
		else {
			$val = Request::input($key);	
		}
	}
	 ?>
@if(str_contains($key, 'row_break'))
	<div class="clearfix"></div>
	<h4 class="margin-top10">{{$val}}</h4>
@else
	<div class="card col-lg-6 col-md-{{ $key == 'download_filename' ? '6' : '3' }}">
		<div class="form-group">
			<label class="form-label">{{  ucwords(str_replace('_', ' ', $key))  }}</label>
			<input class="{{$form_control}}" name="{{$key}}" @if($key == 'download_filename') type="text" @endif {!! $val === false ? 'type="checkbox" value="1" ' . $checked : '' !!}  value="{{$val}}"  {!! starts_with($val, '#') ? 'type="color"' : 'type="number"' !!}>
		</div>
	</div>
@endif
@endforeach
	
<!-- 	<div class="clearfix"></div> -->
	
	<div class="card col-md-4 col-lg-7">
		<div class="form-group">
			<label class="form-label">Kosher/Non-Kosher</label>
			<div class="label-list">
				<label class="form-label">
					<input {{ Request::has('kosher') && Request::input('kosher') == '1' ? 'checked' : '' }} class="" name="kosher" type="radio" value="1"> Kosher
				</label>
				<label class="form-label">
					<input {{ Request::has('kosher') && Request::input('kosher') == '0' ? 'checked' : '' }} class="" name="kosher" type="radio" value="0"> Non-Kosher
				</label>
				<label class="form-label">
					<input {{ Request::has('kosher') && Request::input('kosher') == '2' ? 'checked' : '' }}{{ !Request::has('kosher') ? 'checked' : '' }}  name="kosher" type="radio" value="2"> Both
				</label>							
			</div>
		</div>
	</div>	
	
	
	<div class="card col-md-4 col-lg-7">
		<div class="form-group">
			<label class="form-label">Salmon Species</label>
			<div class="label-list">
				<label class="form-label">
					<input {{ Request::has('species') && Request::input('species') == 'pink' ? 'checked' : '' }} name="species" type="radio" value="pink"> Pink
				</label>
				<label class="form-label">
					<input {{ Request::has('species') && Request::input('species') == 'red' ? 'checked' : '' }} class="" name="species" type="radio" value="red"> Red
				</label>
				<label class="form-label">
					<input {{ Request::has('species') && Request::input('species') == '2' ? 'checked' : '' }}{{ !Request::has('species') ? 'checked' : '' }} name="species" type="radio" value="2"> Both</label>							
			</div>
		</div>
	</div>	
	
	
	<div class="card col-md-2 col-lg-5">
		<div class="form-group">
			<label class="form-label">Type</label>
			<div class="label-list">
				<label class="form-label">
					<input {{ Request::has('type') && Request::input('type') == 'cases' ? 'checked' : '' }}{{ !Request::has('type') ? 'checked' : '' }} name="type" type="radio" value="cases"> Cases
				</label>			
				<label class="form-label">
					<input {{ Request::has('type') && Request::input('type') == 'dollars' ? 'checked' : '' }} name="type" type="radio" value="dollars"> Dollars
				</label>
			</div>
		</div>
	</div>
	
	
	<div class="card col-md-2 col-lg-6">
		<div class="form-group">
			<label class="form-label">Output Format</label>
			<div class="label-list">
				<label>
					<input {{ Request::has('output') && Request::input('output') == 'svg' ? 'checked' : '' }}{{ !Request::has('output') ? 'checked' : '' }}  name="output" type="radio" value="svg"> SVG
				</label>			
				<label>
					<input {{ Request::has('output') && Request::input('output') == 'jpg' ? 'checked' : '' }} name="output" type="radio" value="jpg"> JPG
				</label>			
				<label>
					<input {{ Request::has('output') && Request::input('output') == 'pdf' ? 'checked' : '' }} name="output" type="radio" value="pdf"> PDF
				</label>
			</div>
		</div>
	</div>
	
	<div class="card col-md-3 col-lg-6">
		<div class="form-group">
			<label class="form-label">JPG Size</label>
			<div class="label-list">
				<label><input {{ Request::has('jpg_size') && Request::input('jpg_size') == 'xs' ? 'checked' : '' }} name="jpg_size" type="radio" value="xs"> XS</label>			
				<label><input {{ Request::has('jpg_size') && Request::input('jpg_size') == 'sm' ? 'checked' : '' }}{{ !Request::has('output') ? 'checked' : '' }} name="jpg_size" type="radio" value="sm"> SM</label>			
				<label><input {{ Request::has('jpg_size') && Request::input('jpg_size') == 'md' ? 'checked' : '' }} name="jpg_size" type="radio" value="md"> MD</label>
				<label><input {{ Request::has('jpg_size') && Request::input('jpg_size') == 'lg' ? 'checked' : '' }} name="jpg_size" type="radio" value="lg"> LG (ok print)</label>
				<label><input {{ Request::has('jpg_size') && Request::input('jpg_size') == 'xl' ? 'checked' : '' }} name="jpg_size" type="radio" value="xl"> XL (ok print)</label>
			</div>
		</div>
	</div>
	
	<div class="col-md-2 col-lg-6">
		<div class="form-group text-center">
			<button type="reset" class="btn btn-default btn-md">Reset</button>
			<button class="btn btn-default btn-md">Submit</button>
			<input type="hidden" id="version" name="v" value="{{rand()}}">
		</div>		
	</div>
	
</form>
</div>

<!-- <div class="clearfix"></div> -->




<script>
	
	$(document).ready(function(){
		$('.buttons').append('<i class="fa fa-cog fa-spin fa-lg dlbutton"></i>');
		$('form').submit();
		$.event.trigger({
			type: "formchanged",
		});			
		
	});
		
	$(document).on('click', '.savepreset, .deletepreset', function(e){
		e.preventDefault();
		$('form').removeAttr('action').removeAttr('target');
		$('.preset_name_holder').slideDown();
		$(this).attr('type', 'submit').removeClass('savepreset deletepreset').toggleClass('btn-info btn-success').text('Click Again to Continue...');
	});
		
	var notspreadsheet = false;
		
	$(document).on('change click', 'input, select, textarea, .spreadsheetbutton', function(e){
		var thistarget = e.target;
		if($(e.target).is('[readonly], .noautosubmit')) return;
		$('.dlbutton').remove();
		$('.buttons').append('<i class="fa fa-cog fa-spin fa-lg dlbutton"></i>');
		if($(this).is('[name="combined"]')){
			if($(this).is(':checked'))
				$('.pa-list-fields, .pa-list').attr('disabled', 'disabled').addClass('disabled');
			else
				$('.pa-list-fields, .pa-list').removeAttr('disabled').removeClass('disabled');	
		}
		if(!$(this).is('.spreadsheetbutton')){
// 			setTimeout(function(){ 				
				$('form').submit(); 
// 			}, 300);
			notspreadsheet = true;
			// $('.mapreturnbutton').fadeOut();
		}
		// else $('.mapreturnbutton').fadeIn();

		$.event.trigger({
			type: "formchanged",
			target: thistarget,
		});			
		

	});
	
	
	$(document).on('formchanged', function(e){
		setTimeout(function(){
			var filename = $('[name="download_filename"]').val();
			if(!filename.length) filename = new Date;
			var ext = $('[name="output"]:checked').val();
			filename = filename + '.' + ext;
			$('#version').val(Math.floor((Math.random()*100000)+1));
			$('.dlbutton').remove();
			if(notspreadsheet === true)
				$('.buttons').append('<a class="dlbutton margin-bottom10 btn btn-default btn-sm downloadbutton" href="' + $('iframe')[0].contentWindow.location + '" download="' + filename + '"><i class="fa fa-cloud-download"></i> Download</a> ');			
			$('.buttons').append('<a class="dlbutton margin-bottom10 btn btn-default btn-sm externalwindowbutton" href="' + $('iframe')[0].contentWindow.location + '" target="_blank"><i class="fa fa-external-link-square"></i> Open New Window</a> ');			
			$('.buttons').append(' <input style="min-width:300px;" class="dlbutton XXmargin-bottom10 input-sm form-control form-inline" readonly value="' + $('iframe')[0].contentWindow.location + '">');
		}, 3500);
		
		$('fieldset').each(function(){
			if(!$(this).find('input:checked').length) $(this).find('.default-warning').fadeIn();
			else $(this).find('.default-warning').hide();
		});
	});
	
	
	
	$(document).on('click', '.downloadbutton, .externalwindowbutton', function(e){
		$(this).attr('href', $('iframe')[0].contentWindow.location );		
	});
	
	
</script>

@endsection