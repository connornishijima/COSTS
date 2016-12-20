<?php
	$active = "products";
	include("header.php");

    $server_ip = $_SERVER['SERVER_ADDR'];
    $product_id = $_GET["product_id"];
?>

<a href="products.php">PRODUCTS</a> > <a class="postlink" href="product_view.php?product_id=<?php echo $product_id;?>" id="bread_name">NAME</a> > ADD SALE<br><br>
<div id="product_name_whole"><div id="product_name"></div> | ADD SALE</div><br><br>

<div class="row">
	<div class="span12">
		<input type="textbox" id="units_in_sale" placeholder="Units in sale"/>
		<button onclick="add_sale();">ADD SALE</button>
	</div>
</div>
<br>
<div class="row">
	<div class="span4">
		Selling Price: <div id="product_asking_price" class="glance_value"></div><br>
	</div>
	<div class="span4">
		Cost Per Unit: <div id="product_unit_cost" class="glance_value"></div><br>
	</div>
	<div class="span4">
		Profit Per Unit: <div id="product_profit_per" class="glance_value"></div><br>
	</div>
</div>

<script>
	window["product_nick"] = "";
	window["asking"] = 0;
	fetch_info();

	function add_sale(){
		var units_sold = $("#units_in_sale").val();
		var profits = units_sold*window["asking"].toFixed(2);
		var sale_info = "profits="+profits+"&units="+units_sold;
		$.getJSON( "http://<?php echo $server_ip;?>:8080/"+window["product_nick"]+"/add_sale/"+sale_info, function(data){
			if(data["status"] == "success"){
                finish("product_view.php","Sale added successfully!","alert-success");
            }
        });

	}

	function fetch_info(){
		$.getJSON( "http://<?php echo $server_ip;?>:8080/products", function( data ) {
			for(x in data["products"]){
				var product_info = data["products"][x];
				if(product_info["ID"] == <?php echo json_encode($product_id);?>){
					window["product_nick"] = product_info["nick"];
					render_product(product_info);
				}
			}
		});
	}

	function render_product(info){
		var info_blocks = ["asking_price"];
		for(x in info_blocks){
			if(info_blocks[x] == "asking_price"){
				$("#product_"+info_blocks[x]).html(price_short(info[info_blocks[x]]));
			}
			else{
				$("#product_"+info_blocks[x]).html(info[info_blocks[x]]);
			}
		}

		$("#product_name").html(info["name"]);
        $("#bread_name").html(info["name"].toUpperCase());

		var product_cost = 0;

		for(x in info["parts"]){
			var part_name = info["parts"][x]["name"];
			var part_desc = info["parts"][x]["desc"];
			var part_ID = info["parts"][x]["ID"];
			var needed_per = info["parts"][x]["needed_per"];
			var order_cost = info["parts"][x]["order_cost"];
			var order_quantity = info["parts"][x]["order_quantity"];
			var shipping_cost = info["parts"][x]["shipping_cost"];

			var part_cost = (shipping_cost/order_quantity)+(order_cost/order_quantity)*needed_per;
			product_cost+=part_cost;
		}

		$("#product_unit_cost").html(price_short(product_cost));
		window["asking"] = info["asking_price"];
		profit = price_short(info["asking_price"]-product_cost);
		$("#product_profit_per").html(profit);

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
