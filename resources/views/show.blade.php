@extends('welcome')

@section('main')
<p>
	<a class="btn btn-default" href="/map/{{$mapname}}">EDIT</a>
</p>
<table class="table table-striped table-bordered">
@foreach($data as $i => $row)
<tr>
	@foreach($row as $cell)
	<td>{{$cell}}</td>
	@endforeach
</tr>
@endforeach
</table>

@endsection