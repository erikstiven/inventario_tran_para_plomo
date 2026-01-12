<? /* * ***************************************************************** */ ?>
<? /* NO MODIFICAR ESTA SECCION */ ?>
<? include_once('../_Modulo.inc.php'); ?>
<? include_once(HEADER_MODULO); ?>
<? if ($ejecuta) { ?>
    <? /*     * ***************************************************************** */ ?>
    
   <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?=$_COOKIE["JIREH_COMPONENTES"]?>bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/dataTables/dataTables.buttons.min.css" media="screen">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=$_COOKIE["JIREH_COMPONENTES"]?>bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?=$_COOKIE["JIREH_COMPONENTES"]?>bower_components/Ionicons/css/ionicons.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?=$_COOKIE["JIREH_COMPONENTES"]?>bower_components/select2/dist/css/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=$_COOKIE["JIREH_COMPONENTES"]?>dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skinsfolder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?=$_COOKIE["JIREH_COMPONENTES"]?>dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/dataTables/dataTables.bootstrap.min.css" media="screen">

    <!--JavaScript--> 
    <script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.buttons.flash.min.js"></script>
    <script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.jszip.min.js"></script>
    <script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.pdfmake.min.js"></script>
    <script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.vfs_fonts.js"></script>
    <script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.buttons.html5.min.js"></script>
    <script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.buttons.print.min.js"></script>
    
    <!-- Select2 -->
    <script src="<?=$_COOKIE["JIREH_COMPONENTES"]?>bower_components/select2/dist/js/select2.full.min.js"></script>

    <!-- AdminLTE App -->
    <script src="<?=$_COOKIE["JIREH_COMPONENTES"]?>dist/js/adminlte.min.js"></script>

    <!--CSS-->
    <link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/bootstrap-3.3.7-dist/css/bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/bootstrap-3.3.7-dist/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>js/treeview/css/bootstrap-treeview.css" media="screen">
    <link rel="stylesheet" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/dataTables/dataTables.bootstrap.min.css">
    
    <link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>js/select2/dist/css/select2.min.css" media="screen" />
	
    <script src="media/js/lenguajeusuario_tran.js"></script>   
    
    <script>
        function genera_formulario() {
            //document.getElementById('divReporteProdServClpv').innerHTML = '';
            //document.getElementById('divReporteDsctLinp').innerHTML = '';
            xajax_genera_formulario('nuevo', xajax.getFormValues("form1"));
        }

		function generaSelect2(){
            $('.select2').select2();
        }
		
        function cerrar() {
            parent.CloseAjaxWin();
        }

        function guardar() {
            if (ProcesarFormulario() == true) {
                var codigo = document.getElementById('defi_cod_defi').value;
                if(codigo == ''){
                    xajax_guardar_tran(xajax.getFormValues("form1"));
                }else{
                    xajax_update_tran_frame(xajax.getFormValues("form1"));
                }
            }
        }
        

        function copiar_nombre() {
            var val = document.getElementById('nombre').value;
            document.getElementById('nombre_comercial').value = val;
        }

        function editarCliente(tip, ruc, nom, com, grpv, dir, tlf, ema, suc, zon, pre, vend, est, lim, dia, pag, gen, det, cod,
                                tidu, tclp, trta, edes, sexo, civi, prov, ciud, cant, parr, ingr, cobr, ret) {
            document.getElementById('identificacion').value = tip;
            document.getElementById('ruc_cli').value = ruc;
            document.getElementById('nombre').value = nom;
            document.getElementById('nombre_comercial').value = com;
            document.getElementById('grupo').value = grpv;
            document.getElementById('direccion_cli').value = dir;
            document.getElementById('telefono_cli').value = tlf;
            document.getElementById('emai_ema_emai').value = ema;
            document.getElementById('dire_op').value = dir;
            document.getElementById('telf_op').value = tlf;
            document.getElementById('mail_op').value = ema;
            document.getElementById('clpv_cod_sucu').value = suc;
            document.getElementById('zona').value = zon;
            document.getElementById('clpv_pre_ven').value = pre;
            document.getElementById('clpv_cod_vend').value = vend;
            if(est != ''){
                document.getElementById(est).checked = true;
            }else{
                document.getElementById('A').checked = true;
            }   
            document.getElementById('limite').value = lim;
            document.getElementById('dias_pago').value = dia;
            document.getElementById('pago').value = pag;
            document.getElementById('dsctGeneral').value = gen;
            document.getElementById('dsctDetalle').value = det;
            document.getElementById('codigoCliente').value = cod;
            
            //datos cliente
            document.getElementById('tipo_cliente').value = tidu;
            document.getElementById('clase').value = tclp;
            document.getElementById('transportista').value = trta;
            document.getElementById('especialidad').value = edes;
            document.getElementById('sexo').value = sexo;
            document.getElementById('estado_civil').value = civi;
            document.getElementById('provincia').value = prov;
            
            if(ciud != ''){
                xajax_cargar_ciudad(xajax.getFormValues("form1"));
            }
            
            if(cant != ''){
                xajax_cargar_canton(xajax.getFormValues("form1"));
            }
            
            document.getElementById('ciudad').value = ciud;
            document.getElementById('canton').value = cant;
            document.getElementById('parroquia').value = parr;
            document.getElementById('origenIngreso').value = ingr;
            document.getElementById('cobrador').value = cobr;
            
            if(ret == 'N' || ret == ''){
                document.getElementById('aplicaRet').checked = false;
            }else{
                document.getElementById('aplicaRet').checked = true;
            }
            
            document.getElementById('lgTitulo_frame').innerHTML = 'EDITAR FICHA CLIENTE';
            //xajax_listaDatos(xajax.getFormValues("form1"));
            xajax_listaCcli(xajax.getFormValues("form1"));
            xajax_listaProdServCliente(xajax.getFormValues("form1"));
            xajax_listaDsctoLinpCliente(xajax.getFormValues("form1"));
        }

     
        function copiar_nombre_() {
            var val = document.getElementById('nombre_').value;
            document.getElementById('nombre_comercial').value = val;
        }

        function cargar_zona_lista(cod) {
            xajax_cargar_lista_zona(xajax.getFormValues("form1"), cod);
        }
        
        function eliminar_lista_zona() {
            var sel = document.getElementById("zona");
            for (var i = (sel.length - 1); i >= 1; i--) {
                aBorrar = sel.options[i];
                aBorrar.parentNode.removeChild(aBorrar);
            }
        }
        
        function anadir_elemento_zona(x, i, elemento) {
            var lista = document.form1.zona;
            var option = new Option(elemento, i);
            lista.options[x] = option;
            document.form1.zona.value = i;
        }
        
        function consultarReporteCliente(){
            xajax_consultarReporteCliente(xajax.getFormValues("form1"));
        }
        
        function consultaExistenciaIden(){
            xajax_consultaExistenciaIden(xajax.getFormValues("form1"));
        }
        
        function focoCampo(){
            document.getElementById('ruc_cli').focus();
        }
        
        function focoCampoCcli(){
            document.getElementById('ruc_ccli').focus();
        }
        
        function focoBodega(){
            document.getElementById('prodProdServ').focus();
        }
        
        function focoLinp(){
            document.getElementById('dsctoLinp').focus();
        }
        
        function  autocompletarProdServ(event) {
            if (event.keyCode == 115 || event.keyCode == 13) { // F4
                var bode = document.getElementById('idBodegaProdServ').value;
                var sucursal = document.getElementById('clpv_cod_sucu').value;
                var prod = document.getElementById('prodProdServ').value;

                if(bode != ''){
                    var arrayPametros = [bode, prod, sucursal];
                    var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=780, height=380, top=255, left=130";
                    var pagina = '../cxc_clientes/buscar_prod.php?sesionId=<?= session_id() ?>&mOp=true&mVer=false&array=' + arrayPametros;
                    window.open(pagina, "", opciones);
                }else{
                    alert('Seleccione Bodega para continuar..!');
                }
               
            }
        }
        
        function listaProdServCliente(){
            xajax_listaProdServCliente(xajax.getFormValues("form1"));
        }
        
        function guardarProdServ(){
            var codProdProdServ = document.getElementById('codProdProdServ').value;
            var codigoCliente = document.getElementById('codigoCliente').value;
            var idContrato = document.getElementById('idContrato').value;
            var tipo_cobro = document.getElementById('tipo_cobro').value;
            if(codigoCliente != ''){
                if(codProdProdServ != '' && idContrato != '' && tipo_cobro != ''){
                    xajax_guardarProdServ(xajax.getFormValues("form1"));
                }else{
                    alert('Seleccione Contrato y Producto para continuar...');
                }
            }else{
                alert('Seleccione Cliente para continuar...');
            }
        }
        
        function eliminarProdServ(clpv, clse){
            xajax_eliminarProdServ(clpv, clse);
        }
        
        function modificarProdServ(){
            xajax_modificarProdServ(xajax.getFormValues("form1"));
        }
        
        function guardarDsctoLinpCliente(){
            var linp = document.getElementById('linp').value;
            var dscto = document.getElementById('dsctoLinp').value;
            
            if(linp != '' && dscto > 0){
                xajax_guardarDsctoLinpCliente(xajax.getFormValues("form1"));
            }else{
                alert('Seleccione Linea Inventario y Descuento Mayor a Cero...');
            }
            
        }

        function eliminarDsctoLinpCliente(clpv, clnp){
            xajax_eliminarDsctoLinpCliente(clpv, clnp);
        }
        
        function modificarDsctoLinpCliente(){
            xajax_modificarDsctoLinpCliente(xajax.getFormValues("form1"));
        }
        
        function listaDsctoLinpCliente() {
            xajax_listaDsctoLinpCliente(xajax.getFormValues("form1"));
        }
        
        function nuevoFormCcli(){
            document.getElementById('identificacionCcli').value = '';
            document.getElementById('codigoSubCliente').value = '';
            document.getElementById('ruc_ccli').value = '';
            document.getElementById('nombreCcli').value = '';
            document.getElementById('emaiCcli').value = '';
            document.getElementById('telefonoCcli').value = '';
            document.getElementById('direccionCcli').value = '';
            document.getElementById('vendCcli').value = '';
            xajax_listaCcli(xajax.getFormValues("form1"));
        }
        
        function editarCcli(cod, tip, ruc, nom, dir, tlf, ema, ven){
            document.getElementById('identificacionCcli').value = tip;
            document.getElementById('codigoSubCliente').value = cod;
            document.getElementById('ruc_ccli').value = ruc;
            document.getElementById('nombreCcli').value = nom;
            document.getElementById('emaiCcli').value = ema;
            document.getElementById('telefonoCcli').value = tlf;
            document.getElementById('direccionCcli').value = dir;
            document.getElementById('vendCcli').value = ven;
        }
        
        function guardarCcli(){
            var cod = document.getElementById('codigoCliente').value;
            if(cod != ''){
                xajax_guardarCcli(xajax.getFormValues("form1"));
            }else{
                alert('Seleccione Cliente para continuar...!');
            }
        }
        
        function listaCcli(){
            xajax_listaCcli(xajax.getFormValues("form1"));
        }
        
        function listaCcliNombre(){
            xajax_listaCcliNombre(xajax.getFormValues("form1"));
        }
        
        function limpiarCampoCcli(){
            document.getElementById('ccliNombreSearch').value = '';
            xajax_listaCcliNombre(xajax.getFormValues("form1"));
        }
        
        function cargar_ciudad(val) {
            var op = document.getElementById('provincia').value;
            if (op == 0) {
                document.getElementById("ciudad").options.length = 0;
            } else {
                xajax_cargar_ciudad(xajax.getFormValues("form1"), val);
            }
        }
        
        function limpiar_lista() {
            document.getElementById("ciudad").options.length = 0;
        }

        function anadir_elemento_comun(x, i, elemento) {
            var lista = document.form1.ciudad;
            var option = new Option(elemento, i);
            lista.options[x] = option;
        }
        
        function cargar_canton() {
            var op = document.getElementById('canton').value;
            if (op == 0) {
                document.getElementById("parroquia").options.length = 0;
            } else {
                xajax_cargar_canton(xajax.getFormValues("form1"));
            }
        }
        
        function limpiar_lista_canton() {
            document.getElementById("parroquia").options.length = 0;
        }

        function anadir_elemento_comun_canton(x, i, elemento) {
            var lista = document.form1.parroquia;
            var option = new Option(elemento, i);
            lista.options[x] = option;
        }

        function tipoCorreo(event){
            if (event.keyCode == 115) { // ENTER
                var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=200, height=200, top=170, left=590";
                var pagina = '../cxc_clientes/buscar_correo.php?sesionId=<?= session_id() ?>&mOp=true&mVer=false&id=';
                window.open(pagina, "", opciones);
            }
        }

        function asignarTipoCorreo(a){
            var correo = document.getElementById('emai_ema_emai').value;
            document.getElementById('emai_ema_emai').value = correo + a;
        }

        function seleccionaItem(tran_cod, id){
            xajax_seleccionarTran(xajax.getFormValues("form1"), tran_cod, id);
        }
        
        function agregarEntidad(op){
            var codigoCliente = document.getElementById('codigoCliente').value;
            if(codigoCliente != ''){
                xajax_agregarEntidad(xajax.getFormValues("form1"), op);
            }else{
                alert('Seleccione Cliente para continuar..!');
            }
            
        }

        function reporteTelefonoCliente(){
            xajax_reporteTelefonoCliente(xajax.getFormValues("form1"));
        }

        function updateEntidad(op){
            xajax_updateEntidad(xajax.getFormValues("form1"), op);
        }

        function reporteEmailCliente(){
            xajax_reporteEmailCliente(xajax.getFormValues("form1"));
        }

        function generarContrato(){
            var codigoCliente = document.getElementById('codigoCliente').value;
            var op = document.getElementById('fechaContrato').value;
            var op_1 = document.getElementById('fechaFirma').value;
            var op_2 = document.getElementById('duracionContrato').value;
            var op_3 = document.getElementById('penalidadContrato').value;

            if(codigoCliente != ''){
                if(op != '' && op_1 != '' && op_2 != '' && op_3 != ''){
                    xajax_generarContrato(xajax.getFormValues("form1"));  
                }else{
                    alert('Los campos con * son de ingreso obligatorio para genera Contrato..!');
                }
            }else{
                alert('Seleccione Cliente para continuar..!');
            }
            
        }

        function reporteContratoCliente(){
            xajax_reporteContratoCliente(xajax.getFormValues("form1"));  
        }

        function consultaPrecio(){
            xajax_consultaPrecio(xajax.getFormValues("form1"));  
        }

        //lista contratos
         function cargarListaContrato() {
            xajax_cargarListaContrato(xajax.getFormValues("form1"));
        }
        
        function eliminar_lista_contrato() {
            var sel = document.getElementById("idContrato");
            for (var i = (sel.length - 1); i >= 1; i--) {
                aBorrar = sel.options[i];
                aBorrar.parentNode.removeChild(aBorrar);
            }
        }
        
        function anadir_elemento_contrato(x, i, elemento) {
            var lista = document.form1.idContrato;
            var option = new Option(elemento, i);
            lista.options[x] = option;
            document.form1.idContrato.value = i;
        }

        function estadoCuentaContrato(id){
            $("#miModal").modal("show");
            xajax_estadoCuentaContrato(xajax.getFormValues("form1"), id);
        }

        function cargarSecuencial(){
            xajax_cargarSecuencial(xajax.getFormValues("form1"));
        }
		
		function reporteDireCliente(){
            xajax_reporteDireCliente(xajax.getFormValues("form1"));
        }
		
		function updateContrato(id){
			xajax_updateContrato(xajax.getFormValues("form1"), id);
		}
		
		function imprimirContrato(id){
			xajax_imprimirContrato(xajax.getFormValues("form1"), id);
		}
		
		function generar_pdf() {
            if (ProcesarFormulario() == true) {
                var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=730, height=370, top=255, left=130";
                var pagina = '../../Include/documento_pdf.php?sesionId=<?= session_id() ?>';
                window.open(pagina, "", opciones);
            }
        }

		function cargar_button(id, val){
			
		}
		
    </script>

    <!--DIBUJA FORMULARIO FILTRO-->
    <body onload='javascript:cambiarPestanna(pestanas, pestana1);'>
        <div class="row">
            <form id="form1" name="form1" action="javascript:void(null);">
                <div id="pestanas">
                    <ul id="lista" class="nav nav-tabs bg-info">
                        <li role="presentation" id="pestana1"><a href='javascript:cambiarPestanna(pestanas,pestana1);'>TRANSACCIONES</a></li>
                    </ul>
                </div>
                <div class="col-md-3 col-md-offset-9" align="right">
                    <h6 class="text-primary fecha_letra" id="informacionCliente"></h6>
                </div>
                
				<div id="contenidopestanas">
                    <div id="cpestana1"></div>
                    <div id="tpestana1" class="main-row col-md-12">
                        <div class="col-md-5">
                            <div class="table responsive" style="width: 100%;">
                                <table id="example" class="table table-striped table-bordered table-hover table-condensed"  style="width: 100%;" align="center">
                                    <thead>
                                        <tr>
                                            <td colspan="5" class="bg-primary">REPORTE DE TRANSACCIONES</td>
                                        </tr>
                                        <tr class="info">
                                            <td>Codigo</td>
                                            <td>Tipo</td>
                                            <td>Transaccion</td>
											<td>Sucursal</td>
                                            <td>Editar</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>        
                            </div>
                        </div>
                        <div class="col-md-7" id="divFormularioCli" align="center"></div>                       
                    </div>
                    
                </div>
                
				<div style="width: 100%;">
                    <div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
                </div>
            </form>
        </div>
    </body>

   
    <script>genera_formulario();</script>
    <script src="js/validacion_.js" type="text/javascript"></script>

    <script>
        function selectItemByValue(select_id, value){
            var elmnt =  document.getElementById(select_id);
            for(var i=0; i < elmnt.options.length; i++){
                if(elmnt.options[i].value === value) {
                    elmnt.selectedIndex = i;
                    break;
                }
            }
            $('#'+select_id).val(""+value); // Select the option with a value of '21'
            $('#'+select_id).trigger('change'); 
        }
        
        
    </script>
    <? /*     * ***************************************************************** */ ?>
    <? /* NO MODIFICAR ESTA SECCION */ ?>
<? } ?>
<? include_once(FOOTER_MODULO); ?>
<? /* * ***************************************************************** */ ?>