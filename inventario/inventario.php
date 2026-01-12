<? /********************************************************************/ ?>
<? /* NO MODIFICAR ESTA SECCION*/ ?>
<? include_once('../_Modulo.inc.php');?>
<? include_once(HEADER_MODULO);?>
<? if ($ejecuta) { ?>
<? /********************************************************************/ ?>

<!--CSS--> 
    <link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/bootstrap-3.3.7-dist/css/bootstrap.min.css" media="screen" />
	<link type="text/css" href="css/style.css" rel="stylesheet"></link>

    <!--Javascript--> 
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>css/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
	
<script>
	
	function genera_formulario(){
		xajax_genera_formulario();
	}

	function cargar_sucursal(){               
			xajax_genera_formulario('sucursal', xajax.getFormValues("form1") );
	}
        
	function cargar_bodega(){               
			xajax_genera_formulario('bodega', xajax.getFormValues("form1") );
	}

	function cerrar_ventana(){
		CloseAjaxWin();
	}

	function buscar(){
			xajax_reporte( xajax.getFormValues("form1") );
	}

	function autorizar(){
			xajax_autorizar_pedido( xajax.getFormValues("form1") );
	}
	
	function cargar_arbol(){
			xajax_cargar_arbol( xajax.getFormValues("form1") );
	}
       

	function anadir_elemento(x, i, elemento, form ){            
        var lista = document.getElementById(form);
		var option = new Option(elemento,i);
		lista.options[x] = option;
	}
	
	function borrar_lista(form){
		document.getElementById(form).options.length= 0;
	}
	
	function autocompletar_producto(empresa, event, op) {
		if (event.keyCode == 115 || event.keyCode == 13) { // F4
			var prod_nom = document.getElementById('producto').value;
			var cod_nom = document.getElementById('codigo_producto').value;
			var sucu = document.getElementById('sucursal').value;
			var bodega = document.getElementById('bodega').value;
			var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=730, height=380, top=255, left=130";
			var pagina = '../kardex_inv/buscar_prod.php?sesionId=<?= session_id() ?>&mOp=true&mVer=false&producto=' + prod_nom + '&codigo=' + cod_nom + '&opcion=' + op + '&sucursal=' + sucu + '&bodega=' + bodega + '&empresa=' + empresa;
			window.open(pagina, "", opciones);
		}
	}
		
		
	function vista_previa(id){
            var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=730, height=380, top=255, left=130";

            var pagina = '../inventario/vista_previa.php?sesionId=<?=session_id()?>&codigo='+id;
            window.open(pagina,"",opciones);	
	}
	
	 function verificaCondicion(op) {
            if (op.value == 'between') {
                xajax_verificaCondicion(xajax.getFormValues("form1"));
            } else if(op.value == '') {
                document.getElementById('cantidad').value = "";
                document.getElementById('campoCantidad_').innerHTML = '';
            }else{
                document.getElementById('cantidad').focus();
                document.getElementById('campoCantidad_').innerHTML = '';
            }
        }
</script>

<!--DIBUJA FORMULARIO FILTRO-->
<div align="center">
    <form id="form1" name="form1" action="javascript:void(null);">
      <table align="center" border="0" cellpadding="2" cellspacing="0" width="100%">
        <tr>
          	<td valign="top" align="center">
                    <div id="divFormularioCabecera"></div>
         	</td>
        </tr>
        <tr>
          	<td valign="top" align="center">
                    <div id="divFormularioDetalle"></div>
         	</td>
        </tr>
		<tr>
          	<td valign="top" align="center">
            	<div id="divReporte"></div>
         	</td>
        </tr>
      </table>
     </form>
</div>
<div id="divGrid" ></div>
<script>genera_formulario();/*genera_detalle();genera_form_detalle();*/</script>
<? /********************************************************************/ ?>
<? /* NO MODIFICAR ESTA SECCION*/ ?>
<? } ?>
<? include_once(FOOTER_MODULO); ?>
<? /********************************************************************/ ?>