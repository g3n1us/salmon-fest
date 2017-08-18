<!DOCTYPE html>
<html>
    <head>
        <title>ASMI Salmon Maps</title>
		<meta charset="utf-8">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">		
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        
        
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="/css/combined.css">
		<link rel="stylesheet" type="text/css" href="/css/paper.bootstrap.min.css" media="all">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="/js/js-xlsx-master/dist/xlsx.full.min.js"></script>
		<script type="text/javascript" src="/js/Blob.js-master/Blob.js"></script>
		<script type="text/javascript" src="/js/FileSaver.js-master/FileSaver.min.js"></script>
		<script type="text/javascript" src="/js/Export2Excel.js?v={{ rand() }}"></script>
				
@include('css');		
        
		
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12" style="margin-top:40px;">
	                @if(!Request::has('json') || Request::has('show_nav')) 
	                <p class="clearfix">
		                <a class="btn btn-default" href="/"><i class="fa fa-home"></i> Home</a> 
		                <a class="btn btn-default" href="/configure"><i class="fa fa-gears"></i> Configure</a> 
		                @if(Auth::check()) <a class="btn btn-danger pull-right" href="/auth/logout"><i class="fa fa-ban"></i> Log Out</a> @endif
	                </p> 
	                
	                @endif
	                @yield('main')
					
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
	                {{ \App\User::count() }}
                </div>
            </div>            
            
        </div>
		<script>
		
		$(document).on('click', '.spreaddl', function(){
			export_table_to_excel('table');
		});
		</script>    
        
		<!-- Latest compiled and minified JavaScript -->
		<script src="/js/combined.js"></script>
        <script>
// 	        $('.img-link').tooltip({container: 'body'});
$(document).on('click', '.img-link img', function(e){
	e.preventDefault();
	window.open($(this).parent().attr('href'), "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=10, left=10, width=1400, height=1200");
});
        </script>
    </body>
</html>




