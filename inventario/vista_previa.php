<?
include_once('../../Include/config.inc.php');
include_once(path(DIR_INCLUDE).'conexiones/db_conexion.php');
include_once(path(DIR_INCLUDE).'comun.lib.php');

if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type = "text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/general.css">
<link href="<?=$_COOKIE["JIREH_INCLUDE"]?>Clases/Formulario/Css/Formulario.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type = "text/css" href="css/estilo.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DISPONIBLES</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
	color: #000000;
	font-weight: bold;
}
.Estilo2 {font-size: 10px; font-family: Georgia, "Times New Roman", Times, serif; color: #000000; font-weight: bold; }
.Estilo3 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo4 {
	font-size: 16px;
	font-weight: bold;
	color:#000000;
}
.fecha {
	font-family: Tahoma, Arial, sans-serif;
	font-size: 34px;
	font-weight: bold;
	color:#000000;
}
-->
</style>

<script>
	function formato(){
		document.getElementById('dos').style.display= "none"; 
		window.print();	
	}
</script>
</head>

<body>

<?
	$oCnx = new Dbo ( );
	$oCnx->DSN = $DSN;
	$oCnx->Conectar ();
	
	$oIfx = new Dbo;
	$oIfx -> DSN = $DSN_Ifx;
	$oIfx -> Conectar();
	
	$array_tmp    = $_SESSION['U_ARRAY'];	
	//$serial_minv = 38687;
	
	// USAUURO
	$sql = "select u.USUARIO_ID, u.USUARIO_USER from usuario u ";
	unset($array_user);
	$array_user = array_dato($oCnx, $sql, 'usuario_id', 'usuario_user');
        
		
	if(count($array_tmp)>0){
		// $cod_prod, $cant, $producto, $costo, $unidad, $bode_nom_bode , $fecha_ini, $idempresa, $sucursal
		foreach($array_tmp as $val){
			$idempresa  = $val[7];
			$idsucursal = $val[8];
			$fecha      = $val[6];
			$bode_nom   = $val[5];
		}
		
		$sql = "select empr_nom_empr from saeempr where empr_cod_empr = $idempresa ";
		$empr_nom = consulta_string_func($sql, 'empr_nom_empr', $oIfx, '');
		
		$sql = "select sucu_nom_sucu from saesucu where sucu_cod_empr = $idempresa and sucu_cod_sucu = $idsucursal ";
		$sucu_nom = consulta_string_func($sql, 'sucu_nom_sucu', $oIfx, '');
?> 

<div id="uno">

<table width="95%" height="95%" border="0" align="center">
  <tr>
    <td height="5">&nbsp;</td>
    <td height="20" colspan="2"><div align="center" class="fecha_balance"><?=$empr_nom?></div></td>
  </tr>
  <tr>
      <td colspan="4" class="Estilo2" align="left" width="90%">
	
		<?
			
			echo '<fieldset style="border:#999999 1px solid; padding:2px; text-align:center; width:98%; background-color:#FFFFFF ">';
			echo '<table align="center" border="0" cellpadding="2" cellspacing="1" width="99%" class="footable">';
                       
			
                            echo '<tr>
                                        <td class="fecha_balance" scope="row" colspan="9">DISPONIBLES COSTOS</td>
                                  </tr>';
                            echo '<tr>
                                        <td class="fecha_balance" scope="row" align="left">SUCURSAL:</td>
                                        <td class="fecha_balance" colspan="3" align="left">'.$sucu_nom.'</td>
                                        <td class="fecha_balance" colspan="2" align="left">BODEGA: '.$bode_nom.'</td>
                                  </tr>';
                            echo '<tr>
                                        <td class="fecha_balance" scope="row" align="left">FECHA CORTE:</td>
                                        <td colspan="5" align="left"> '.$fecha.'</td>
                                  </tr>';
                            echo '<tr>
                                        <td colspan="6"></td>
                                  </tr>';
                            echo '<tr height="25">
                                        <th class="diagrama">CODIGO</th>
                                        <th class="diagrama">PRODUCTO</th>
                                        <th class="diagrama">UNIDAD</th>
                                        <th class="diagrama">DISP. CORTE</th>
                                        <th class="diagrama">COSTO</th>
                                        <th class="diagrama">TOTAL</th>
                                  </tr>';
							$total    = 0;
							$tot_cant = 0;
							foreach($array_tmp as $val){
								$idempresa  = $val[7];
								$idsucursal = $val[8];
								$fecha      = $val[6];
								$bode_nom   = $val[5];
								$unidad     = $val[4];
								$costo      = $val[3];
								$producto   = $val[2];
								$cant       = $val[1];
								$cod_prod   = $val[0];
								
								echo '<tr>';
								echo '<td align="left">'.$cod_prod.'</td>';
								echo '<td align="left">'.htmlentities($producto).'</td>';
								echo '<td align="left">'.$unidad.'</td>';                                               
								echo '<td align="right">'.$cant.'</td>';
								echo '<td align="right">'.$costo.'</td>';
								echo '<td align="right">'.($cant*$costo).'</td>';
								echo '</tr>';
								
								$total += $cant*$costo;
								$tot_cant += $cant;
							}
							
                            
                            
				
                            echo '<tr>';
							echo '<td align="left"></td>';
							echo '<td align="left"></td>';
							echo '<td align="right" class="fecha_letra">TOTAL:</td>';
							echo '<td align="right" class="fecha_letra">'.$tot_cant.'</td>';
							echo '<td></td>';							
							echo '<td align="right" class="fecha_letra">'.$total.'</td>';
					echo '</tr>';
			echo '</table>';
		?>	</td>
    </tr>
  <tr>
    <td colspan="4" class="Estilo2" align="left">&nbsp;</td>
  </tr>
 
  <tr>
    <td colspan="4" class="Estilo2" align="left">&nbsp;</td>
  </tr>
  
</table>

</div>



<div id="dos">

<table width="464" border="0" align="center">
  <tr>
    <td align="center"><label>
      <input name="Submit2" type="submit" class="Estilo2" value="Imprimir" onclick="formato();" />
    </label></td>
  </tr>
</table>
<?

	}else{
	
		echo '<div align="center" class="Estilo1">ERROR!!!! AUN NO INGRESA ORDEN COMPRA.... </div>';
	}

?>

</div>
</body>
</html>