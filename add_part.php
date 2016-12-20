<?php
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<a href="product_view.php?product_id=<?php echo $product_id;?>">GO BACK</a><br>

Name: <input type="textbox" id="name" /><br>
Description: <input type="textbox" id="desc" /><br>
<br>
Quantity per product: <input type="textbox" id="needed_per" /><br>
Order quantity: <input type="textbox" id="order_quantity" /><br>
Order cost: <input type="textbox" id="order_cost" /><br>
Shipping cost: <input type="textbox" id="shipping_cost" /><br>
<input type="hidden" id="onhand" value="0">
<button onclick="addPart();">ADD PART</button>

<script>
	function addPart(){
		var param_list = ["name","desc","needed_per","order_quantity","order_cost","shipping_cost","onhand"];
		var params = ""
		var info_provided = true;
		for(x in param_list){
			params = params+param_list[x]+"="+encodeURI($("#"+param_list[x]).val())+"&";
			if($("#"+param_list[x]).val() == ""){
				info_provided = false;
			}
		}
		if(info_provided == true){
			$.getJSON( "http://<?php echo $server_ip;?>/<?php echo $product_nick;?>/add_part/"+params.substring(0, params.length - 1), function( data ) {
				alert(data["status"]);
			});
		}
		else{
			alert("MISSING INFO");
		}
	}
</script>
