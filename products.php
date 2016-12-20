<?php
	$active = "products";
	include("header.php");

	$server_ip = $_SERVER['SERVER_ADDR'];
?>

<div id="product_list">
</div>
<a href="add_product.php">ADD PRODUCT</a>
<script>
	function load_product(id){
		var form = document.createElement("form");
		form.method = 'get';
		form.action = 'product_view.php';
		var input = document.createElement('input');
		input.type = "text";
		input.name = "product_id";
		input.value = id;
		form.appendChild(input);
		form.submit();
    }

	get_products();
	function get_products(){
		$.getJSON( "http://<?php echo $server_ip;?>:8080/products", function( data ) {
			for(x in data["products"]){
				var product_info = data["products"][x];
				render_product(product_info);
			}
		});
	}

	function render_product(info){
		var html = $("#product_list").html();
		html+= "<a onclick='load_product("+'"'+info["ID"]+'"'+");'>";
		html+='<div class="product_list_title">'+info["name"]+'</div>';
		html+='Units Sold: '+info["funds"]["units_sold"]+" &nbsp; ";
		html+='Invested: '+price_short(info["funds"]["invested"])+" &nbsp; ";
		html+='Earned: '+price_short(info["funds"]["earned"])+" &nbsp; ";
		html+='Net Profit: '+price_short(info["funds"]["earned"]-info["funds"]["invested"]);
		html+="</a>";
		html+='<ul class="nav nav-list">';
	    html+=	'<li class="divider"></li>';
	    html+='</ul>';
		$("#product_list").html(html);
	}

	function price_short(price){
        price += 0.0000000001;
        return "$"+numberWithCommas(price.toFixed(2));
    }

	function numberWithCommas(x) {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }

</script>

<?php include("footer.php");?>
