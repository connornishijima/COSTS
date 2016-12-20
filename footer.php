			</div>
		</div>
		<script>
	    	$('.postlink').click(function() {
	        	var form= document.createElement('form');
	        	form.method= 'get';
	        	form.action= this.protocol+'//'+this.hostname+this.pathname;
	        	$.each(this.search.slice(1).split(/[&;]/g), function() {
	            	var ix= this.indexOf('=');
	            	if (ix===-1) return;
	            	var input= document.createElement('input');
	            	input.type= 'hidden';
	            	input.name= decodeURIComponent(this.slice(0, ix));
	            	input.value= decodeURIComponent(this.slice(ix+1));
	            	form.appendChild(input);
	        	});
	        	document.body.appendChild(form);
	        	form.submit();
	        	return false;
	    	});
	
			// SELECT CURRENT PAGE IN NAV
			var active_page = <?php echo json_encode($active);?>;
			$("#nav_"+active_page).addClass("active");
	
			$("#load").hide();
			$("#content").show();
	
			var message = <?php echo json_encode($message);?>;
			var message_type = <?php echo json_encode($message_type);?>;
			if(message != "NULL"){
				$("#message").addClass(message_type);
				$("#message").html(message);
				$("#message").show();
			}
		</script>
	</body>
</html>
