@extends('welcome')

@section('main')



<h1>Purchase Award</h1>
<p>
<!-- 	<a class="btn btn-default" href="/map/{{$mapname or ''}}/show">SHOW</a>  --><a class="btn btn-default" href="/pdf/{{$filename or ''}}" target="_blank">View PDF</a>
</p>
<p>
	<h5>Purchases</h5>
    <p>
	    Purchases<br>
    @foreach($purchases as $purchase)
    <a class="btn btn-default" href="/admin/purchase/{{$purchase['id']}}">{{$purchase['vendor']['name']}}{{ $purchase['kosher'] ? ' (Kosher)' : '' }}</a>
    @endforeach
    </p>	<a class="btn btn-default" href="/admin/purchase?purchase_award_id={{$id}}">New</a>
	
</p>
	<form enctype="multipart/form-data" method="post" action="/admin/purchase-award/{{$id}}">
	{!! csrf_field() !!}
	{!! method_field('post') !!}
	<p>product 
		<input name="title" placeholder="title" value="{{ $title or null }}" class="form-control">
	</p>
	
	<p>solicitation_number 
		<input name="solicitation_number" placeholder="solicitation_number" value="{{ $solicitation_number or null }}" class="form-control">
	</p>
	
	<p>contract_number 
		<input name="contract_number" placeholder="contract_number" value="{{ $contract_number or null }}" class="form-control">
	</p>
	
	<p>fiscal_year 
		<input name="fiscal_year" placeholder="fiscal_year" value="{{ $fiscal_year or null }}" class="form-control">
	</p>
	
	<p>solicitation_date 
		<input name="solicitation_date" type="date" value="{{ isset($solicitation_date) ? date('Y-m-d', strtotime($solicitation_date)) : null }}" class="form-control">
	</p>
	
	<p>date 
		<input name="date" type="date" value="{{ isset($date) ? date('Y-m-d', strtotime($date)) : null }}" class="form-control">
	</p>
	
	<p>lost_cases 
		<input name="lost_cases" placeholder="lost_cases" value="{{ $lost_cases or null }}" class="form-control">
	</p>
	


	<p> extra1 
		<textarea name="extra1" class="form-control">{{ $extra1 or null}}</textarea>
	</p>
	
	<p> extra2
		<textarea name="extra2" class="form-control">{{ $extra2 or null}}</textarea>
	</p>
	
	<p> extra3 
		<textarea name="extra3" class="form-control">{{ $extra3 or null}}</textarea>
	</p>
	
	<p> extra4 
		<textarea name="extra4" class="form-control">{{ $extra4 or null}}</textarea>
	</p>
	
	<p> extra5 
		<textarea name="extra5" class="form-control">{{ $extra5 or null}}</textarea>
	</p>
	
	<p> extra6 
		<textarea name="extra6" class="form-control">{{ $extra6 or null}}</textarea>
	</p>
	
	
	
<p><button type="submit" class="btn btn-default">Submit</button></p>
</form>


@endsection