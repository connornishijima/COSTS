<?php
	$active = "products";
	include("header.php");

    $server_ip = $_SERVER['SERVER_ADDR'];

	if(isset($_GET["product_id"])){
    	$product_id = $_GET["product_id"];
	}
	else{
    	$product_id = $_GET["product_id"];
	}

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

<div id="warnings">
</div>

<a href="products.php">PRODUCTS</a> > <a href="product_view.php?product_id=<?php echo $product_id;?>" id="bread_name">NAME</a><br><br>
<div id="product_page">
	<div id="product_name"></div>
	
	<div id="product_desc"></div><br>
	
	<a class="postlink" href="add_order.php?product_id=<?php echo $product_id;?>"><button type="button" class="btn">Order</button></a>
	<a class="postlink" href="receive.php?product_id=<?php echo $product_id;?>"><button type="button" class="btn btn">Receiving</button></a>
	<a class="postlink" href="add_assembly.php?product_id=<?php echo $product_id;?>"><button type="button" class="btn btn">Assembly</button></a>
	<a class="postlink" href="add_sale.php?product_id=<?php echo $product_id;?>"><button type="button" class="btn btn">Sale</button></a>
	<a class="postlink" href="add_funds.php?product_id=<?php echo $product_id;?>"><button type="button" class="btn btn">Income / Expenditure</button></a>
	<a class="postlink" href="edit.php?product_id=<?php echo $product_id;?>"><button type="button" class="btn">Edit</button></a>
	<br>
	<br>
	
	<ul class="nav nav-list">
  	<li class="divider"></li>
	</ul>
	
	<h3>UNITS</h3>
	<div class="row">
		<div class="span2 glance">
			Selling Price: <div id="product_asking_price" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			Cost Per Unit: <div id="product_unit_cost" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			Profit Per Unit: <div id="product_profit_per" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			Sold: <div id="product_units_sold" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			Waiting: <div id="product_units_available" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			Assembled: <div id="product_units_assembled" class="glance_value"></div><br>
		</div>
	</div>
	
	<ul class="nav nav-list">
  	<li class="divider"></li>
	</ul>
	
	<h3>FUNDS</h3>
	<div class="row">
		<div class="span2 glance">
			Current: <div id="product_current" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			Miminum: <div id="product_bottom" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			Leisure: <div id="product_leisure" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			Invested: <div id="product_invested" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			Gross Profit: <div id="product_earned" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			Net Profit: <div id="product_earned_net" class="glance_value"></div><br>
		</div>
	</div>
	
	<ul class="nav nav-list">
  	<li class="divider"></li>
	</ul>
	
	<h3>MILESTONES</h3>
	<div class="row">
		<div class="span2 glance">
			Profitable: <div id="milestone_tipping_point" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			10 Units: <div id="milestone_10" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			50 Units: <div id="milestone_50" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			100 Units: <div id="milestone_100" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			500 Units: <div id="milestone_500" class="glance_value"></div><br>
		</div>
		<div class="span2 glance">
			1000 Units: <div id="milestone_1000" class="glance_value"></div><br>
		</div>
	</div>

	<ul class="nav nav-list">
  	<li class="divider"></li>
	</ul>
	
	<h3>INVENTORY</h3>
	<div id="parts_list"></div>

	<ul class="nav nav-list">
  	<li class="divider"></li>
	</ul>
	

	<h3>TOP LINKS</h3>
	<div id="links_list">
		Top links are grabbed every 15 minutes - check back soon!
		<table class="table">
			<tr>
				<td class="table_head">TITLE</td>
				<td class="table_head">LINK</td>
				<td class="table_head">DISCOVERY TIME</td>
			</tr>
		</table>
	</div>

	<ul class="nav nav-list">
  	<li class="divider"></li>
	</ul>

	<h3>HISTORY</h3>
	<div id="history_list">
		<table class="table">
			<tr>
				<td class="table_head">DATE</td>
				<td class="table_head">TYPE</td>
				<td class="table_head">INFO</td>
				<td class="table_head">FUNDS</td>
			</tr>
			<tr>
				<td>11/18/2016 12:13 AM</td>
				<td>SALE</td>
				<td>Lixie Display - 6 UNITS</td>
				<td class="highlight_good">+240.00</td>
			</tr>
			<tr>
				<td>11/18/2016 12:13 AM</td>
				<td>RECEIVED</td>
				<td>Acrylic (Black) - 32 PIECES</td>
				<td></td>
			</tr>
			<tr>
				<td>11/18/2016 12:13 AM</td>
				<td>ORDER</td>
				<td>Acrylic (Black) - 60 PIECES</td>
				<td class="highlight_bad">-77.67</td>
			</tr>
			<tr>
				<td>11/18/2016 12:13 AM</td>
				<td>ORDER</td>
				<td>Acrylic (Clear) - 32 PIECES</td>
				<td class="highlight_bad">-276.80</td>
			</tr>
		</table>
	</div>
