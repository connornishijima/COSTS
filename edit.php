<?php
	$active = "products";
	include("header.php");

    $server_ip = $_SERVER['SERVER_ADDR'];
    $product_id = $_GET["product_id"];
?>

<a href="products.php">PRODUCTS</a> > <a class="postlink" href="product_view.php?product_id=<?php echo $product_id;?>" id="bread_name">NAME</a> > EDIT<br><br>
<div id="product_name_whole"><div id="product_name"></div> | EDIT</div><br><br>

<ul class="nav nav-list">
    <li class="divider"></li>
</ul>

<h3 style="display:inline-block;">PRODUCT INFORMATION</h3>
<div class="row">
	<div class="span6">
		<div class="info_key">
			Name
		</div>
		<input id="p_name" type="textbox" class="info_value"></input>
	</div>
	<div class="span6">
		<div class="info_key">
			Description
		</div>
		<input id="p_desc" type="textbox" class="info_value"></input>
	</div>
</div>
<div class="row">
	<div class="span6">
		<div class="info_key">
			Selling Price ($)
		</div>
		<input id="p_asking_price" type="textbox" class="info_value"></input>
	</div>
	<div class="span6">
		<div class="info_key">
			Minimum Units
		</div>
		<input id="p_minimum_units" type="textbox" class="info_value"></input>
	</div>
</div>
<button onclick="adjust_information();">UPDATE INFORMATION</button><br><br>

<ul class="nav nav-list">
    <li class="divider"></li>
</ul>

<h3 style="display:inline-block;">INVENTORY</h3> &nbsp;|&nbsp; <a href="add_part.php?product_id=<?php echo $product_id;?>" style="font-size:18px;">ADD PART</a>

<div id="parts_list"></div>
<button onclick="adjust_inventory();">UPDATE INVENTORY</button><br><br>

<ul class="nav nav-list">
    <li class="divider"></li>
</ul>

<h3>FUNDS</h3>

<table class="table">
    <tr>
        <td>CURRENT FUNDS</td>
        <td><input type="textbox" id="current" /></td>
    </tr>
    <tr>
		<td>MINIMUM FUNDS <div id="min_recommend" style="display:inline-block;">($586.43 recommended)</div></td>
        <td><input type="textbox" id="bottom" /></td>
    </tr>
    <tr>
        <td>INVESTED:</td>
        <td><input type="textbox" id="invested" /></td>
    <tr>
        <td>EARNED:</td>
        <td><input type="textbox" id="earned" /></td>
    </tr>
    <tr>
        <td>UNITS SOLD:</td>
        <td><input type="textbox" id="units_sold" /></td>
    </tr>
    <tr>
        <td>UNITS ASSEMBLED:</td>
        <td><input type="textbox" id="units_assembled" /></td>
    </tr>
</table>

<button onclick="adjust_funds();">UPDATE FUNDS</button>

