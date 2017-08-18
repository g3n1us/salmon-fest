@extends('welcome')

@section('main')
<?php $version = date('Ymd'); ?>


<!--
<style >
	.img-label{
		position: absolute;
	    bottom: 0;
	    left: 0;
	    width: 100%;
	    padding: 10px 0;
	    background-color: rgba(0, 0, 0, 0.39);
	    color: #fff;
	    font-size: 20px;
	    text-shadow: 1px 1px 1px black;
	}
	
	.img-label a{
		text-shadow: none;
	}
	
	.img-link{
	    position: relative;
	    display: inline-block;
/* 	    width: 33%; */
	    text-align: center;
	    border: solid 1px #cfcfcf;
	    float: none;
		padding-right: 0;
	    padding-left: 0;	    
	}
	.img-link:hover{
		border-color: #ffb2b2;
	}
	.img-link img{
		max-width: 100%;
		cursor: pointer;
	}
	
	.jmb_slider img{
		width: 100%;
		
	}
	.carousel-control {
		background-image: none !important;
		filter: none;
	}
	.btn+.btn{
		margin: 3px 0;
	}	
</style>
-->

    <div class="panel panel-default">
	    <div class="panel-body text-center">
	    <h3>Combined Fiscal Year 2015</h3>
<div		
class="img-link col-sm-5" title="Download: Cases-combined.svg" href="/admin/map/1.svg?combined=1&v={{$version}}">
		<img src="/admin/map/1.svg?combined=1">
		<span class="img-label">Map Combined - Cases 
		<a href="/admin/map/1.svg?combined=1&v={{$version}}" download="Cases-combined.svg" class="btn btn-xs btn-default"><i class="fa fa-save"></i> .svg</a>
<!-- 		<a href="/admin/map/1.svg?combined=1&v={{$version}}&jpg=1" download="Cases-combined.jpg" class="btn btn-xs btn-default"><i class="fa fa-save"></i> .jpg</a> -->
		<a href="/configure?combined=1" class="btn btn-xs btn-default"><i class="fa fa-gears"></i> configure</a>
		</span>
	</div>
	<div 		
class="img-link col-sm-5" title="Download: Dollar Amounts-combined.svg" href="/admin/map/1.svg?combined=1&type=dollars&v={{$version}}">
		<img src="/admin/map/1.svg?combined=1&type=dollars">
		<span class="img-label">Map Combined - Dollar Amounts 
		<a href="/admin/map/1.svg?combined=1&type=dollars&v={{$version}}" class="btn btn-xs btn-default" download="Dollars-combined.svg"><i class="fa fa-save"></i> .svg</a>
<!-- 		<a href="/admin/map/1.svg?combined=1&type=dollars&v={{$version}}&jpg=1" class="btn btn-xs btn-default" download="Dollars-combined.jpg"><i class="fa fa-save"></i> .jpg</a> -->
		<a href="/configure?combined=1&type=dollars" class="btn btn-xs btn-default"><i class="fa fa-gears"></i> customize</a>
		</span>
	</div>
	    </div>
    </div>

<!-- <p><a class="btn btn-default" href="/admin/refresh">Refresh List</a></p> -->
@foreach($pas as $pa)
    <div class="panel panel-default">
	    <div class="panel-body text-center">
	    <h3>{{$pa->extra1}}<br>
		    <small>Solicitation Number: {{ $pa->solicitation_number }}</small><br>
		    <small>{{ $pa->date }}</small>
	    </h3>
	    
<div>
	    <a class="btn btn-default" href="/pdf/{{$pa->filename}}" target="_blank">View USDA PDF</a> 
	    <a class="btn btn-default" href="/admin/map/{{$pa->id}}?json=1&show_nav=1">Download Spreadsheet</a>
		<a href="/configure?id={{$pa->id}}" class="btn btn-default"><i class="fa fa-gears"></i> Configure</a>
</div>   
	    
    

	<div
class="img-link col-sm-5" title="Download: {{ $pa->title }}-{{ $pa->solicitation_number }}-cases.svg" Xdownload="{{ $pa->title }}-{{ $pa->solicitation_number }}-cases.svg" href="/admin/map/{{$pa->id}}.svg&v={{$version}}">
		<img src="/admin/map/{{ $pa->id }}?v={{$version}}">
		<span class="img-label">Map - Cases
		<a href="/admin/map/{{$pa->id}}.svg?v={{$version}}" class="btn btn-xs btn-default" download="{{ $pa->title }}-{{ $pa->solicitation_number }}-dollars.svg"><i class="fa fa-save"></i> .svg</a>
<!-- 		<a href="/admin/map/{{$pa->id}}.svg?v={{$version}}&jpg=1&jpg_size=lg" class="btn btn-xs btn-default" download="{{ $pa->title }}-{{ $pa->solicitation_number }}-dollars.jpg"><i class="fa fa-save"></i> .jpg</a> -->
		</span>
	</div>
	<div 
class="img-link col-sm-5" title="Download: {{ $pa->title }}-{{ $pa->solicitation_number }}-dollars.svg" Xdownload="{{ $pa->title }}-{{ $pa->solicitation_number }}-dollars.svg" href="/admin/map/{{$pa->id}}.svg?type=dollars&v={{$version}}">
		<img src="/admin/map/{{ $pa->id }}&type=dollars&v={{$version}}">
		<span class="img-label">Map - Dollar Amounts
		<a href="/admin/map/{{ $pa->id }}?type=dollars&v={{$version}}" class="btn btn-xs btn-default" download="{{ $pa->title }}-{{ $pa->solicitation_number }}-dollars.svg"><i class="fa fa-save"></i> .svg</a>
<!-- 		<a href="/admin/map/{{$pa->id}}.svg?type=dollars&v={{$version}}&jpg=1&jpg_size=lg" class="btn btn-xs btn-default" download="{{ $pa->title }}-{{ $pa->solicitation_number }}-dollars.jpg"><i class="fa fa-save"></i> .jpg</a> -->
		
		</span>
	</div>
	    </div>
    </div>
    
    
@endforeach


@endsection
