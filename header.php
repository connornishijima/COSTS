<?php
	$message = "NULL";
	$message_type = "NULL";
	if(isset($_GET["message"])){
		$message = $_GET["message"];
		$message_type = $_GET["message_type"];
	}
?>

<!DOCTYPE html>
	<head>
		<meta charset="utf-8"> 
		<link rel="stylesheet" href="bootstrap/css/bootstrap.css"  type="text/css"/>
		<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>

		<script src="bootstrap/js/bootstrap.js"></script>

		<title>COSTS</title>

		<style>
		#investment_big{
			width: 40%;
    		display: block;
    		float: left;
    		font-size: 24px;
    		text-align: right;
    		padding-right: 10px;
		}
		#return_big{
			width: 40%;
    		display: block;
    		float: right;
    		font-size: 24px;
    		text-align: left;
    		padding-left: 10px;
		}
		.pad_green{
			display: inline-block;
    		background-color: #89d289;
    		padding: 20px;
    		border-radius: 5px;
		}
		.pad_yellow{
			display: inline-block;
    		background-color: #d2c289;
    		padding: 20px;
    		border-radius: 5px;
		}
		.updater{
			width:100px;
		}
		#product_name{
			display: inline-block;
			font-size: 32px;
			font-weight: bold;
			color:#777777;
		}
		#product_name_whole{
			display: inline-block;
			font-size: 32px;
			font-weight: bold;
			color:#353535;
		}
		.glance{
			text-align: center;
		}
		.glance_value{
			font-size: 24px;
			margin-top: 5px;
		}
		.table_head{
			font-weight: bold;
		}
		#content{
			display:none;
		}
		#load{
			display:block;
		}
		#product_page{
			text-align:center;
		}
		#message{
			display:none;
		}
		.highlight_bad{
			color:#f00;
		}
		.highlight_good{
			color:#090;
		}
		.product_list_title{
			font-size:24px;
			cursor:pointer;
		}
		.info_key{
			font-weight: bold;
			font-size: 16px;
			margin-bottom: 10px;
		}
		.info_value{
			width: 100%;
			margin-bottom: 20px;
		}
		</style>

		<script>
			// jquery extend function
			$.extend(
			{
    			redirectPost: function(location, args)
    			{
        			var form = '';
        			$.each( args, function( key, value ) {
            			form += '<input type="hidden" name="'+key+'" value="'+value+'">';
        			});
        			$('<form action="'+location+'" method="GET">'+form+'</form>').appendTo('body').submit();
    			}
			});
		</script>
	</head>
	<body>
		<div class="container">
			<a href="index.php"><h1>COSTS</h1></a>

			<div class="navbar">
				<div class="navbar-inner">
					<div class="container">
						<ul class="nav">
							<li id="nav_home"><a href="index.php">Home</a></li>
							<li id="nav_products"><a href="products.php">Products</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="alert" id="message"><?php echo $message;?></div>

			<div id="load">LOADING...</div>
			<div id="content">
