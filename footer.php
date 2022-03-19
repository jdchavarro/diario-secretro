	<!-- jQuery first,then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    
    <script type="text/javascript">
		$(".alternarFormularios").click(function(){
			$("#formularioRegistro").toggle();
			$("#formularioLogin").toggle();
		});
		
		//detectar cambios en el textarea
		$("#diario").on('input',function(){
			$.ajax({
				type: "POST",
	            url: "actualizarDB.php",
	            data: { content: $("#diario").val() }
			});
		});
    </script>
    
  </body>
</html>