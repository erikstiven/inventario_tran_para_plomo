<?php	
	include_once('../../Include/config.inc.php');
	include_once(path(DIR_INCLUDE).'conexiones/db_conexion.php');
	include_once(path(DIR_INCLUDE).'comun.lib.php');

	if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    global $DSN_Ifx, $DSN;

	$oIfx = new Dbo;
    $oIfx->DSN = $DSN_Ifx;
    $oIfx->Conectar();

    //varibales de sesion
    $idempresa  = $_SESSION['U_EMPRESA'];
    $idsucursal = $_SESSION['U_SUCURSAL'];
    
    if (isset($_REQUEST['nomClpv']))
        $nomClpv = $_REQUEST['nomClpv'];
    else
        $nomClpv = null;

    //lectura sucia
    //////////////

	// SUCURSAL
	$sql = "select sucu_cod_sucu, sucu_nom_sucu from saesucu where sucu_cod_empr = $idempresa ";
	unset($array_sucu);
	$array_sucu = array_dato($oIfx, $sql, 'sucu_cod_sucu', 'sucu_nom_sucu');
	
    $tabla = '';

    $sql = "select  tran_cod_tran, tran_des_tran , defi_tip_defi, tran_cod_sucu, defi_cod_defi
					from saedefi, saetran where
					tran_cod_tran = defi_cod_tran and
					defi_cod_empr = $idempresa and
					tran_cod_empr = $idempresa and
					defi_cod_modu = 10
					order by tran_cod_sucu,1 ";
    if($oIfx->Query($sql)){
    	if($oIfx->NumFilas() > 0){
    		$sHtmlEstado = '';
    		do{
    			$tran_cod  = $oIfx->f('tran_cod_tran');
    			$defi_tip  = $oIfx->f('defi_tip_defi');
				$tran_sucu = $oIfx->f('tran_cod_sucu');
				$defi_cod  = $oIfx->f('defi_cod_defi');
				$sucu_nom  = $array_sucu[$tran_sucu];
                $tran_nom  = utf8_encode($oIfx->f('tran_des_tran'));

				$img = '<div align=\"center\"> <div class=\"btn btn-warning btn-sm\" onclick=\"seleccionaItem(\'' . $tran_cod . '\', \'' . $defi_cod . '\')\"><span class=\"glyphicon glyphicon-pencil\"><span></div> </div>';

    			$tabla.='{
						  "tran_cod_tran":"'.$tran_cod.'",
						  "defi_tip_defi":"'.$defi_tip.'",						  
						  "tran_des_tran":"'.$tran_nom.'",
						  "tran_cod_sucu":"'.$sucu_nom.'",
						  "selecciona":"'.$img.'"
				},';

			}while($oIfx->SiguienteRegistro());
    	}
	}
	$oIfx->Free();

	//eliminamos la coma que sobra
	$tabla = substr($tabla,0, strlen($tabla) - 1);

	echo '{"data":['.$tabla.']}';
	
?>