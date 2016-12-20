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

<a href="products.php">PRODUCTS</a> > <a class="postlink" href="product_view.php?product_id=<?php echo $product_id;?>" id="bread_name">NAME</a> > ADJUST FUNDS<br><br>
<div id="product_name_whole"><div id="product_name"></div> | ADJUST FUNDS</div><br><br>

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
</table>

<button onclick="adjust_funds();">ADJUST FUNDS</button>

<script>
	fetch_info();
	function adjust_funds(){

		var current = $("#current").val();
		var bottom = $("#bottom").val();
		var invested = $("#invested").val();
		var earned = $("#earned").val();
		var units_sold = $("#units_sold").val();

		var order_info = "current="+current+"&bottom="+bottom+"&invested="+invested+"&earned="+earned+"&units_sold="+units_sold;
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
