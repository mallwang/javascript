<?php $title="Schankimperium"; include 'sessionpruefung.php';?>

<div class="hero-unit">
  	<h2>Herzlich willkommen bei Schankimperium!</h2>
  	<p>Hier entsteht demn&auml;chst eine Internetpr&auml;senz.</p>	
</div>
<script src="js/jvectormap-1.2.2.js"></script>
<script src="js/jvectormap-world.js"></script>
<script>
    $(function(){
    	$('#world-map').vectorMap();
    });
</script>
<div align="center">
	<div id="world-map" style="width: 800px; height: 400px;"></div>
</div>



<?php include 'footer.php';?>