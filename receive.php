<?php
	$active = "products";
	include("header.php");

    $server_ip = $_SERVER['SERVER_ADDR'];
    $product_id = $_GET["product_id"];
?>

<a href="products.php">PRODUCTS</a> > <a class="postlink" href="product_view.php?product_id=<?php echo $product_id;?>" id="bread_name">NAME</a> > RECEIVING<br><br>
<div id="product_name_whole"><div id="product_name"></div> | RECEIVING</div><br><br>

<div id="parts_list"></div><br>
<button onclick="receive_order();">RECEIVE ORDER</button>

<script>
	window["product_nick"] = "";
	window["profit_per"] = 0;
	window["parts_list"] = [];
	window["current_funds"] = 0;

	fetch_info();

	$(document ).on( "keyup", ".updater", function(){
		calc_prices();
	});
	$(document ).on( "click", ".updater", function(){
		calc_prices();
	});

	function calc_prices(){
		var total = 0;
		var minimum = 99999999;
		for(x in window["parts_list"]){
			console.log(ID);
			var ID = window["parts_list"][x];
			var subtotal = 0;
			var enabled = $("#"+ID+"_enabled").is(":checked")

			var onhand = parseInt($("#"+ID+"_onhand").html());
			var needed_per = parseInt($("#"+ID+"_needed_per").html());
			var order_quantity = parseInt($("#"+ID+"_order_quantity").val());

			var makes = 0;

			if(enabled){
				var order_cost = parseFloat($("#"+ID+"_order_cost").val());
				var shipping_cost = parseFloat($("#"+ID+"_shipping_cost").val());
				var subtotal = order_cost+shipping_cost;
				total+=subtotal;
				makes = parseInt((order_quantity/needed_per)+(onhand/needed_per));
			}
			else{
				makes = parseInt(onhand/needed_per);
			}

			$("#"+ID+"_makes").html( makes );
			if(makes < minimum){
				minimum = makes;
			}
		}

		$("#order_total").html(price_short(total));
		$("#after_funds").html(price_short(window["current_funds"]-total));
		$("#units_total").html(minimum);
	}

	function receive_order(){
		var order_info = "";
		for(x in window["parts_list"]){
			var ID = window["parts_list"][x];
			var received = $("#"+ID+"_received").val();
			order_info += ID+"="+received+"&";
		}
		console.log(order_info);

		$.getJSON( "http://<?php echo $server_ip;?>:8080/"+window["product_nick"]+"/receive_order/"+order_info.substring(0, order_info.length - 1), function(data){
			if(data["status"] == "success"){
				finish("product_view.php","Order received successfully!","alert-success");
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
		$("#product_name").html(info["name"]);
		$("#bread_name").html(info["name"].toUpperCase());

		window["current_funds"] = info["funds"]["current"];
		$("#current_funds").html(price_short(window["current_funds"]));
		$("#after_funds").html(price_short(window["current_funds"]));

		var part = "<table class='table'>";
		part += "<tr>";
			part += "<td class='table_head'>PART NAME</td>";
			part += "<td class='table_head'>INCOMING</td>";
			part += "<td class='table_head'>ON HAND</td>";
			part += "<td class='table_head'>RECEIVED</td>";
		part += "</tr>";

		for(x in info["parts"]){
			var ID = info["parts"][x]["ID"];
			if(info["parts"][x]["incoming"] > 0){
				window["parts_list"].push(ID);

				part += "<tr>";
				part += 	"<td id='"+ID+"_name'>";
				part += 		info["parts"][x]["name"];
				part += 	"</td>";
				part += 	"<td id='"+ID+"_incoming'>";
				part += 		info["parts"][x]["incoming"];
				part += 	"</td>";
				part += 	"<td id='"+ID+"_onhand'>";
				part += 		info["parts"][x]["onhand"];
				part += 	"</td>";
				part += 	"<td>";
				part +=			"<input type='textbox' id='"+ID+"_received' value='0'/>";
				part += 	"</td>";
				part += "</tr>";
			}
		}
		part += "</table>";
		$("#parts_list").html(part);

		calc_prices();
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
