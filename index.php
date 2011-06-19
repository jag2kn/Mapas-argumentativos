<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/black-tie/jquery-ui.css"/>
	<link rel="stylesheet" href="js/svg/jquery.svg.css"/>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
	<script src="http://jqueryui.com/themeroller/themeswitchertool/"></script>
	<script src="js/svg/jquery.svg.min.js"></script>
	

	<script>
		//formulario
		var titulo;
		var contenido;
		var autor;
		var tipo;
		var allFields;
		
		//identificadores
		var contador=0;
		var actual;
		var padre=-1;
		
		//svg
		var svg;
		
		
		function calcularLineas(){
		
			svg = $('#set').svg('get');
			svg.clear();
			for(i=0;i<contador;i++){
				

				ipadre = $("#padre_"+i).val()
				
				if(ipadre!=-1){
					left = parseInt($("#caja_"+i).css("left"));
					top = parseInt($("#caja_"+i).css("top"));
					ancho = parseInt($("#caja_"+i).css("width"));
					alto = parseInt($("#caja_"+i).css("height"));
					
					left2 = parseInt($("#caja_"+ipadre).css("left"));
					top2 = parseInt($("#caja_"+ipadre).css("top"));
					ancho2 = parseInt($("#caja_"+ipadre).css("width"));
					alto2 = parseInt($("#caja_"+ipadre).css("height"));
					
					color="green";
					if ($("#tipo_"+i).val()=="premisa"){
						color="blue";
					}else if ($("#tipo_"+i).val()=="razon"){
						color="green";
					}else if ($("#tipo_"+i).val()=="objecion"){
						color="red";
					}else if ($("#tipo_"+i).val()=="ayuda"){
						color="yellow";
					}
					
					
					svg.line(left+ancho/2, top+alto/2, left2+ancho2/2, top2+alto2/2,
							{stroke: color, 'stroke-width': 3}
					);
				}
			}
		}
		
		function agregarCaja(tipo){
			if (tipo==undefined){
				tipo="premisa";
			}
			$("#set").append(
				'<div id="caja_'+contador+'" class="'+tipo+' box ui-widget-content">'+
					'<div class="ui-widget-header ui-corner-all ui-helper-clearfix ui-dialog-titlebar t">'+
						'<div id="ttitulo_'+contador+'" class="ttitulo">Titulo '+contador+'</div>'+
						'<div id="propiedades_'+contador+'" class="botonPropiedades ui-state-default ui-corner-all"><span class="ui-icon ui-icon-pencil"></span></div>'+
						'<div id="toggle_'+contador+'" class="botonPropiedades ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'+
						
						'<div id="ayuda_'+contador+'" class="botonAyuda ui-state-default ui-corner-all"><span class="ui-icon ui-icon-info"></span></div>'+
						'<div id="razon_'+contador+'" class="botonRazon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-check"></span></div>'+
						'<div id="objecion_'+contador+'" class="botonObjecion ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-close"></span></div>'+
						
					'</div>'+
					'<div class="ui-widget-content">'+
						'<input type="text" readonly=readonly name="autor_'+contador+'"  id="autor_'+contador+'" value="" /'+'>'+
						'<textarea class="contenido" name="contenido_'+contador+'" id="contenido_'+contador+'" readonly ></textarea>'+
					'</div>'+
					'<input type="hidden" name="padre_'+contador+'"  id="padre_'+contador+'" value="'+padre+'" /' +'>'+
					'<input type="hidden" name="titulo_'+contador+'" id="titulo_'+contador+'" value="Titulo '+contador+'" /'+'>'+
					'<input type="hidden" name="tipo_'+contador+'"  id="tipo_'+contador+'" value="'+tipo+'" /'+'>'+
				'</div>'
			);
			
			$( "#set div.box" ).draggable({
						stack: "#set div.box", 
						handle: "div.t", 
						containment: "parent",
						stop: function(event, ui) {
							calcularLineas();
						}
				});
			$( "#set div.box" ).resizable({
						minHeight: 22,
						minWidth: 100,
						stop: function(event, ui) {
							calcularLineas();
						}
				});
			$('.ui-state-default').hover(
				function(){ $(this).addClass('ui-state-hover'); }, 
				function(){ $(this).removeClass('ui-state-hover'); }
			);
			
			
			if (padre==-1){
				$('#caja_'+contador).css("left", 100+(20*contador));
				$('#caja_'+contador).css("top",  50+(10*contador));
			}else{
				$('#caja_'+contador).css("left", 50+parseInt($('#caja_'+padre).css("left")));
				$('#caja_'+contador).css("top",  120+parseInt($('#caja_'+padre).css("top")));
			}

			

			$('#propiedades_'+contador).click(function(){
				$(this).toggleClass('ui-state-active');
				$(this).toggleClass('ui-state-active');
				try{
					id = $(this).attr("id");
					nombres=id.split("_");
					actual=nombres[1];
					titulo.val($("#titulo_"+actual).val());
					contenido.val($("#contenido_"+actual).text());
					autor.val($("#autor_"+actual).val());
				
					$( "#propiedadesCaja" ).dialog( "open" );
				}catch(aa){
				}
			});
			$('#toggle_'+contador).click(function(){
				id = $(this).attr("id");
				nombres=id.split("_");
				actual=nombres[1];
				
				alto=parseInt($("#caja_"+actual).css("height"));
				if (alto==22){
					$("#caja_"+actual).css("height", "100px");
				}else{
					$("#caja_"+actual).css("height", "22px");
				}
				calcularLineas();
			});
			$('#razon_'+contador).click(function(){
				id = $(this).attr("id");
				nombres=id.split("_");
				padre = nombres[1];
				agregarCaja("razon");
				padre=-1;
			});
			$('#objecion_'+contador).click(function(){
				id = $(this).attr("id");
				nombres=id.split("_");
				padre = nombres[1];
				agregarCaja("objecion");
				padre=-1;
			});
			$('#ayuda_'+contador).click(function(){
				id = $(this).attr("id");
				nombres=id.split("_");
				padre = nombres[1];
				agregarCaja("ayuda");
				padre=-1;
			});
			actual=contador;
			contador++;
			calcularLineas();
		}
		
		$(function() {
			titulo = $( "#titulo" );
			contenido = $( "#contenido" );
			autor = $( "#autor" );
			tipo = $( "#tipo" );
			allFields = $( [] ).add( titulo ).add( contenido ).add( tipo ).add( autor );

			
			$( ".button" ).button();
			
			$("#nuevo").click(function () { 
				agregarCaja("premisa");
				$("#tipo").hide();
				$( "#propiedadesCaja" ).dialog( "open" );
			});
			
			$("#guardar").click(function () { 
				alert("No implementado");
			});
			$("#cargar").click(function () { 
				alert("No implementado");
			});
			
			$( "#propiedadesCaja" ).dialog({
				autoOpen: false,
				height: 300,
				width: 600,
				modal: true,
				buttons: {
					"Guardar": function() {
						$("#titulo_"+actual).val(titulo.val());
						$("#ttitulo_"+actual).html(titulo.val());
						$("#contenido_"+actual).text(contenido.val());
						$("#autor_"+actual).val(autor.val());
						$("#tipo_"+actual).val(tipo.val());
						
						$("#caja_"+actual).removeClass("premisa razon objecion ayuda");
						$("#caja_"+actual).addClass(tipo.val());
						
						$( this ).dialog( "close" );
					}
				},
				close: function() {
					allFields.val( "" );
					$("#tipo").show();
				}
			});
			svg = $('#set').svg();
			
			
			$('#switcher').themeswitcher();
		});
	</script>
	<style>
		#set { clear:both; float:left; width: 100%; height: 100%; overflow:auto;}
		#set div.t .ttitulo { float: left; background: none; border: 0px; }
		.box{ width:300px; position: absolute; overflow: hidden;}
		.botonPropiedades{overflow:hidden;float:right;}
		.botonRazon{overflow:hidden;float:right;border:1.5px solid green !important;}
		.botonObjecion{overflow:hidden;float:right;border:1.5px solid red !important;}
		.botonayuda{overflow:hidden;float:right;border:1.5px solid yellow !important;}
		
		
		
		.contenido{height: 100%; width: 100%; background: none; border: 0px;}
		p { clear:both; margin:0; padding:1em 0; }
		#switcher{float:right;}
		#panel{float:left;}
		#control{position:absolute;}
		
		#contenido{	}
		.premisa{ border:2px solid blue !important; padding:3px !important; }
		.razon{ border:2px solid green !important; padding:3px !important; }
		.objecion{ border:2px solid red !important; padding:3px !important; }
		.ayuda{ border:2px solid yellow !important; padding:3px !important; }
		
	</style>
</head>
<body>

<div id='control'>
	<div id='panel'>
		<div class="button" id="nuevo">Nueva premisa</div>
		<div class="button" id="guardar">Guardar mapa</div>
		<div class="button" id="cargar">Cargar mapa</div>
	</div>

	<div id="switcher"></div>
</div>

<div id="set" class='ui-widget-content'>
</div>




<div id="propiedadesCaja" title="Editar">
	<form>
	<fieldset>
		<label>Titulo</label>
			<input type="text" name="titulo" id="titulo" class="text ui-widget-content ui-corner-all" /><br/>
		<label>Contenido</label>
			<textarea name="contenido" id="contenido" value="" class="text ui-widget-content ui-corner-all" ></textarea><br/>
		<label>Autor</label>
			<input type="text" name="autor" id="autor" class="text ui-widget-content ui-corner-all" /><br/>
		<label>Tipo</label>
			<select name="tipo" id="tipo">
				<option value="premisa">Premisa</option>
				<option value="razon">Razón</option>
				<option value="objecion">Objeción</option>
				<option value="ayuda">Ayuda</option>
			</select> <br/>
	</fieldset>
	</form>
</div>

</body>
</html>
