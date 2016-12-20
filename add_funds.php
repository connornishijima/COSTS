<?php
	$active = "products";
	include("header.php");

    $server_ip = $_SERVER['SERVER_ADDR'];
    $product_id = $_GET["product_id"];
    $product_nick = "null";
    $data = file_get_contents("product_index.lst");
    $data = explode("\n",$data);
    foreach($data as $line){
        $line = explode("%|%",$line);
        $nick = $line[0];
        $pid = $line[1];
        if($pid == $product_id){
            $product_nick = $nick;
        }
    }

?>

<a href="products.php">PRODUCTS</a> > <a class="postlink" href="product_view.php?product_id=<?php echo $product_id;?>" id="bread_name">NAME</a> > FUNDS<br><br>
<div id="product_name_whole"><div id="product_name"></div> | FUNDS</div><br><br>

<div class="row">
	<div class="span12" style="text-align:center;">
		<input type="textbox" id="units_in_sale" placeholder="Amount ($)"/><br><br>
		<input type="textbox" id="reason" placeholder='Reason (i.e. "Purchased Tools")'/><br><br>
		<button onclick="add_funds(true);">ADD INCOME</button>
		<button onclick="add_funds(false);">ADD EXPENDITURE</button>
	</div>
</div>
<br>
<div class="row">
	<div class="span4 glance">
		Current: <div id="product_current" class="glance_value"></div><br>
	</div>
	<div class="span4 glance">
		Minimum: <div id="product_minimum" class="glance_value"></div><br>
	</div>
	<div class="span4 glance">
		Leisure: <div id="product_leisure" class="glance_value"></div><br>
	</div>
</div>

<script>
	window["asking"] = 0;
	fetch_info();

	function add_funds(adding){
		if(adding == true){
			var funds = parseFloat($("#units_in_sale").val());
		}
		else if(adding == false){
			var funds = parseFloat($("#units_in_sale").val())*-1;
		}
		var reason = $("#reason").val();
		var sale_info = "funds="+funds+"&reason="+reason;
		$.getJSON( "http://<?php echo $server_ip;?>:8080/<?php echo $product_nick;?>/add_funds/"+sale_info, function(data){
			if(data["status"] == "success"){
				if(adding == true){
        	        finish("product_view.php","Income added successfully!","alert-success");
				}
				else if(adding == false){
        	        finish("product_view.php","Expenditure added successfully!","alert-success");
				}
            }
        });
	}

	function fetch_info(){
		$.getJSON( "http://<?php echo $server_ip;?>:8080/products", function( data ) {
			for(x in data["products"]){
				var product_info = data["products"][x];
				if(product_info["ID"] == <?php echo json_encode($product_id);?>){
					render_product(product_info);
				}
			}
		});
	}

	function render_product(info){
		$("#product_name").html(info["name"]);
        $("#bread_name").html(info["name"].toUpperCase());

		$("#product_current").html(price_short(info["funds"]["current"]));
		$("#product_minimum").html(price_short(info["funds"]["bottom"]));

		var leisure = parseFloat(info["funds"]["current"]) - parseFloat(info["funds"]["bottom"]);
		if(leisure < 0){
			leisure = 0;
		}

		$("#product_leisure").html(price_short(leisure));

		console.log(info);
	}

	function price_short(price){
		price += 0.0000000001;
		return "$"+price.toFixed(2);
	}

	function price_long(price){
		price += 0.0000000001;
		return "$"+price.toFixed(5);
	}

	function finish(link,m,t){
        $.redirectPost(link, {product_id: "<?php echo $product_id;?>", message: m, message_type: t});
    }

</script>

<?php include("footer.php");?>
