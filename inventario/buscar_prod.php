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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LISTA DE DESCRIPCION</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 12px;
	font-family: Georgia, "Times New Roman", Times, serif;
	color: #000000;
}
-->
</style>

<script>
	function datos( a, b, c, d, e ){
		window.opener.document.form1.codigo_producto.value = a;
		window.opener.document.form1.producto.value = b;
                window.opener.document.form1.costo.value = e;
                window.opener.document.form1.tprod.value = d;
		close();
	}
</script>
</head>

<body>

<?
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

        $oIfx = new Dbo;
	$oIfx -> DSN = $DSN_Ifx;
	$oIfx -> Conectar();

        $oIfxA = new Dbo;
	$oIfxA -> DSN = $DSN_Ifx;
	$oIfxA -> Conectar();

	$idempresa =  $_SESSION['U_EMPRESA'];
        $sucursal =  $_GET['sucursal'];
	$prod_nom = $_GET['producto'];
        $codigo_nom = $_GET['codigo'];
        $opcion = $_GET['opcion'];
        $bodega       = $_GET['bodega'];
        $fecha        = fecha_informix_func($_GET['fecha']);
        

        //  LECTURA SUCIA
        //////////////

        if($opcion==1){
            // producto
            $sql_tmp = " and p.prod_nom_prod like upper('%$prod_nom%') ";
        }elseif($opcion==2){
            // codigo
            $sql_tmp = " and p.prod_cod_prod like upper('%$codigo_nom%') ";
        }

	$sql = "select pr.prbo_cod_prod, p.prod_nom_prod, pr.prbo_dis_prod , pr.prbo_pco_prod, prod_cod_tpro 
                    from saeprbo pr, saeprod p where
                    p.prod_cod_prod = pr.prbo_cod_prod and
                    p.prod_cod_empr = $idempresa and
                    p.prod_cod_sucu = $sucursal and
                    pr.prbo_cod_empr = $idempresa and
                    pr.prbo_cod_bode = '$bodega'
                    $sql_tmp  order by  2";
        
       //cho $sql;
?>
</body>
<div id="contenido">
<?
	$cont=1;
        echo '<fieldset align="center" style="border:#999999 1px solid; padding:2px; width:100%;">
              <legend class="Titulo">LISTA DE PRODUCTOS</legend>';
	echo '<table align="center" cellpadding="2" cellspacing="1" width="98%">';
	echo '<tr>
            <th align="left" bgcolor="#EBF0FA" class="titulopedido">ID</th>
            <th align="left" bgcolor="#EBF0FA" class="titulopedido">CODIGO ITEM</th>
            <th align="left" bgcolor="#EBF0FA" class="titulopedido">PRODUCTO</th>
            <th align="left" bgcolor="#EBF0FA" class="titulopedido">STOCK</th>
            <th align="left" bgcolor="#EBF0FA" class="titulopedido">TIPO</th>
            <th align="left" bgcolor="#EBF0FA" class="titulopedido">PRECIO</th>
            </tr>';

    if ($oIfx->Query($sql)){
        if( $oIfx->NumFilas() > 0 ){
		do {
			$codigo = ($oIfx->f('prbo_cod_prod'));
			$nom_prod = htmlentities($oIfx->f('prod_nom_prod'));
			$stock = $oIfx->f('prbo_dis_prod');
                        $costo = round($oIfx->f('prbo_pco_prod'),3);
                        $tprod = $oIfx->f('prod_cod_tpro');

                        $sql_ = "select ppr_pre_raun from saeppr where ppr_cod_prod = '$codigo'";
                        
                        //echo $sql_;
                        
                        $precio = consulta_string($sql_, 'ppr_pre_raun', $oIfxA, '0.0');

                        if ($sClass=='off') $sClass='on'; else $sClass='off';
			echo '<tr height="20" class="'.$sClass.'"
                                        onMouseOver="javascript:this.className=\'link\';"
                                        onMouseOut="javascript:this.className=\''.$sClass.'\';">';
				echo '<td>'.$cont.'</td>';
				echo '<td width="100">';
	?>
    				<a href="#" onclick="datos('<? echo $codigo;?>','<? echo $nom_prod;?>', '<? echo $costo;?>', '<? echo $tprod;?>', '<? echo $precio;?>')">
					             <? echo $codigo;?> </a>
    <?
				echo '</td>';
				echo '<td>'
	?>
    				<a href="#" onclick="datos('<? echo $codigo;?>','<? echo $nom_prod;?>', '<? echo $costo;?>', '<? echo $tprod;?>', '<? echo $precio;?>')">
						     <? echo $nom_prod;?></a>
    <?
				echo '</td>';
                                echo '<td>';
    ?>
                                <a href="#" onclick="datos('<? echo $codigo;?>','<? echo $nom_prod;?>', '<? echo $costo;?>', '<? echo $tprod;?>', '<? echo $precio;?>')">
						     <? echo $stock;?></a>
    <?
                                echo '</td>';  
                                                             echo '<td>';
    ?>
                                <a href="#" onclick="datos('<? echo $codigo;?>','<? echo $nom_prod;?>', '<? echo $costo;?>', '<? echo $tprod;?>', '<? echo $precio;?>')">
						     <? echo $tprod;?></a>
    <?
                                echo '</td>';  
                                echo '<td>';
    ?>                                 
                                <a href="#" onclick="datos('<? echo $codigo;?>','<? echo $nom_prod;?>', '<? echo $costo;?>', '<? echo $tprod;?>', '<? echo $precio;?>')">
						     <? echo $precio;?></a>
    <?
                                echo '</td>';  
    ?>
    <?
			echo '</tr>';
			echo '<tr>'; echo '</tr>'; 		echo '<tr>'; echo '</tr>';
			echo '<tr>'; echo '</tr>'; 		echo '<tr>'; echo '</tr>';
		$cont++;
		}while($oIfx->SiguienteRegistro());
            }else{
                echo '<span class="fecha_letra">Sin Datos....</span>';
            }
	}
	$oIfx->Free();
	echo '<tr><td colspan="3">Se mostraron '.($cont-1).' Registros</td></tr>';
	echo '</table>';
        echo '</fieldset>';
	//echo $cod_producto;
?>
    <?
    function consulta_string($sql, $campo, $Conexion, $defecto) {

    $total_mes_stock = 0;
    if ($Conexion->Query($sql)) {
        if ($Conexion->NumFilas() > 0) {
            $total_mes_stock = $Conexion->f($campo);
            if (empty($total_mes_stock)) {
                $total_mes_stock = $defecto;
            }
        } else {
            $total_mes_stock = $defecto;
        }
    }
    $Conexion->Free();
    return $total_mes_stock;
}
?>

</div>
</html>