<script>
	window["product_nick"] = "";
	window["profit_per"] = 0;
	window["parts_list"] = [];
	fetch_info();

	function adjust_information(){
		var product_info = "";
		var keys = ["name","desc","asking_price","minimum_units"];
		for(x in keys){
			product_info += keys[x];
			product_info += "=";
			product_info += encodeURIComponent($("#p_"+keys[x]).val());
			product_info += "&";

		}
		console.log(product_info);

		$.getJSON( "http://<?php echo $server_ip;?>:8080/"+window["product_nick"]+"/adjust_information/"+product_info.substring(0, product_info.length - 1), function(data){
			if(data["status"] == "success"){
                finish("edit.php","Product Info updated.","alert-success");
            }
		});
	}

	function adjust_inventory(){
		var order_info = "";
		for(x in window["parts_list"]){
			var ID = window["parts_list"][x];
			var onhand = $("#"+ID+"_onhand").val();
			var order_cost = $("#"+ID+"_order_cost").val();
			var order_quantity = $("#"+ID+"_order_quantity").val();
			var shipping_cost = $("#"+ID+"_shipping_cost").val();
			var needed_per = $("#"+ID+"_needed_per").val();

			order_info += ID+"+onhand="+onhand+"&";
			order_info += ID+"+order_cost="+order_cost+"&";
			order_info += ID+"+order_quantity="+order_quantity+"&";
			order_info += ID+"+shipping_cost="+shipping_cost+"&";
			order_info += ID+"+needed_per="+needed_per+"&";
		}
		console.log(order_info);

		$.getJSON( "http://<?php echo $server_ip;?>:8080/"+window["product_nick"]+"/adjust_stock/"+order_info.substring(0, order_info.length - 1), function(data){
			if(data["status"] == "success"){
                finish("product_view.php","Inventory updated.","alert-success");
            }
		});
	}

	function adjust_funds(){
        var current = $("#current").val();
        var bottom = $("#bottom").val();
        var invested = $("#invested").val();
        var earned = $("#earned").val();
        var units_sold = $("#units_sold").val();
        var units_assembled = $("#units_assembled").val();

        var order_info = "current="+current+"&bottom="+bottom+"&invested="+invested+"&earned="+earned+"&units_sold="+units_sold+"&units_assembled="+units_assembled;
        console.log(order_info);

        $.getJSON( "http://<?php echo $server_ip;?>:8080/"+window["product_nick"]+"/adjust_funds/"+order_info, function(data){
            if(data["status"] == "success"){
                finish("product_view.php","Funds were adjusted.","alert-success");
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

		$("#p_name").val(info["name"]);
		$("#p_desc").val(info["desc"]);
		$("#p_asking_price").val(info["asking_price"]);
		$("#p_minimum_units").val(info["minimum_units"]);

        $("#current").val(info["funds"]["current"].toFixed(2));
        $("#invested").val(info["funds"]["invested"].toFixed(2));
        $("#earned").val(info["funds"]["earned"].toFixed(2));
        $("#bottom").val(info["funds"]["bottom"].toFixed(2));
        $("#units_sold").val(info["funds"]["units_sold"]);
        $("#units_assembled").val(info["assembled"]);

		var part = "<table class='table'>";
		part += "<tr>";
			part += "<td class='table_head'>PART NAME</td>";
			part += "<td class='table_head'>ON HAND</td>";
			part += "<td class='table_head'>ORDER COST</td>";
			part += "<td class='table_head'>ORDER QUANTITY</td>";
			part += "<td class='table_head'>SHIPPING COST</td>";
			part += "<td class='table_head'>NEEDED PER</td>";
			part += "<td class='table_head'>ACTION</td>";
		part += "</tr>";

		var min_total = 0;

		for(x in info["parts"]){
			var ID = info["parts"][x]["ID"];
			window["parts_list"].push(ID);
			var part_cost = parseFloat(info["parts"][x]["order_cost"]) + parseFloat(info["parts"][x]["shipping_cost"])
			min_total += part_cost;

			part += "<tr>";
			part += 	"<td id='"+ID+"_name'>";
			part += 		info["parts"][x]["name"];
			part += 	"</td>";
			part += 	"<td>";
			part += 		"<input type='textbox' class='updater' id='"+ID+"_onhand' value='"+info["parts"][x]["onhand"]+"' />";
			part += 	"</td>";
			part += 	"<td>";
			part += 		"<input type='textbox' class='updater' id='"+ID+"_order_cost' value='"+info["parts"][x]["order_cost"]+"' />";
			part += 	"</td>";
			part += 	"<td>";
			part += 		"<input type='textbox' class='updater' id='"+ID+"_order_quantity' value='"+info["parts"][x]["order_quantity"]+"' />";
			part += 	"</td>";
			part += 	"<td>";
			part += 		"<input type='textbox' class='updater' id='"+ID+"_shipping_cost' value='"+info["parts"][x]["shipping_cost"]+"' />";
			part += 	"</td>";
			part += 	"<td>";
			part += 		"<input type='textbox' class='updater' id='"+ID+"_needed_per' value='"+info["parts"][x]["needed_per"]+"' />";
			part += 	"</td>";
			part += 	"<td>";
			part += 		"<div onclick=remove_part(";
			part +=         	"'" + ID + "',";
			part +=         	"'" + info["parts"][x]["name"] + "'";
			part +=			"); class='highlight_bad'>DELETE</div>";
			part += 	"</td>";
			part += "</tr>";
		}
		part += "</table>";
		$("#parts_list").html(part);

		min_total *= 1.15;
		$("#min_recommend").html("(Recommended: "+price_short(min_total)+")");

		console.log(info);
	}

	function remove_part(part_id,part_name){
		if(confirm("Do you really want to delete the part '"+part_name+"'?") == true){
			$.getJSON( "http://<?php echo $server_ip;?>:8080/"+window["product_nick"]+"/remove_part/"+part_id, function(data){
        	    if(data["status"] == "success"){
        	        finish("edit.php","Part removed successfully.","alert-success");
        	    }
        	});
		}
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
