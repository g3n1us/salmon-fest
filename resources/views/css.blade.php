<style >
	        body{
		        background-color: #e2e2e2;
				/* background-image: url('/bg_images/{{ rand(1, 4) }}.jpg'); */
		        background-size: cover;
		        background-repeat: no-repeat;
		        background-attachment: fixed;
	        }
	        
	        .col-count-2 {
		        -webkit-columns: 2 150px;
		        -moz-columns: 2 150px;
		        position: relative;
	        }
	        
	        .pa-list{
		        position: relative;
	        }

	        .container-fluid, .container{
		        background-color: rgba(255, 255, 255, 0.85);
	        }
	        
	        @media(min-width: 700px){
		        .container-fluid, .container{
			        max-width: 95%;
		        }		        
	        }
	        
	        .label-list label{
		        margin-right: 5px;
		        margin-left: 5px;
	        }
	        .label-list label:last-child{
		        margin-right:0;
	        }
        
        .disabled:after{
	        content: "Option not available with Combined Report option";
	        color: #9b0808;
	        padding: 0px;
	        border: 1px solid #9b0808;
	        position: absolute;
	        top: 0;
	        left: 0;
	        right: 0;
	        bottom: 0;
	        width: 100%;
	        height: 30px;
	        text-align: center;
	        margin: auto;
	        background-color: #fff;
	        line-height: 30px;
        }
	        
		/*! CARD */
		.projlist li a, .cards > .card, .cards .card{
			border: solid 1px gray;
			display: inline-block;
			border-radius: 3px;
			box-shadow: 1px 1px 0px gray;
			position: relative;
			margin-bottom: 20px;
			transition: all .03s;
			margin-right: -2px;
			float: none;
			vertical-align: top;
		}		
				
		.card.padded{
			padding: 5px 10px;	
		}
		
		
		.cards{
			margin: 20px auto;
			vertical-align: middle;
		}
		
		
		/* Brown #714D0C */
		.card.standard{
			box-shadow: 1px 1px 0px #808080, 4px 4px 0px #714D0C;
		}
		
		.card.standard .h3.text-info{
			color: #714D0C;
		/* 	text-shadow: 1px 1px 0px #e5e5e5; */
		}
		
		/* Blue #060156 */
		.card.cards{
			box-shadow: 1px 1px 0px #808080, 4px 4px 0px #1c1686;
		}
		
		.card.cards .h3.text-info{
			color: #1c1686;
			
		}
		
		
		.card .close{
			display: none;
		}
		
		.card:hover .close{
			display: block;
		}
		
		.card fieldset.disabled{
			opacity: .3;
			-moz-opacity: .3;
			outline: red;
		}	      
		
		@media(max-width: 960px){
			.cards .card{
				width: 50%;
			}
		}  
		@media(max-width: 768px){
			.cards .card{
				display: block;
				width: 100%;
			}
		}  
		
		.embed-responsive-4by3 {
			padding-bottom: 200%;
		}		
	        
		@media(min-width: 480px){
			.embed-responsive-4by3 {
				padding-bottom: 120%;
			}					
		}
	        
		@media(min-width: 768px){
			.embed-responsive-4by3 {
				padding-bottom: 80%;
			}					
		}
	        
        .table-responsive{
	        -webkit-overflow-scrolling: touch;
        }
	
	
	
	
	
	
	
/* Prev at top above	 */
	
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
