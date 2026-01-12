<?php

require("_Ajax.comun.php"); // No modificar esta linea
/* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
  // S E R V I D O R   A J A X //
  :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */

/**
  Herramientas de apoyo
 */
/* * ************************************************************* */
/* DF01 :: G E N E R A    F O R M U L A R I O    P R O C E S O  */
/* * ************************************************************* */
function genera_formulario($sAccion = 'nuevo', $aForm = '') {
    //Definiciones
    global $DSN_Ifx, $DSN;
    if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

    $oIfx = new Dbo;
    $oIfx->DSN = $DSN_Ifx;
    $oIfx->Conectar();

    $ifu = new Formulario;
    $ifu->DSN = $DSN_Ifx;

    $oReturn = new xajaxResponse();

    $idempresa    = $aForm['empresa'];
    $sucursal 	  = $aForm['sucursal'];
    $fecha_inicio = $aForm['fecha_inicio'];
    $fecha_fin    = $aForm['fecha_fin'];

    switch ($sAccion) {
        case 'nuevo':
			
			$idempresa   =  $_SESSION['U_EMPRESA'];
			$idsucursal  =  $_SESSION['U_SUCURSAL'];
		
            $ifu->AgregarCampoListaSQL('empresa', 'Empresa|left', "SELECT EMPR_COD_EMPR, EMPR_NOM_EMPR FROM SAEEMPR
                                                                                    ORDER BY 2 ", true, 170,150);
            $ifu->AgregarComandoAlCambiarValor('empresa', 'cargar_sucursal()');            
            $ifu->AgregarCampoListaSQL('sucursal', 'Sucursal|left', "select sucu_cod_sucu, sucu_nom_sucu from saesucu where
                                                                        sucu_cod_empr = $idempresa order by 2", true, 170,150);            
            
			$ifu->AgregarCampoListaSQL('bodega', 'Bodega|left', "select  b.bode_cod_bode, b.bode_nom_bode from saebode b, saesubo s where
                                                                                b.bode_cod_bode = s.subo_cod_bode and
                                                                                b.bode_cod_empr = $idempresa and
                                                                                s.subo_cod_empr = $idempresa and
                                                                                s.subo_cod_sucu = $idsucursal", false, 170,150);
																				
            $ifu->AgregarCampoFecha('fecha_ini', 'Fecha Corte|left', true, date('Y') . '/' . date('m') . '/' . date('d'));
            
			// ARBOL DE INVENTARIO
			$ifu->AgregarCampoListaSQL('linea', 'Linea|left', "select linp_cod_linp, linp_des_linp  from saelinp where
																	linp_cod_empr = $idempresa order by 2", false, 'auto');
			$ifu->AgregarComandoAlCambiarValor('linea', 'cargar_arbol();');
			$ifu->AgregarCampoListaSQL('grupo', 'Grupo|left', '', false, 'auto');
			$ifu->AgregarComandoAlCambiarValor('grupo', 'cargar_arbol();');
			$ifu->AgregarCampoListaSQL('cate',  'Categoria|left', '', false, 'auto');
			$ifu->AgregarComandoAlCambiarValor('cate', 'cargar_arbol();');
			$ifu->AgregarCampoListaSQL('marca', 'Marca|left', '', false, 'auto');
			
			$ifu->AgregarCampoLista('condicion', 'Condicion Stock|left', false, 100, 100);
            $ifu->AgregarOpcionCampoLista('condicion', 'Mayor Igual', '>=');
            $ifu->AgregarOpcionCampoLista('condicion', 'Mayor', '>');
            $ifu->AgregarOpcionCampoLista('condicion', 'Menor Igual', '<=');
            $ifu->AgregarOpcionCampoLista('condicion', 'Menor', '<');
            $ifu->AgregarOpcionCampoLista('condicion', 'Igual', '=');
            $ifu->AgregarOpcionCampoLista('condicion', 'Entre', 'between');
            $ifu->AgregarComandoAlCambiarValor('condicion', 'verificaCondicion(this);');
			
			$ifu->cCampos["empresa"]->xValor = $idempresa;
			$ifu->cCampos["sucursal"]->xValor = $idsucursal;
				

			// PRODUCTO
			$ifu->AgregarCampoTexto('producto', 'Producto|LEFT', false, '', 250, 200);
			$ifu->AgregarComandoAlEscribir('producto', 'autocompletar_producto(' . $idempresa . ', event, 1 )');

			$ifu->AgregarCampoTexto('codigo_producto', 'Cod. Prod|left', false, '', 120, 100);
			$ifu->AgregarComandoAlEscribir('codigo_producto', 'autocompletar_producto(' . $idempresa . ', event, 2)');
						
			$ifu->AgregarCampoNumerico('cantidad', 'Cant|left', false, '', 50, 9);
        break;        
        case 'sucursal':

            $ifu->AgregarCampoListaSQL('empresa', 'Empresa|left', "SELECT EMPR_COD_EMPR, EMPR_NOM_EMPR FROM SAEEMPR
                                                                                    ORDER BY 2 ", true, 170,150);
            $ifu->AgregarComandoAlCambiarValor('empresa', 'cargar_sucursal()');            
            $ifu->AgregarCampoListaSQL('sucursal', 'Sucursal|left', "select sucu_cod_sucu, sucu_nom_sucu from saesucu where
                                                                                    sucu_cod_empr = $idempresa order by 2 ", true, 170,150);
            $ifu->AgregarComandoAlCambiarValor('sucursal', 'cargar_bodega()');            
            $ifu->AgregarCampoListaSQL('bodega', 'Bodega|left', "", false, 170,150);            
            $ifu->AgregarCampoFecha('fecha_ini', 'Fecha Corte|left', true, date('Y') . '/' . date('m') . '/' . date('d'));
            
            $ifu->cCampos["empresa"]->xValor = $idempresa;
			
			// ARBOL DE INVENTARIO
			$ifu->AgregarCampoListaSQL('linea', 'Linea|left', "select linp_cod_linp, linp_des_linp  from saelinp where
																	linp_cod_empr = $idempresa order by 2", false, 'auto');
			$ifu->AgregarComandoAlCambiarValor('linea', 'cargar_arbol();');
			$ifu->AgregarCampoListaSQL('grupo', 'Grupo|left', '', false, 'auto');
			$ifu->AgregarComandoAlCambiarValor('grupo', 'cargar_arbol();');
			$ifu->AgregarCampoListaSQL('cate',  'Categoria|left', '', false, 'auto');
			$ifu->AgregarComandoAlCambiarValor('cate', 'cargar_arbol();');
			$ifu->AgregarCampoListaSQL('marca', 'Marca|left', '', false, 'auto');
			
			
			// PRODUCTO
			$ifu->AgregarCampoTexto('producto', 'Producto|LEFT', false, '', 250, 200);
			$ifu->AgregarComandoAlEscribir('producto', 'autocompletar_producto(' . $idempresa . ', event, 1 )');

			$ifu->AgregarCampoTexto('codigo_producto', 'Cod. Prod|left', false, '', 120, 100);
			$ifu->AgregarComandoAlEscribir('codigo_producto', 'autocompletar_producto(' . $idempresa . ', event, 2)');
			
			$ifu->AgregarCampoLista('condicion', 'Condicion Stock|left', false, 100, 100);
            $ifu->AgregarOpcionCampoLista('condicion', 'Mayor Igual', '>=');
            $ifu->AgregarOpcionCampoLista('condicion', 'Mayor', '>');
            $ifu->AgregarOpcionCampoLista('condicion', 'Menor Igual', '<=');
            $ifu->AgregarOpcionCampoLista('condicion', 'Menor', '<');
            $ifu->AgregarOpcionCampoLista('condicion', 'Igual', '=');
            $ifu->AgregarOpcionCampoLista('condicion', 'Entre', 'between');
            $ifu->AgregarComandoAlCambiarValor('condicion', 'verificaCondicion(this);');
			
			$ifu->AgregarCampoNumerico('cantidad', 'Cant|left', false, '', 50, 9);
						
        break;        
        case 'bodega':

            $ifu->AgregarCampoListaSQL('empresa', 'Empresa|left', "SELECT EMPR_COD_EMPR, EMPR_NOM_EMPR FROM SAEEMPR
                                                                                    ORDER BY 2 ", true, 170,150);
            $ifu->AgregarComandoAlCambiarValor('empresa', 'cargar_sucursal()');            
            $ifu->AgregarCampoListaSQL('sucursal', 'Sucursal|left', "select sucu_cod_sucu, sucu_nom_sucu from saesucu where
                                                                     sucu_cod_empr = $idempresa order by 2 ", true, 170,150);
            $ifu->AgregarComandoAlCambiarValor('sucursal', 'cargar_bodega()');            
            $ifu->AgregarCampoListaSQL('bodega', 'Bodega|left', "select  b.bode_cod_bode, b.bode_nom_bode from saebode b, saesubo s where
                                                                                b.bode_cod_bode = s.subo_cod_bode and
                                                                                b.bode_cod_empr = $idempresa and
                                                                                s.subo_cod_empr = $idempresa and
                                                                                s.subo_cod_sucu = $sucursal", false, 170,150);            
            $ifu->AgregarCampoFecha('fecha_ini', 'Fecha Corte|left', true, date('Y') . '/' . date('m') . '/' . date('d'));
            
            $ifu->cCampos["empresa"]->xValor = $idempresa;
            $ifu->cCampos["sucursal"]->xValor = $sucursal;

			
			// ARBOL DE INVENTARIO
			$ifu->AgregarCampoListaSQL('linea', 'Linea|left', "select linp_cod_linp, linp_des_linp  from saelinp where
																	linp_cod_empr = $idempresa order by 2", false, 'auto');
			$ifu->AgregarComandoAlCambiarValor('linea', 'cargar_arbol();');
			$ifu->AgregarCampoListaSQL('grupo', 'Grupo|left', '', false, 'auto');
			$ifu->AgregarComandoAlCambiarValor('grupo', 'cargar_arbol();');
			$ifu->AgregarCampoListaSQL('cate',  'Categoria|left', '', false, 'auto');
			$ifu->AgregarComandoAlCambiarValor('cate', 'cargar_arbol();');
			$ifu->AgregarCampoListaSQL('marca', 'Marca|left', '', false, 'auto');
			
			
			// PRODUCTO
			$ifu->AgregarCampoTexto('producto', 'Producto|LEFT', false, '', 250, 200);
			$ifu->AgregarComandoAlEscribir('producto', 'autocompletar_producto(' . $idempresa . ', event, 1 )');

			$ifu->AgregarCampoTexto('codigo_producto', 'Cod. Prod|left', false, '', 120, 100);
			$ifu->AgregarComandoAlEscribir('codigo_producto', 'autocompletar_producto(' . $idempresa . ', event, 2)');
			
			$ifu->AgregarCampoLista('condicion', 'Condicion Stock|left', false, 100, 100);
            $ifu->AgregarOpcionCampoLista('condicion', 'Mayor Igual', '>=');
            $ifu->AgregarOpcionCampoLista('condicion', 'Mayor', '>');
            $ifu->AgregarOpcionCampoLista('condicion', 'Menor Igual', '<=');
            $ifu->AgregarOpcionCampoLista('condicion', 'Menor', '<');
            $ifu->AgregarOpcionCampoLista('condicion', 'Igual', '=');
            $ifu->AgregarOpcionCampoLista('condicion', 'Entre', 'between');
            $ifu->AgregarComandoAlCambiarValor('condicion', 'verificaCondicion(this);');
			
			$ifu->AgregarCampoNumerico('cantidad', 'Cant|left', false, '', 50, 9);
			
        break;
    }

	
	$sHtml .= '<table class="table table-striped table-condensed" style="width: 60%; margin-bottom: 0px;" align="center">';
    $sHtml .= '<tr>
                <td align="left" colspan="4">
					<div class="btn-group">
						<div class="btn btn-primary btn-sm" onclick="genera_formulario();">
							<span class="glyphicon glyphicon-file"></span>
							Nuevo
						</div>
						<div class="btn btn-primary btn-sm" onclick="document.location=\'excel.php?\'" >
							<span class="glyphicon glyphicon-print"></span>
							Excel
						</div>
						<div class="btn btn-primary btn-sm" onclick="buscar();">
							<span class="glyphicon glyphicon-search"></span>
						Consultar
					</div>
					</div>
                </td>
            </tr>';
			
    $sHtml .= '<tr>
                    <td colspan="4" height="20px" align="center" class="bg-primary" >REPORTE INVENTARIO</td>
               </tr>
               <tr>
                    <td colspan="4" align="center">Los campos con * son de ingreso obligatorio</td>
	       </tr>';
    $sHtml .= '<tr>
                <td >' . $ifu->ObjetoHtmlLBL('empresa') . '</td>
                <td>' . $ifu->ObjetoHtml('empresa') . '</td>
                <td >' . $ifu->ObjetoHtmlLBL('sucursal') . '</td>
                <td>' . $ifu->ObjetoHtml('sucursal') . '</td>
            </tr>';
    $sHtml .= '<tr>
                <td >' . $ifu->ObjetoHtmlLBL('bodega') . '</td>
                <td>' . $ifu->ObjetoHtml('bodega') . '</td>
                <td >' . $ifu->ObjetoHtmlLBL('fecha_ini') . '</td>
                <td>
					<input type="date" id="fecha_ini" name="fecha_ini" step="1" value="'.date("Y-m-d").'" class="form-control" style="width: 170px;">   
				</td>
              </tr>';
				   
	$sHtml .= '<tr>
                <td >' . $ifu->ObjetoHtmlLBL('linea') . '</td>
                <td>' . $ifu->ObjetoHtml('linea') . '</td>
                <td >' . $ifu->ObjetoHtmlLBL('grupo') . '</td>
                <td>' . $ifu->ObjetoHtml('grupo') . '</td>
              </tr>';
			  
	$sHtml .= '<tr>
                <td >' . $ifu->ObjetoHtmlLBL('cate') . '</td>
                <td>' . $ifu->ObjetoHtml('cate') . '</td>
                <td >' . $ifu->ObjetoHtmlLBL('marca') . '</td>
                <td>' . $ifu->ObjetoHtml('marca') . '</td>
              </tr>';
	
$sHtml .= '<tr>
                        <td >'.$ifu->ObjetoHtmlLBL('producto').'</td>
                        <td colspan="1">
                            <table> 
                                <tr>
                                    <td>'.$ifu->ObjetoHtml('producto').'</td>
                                    <td >'.$ifu->ObjetoHtmlLBL('codigo_producto').'</td>
                                    <td>'.$ifu->ObjetoHtml('codigo_producto').'</td>
                                </tr>
                            </table>
                        </td>
						 <td class="fecha_letra" bgcolor="ebf0f0">' . $ifu->ObjetoHtmlLBL('condicion') . '</td>
						<td bgcolor="ebf0f0">' . $ifu->ObjetoHtml('condicion') . '
									<span id="campoCantidad">' . $ifu->ObjetoHtml('cantidad') . '</span>
									<span id="campoCantidad_"></span>
						</td>
                   </tr>'; 	
			  
    $sHtml .= '<tr>
                 <td colspan="4" align="center">										
                 </td>
               </tr>';
    $sHtml .='</table>';

    $oReturn->assign("divFormularioCabecera", "innerHTML", $sHtml);

    return $oReturn;
}



function cargar_arbol( $aForm='' ){
    if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    global $DSN_Ifx;

    $oIfx = new Dbo;
    $oIfx -> DSN = $DSN_Ifx;
    $oIfx -> Conectar();

    $oReturn   = new xajaxResponse();
    $idempresa = $aForm['empresa'];
    $linea     = $aForm['linea'];
    $grupo     = $aForm['grupo'];
    $cate      = $aForm['cate'];
    $marca     = $aForm['marca'];
		
    if(!empty($linea)){
        // GRUPO        
        $sql = "select grpr_cod_grpr, grpr_des_grpr from saegrpr where
                    grpr_cod_empr = $idempresa and
                    grpr_cod_linp = $linea order by 2 ";    
        $i = 0;    
        $msn = "...Seleccione una Opcion...";
        $txt = 'grupo';
        $oReturn->script('borrar_lista( \''.$txt.'\' )');
        if($oIfx->Query($sql)){        
            if ($oIfx->NumFilas() > 0){
                do{
                    $id   = $oIfx->f('grpr_cod_grpr');
                    $nom  = $oIfx->f('grpr_des_grpr');
                    $oReturn->script(('anadir_elemento('.$i.','.$id.', \''.$nom.'\', \''.$txt.'\' )'));
                    $i++;
                }while($oIfx->SiguienteRegistro());
                $oReturn->script(('anadir_elemento('.$i.',"", \''.$msn.'\', \''.$txt.'\' )'));
            }
        }
        $oIfx->Free();
        $oReturn->assign("linea","value",$linea);
        $oReturn->assign("grupo","value",'');
        $oReturn->assign("cate","value",'');
        $oReturn->assign("marca","value",'');
    }else{
        $oReturn->assign("linea","value",'');
    }
    
    if(!empty($grupo)){
        // CATEGORIA
        $sql = "select  cate_cod_cate, cate_nom_cate from saecate where
                    cate_cod_empr = $idempresa and
                    cate_cod_grpr = $grupo order by 2 ";    
        $i = 0;    
        $msn = "...Seleccione una Opcion...";
        $txt = 'cate';
        $oReturn->script('borrar_lista( \''.$txt.'\' )');
        if($oIfx->Query($sql)){        
            if ($oIfx->NumFilas() > 0){
                do{
                    $id   = $oIfx->f('cate_cod_cate');
                    $nom  = $oIfx->f('cate_nom_cate');
                    $oReturn->script(('anadir_elemento('.$i.','.$id.', \''.$nom.'\', \''.$txt.'\' )'));
                    $i++;
                }while($oIfx->SiguienteRegistro());
                $oReturn->script(('anadir_elemento('.$i.',"", \''.$msn.'\', \''.$txt.'\' )'));
            }
        }
        $oIfx->Free();
    
        $oReturn->assign("grupo","value",$grupo);
        $oReturn->assign("cate","value",'');
        $oReturn->assign("marca","value",'');
    }else{
        $oReturn->assign("grupo","value",'');
    }
    
    if(!empty($cate)){
        // MARCA
        $sql = "select   marc_cod_marc, marc_des_marc from saemarc where
                    marc_cod_empr = $idempresa and
                    marc_cod_cate = $cate order by 2 ";    
        $i = 0;    
        $msn = "...Seleccione una Opcion...";
        $txt = 'marca';
        $oReturn->script('borrar_lista( \''.$txt.'\' )');
        if($oIfx->Query($sql)){        
            if ($oIfx->NumFilas() > 0){
                do{
                    $id   = $oIfx->f('marc_cod_marc');
                    $nom  = $oIfx->f('marc_des_marc');
                    $oReturn->script(('anadir_elemento('.$i.','.$id.', \''.$nom.'\', \''.$txt.'\' )'));
                    $i++;
                }while($oIfx->SiguienteRegistro());
                $oReturn->script(('anadir_elemento('.$i.',"", \''.$msn.'\', \''.$txt.'\' )'));
            }
        }
        $oIfx->Free();
        $oReturn->assign("cate","value",$cate);
        $oReturn->assign("marca","value",'');
    }else{
        $oReturn->assign("cate","value",'');
    }
    
   
    return $oReturn;
}



// reporte general
function reporte($aForm = '') {
    //Definiciones
    global $DSN_Ifx, $DSN;

    if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

    $oCon = new Dbo;
    $oCon->DSN = $DSN;
    $oCon->Conectar();

    $oIfx = new Dbo;
    $oIfx->DSN = $DSN_Ifx;
    $oIfx->Conectar();
	
	

    $oIfxA = new Dbo;
    $oIfxA->DSN = $DSN_Ifx;
    $oIfxA->Conectar();

	$oIfxB = new Dbo;
    $oIfxB->DSN = $DSN_Ifx;
    $oIfxB->Conectar();
    $oReturn = new xajaxResponse();

//      VARIABLES
    $idempresa 	= $aForm['empresa'];
    $sucursal 	= $aForm['sucursal'];
    $bodega 	= $aForm['bodega'];
    $fecha_ini 	= fecha_informix($aForm['fecha_ini']);
	$linea      = $aForm['linea'];
    $grupo      = $aForm['grupo'];
    $cate       = $aForm['cate'];
    $marca      = $aForm['marca'];
	$cod_prod   = $aForm['codigo_producto'];
	$nom_prod   = $aForm['producto'];
	$condicion  = $aForm['condicion'];
	$cantidad   = $aForm['cantidad'];
	//echo  $condicion.'=='.$cantidad;exit;
	
	$sql_prod = '';
	if(!empty($cod_prod)){
		$sql_prod = " and a.prod_cod_prod = '$cod_prod' ";
	}
	
	$sql_linp = '';
	if(!empty($linea)){
		$sql_linp = " and a.prod_cod_linp = $linea ";
	}
	
	$sql_grpr = '';
	if(!empty($grupo)){
		$sql_grpr = " and a.prod_cod_grpr = $grupo ";
	}
	
	$sql_cate = '';
	if(!empty($cate)){
		$sql_cate = " and a.prod_cod_cate = $cate ";
	}
	
	$sql_marca = '';
	if(!empty($marca)){
		$sql_marca = " and a.prod_cod_marc = $marca ";
	}
	
	
	
    //  LECTURA SUCIA
    //////////////

    // Linea
    $sql = "select  linp_cod_linp , linp_des_linp  from saelinp where
					linp_cod_empr = $idempresa  ";
    unset($array);
    if ($oIfx->Query($sql)) {
        if ($oIfx->NumFilas() > 0) {
            do {
                $array [$oIfx->f('linp_cod_linp')] = $oIfx->f('linp_des_linp');
            } while ($oIfx->SiguienteRegistro());
        }
    }
    
    //seleeciona bodega

   //NOMBRE DE CUENTA
    $sql="select cuen_cod_cuen, cuen_nom_cuen from saecuen where cuen_cod_empr='$idempresa'";	
	if($oIfx->Query($sql)){
		if($oIfx->Numfilas()>0){
			unset($array_cuentas);
			do{
				$array_cuentas[$oIfx->f('cuen_cod_cuen')]=array($oIfx->f('cuen_nom_cuen'));
				//$array_cuentas[$oIfx->f('cuen_nom_cuen')]=array($oIfx->f('cuen_cod_cuen'));
				//var_dump($array_cuentas);
			}while($oIfx->SiguienteRegistro());
		}
	}
	
	// UNIDAD
	$sql = "select unid_cod_unid, unid_nom_unid from saeunid where unid_cod_empr = $idempresa ";
	unset($array_unid);
	$array_unid = array_dato($oIfx, $sql, 'unid_cod_unid', 'unid_nom_unid');
	
    $table_op .='<br>';
    $table_op .='<table class="table table-striped table-condensed table-bordered table-hover" align="center" style="width: 90%; margin-top: 10px; border-collapse: collapse;" border="1">';
    $table_op .='<tr>
					 <td colspan="12" class="bg-primary" align="center">REPORTE INVENTARIO</td>
				</tr>
				<tr>
                    <td align="center">N.-</td>
                    <td align="center">FECHA CORTE</td>
					<td align="center">BODEGA</td>
                    <td align="center">COD. PRODUCTO</td>
					<td align="center">COD. BARRA</td>
                    <td align="center">PRODUCTO</td>                    
                    <td align="center">CUENTA INVENTARIO</td>                    
                    <td align="center">NOMBRE CUENTA</td>                    
					<td align="center">UNIDAD</td>    
					<td align="center">MIN</td>    
					<td align="center">MAX</td>    
                    <td align="center">DISP. CORTE</td>
                    <td align="center">COSTO</td>
                    <td align="center">TOTAL</td>
                </tr>';
	if($bodega!=''){
		 $sql_bode = "select bode_nom_bode from saebode where bode_cod_bode = $bodega";
		$bode_nom_bode = consulta_string($sql_bode, 'bode_nom_bode', $oIfxA, '');
		$sql = "SELECT saeprbo.prbo_cod_prod,  saeprbo.prbo_cod_bode,  saeprbo.prbo_cod_unid,  saeprbo.prbo_cod_empr,  saeprbo.prbo_cod_sucu, prod_cod_barra,
                    saeprbo.prbo_dis_prod,  a.prod_cod_prod,  a.prod_nom_prod,  a.prod_cod_sucu,   a.prod_cod_empr, saeprbo.prbo_cta_inv, a.prod_cod_linp, prbo_sma_prod,
					prbo_smi_prod,
                    COALESCE(( select sum(case d.defi_tip_defi when '5'
                              then c.dmov_can_dmov when '0'
                              then c.dmov_can_dmov when '1'
                              then - c.dmov_can_dmov when '6'
                              then -c.dmov_can_dmov end )
                              from  saedmov c,saeminv b,saedefi d  where
                              b.minv_cod_empr= c.dmov_cod_empr and
                              b.minv_cod_sucu= c.dmov_cod_sucu and
                              b.minv_num_comp= c.dmov_num_comp and
                              c.dmov_cod_prod= saeprbo.prbo_cod_prod and
                              d.defi_cod_empr = b.minv_cod_empr and
                              d.defi_cod_tran = b.minv_cod_tran and
                              b.minv_cod_empr = $idempresa and
                              ( c.dmov_cod_bode = $bodega ) and
                              b.minv_fmov <= '$fecha_ini' ),0)    ingresos,
                    COALESCE(( select sum(case d.defi_tip_defi when '5'
                            then c.dmov_can_dmov when '0'
                            then c.dmov_can_dmov when '1'
                            then - c.dmov_can_dmov when '6'
                            then c.dmov_can_dmov end)
                            from  saedmov c,saeminv b,saedefi d   where
                            b.minv_cod_empr = c.dmov_cod_empr and
                            b.minv_cod_sucu = c.dmov_cod_sucu and
                            b.minv_num_comp = c.dmov_num_comp and
                            c.dmov_cod_prod = saeprbo.prbo_cod_prod and
                            d.defi_cod_empr = b.minv_cod_empr and
                            d.defi_cod_tran = b.minv_cod_tran and
                            b.minv_cod_empr = $idempresa and
                            ( c.dmov_bod_envi = $bodega ) and
                            b.minv_fmov <= '$fecha_ini' ),0)   egresos,
                    COALESCE(( SELECT cost_val_unit  FROM saecost    WHERE
                            cost_cod_bode = $bodega and
                            cost_cod_prod = a.prod_cod_prod AND
                            cost_cod_empr = $idempresa and
                            cost_cod_sucu = $sucursal and
                            cost_cod_cost = (SELECT max(cost_cod_cost)  FROM saecost  WHERE
                                                cost_cod_bode  = $bodega and
                                                cost_cod_prod  =  a.prod_cod_prod and
                                                cost_fec_cost <=  '$fecha_ini' AND
                                                cost_cod_empr  = $idempresa and
                                                cost_cod_sucu  = $sucursal and
                                                cost_val_unit  >  0 ) ),0) costo
                    FROM saeprbo,  saeprod a   WHERE
                    ( a.prod_cod_prod = saeprbo.prbo_cod_prod ) and
                    ( a.prod_cod_empr = saeprbo.prbo_cod_empr ) and
                    ( a.prod_cod_sucu = saeprbo.prbo_cod_sucu ) and
                    ( ( saeprbo.prbo_cod_empr = $idempresa ) AND
                    ( saeprbo.prbo_cod_sucu = $sucursal ) AND
                    ( saeprbo.prbo_cod_bode = $bodega )) 
					$sql_linp $sql_grpr $sql_cate $sql_marca  $sql_prod order by a.prod_nom_prod ";
			$oReturn->alert('Buscando...');
			//echo $sql;exit;
			$i = 1;
			$total = 0;
			$total_cant = 0;
			$total_costo = 0;
			unset($array_tmp);
			if ($oIfx->Query($sql)) {
				if ($oIfx->NumFilas() > 0) {
					do {
						$cod_prod 	= $oIfx->f('prbo_cod_prod');
						$cant 		= $oIfx->f('ingresos') + $oIfx->f('egresos');
						$producto 	= $oIfx->f('prod_nom_prod');
						$costo 		= $oIfx->f('costo');
						$cta 		= $oIfx->f('prbo_cta_inv');
						
						$sql = "select cuen_cod_cuen, cuen_nom_cuen from saecuen where cuen_cod_empr=$idempresa and  cuen_cod_cuen ='$cta'";
						$cuen_nom_cuen = consulta_string($sql, 'cuen_nom_cuen', $oIfxB, '');
						
						$linea 		= $array[$oIfx->f('prod_cod_linp')];
						$unidad     = $array_unid[$oIfx->f('prbo_cod_unid')];
						$cod_barra	= $oIfx->f('prod_cod_barra');
						$prod_sma_prod	= $oIfx->f('prbo_sma_prod');
						$prod_smi_prod	= $oIfx->f('prbo_smi_prod');
						
						$array_fec = explode("/", $fecha_ini);
$m = $array_fec[0];
$d = $array_fec[1];
$a = $array_fec[2];	
$fec = $d.'/'.$m.'/'.$a;
		

						$array_tmp [$i] = array( $cod_prod, $cant, $producto, $costo, $unidad, $bode_nom_bode , $fecha_ini, $idempresa, $sucursal );
						if($condicion=="="){
							if ($cant == $cantidad) {
						
				//echo 	$cuen_nom_cuen ;
	
						
							
								if ($sClass == 'off')
								$sClass = 'on';
								else
									$sClass = 'off';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';                   
								//$table_op .='<td>' . $array_cuentas[$cta] . '</td>';                   
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';                   
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}
						if($condicion==">="){
							if ($cant >= $cantidad) {
							
						
								if ($sClass == 'off')
								$sClass = 'on';
								else
									$sClass = 'off';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>'; 
								//$table_op .='<td>' . $array_cuentas[$cta]  . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>'; 
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}
						if($condicion==">"){
							if ($cant > $cantidad) {
								if ($sClass == 'off')
								$sClass = 'on';
								else
									$sClass = 'off';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';  
								//$table_op .='<td>' . $array_cuentas[$cta]  . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}
						if($condicion=="<="){
							if ($cant <= $cantidad) {
								if ($sClass <= 'off')
								$sClass = 'on';
								else
									$sClass = 'off';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';   
								//$table_op .='<td>' . $array_cuentas[$cta]  . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}	
						if($condicion=="<"){
							if ($cant < $cantidad) {
								if ($sClass < 'off')
								$sClass = 'on';
								else
									$sClass = 'off';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';   
								//$table_op .='<td>' .$array_cuentas[$cta]  . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}	
						if($condicion=="between"){
							$cantidad2=$aForm['cantidad_'];
							if (($cant >= $cantidad)&&($cant <= $cantidad2)){
								if ($sClass < 'off')
								$sClass = 'on';
								else
									$sClass = 'offa';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';   
								//$table_op .='<td>' .$array_cuentas[$cta]  . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}
						if($condicion==""){
							
								if ($sClass < 'off')
								$sClass = 'on';
								else
									$sClass = 'offa';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';   
								//$table_op .='<td>' . $array_cuentas[$cta]  . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							
						}
						
						
						
					}while ($oIfx->SiguienteRegistro());
					$table_op .='<tr height="20" class="' . $sClass . '"
													onMouseOver="javascript:this.className=\'link\';"
													onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
					$table_op .='<td align="right"></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td align="right" class="fecha_letra" style="color: red;">TOTAL:</td>';
					$table_op .='<td align="right" class="fecha_letra" style="color: red;">' .  number_format($total_cant, 2 , ',', '.')  . '</td>';
					$table_op .='<td align="right" class="fecha_letra" style="color: red;">' . number_format($total_costo, 2 , ',', '.') . '</td>';
					$table_op .='<td align="right" class="fecha_letra" style="color: red;">' . number_format($total, 2 , ',', '.') . '</td>';
					$table_op .='</tr>';
				}else {
					$table_op = '<span class="fecha_letra">Sin Datos...</span>';
				}
			}
	}else{
		$sql="select * from saebode where bode_cod_empr=' $idempresa'";
		$oReturn->alert('Buscando...');
		//echo $sql;exit;
		$i = 1;
		$total = 0;
		$total_cant = 0;
		$total_costo = 0;
		if($oIfxA->Query($sql)){
			if($oIfxA->Numfilas()>0){
				do{
					$bodega=$oIfxA->f('bode_cod_bode');
					$bode_nom_bode=$oIfxA->f('bode_nom_bode');
					$sql = "SELECT saeprbo.prbo_cod_prod,  saeprbo.prbo_cod_bode,  saeprbo.prbo_cod_unid,  saeprbo.prbo_cod_empr,  saeprbo.prbo_cod_sucu, prod_cod_barra,
                    saeprbo.prbo_dis_prod,  a.prod_cod_prod,  a.prod_nom_prod,  a.prod_cod_sucu,   a.prod_cod_empr, saeprbo.prbo_cta_inv, a.prod_cod_linp, prbo_sma_prod,
					prbo_smi_prod,
                    COALESCE(( select sum(case d.defi_tip_defi when '5'
                              then c.dmov_can_dmov when '0'
                              then c.dmov_can_dmov when '1'
                              then - c.dmov_can_dmov when '6'
                              then -c.dmov_can_dmov end )
                              from  saedmov c,saeminv b,saedefi d  where
                              b.minv_cod_empr= c.dmov_cod_empr and
                              b.minv_cod_sucu= c.dmov_cod_sucu and
                              b.minv_num_comp= c.dmov_num_comp and
                              c.dmov_cod_prod= saeprbo.prbo_cod_prod and
                              d.defi_cod_empr = b.minv_cod_empr and
                              d.defi_cod_tran = b.minv_cod_tran and
                              b.minv_cod_empr = $idempresa and
                              ( c.dmov_cod_bode = $bodega ) and
                              b.minv_fmov <= '$fecha_ini' ),0)    ingresos,
                    COALESCE(( select sum(case d.defi_tip_defi when '5'
                            then c.dmov_can_dmov when '0'
                            then c.dmov_can_dmov when '1'
                            then - c.dmov_can_dmov when '6'
                            then c.dmov_can_dmov end)
                            from  saedmov c,saeminv b,saedefi d   where
                            b.minv_cod_empr = c.dmov_cod_empr and
                            b.minv_cod_sucu = c.dmov_cod_sucu and
                            b.minv_num_comp = c.dmov_num_comp and
                            c.dmov_cod_prod = saeprbo.prbo_cod_prod and
                            d.defi_cod_empr = b.minv_cod_empr and
                            d.defi_cod_tran = b.minv_cod_tran and
                            b.minv_cod_empr = $idempresa and
                            ( c.dmov_bod_envi = $bodega ) and
                            b.minv_fmov <= '$fecha_ini' ),0)   egresos,
                    COALESCE(( SELECT cost_val_unit  FROM saecost    WHERE
                            cost_cod_bode = $bodega and
                            cost_cod_prod = a.prod_cod_prod AND
                            cost_cod_empr = $idempresa and
                            cost_cod_sucu = $sucursal and
                            cost_cod_cost = (SELECT max(cost_cod_cost)  FROM saecost  WHERE
                                                cost_cod_bode  = $bodega and
                                                cost_cod_prod  =  a.prod_cod_prod and
                                                cost_fec_cost <=  '$fecha_ini' AND
                                                cost_cod_empr  = $idempresa and
                                                cost_cod_sucu  = $sucursal and
                                                cost_val_unit  >  0 ) ),0) costo
                    FROM saeprbo,  saeprod a   WHERE
                    ( a.prod_cod_prod = saeprbo.prbo_cod_prod ) and
                    ( a.prod_cod_empr = saeprbo.prbo_cod_empr ) and
                    ( a.prod_cod_sucu = saeprbo.prbo_cod_sucu ) and
                    ( ( saeprbo.prbo_cod_empr = $idempresa ) AND
                    ( saeprbo.prbo_cod_sucu = $sucursal ) AND
                    ( saeprbo.prbo_cod_bode = $bodega )) 
					$sql_linp $sql_grpr $sql_cate $sql_marca  $sql_prod order by a.prod_nom_prod ";
				
				unset($array_tmp);
					if ($oIfx->Query($sql)) {
						if ($oIfx->NumFilas() > 0) {
							do {
								$cod_prod 	= $oIfx->f('prbo_cod_prod');
								$cant 		= $oIfx->f('ingresos') + $oIfx->f('egresos');
								$producto 	= $oIfx->f('prod_nom_prod');
								$costo 		= $oIfx->f('costo');
								$cta 		= $oIfx->f('prbo_cta_inv');
								$linea 		= $array[$oIfx->f('prod_cod_linp')];
								$unidad     = $array_unid[$oIfx->f('prbo_cod_unid')];
								$cod_barra	= $oIfx->f('prod_cod_barra');
								$prod_sma_prod	= $oIfx->f('prbo_sma_prod');
								$prod_smi_prod	= $oIfx->f('prbo_smi_prod');
								

								$array_tmp [$i] = array( $cod_prod, $cant, $producto, $costo, $unidad, $bode_nom_bode , $fecha_ini, $idempresa, $sucursal );
								
								
							if($condicion=="="){
							if ($cant == $cantidad) {
								if ($sClass == 'off')
								$sClass = 'on';
								else
									$sClass = 'off';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';  
								//$table_op .='<td>' .$array_cuentas[$cta]  . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}
						if($condicion==">="){
							if ($cant >= $cantidad) {
								if ($sClass == 'off')
								$sClass = 'on';
								else
									$sClass = 'off';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';     
								//$table_op .='<td>' . $array_cuentas[$cta] . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}
						if($condicion==">"){
							if ($cant > $cantidad) {
								if ($sClass == 'off')
								$sClass = 'on';
								else
									$sClass = 'off';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';       
								//$table_op .='<td>' . $array_cuentas[$cta] . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}
						if($condicion=="<="){
							if ($cant <= $cantidad) {
								if ($sClass <= 'off')
								$sClass = 'on';
								else
									$sClass = 'off';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';       
								//$table_op .='<td>' . $array_cuentas[$cta] . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}	
						if($condicion=="<"){
							if ($cant < $cantidad) {
								if ($sClass < 'off')
								$sClass = 'on';
								else
									$sClass = 'off';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';  
								//$table_op .='<td>' . $array_cuentas[$cta]  . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}	
						if($condicion=="between"){
							$cantidad2=$aForm['cantidad_'];
							if (($cant >= $cantidad)&&($cant <= $cantidad2)){
								if ($sClass < 'off')
								$sClass = 'on';
								else
									$sClass = 'offa';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';   
								//$table_op .='<td>' . $array_cuentas[$cta]  . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							}
						}
						if($condicion==""){
							
								if ($sClass < 'off')
								$sClass = 'on';
								else
									$sClass = 'offa';
								$table_op .='<tr height="20" class="' . $sClass . '"
												onMouseOver="javascript:this.className=\'link\';"
												onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
								$table_op .='<td align="center">' . $i . '</td>';
								$table_op .='<td>' . $fec . '</td>';
								$table_op .='<td>' . $bodega . ' | '.$bode_nom_bode.'</td>';
								$table_op .='<td>' . $cod_prod . '</td>';
								$table_op .='<td>' . $cod_barra . '</td>';
								$table_op .='<td>' . $producto . '</td>';                   
								$table_op .='<td>' . $cta  . '</td>';   
								//$table_op .='<td>' . $array_cuentas[$cta]  . '</td>';								
								$table_op .='<td>' .  $cuen_nom_cuen . '</td>';
								$table_op .='<td>' . $unidad . '</td>';    
								$table_op .='<td>'. number_format($prod_smi_prod, 2 , ',', '.').'</td>';    
								$table_op .='<td>'. number_format($prod_sma_prod, 2 , ',', '.')	.'</td>';    
								$table_op .='<td align="right">' .  number_format($cant, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format($costo, 2 , ',', '.'). '</td>';
								$table_op .='<td align="right">' .  number_format(($costo * $cant), 2 , ',', '.'). '</td>';
								$table_op .='</tr>';
								$total_cant = $total_cant + $cant;
								$total_costo = $total_costo + $costo;
								$total += $costo * $cant;
								$i++;
							
						}
						
								
							}while ($oIfx->SiguienteRegistro());
							
						}
					}
				}while($oIfxA->SiguienteRegistro());
				$table_op .='<tr height="20" class="' . $sClass . '"
													onMouseOver="javascript:this.className=\'link\';"
													onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
					$table_op .='<td align="right"></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td></td>';
					$table_op .='<td align="right" class="fecha_letra" style="color: red;">TOTAL:</td>';
					$table_op .='<td align="right" class="fecha_letra" style="color: red;">' . number_format($total_cant, 2 , ',', '.') . '</td>';
					$table_op .='<td align="right" class="fecha_letra" style="color: red;">' . number_format($total_costo, 2 , ',', '.') . '</td>';
					$table_op .='<td align="right" class="fecha_letra" style="color: red;">' . number_format($total, 2 , ',', '.') . '</td>';
					$table_op .='</tr>';
			}else {
					$table_op = '<span class="fecha_letra">Sin Datos...</span>';
				}
		}
	}
    
    $oIfx->Free();
	
	unset($_SESSION['U_ARRAY']);
	$_SESSION['U_ARRAY'] = $array_tmp;
	 
    //Armado Cabecera Excel
    unset($_SESSION['sHtml_cab']);
    unset($_SESSION['sHtml_det']);
    $sHtml_exe_p ='<table align="center" border="0" cellpadding="2" cellspacing="1" width="100%">
                            <tr>
                                    <th colspan = "10">REPORTE INVENTARIO</th>
                            </tr>
                            <tr></tr><tr></tr>
                                    <th colspan="2">Fecha Reporte:</th>
                                    <td align="left">' . date("d-m-Y") . '</td>
                                    <td></td>
                            </tr>
                            <tr></tr><tr></tr>
                        </table>';

    $_SESSION['sHtml_cab'] = $sHtml_exe_p;
    $_SESSION['sHtml_det'] = $table_op;

    $oReturn->assign("divReporte", "innerHTML", $table_op);

    return $oReturn;
}

function fecha_informix($fecha) {
    $m = substr($fecha, 5, 2);
    $y = substr($fecha, 0, 4);
    $d = substr($fecha, 8, 2);

    return ( $m . '/' . $d . '/' . $y );
}

function fecha_mysql($fecha) {
    $fecha_array = explode('/', $fecha);
    $m = $fecha_array[0];
    $y = $fecha_array[2];
    $d = $fecha_array[1];

    return ( $d . '/' . $m . '/' . $y );
}
function verificaCondicion($aForm = '') {
    //Definiciones
    global $DSN_Ifx, $DSN;

    if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

    $ifu = new Formulario;
    $ifu->DSN = $DSN_Ifx;

    $oReturn = new xajaxResponse();

    $ifu->AgregarCampoNumerico('cantidad_', 'Cant|left', false, '', 50, 9);

    $campo = $ifu->ObjetoHtml('cantidad_');

    $oReturn->assign("campoCantidad_", "innerHTML", $campo);

    return $oReturn;
}

/* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
/* PROCESO DE REQUEST DE LAS FUNCIONES MEDIANTE AJAX NO MODIFICAR */
$xajax->processRequest();
/* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
?>