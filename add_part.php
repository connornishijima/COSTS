<?php
	$active = "products";
	include("header.php");

    $server_ip = $_SERVER['SERVER_ADDR'];
    $product_id = $_GET["product_id"];
?>

<a href="products.php">PRODUCTS</a> > <a class="postlink" href="product_view.php?product_id=<?php echo $product_id;?>" id="bread_name">NAME</a> > ADD PART<br><br>
<div id="product_name_whole"><div id="product_name"></div> | ADD PART</div><br><br>

<div class="row">
    <div class="span6">
        <h4>Name</h4>
        <input type="textbox" id="name" style="width: 100%;" placeholder="What part is this?" />
    </div>
    <div class="span6">
        <h4>Description</h4>
        <input type="textbox" id="desc" style="width: 100%;" placeholder="Give a short description of the part" />
    </div>
</div>
<div class="row">
    <div class="span6">
        <h4>Order Cost ($)</h4>
        <input type="textbox" id="order_cost" style="width: 100%;" placeholder="How much does one order of this part cost? ($69.00)" />
    </div>
    <div class="span6">
        <h4>Order Quantity</h4>
        <input type="textbox" id="order_quantity" style="width: 100%;" placeholder="How many of this part comes in one order?" />
    </div>
</div>
<div class="row">
    <div class="span6">
        <h4>Shipping Cost ($)</h4>
        <input type="textbox" id="shipping_cost" style="width: 100%;" placeholder="How much is shipping for this order?" />
    </div>
    <div class="span6">
        <h4>Needed Per</h4>
        <input type="textbox" id="needed_per" style="width: 100%;" placeholder="How many of these are in a single finished product?" />
    </div>
</div>
<div class="row">
    <div class="span12">
        <h4>Order Link</h4>
        <input type="textbox" id="link" style="width: 100%;" placeholder="Where can you buy this product?" />
    </div>
</div>

<br>
<button onclick="add_part();">ADD PART</button>

<script>
	window["product_nick"] = "";
	fetch_info();

	function add_part(){
		var param_list = ["name","desc","needed_per","order_quantity","order_cost","shipping_cost","link"];
        var params = ""
        var info_provided = true;
        for(x in param_list){
            var param = param_list[x]+"="+encodeURIComponent($("#"+param_list[x]).val())+"&";
            params += param.replace("http://","").replace("https://","");
            if($("#"+param_list[x]).val() == ""){
                info_provided = false;
            }
        }
		console.log(params);
		$.getJSON( "http://<?php echo $server_ip;?>:8080/<?php echo $product_id;?>/add_part/"+params.substring(0, params.length - 1), function( data ) {
			if(data["status"] == "success"){
				finish("edit.php","Part added successfully!","alert-success");
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
    }


	function finish(link,m,t){
        $.redirectPost(link, {product_id: "<?php echo $product_id;?>", message: m, message_type: t});
    }

</script>

<?php include("footer.php");?>
