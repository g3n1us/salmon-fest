@extends('welcome')

@section('main')

<?php // dd($purchase) ?>

<h1>Purchase</h1>
<p>
<a class="btn btn-default" href="/pdf/{{$purchase->purchase_award->filename or ''}}" target="_blank">View PDF</a>
</p>
	<form enctype="multipart/form-data" method="post" action="/admin/purchase/{{$purchase->id}}">
	{!! csrf_field() !!}
	{!! method_field('post') !!}
	<p>product - 
		<b class="h3">{{ $purchase->purchase_award->title or '' }}</b>
	</p>
	
	<p> kosher 
		<input type="hidden" name="kosher" value="0">
		<input name="kosher" type="checkbox" value="1" {{ $purchase->kosher ? 'checked' : '' }} class="form-control">
	</p>
	
	<p> Vendor</p>
	<p> 
		@foreach(\App\Vendor::all() as $vendor)
		<label><input name="vendor_id" type="radio" value="{{ $vendor->id }}" {{ $vendor->id == $purchase->vendor_id ? 'checked' : '' }}> {{ $vendor->name }}</label>
		@endforeach
	</p>
	
	<h4>Data</h4>
	<a href="/admin/deleteallpurchasedatapoints/{{$purchase->id}}">delete all data</a>
<table class="table table-striped table-bordered">	
	@forelse($purchase->data_points as $row)


<tr>
	<td>{{$row->city}}, {{$row->state}}</td>
	<td>{{$row->quantity}}</td>
	<td>{{$row->price}}</td>
	<td style="width: 30px"><a class="btn btn-danger btn-xs" href="/admin/delete-datapoint/{{$row->id}}">x</a></td>
</tr>

	
	@empty
	<p>
		@if($purchase->vendor) <textarea name="bulktext" rows="20" class="form-control">{{ $pdftext }}</textarea> @else add a vendor first @endif
	</p>
	
	@endforelse
</table>

	<p>
	</p>
	
	
<p><button type="submit" class="btn btn-default">Submit</button></p>
</form>


@endsection