<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="/css/combines.css" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
                {{ $anger > 4 ? 'background-color: #AF2F2F; color: #FFF; ' : '' }}
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: left;
                display: inline-block;
                max-width: 800px
            }

            .title {
                font-size: 42px;
                margin-bottom: 40px;
            }
            
            .talking{
	            margin-top: 40px;
	            position: relative;
	            display: inline-block;
            }
            
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">{!! nl2br("This site is not intended for public use. 
	                During certain times of the day, and for other mysterious reasons, you will see this message in lieu of the fine craftsmanship you came to see. 
	                Sorry for the inconvenience. 
	                P.S. If you email me I can give you the keys. ") !!} <span class="talking">{{$anger_message}}</span></div>
            </div>
        </div>
        {{$anger}}
    </body>
</html>
