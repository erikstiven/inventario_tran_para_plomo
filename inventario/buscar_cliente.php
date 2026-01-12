<?
include_once('../../Include/config.inc.php');
include_once(path(DIR_INCLUDE) . 'conexiones/db_conexion.php');
include_once(path(DIR_INCLUDE) . 'comun.lib.php');
include_once('../_Modulo.inc.php');
include_once(HEADER_MODULO);

if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="stylesheet" type = "text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/general.css">
            <link href="<?=$_COOKIE["JIREH_INCLUDE"]?>Clases/Formulario/Css/Formulario.css" rel="stylesheet" type="text/css"/>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>LISTADO DE PACIENTES</title>
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
                function datos(a, b, c, d, e, f, g, h, i) {
                    if (e == '--') {
                        alert('Debe asignar cama al Paciente para continuar...')
                    } else {
                        if (g == 'S') {
                            alert('Paciente dado de Alta el ' + h + ' no puede generar Pedidos');
                        } else {
                            window.opener.document.form1.codigo.value = a;
                            window.opener.document.form1.cliente_nombre.value = b;
                            window.opener.document.form1.ruc.value = c;
                            window.opener.document.form1.historia.value = d;
                            window.opener.document.form1.cama.value = e;
                            window.opener.document.form1.admision.value = f;
                            window.opener.document.form1.id_cama.value = i;
                        }
                    }
                    close();

                }
            </script>
    </head>

    <body>

        <?
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

        $oIfx = new Dbo;
        $oIfx->DSN = $DSN_Ifx;
        $oIfx->Conectar();

        $oCon = new Dbo;
        $oCon->DSN = $DSN;
        $oCon->Conectar();

        $oConA = new Dbo;
        $oConA->DSN = $DSN;
        $oConA->Conectar();

        $idempresa = $_SESSION['U_EMPRESA'];
        $cliente_nom = $_GET['cliente'];
        $ruc = $_GET['ruc'];
        $opcion = $_GET['opcion'];

        //  LECTURA SUCIA
        //////////////

        //$codigo_busca = strtr(strtoupper($codigo), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");

        if ($opcion == 1) {
            // ruc
            $sql_tmp = "and ruc_clpv like upper('%$ruc%') ";
        } elseif ($opcion == 2) {
            // nombre
            $sql_tmp = "and nom_clpv like upper('%$cliente_nom%') ";
        }


        $sql = "select id_dato, id_clpv, alta, nom_clpv, ruc_clpv, id_cama, fecha_alta
                from datos_clpv
                where id_empresa = $idempresa and
                alta = 'N'
                $sql_tmp order by 2";

        //echo $sql;
        ?>
    </body>
    <div id="contenido">
        <?
        $cont = 1;

        echo '<fieldset style="border:#999999 1px solid; padding:2px; text-align:center; width:95%; margin-top: 7px;">
              <legend class="Titulo" id="lgTitulo">LISTADO DE PACIENTES</legend>';

        echo '<table align="center" border="0" cellpadding="2" cellspacing="1" width="98%">';
        echo '<tr>
                <th>N�</th>
                <th>CODIGO</th>
                <th>PACIENTE</th>
                <th>IDENTIFICACION</th>
                <th>HISTORIA CLINICA</th>
                <th>N� ADMISI�N</th>
                <th>CAMA</th>
                <th>ALTA</th>
                <th>SELECCIONAR</th>
		</tr>';

        if ($oCon->Query($sql)) {
            if ($oCon->NumFilas() > 0) {
                do {
                    $codigo = ($oCon->f('id_clpv'));
                    $paciente = ($oCon->f('nom_clpv'));
                    $paciente_ruc = ($oCon->f('ruc_clpv'));
                    $id_cama = ($oCon->f('id_cama'));
                    $alta = ($oCon->f('alta'));
                    $admision = ($oCon->f('id_dato'));
                    $fecha_alta = ($oCon->f('fecha_alta'));

                    $sql = "select clpv_secu_hicl from saeclpv where clpv_cod_clpv = $codigo";

                    $historia = consulta_string($sql, 'clpv_secu_hicl', $oIfx, '--');


                    $sql_cama = "select cama_nom_cama from cama where cama_cod_cama = $id_cama";

                    $cama = consulta_string($sql_cama, 'cama_nom_cama', $oConA, '--');


                    if ($sClass == 'off')
                        $sClass = 'on';
                    else
                        $sClass = 'off';
                    echo '<tr height="20" class="' . $sClass . '"
                                        onMouseOver="javascript:this.className=\'link\';"
                                        onMouseOut="javascript:this.className=\'' . $sClass . '\';">';
                    echo '<td>' . $cont . '</td>';
                    echo '<td width="100">';
                    ?>
                    <? echo $codigo; ?>
                    <?
                    echo '</td>';
                    echo '<td>'
                    ?>
                    <? echo $paciente; ?>
                    <?
                    echo '</td>';
                    echo '<td>';
                    ?>
                    <? echo $paciente_ruc; ?>
                    <?
                    echo '</td>';
                    echo '<td>';
                    ?>
                    <? echo $historia; ?>

                    <?
                    echo '</td>';
                    echo '<td>';
                    ?>
                    <? echo $admision; ?>

                    <?
                    echo '</td>';
                    echo '<td>';
                    ?>
                    <? echo $cama; ?>

                    <?
                    echo '</td>';
                    echo '<td>';
                    ?>
                    <? echo $alta; ?>

                    <?
                    echo '</td>';
                    echo '<td>';
                    ?>
                    <a href="#" onclick="datos('<? echo $codigo; ?>', '<? echo $paciente; ?>', '<? echo $paciente_ruc ?>',
                                    '<? echo $historia ?>', '<? echo $cama ?>', '<? echo $admision ?>',
                                    '<? echo $alta ?>', '<? echo $fecha_alta ?>', '<? echo $id_cama ?>')">
                           <?
                           echo '<img src="' . $_COOKIE['JIREH_IMAGENES'] . 'iconos/accept_1.png"/></a>';
                           echo '</td>';
                           echo '</tr>';
                           echo '<tr>';
                           echo '</tr>';
                           echo '<tr>';
                           echo '</tr>';
                           echo '<tr>';
                           echo '</tr>';
                           echo '<tr>';
                           echo '</tr>';
                           $cont++;
                       }while ($oCon->SiguienteRegistro());
                   }else {
                       echo '<span class="fecha_letra">Sin Datos....</span>';
                   }
               }
               $oCon->Free();
               echo '<tr><td colspan="3">Se mostraron ' . ($cont - 1) . ' Registros</td></tr>';
               echo '</table>';
               ?>
               <?

               //echo $cod_producto;
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