</div>

<script>
	fetch_products();
	fetch_links();
	fetch_history();

	function fetch_products(){
		$.getJSON( "http://<?php echo $server_ip;?>:8080/products", function( data ) {
			for(x in data["products"]){
				var product_info = data["products"][x];
				if(product_info["ID"] == <?php echo json_encode($product_id);?>){
					render_product(product_info);
				}
			}
		});
	}

	function fetch_history(){
        $.getJSON( "http://<?php echo $server_ip;?>:8080/<?php echo $product_id;?>/history", function( data ) {
			render_history(data);
        });
    }

	function fetch_links(){
        $.getJSON( "http://<?php echo $server_ip;?>:8080/<?php echo $product_id;?>/links", function( data ) {
			render_links(data);
        });
    }

	function render_links(links){
		var html = "";
		html += '<table class="table" style="table-layout: fixed; word-break: break-word;">';
		html += 	'<tr>';
		html +=		 	'<td class="table_head col-md-2">TITLE</td>';
		html +=		 	'<td class="table_head col-md-2" style="text-align:center;">LINK</td>';
		html +=		 	'<td class="table_head col-md-2" style="text-align:right;">DISCOVERY TIME</td>';
		html += 	'</tr>';

		links.reverse();

		for(x in links){
			var title = links[x]["title"];
			var link = links[x]["link"];
			var date = links[x]["date"];

			html += 	'<tr>';
			html +=		 	'<td class="col-md-2">'+title+'</td>';
			html +=		 	'<td class="col-md-2" style="text-align:center;"><a target="_blank" href="'+link+'">'+link+'</a></td>';
			html +=		 	'<td class="col-md-2" style="text-align:right;">'+date+'</td>';
			html += 	'</tr>';

		}

		html += '</table>';
		$("#links_list").html(html);
	}


	function render_history(history){
		var html = "";
		html += '<table class="table">';
		html += 	'<tr>';
		html += 	'<td class="table_head">DATE</td>';
		html += 	'<td class="table_head">TYPE</td>';
		html += 	'<td class="table_head">INFO</td>';
		html += 	'<td class="table_head">FUNDS</td>';
		html += 	'</tr>';

		for(x in history){
			var color = history[x]["color"];
			var date = history[x]["date"];
			var funds = history[x]["funds"];
			var info = history[x]["info"];
			var type = history[x]["type"];

			if(funds == 0.00){
				funds = "";
			}

			html += '<tr>';
			html += 	'<td>'+date+'</td>';
			html += 	'<td>'+type+'</td>';
			html += 	'<td>'+info+'</td>';
			html += 	'<td class="highlight_'+color+'">'+funds+'</td>';
			html += '</tr>';
		}
		$("#history_list").html(html);
	}

	function render_product(info){
		var info_blocks = ["name","desc","asking_price"];
		for(x in info_blocks){
			if(info_blocks[x] == "asking_price"){
				$("#product_"+info_blocks[x]).html(price_short(info[info_blocks[x]]));
			}
			else{
				$("#product_"+info_blocks[x]).html(info[info_blocks[x]]);
			}
		}

        $("#bread_name").html(info["name"].toUpperCase());

		var product_cost = 0;

		var html = "";
		html += '<table class="table">';
		html += 	'<tr>';
		html += 		'<td class="table_head">PART NAME</td>';
		html += 		'<td class="table_head">ON HAND</td>';
		html += 		'<td class="table_head">INCOMING</td>';
		html += 		'<td class="table_head">UNIT PRICE</td>';
		html += 		'<td class="table_head">ORDER SIZE</td>';
		html +=		'</tr>';

		var minimum = 99999999;
		for(x in info["parts"]){
			var part_name = info["parts"][x]["name"];
			var part_desc = info["parts"][x]["desc"];
			var part_ID = info["parts"][x]["ID"];
			var onhand = info["parts"][x]["onhand"];
			var incoming = info["parts"][x]["incoming"];
			var needed_per = info["parts"][x]["needed_per"];
			var order_cost = info["parts"][x]["order_cost"];
			var order_quantity = info["parts"][x]["order_quantity"];
			var shipping_cost = info["parts"][x]["shipping_cost"];

			html += "<tr>";
			html +=		"<td>" + part_name + "</td>";
			html +=		"<td>" + onhand + "</td>";
			html +=		"<td>" + incoming + "</td>";
			html +=		"<td>" + price_short(order_cost/order_quantity) + "</td>";
			html +=		"<td>" + order_quantity + "</td>";
			html += "</tr>";

			var part_cost = (shipping_cost/order_quantity)+(order_cost/order_quantity)*needed_per;
			product_cost+=part_cost;

			var makes = parseInt(onhand/needed_per);
			if(makes < minimum){
				minimum = makes;
			}

		}
		if(minimum == 99999999){ // If no units waiting...
			minimum = 0;
		}

		html +=	'</table>';
		$("#parts_list").html(html);

		$("#product_units_available").html(minimum);

		var assembled = info["assembled"];
		$("#product_units_assembled").html(assembled);

		$("#product_unit_cost").html(price_short(product_cost));
		$("#product_profit_per").html(price_short(info["asking_price"]-product_cost));

		var current = info["funds"]["current"];
		$("#product_current").html(price_short(current));

		var bottom = info["funds"]["bottom"];
		$("#product_bottom").html(price_short(bottom));

		var invested = info["funds"]["invested"];
		$("#product_invested").html(price_short(invested));

		var earned = info["funds"]["earned"];
		$("#product_earned").html(price_short(earned));

		var earned_net = earned-invested;
		$("#product_earned_net").html(price_short(earned_net));

		var units_sold = info["funds"]["units_sold"];
		$("#product_units_sold").html(units_sold);
		render_milestones(invested, earned, units_sold);

		var leisure = current-bottom;
		if(leisure < 0){
			leisure = 0;
		}
		$("#product_leisure").html(price_short(leisure));

		if(current < bottom){
			$("#product_current").addClass("highlight_bad");
			addWarning("Your current funds are lower than the minimum ("+price_short(bottom)+") for this product!");
		}

		var minimum_units = info["minimum_units"];
		if(assembled < minimum_units){
			$("#product_units_assembled").addClass("highlight_bad");
			addWarning("You currently have less assembled units than the minimum ("+minimum_units+") for this product!");
		}

		console.log(info);
	}

	function render_milestones(invested,earned,sold){
		// TIPPING POINT
		if(earned >= invested && invested != 0 && earned != 0){
			$("#milestone_tipping_point").html("YES");
			$("#milestone_tipping_point").addClass("highlight_good");
		}
		else{
			$("#milestone_tipping_point").html("NO");
			$("#milestone_tipping_point").addClass("highlight_bad");
		}

		// 10 UNITS
		if(sold >= 10){
			$("#milestone_10").html("YES");
			$("#milestone_10").addClass("highlight_good");
		}
		else{
			$("#milestone_10").html("NO");
			$("#milestone_10").addClass("highlight_bad");
		}

		// 50 UNITS
		if(sold >= 50){
			$("#milestone_50").html("YES");
			$("#milestone_50").addClass("highlight_good");
		}
		else{
			$("#milestone_50").html("NO");
			$("#milestone_50").addClass("highlight_bad");
		}

		// 100 UNITS
		if(sold >= 100){
			$("#milestone_100").html("YES");
			$("#milestone_100").addClass("highlight_good");
		}
		else{
			$("#milestone_100").html("NO");
			$("#milestone_100").addClass("highlight_bad");
		}

		// 500 UNITS
		if(sold >= 500){
			$("#milestone_500").html("YES");
			$("#milestone_500").addClass("highlight_good");
		}
		else{
			$("#milestone_500").html("NO");
			$("#milestone_500").addClass("highlight_bad");
		}

		// 1000 UNITS
		if(sold >= 1000){
			$("#milestone_1000").html("YES");
			$("#milestone_1000").addClass("highlight_good");
		}
		else{
			$("#milestone_1000").html("NO");
			$("#milestone_1000").addClass("highlight_bad");
		}
	}

	function price_short(price){
		price += 0.0000000001;
		return "$"+numberWithCommas(price.toFixed(2));
	}

	function price_long(price){
		price += 0.0000000001;
		return "$"+numberWithCommas(price.toFixed(5));
	}

	function numberWithCommas(x) {
    	var parts = x.toString().split(".");
    	parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    	return parts.join(".");
	}

	function addWarning(warning){
		var html = $("#warnings").html();
		html += "<div class='alert alert-error'>";
		html += warning;
		html += "</div>"
		$("#warnings").html(html);
	}

	</script>

<?php include("footer.php");?>
