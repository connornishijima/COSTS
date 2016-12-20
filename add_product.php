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

<a href="products.php">PRODUCTS</a> > <a class="postlink" href="add_product.php" id="bread_name"> ADD PRODUCT</a><br><br>
<div id="product_name_whole">ADD PRODUCT</div><br><br>

<div class="row">
	<div class="span4">
		<h4>Name</h4>
		<input type="textbox" id="p_name" style="width: 100%;" placeholder="What is your product's name?" />
	</div>
	<div class="span4">
		<h4>Description</h4>
		<input type="textbox" id="p_desc" style="width: 100%;" placeholder="Give a short description of the product" />
	</div>
	<div class="span4">
		<h4>Asking Price ($)</h4>
		<input type="textbox" id="p_price" style="width: 100%;" placeholder="59.99" />
	</div>
</div>

<div class="row">
	<div class="span6">
		<h4>Current Funds</h4>
		<input type="textbox" id="p_funds" style="width: 100%;" placeholder="How much money is ready to go?" />
	</div>
	<div class="span6">
		<h4>Minimum Units</h4>
		<input type="textbox" id="p_units" style="width: 100%;" placeholder="Minimum assembled  units at any time" />
	</div>
</div>
<br>
<div class="row">
	<div class="span12">
		<button onclick="add_product();">ADD PRODUCT</button>
	</div>
</div>

<script>
	function add_product(){
		var name = $("#p_name").val();
		var desc = $("#p_desc").val();
		var price = $("#p_price").val();
		var funds = $("#p_funds").val();
		var units = $("#p_units").val();

		var product_info = "";
		product_info += "name=";
		product_info += encodeURI(name);
		product_info += "&desc=";
		product_info += encodeURI(desc);
		product_info += "&price=";
		product_info += encodeURI(price);
		product_info += "&funds=";
		product_info += encodeURI(funds);
		product_info += "&units=";
		product_info += encodeURI(units);

		console.log(product_info);

		$.getJSON( "http://<?php echo $server_ip;?>:8080/add_product/"+product_info, function(data){
			if(data["status"] == "success"){
                		finish("products.php","Product added successfully!","alert-success");
			}
		});
	}

	function finish(link,m,t){
        	$.redirectPost(link, {product_id: "<?php echo $product_id;?>", message: m, message_type: t});
	}

	function guid() {
	  function s4() {
	    return Math.floor((1 + Math.random()) * 0x10000)
	      .toString(16)
	      .substring(1);
	  }
	  return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
	    s4() + '-' + s4() + s4() + s4();
	}

</script>

<?php include("footer.php");?>
