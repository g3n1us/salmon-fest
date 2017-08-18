@extends('welcome')

@section('main')
<?php $version = date('Ymd'); ?>
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
</style>



    <div class="jmb_slider margin-bottom40">
	    @foreach($pas as $pa)
	    <p>
	    <img src="/admin/map/{{ $pa->id }}">
	    </p>
	    <p>
	    <img src="/admin/map/{{ $pa->id }}?type=dollars">
	    </p>
	    @endforeach
	    
    </div>
    


@endsection
