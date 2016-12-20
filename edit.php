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

<a href="products.php">PRODUCTS</a> > <a class="postlink" href="product_view.php?product_id=<?php echo $product_id;?>" id="bread_name">NAME</a> > EDIT<br><br>
<div id="product_name_whole"><div id="product_name"></div> | EDIT</div><br><br>

<ul class="nav nav-list">
    <li class="divider"></li>
</ul>

<h3 style="display:inline-block;">INVENTORY</h3> &nbsp;|&nbsp; <a href="add_part.php?product_id=<?php echo $product_id;?>" style="font-size:18px;">ADD PART</a>

<div id="parts_list"></div>
<button onclick="adjust_inventory();">ADJUST INVENTORY</button><br><br>

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
        <td>MINIMUM FUNDS</td>
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

<button onclick="adjust_funds();">ADJUST FUNDS</button>

<script>
	window["profit_per"] = 0;
	window["parts_list"] = [];
	fetch_info();

	function adjust_inventory(){
		var order_info = "";
		for(x in window["parts_list"]){
			var ID = window["parts_list"][x];
			var onhand = $("#"+ID+"_onhand").val();
			console.log("#"+ID+"_onhand");
			order_info += ID+"="+onhand+"&";
		}
		console.log(order_info);

		$.getJSON( "http://<?php echo $server_ip;?>:8080/<?php echo $product_nick;?>/adjust_stock/"+order_info.substring(0, order_info.length - 1), function(data){
			if(data["status"] == "success"){
                finish("product_view.php","Inventory was adjusted.","alert-success");
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

        $.getJSON( "http://<?php echo $server_ip;?>:8080/<?php echo $product_nick;?>/adjust_funds/"+order_info, function(data){
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
					render_product(product_info);
				}
			}
		});
	}

	function render_product(info){
		$("#product_name").html(info["name"]);
        $("#bread_name").html(info["name"].toUpperCase());

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
		part += "</tr>";

		for(x in info["parts"]){
			var ID = info["parts"][x]["ID"];
			window["parts_list"].push(ID);

			part += "<tr>";
			part += 	"<td id='"+ID+"_name'>";
			part += 		info["parts"][x]["name"];
			part += 	"</td>";
			part += 	"<td>";
			part += 		"<input type='textbox' class='updater' id='"+ID+"_onhand' value='"+info["parts"][x]["onhand"]+"' />";
			part += 	"</td>";
			part += "</tr>";
		}
		part += "</table>";
		$("#parts_list").html(part);

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
