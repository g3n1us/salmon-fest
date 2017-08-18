@extends('welcome')

@section('main')
<?php $onlykeys = ['city', 'state', 'quantity', 'price', 'purchase.product', 'purchase_award.date', 'purchase.kosher', 'purchase.vendor.name', 'purchase.vendor.contractor_id']; 
	$titlestring = count($pa) > 1 ? 'USDA Purchase of Canned Pink and Red Salmon, Oct 2014 - Sep 2015' : $pa[0]->extra1;
	if(Request::has('preset_title'))
		$titlestring = Request::input('preset_title');
	else
		$titlestring = count($pa) > 1 ? 'USDA Purchase of Canned Pink and Red Salmon, Oct 2014 - Sep 2015' : $pa[0]->extra1;
	$items = array_values($items);

?>
<style>
	body{
		background-color: transparent;
	}
</style>
<div class="margin-bottom20">
	<div class="form-group">
		<label>Filename to Save Spreadsheet As...</label>
		<input class="form-control" id="title" value="{{ $titlestring }}">
	</div>
	<a class="btn btn-default spreaddl"><i class="fa fa-cloud-download"></i> Download</a>	
</div>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="table">
<tr><td colspan="{{count($onlykeys)}}">
	<h4><b>{{ $titlestring }}</b></h4>{{"\n"}}
	<h5 class=""><i>All prices in dollars. Quantities are in numbers of cases of 24 cans.</i></h5>
</td></tr>
<tr>
	@foreach(array_only(array_dot($items[0]), $onlykeys) as $heading => $hval)
	<th>{{ tidy(basename(str_replace(".", "/", $heading))) }}</th>
	@endforeach
</tr>
	
@foreach($items as $i => $row)
<tr>
	@foreach(array_only(array_dot($row), $onlykeys) as $cell)
	<td>{{ is_array($cell) ? implode('|', $cell) : $cell }}</td>
	@endforeach
</tr>
@endforeach
</table>
</div>
@endsection