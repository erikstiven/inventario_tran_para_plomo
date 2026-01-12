<?php
require("_Ajax.comun.php"); // No modificar esta linea

function genera_formulario($sAccion = 'nuevo', $aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;

	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$ifu = new Formulario;
	$ifu->DSN = $DSN_Ifx;

	$fu = new Formulario;
	$fu->DSN = $DSN;

	$oReturn = new xajaxResponse();

	try {

		//lectura sucia
		//////////////

		$idempresa  = $_SESSION['U_EMPRESA'];
		$idsucursal = $_SESSION['U_SUCURSAL'];

		// IMPUESTO POR PAIS
		$array_imp = $_SESSION['U_EMPRESA_IMPUESTO'];


		//$oReturn->alert('kakkaia');

		switch ($sAccion) {
			case 'nuevo':

				$ifu->AgregarCampoTexto('tran_cod', 'Codigo|left', true, '', 100, 5, true);
				$ifu->AgregarCampoTexto('tran_nom', 'Nombre|left', true, '', 450, 200, true);
				$ifu->AgregarCampoTexto('tran_secu', 'Secuencia tipo Movimiento|left', true, '000000000', 200, 9, true);
				$ifu->AgregarCampoListaSQL('tran_sucu', 'Sucursal|left', "select sucu_cod_sucu, sucu_nom_sucu from saesucu where
                                                                                sucu_cod_empr = $idempresa", true, '200', 'auto', true);
				$ifu->AgregarCampoCheck('defi_mos_bode', 'Mostrar Bodega|left', false, 'N');
				$ifu->AgregarCampoNumerico('defi_fact_defi', 'Factor|left', false, '', 100, 50, true);

				$ifu->AgregarCampoCheck('defi_prec_vent', 'Precio Venta|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_otr_defi', 'Otros Recargos|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_ret_defi', 'Retenciones|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_prd_defi', 'ATS|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_nov_mos',  'Novedades Prod|left', false, 'N');

				$ifu->AgregarCampoCheck('defi_can_defi',  'Cantidad S/N|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_pro_prov',  'Product. x Prov.|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_lot_clpv',  'Control de Compra|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_ctc_defi',  'Cuenta contable|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_det_dmov',  'Detalle|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_iva_incl',  '' . $array_imp['IVA'] . ' Incluido|left', false, 'N');

				$ifu->AgregarCampoNumerico('defi_num_det', 'No. Registros:|left', true, '', 40, 2, true);
				$ifu->AgregarCampoNumerico('defi_dsc_defi', 'No. Descuentos:|left', false, '', 40, 2, true);

				$ifu->AgregarCampoCheck('defi_prc_defi',  'Precio|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_lot_defi',  'Lotes|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_cco_defi',  'Centro Costo|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_ord_iniv',  'Orden ' . $array_imp['IVA'] . ' Inc.|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_mul_empr',  'Multiempresa|left', false, 'N');

				// CUENTA CONTABLES	

				$ifu->AgregarCampoListaSQL('defi_cod_cuen', '|left', "", false, '150', 'auto', true);
				$sql = "select cuen_cod_cuen, cuen_nom_cuen
							from saecuen  where 
							cuen_cod_empr = $idempresa and
							cuen_mov_cuen = '1' order by 1 ";
				$lista_cuenta = '';
				if ($oIfx->Query($sql)) {
					if ($oIfx->NumFilas() > 0) {
						do {
							$cuen_cod_cuen = $oIfx->f('cuen_cod_cuen');
							$cuen_nom_cuen = $oIfx->f('cuen_nom_cuen');
							$cuen_det      = $cuen_cod_cuen . ' - ' . $cuen_nom_cuen;
							$lista_cuenta .= '<option value="' . $cuen_cod_cuen . '" >' . $cuen_det . '</option>';
						} while ($oIfx->SiguienteRegistro());
					}
				}
				$oIfx->Free();


				$ifu->AgregarCampoListaSQL('defi_for_defi', 'Formato|left', "SELECT saeftrn.ftrn_cod_ftrn , saeftrn.ftrn_des_ftrn																					 
																				FROM saeftrn  WHERE 
																				ftrn_cod_modu = 10 and
																			    ftrn_cod_empr = $idempresa ", true, '150', 'auto', true);

				$ifu->AgregarCampoCheck('defi_ped_defi',  'Integracion en Linea|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_sno_seri',  'Series|left', false, 'N');
				$ifu->AgregarCampoNumerico('defi_can_seri', 'Cantidad|left', true, '0', 40, 2, true);

				$ifu->AgregarCampoCheck('defi_eval_defi',  'Evaluacion|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_ord_trab',  'O.T.|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_ant_movi',  'Anticipo|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_cie_anti',  'Cierre|left', false, 'N');

				$ifu->AgregarCampoCheck('defi_lis_prec',  'General|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_lis_prep',  'Por Producto|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_des_prec',  'Descuento|left', false, 'N');

				$ifu->AgregarCampoListaSQL('defi_tip_comp', 'Tipo Comprob.|left', "SELECT tcmp_cod_tcmp,   
																						( tcmp_cod_tcmp || ' - ' || tcmp_des_tcmp ) as tcmp_des_tcmp  
																							FROM saetcmp ", false, '150', 'auto', true);

				$ifu->AgregarCampoListaSQL('defi_cod_trtc', 'Cod.Ret.:|left', "SELECT saetret.tret_cod,   
																					  tret_det_ret
																					  FROM saetret  WHERE 
																					  ( saetret.tret_cod_empr = $idempresa ) AND  
																					  ( saetret.tret_ban_retf = 'IR' ) AND  
																					  ( saetret.tret_ban_crdb = 'CR' ) order by 1 ", false, '150', 'auto', true);

				$ifu->AgregarCampoListaSQL('defi_cod_retiva', 'Cod.Ret. ' . $array_imp['IVA'] . ':|left', "SELECT saetret.tret_cod,   
																					  tret_det_ret
																					  FROM saetret  WHERE 
																					  ( saetret.tret_cod_empr = $idempresa ) AND  
																					  ( saetret.tret_ban_retf = 'RI' ) AND  
																					  ( saetret.tret_ban_crdb = 'CR' ) order by 1 ", false, '150', 'auto', true);

				$ifu->AgregarCampoListaSQL('defi_cod_tidu', 'Tipo Documento|left', "SELECT tidu_cod_tidu,   
																						( tidu_des_tidu ) as tidu_des_tidu
																						FROM saetidu  where
																						tidu_cod_empr = $idempresa and
																						tidu_cod_modu = 10 ", true, '150', 'auto', true);

				$ifu->AgregarCampoListaSQL('defi_cod_libro', 'Libro|left', "SELECT saelibro.libro_cod_libro, saelibro.libro_des_libro  
																					FROM saelibro  where
																					libro_cod_empr = $idempresa ", false, '150', 'auto', true);

				$ifu->AgregarCampoListaSQL('defi_cod_crtr', 'Sustento Tributario|left', "SELECT saecrtr.crtr_cod_crtr,   
																							( crtr_des_crtr ) as crtr_des_crtr 
																						    FROM saecrtr ", false, '500', 'auto', true);

				$sql = "SELECT saecrtr.crtr_cod_crtr,  ( crtr_des_crtr ) as crtr_des_crtr  FROM saecrtr  ";
				$lista_crtr = '';
				if ($oIfx->Query($sql)) {
					if ($oIfx->NumFilas() > 0) {
						do {
							$crtr_cod_crtr = $oIfx->f('crtr_cod_crtr');
							$crtr_des_crtr = $oIfx->f('crtr_des_crtr');
							$crtr_des_crtr = $crtr_cod_crtr . ' - ' . $crtr_des_crtr;
							$lista_crtr .= '<option value="' . $crtr_cod_crtr . '" >' . $crtr_des_crtr . '</option>';
						} while ($oIfx->SiguienteRegistro());
					}
				}
				$oIfx->Free();


				$ifu->AgregarCampoCheck('defi_tip_cons',  'P. Interno|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_tip_rese',  'Reserva|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_tom_pre',   'Precio Prov.|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_prod_rec',  'Produccion|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_barr_si',   'Utiliza Codigo de Barras|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_tip_roma',  'Romaneos|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_mat_prim',  'Materia Prima|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_ing_xml',   'Importar Doc. XML|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_sin_fact',  'Ingresos Sin Facturas|left', false, 'N');
				$ifu->AgregarCampoCheck('defi_mod_can',  'Modificar Cantidad Kardex|left', false, 'N');


				$ifu->AgregarCampoNumerico('defi_cod_defi', 'No. Serial:|left', false, '', 40, 2, true);
				$ifu->AgregarComandoAlPonerEnfoque('defi_cod_defi', 'this.blur()');

				break;
		}

		$sHtml .= '<table class="table table-striped table-condensed" align="center" style="width: 100%;">
                        <tr>
                            <td colspan="4" align="left">
                                <div class="btn-group">
                                    <div class="btn btn-primary btn-sm" onclick="genera_formulario();">
                                        <span class="glyphicon glyphicon-file"></span>
                                        Nuevo
                                    </div>
                                    <div class="btn btn-primary btn-sm" onclick="guardar();">
                                        <span class="glyphicon glyphicon-floppy-disk"></span>
                                        Guardar
                                    </div>
                                </div>
                        </tr>';
		$sHtml .= '<tr>
                        <td align="center" class="bg-primary" id="lgTitulo_frame" colspan="4">DATOS TRANSACCION NUEVA</th>
                    </tr>
                    <tr class="msgFrm">
                        <td colspan="4" align="center">Los campos con * son de ingreso obligatorio</td>
                    </tr>';
		$sHtml .= '<tr>
                        <td>' . $ifu->ObjetoHtmlLBL('tran_cod') . '</td>
                        <td>' . $ifu->ObjetoHtml('tran_cod') . '' . $ifu->ObjetoHtml('defi_cod_defi') . '</td>
                    </tr>';
		$sHtml .= '<tr>
                        <td>' . $ifu->ObjetoHtmlLBL('tran_nom') . '</td>
                        <td colspan="3">' . $ifu->ObjetoHtml('tran_nom') . '</td>
                    </tr>';
		$sHtml .= '<tr>
                        <td>' . $ifu->ObjetoHtmlLBL('tran_secu') . '</td>
                        <td>' . $ifu->ObjetoHtml('tran_secu') . '</td>
                        <td>' . $ifu->ObjetoHtmlLBL('tran_sucu') . '</td>
                        <td>' . $ifu->ObjetoHtml('tran_sucu') . '</td>
                    </tr>';
		$sHtml .= '<tr>
						<td colspan="4" style="width: 100%;">
							<table class="table table-striped table-condensed" align="center" style="width: 100%;">
								<tr>
									<td>
											<table class="table table-striped table-condensed" align="center" style="width: 100%;">
												<tr>
													<td colspan="2" class="fecha_letra">* Tipo</td>
												</tr>
												<tr>
													<td><label>Ingreso</label></td>
													<td><input type="radio" name="defi_tip_defi" id="defi0" value="0" checked /></td>
												</tr>
												<tr>
													<td><label>Egreso</label></td>  
													<td><input type="radio" name="defi_tip_defi" id="defi1" value="1"  /></td>
												</tr>
												<tr>
													<td><label>Requisicion</label></td>
													<td><input type="radio" name="defi_tip_defi" id="defi3" value="3"  /></td>
												</tr>
												<tr>
													<td><label>Compra</label></td>
													<td><input type="radio" name="defi_tip_defi" id="defi4" value="4"  /></td>
												</tr>
												<tr>
													<td><label>Produccion</label></td>
													<td><input type="radio" name="defi_tip_defi" id="defi5" value="5"  /></td>
												</tr>
												<tr>
													<td><label>Transferencia</label></td>
													<td><input type="radio" name="defi_tip_defi" id="defi6" value="6"  /></td>
												</tr>
												<tr>
													<td><label>Transf. Autorizar</label></td>
													<td><input type="radio" name="defi_tip_defi" id="defi7" value="7"  /></td>
												</tr>
											</table>
									</td>
									<td>
											<table class="table table-striped table-condensed" align="center" style="width: 100%;">
												<tr>
													<td colspan="2">* ' . $array_imp['IVA'] . ' Item</td>
												</tr>
												<tr>
													<td><label>Si</label></td>
													<td><input type="radio" name="defi_iva_defi" id="defi_iva_defi0" value="0" checked /></td>
												</tr>
												<tr>
													<td><label>No</label></td>  
													<td><input type="radio" name="defi_iva_defi" id="defi_iva_defi1" value="1"  /></td>
												</tr>
												<tr>
													<td><label>Suma al C.U.</label></td>  
													<td><input type="radio" name="defi_iva_defi" id="defi_iva_defi2" value="2"  /></td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_otr_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_otr_defi') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_ret_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_ret_defi') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_prd_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_prd_defi') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_nov_mos') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_nov_mos') . '</td>
												</tr>
											</table>
									</td>
									<td>
											<table class="table table-striped table-condensed" align="center" style="width: 100%;">
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_mos_bode') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_mos_bode') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_fact_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_fact_defi') . '</td>
												</tr>
												<tr>
													<td><label>Cliente</label></td>
													<td><input type="radio" name="defi_pro_defi" id="defi_pro_defiCL" value="CL" checked /></td>
												</tr>
												<tr>
													<td><label>Proveedor</label></td>  
													<td><input type="radio" name="defi_pro_defi" id="defi_pro_defiPV" value="PV" checked /></td>
												</tr>
												<tr>
													<td><label>Ambos</label></td>  
													<td><input type="radio" name="defi_pro_defi" id="defi_pro_defiAM" value="AM" checked /></td>
												</tr>
												<tr>
													<td><label>Ninguno</label></td>  
													<td><input type="radio" name="defi_pro_defi" id="defi_pro_defiNI" value="NI" checked /></td>
												</tr>
											</table>
									</td>
								</tr>
							</table>
						</td>						
                    </tr>';

		$sHtml .= '<tr>
						<td colspan="4" style="width: 100%;" valign="top">
							<table class="table table-striped table-condensed" align="center" style="width: 100%;">
								<tr>
									<td>
											<table class="table table-striped table-condensed" align="center" style="width: 100%;">
												<tr>
													<td colspan="2" align="center" class="fecha_letra">* Costos
												</tr>
												<tr>
													<td><label>Si</label></td>
													<td><input type="radio" name="defi_cos_defi" id="defi_cos_defi0" value="0" checked /></td>
												</tr>
												<tr>
													<td><label>No</label></td>  
													<td><input type="radio" name="defi_cos_defi" id="defi_cos_defi1" value="1" checked /></td>
												</tr>
												<tr>
													<td><label>Calculado</label></td>  
													<td><input type="radio" name="defi_cos_defi" id="defi_cos_defi2" value="2" checked /></td>
												</tr>
												<tr>
													<td colspan="2" align="center" class="fecha_letra">* Costeo</td>
												</tr>
												<tr>
													<td><label>Unitario</label></td>
													<td><input type="radio" name="defi_cost_defi" id="defi_cost_defi0" value="0" checked /></td>
												</tr>
												<tr>
													<td><label>Total</label></td>  
													<td><input type="radio" name="defi_cost_defi" id="defi_cost_defi1" value="1" checked /></td>
												</tr>
												<tr>
													<td colspan="2" align="center" class="fecha_letra">* Ice</td>
												</tr>
												<tr>
													<td><label>Si</label></td>
													<td><input type="radio" name="defi_ice_defi" id="defi_ice_defi0" value="0" checked /></td>
												</tr>
												<tr>
													<td><label>No</label></td>
													<td><input type="radio" name="defi_ice_defi" id="defi_ice_defi1" value="1" checked /></td>
												</tr>												
											</table>
									</td>
									<td>
											<table class="table table-striped table-condensed" align="center" style="width: 100%;">
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_can_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_can_defi') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_pro_prov') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_pro_prov') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_lot_clpv') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_lot_clpv') . '</td>
												</tr>
												<tr>
													<td></td>
													<td></td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_det_dmov') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_det_dmov') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_iva_incl') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_iva_incl') . '</td>
												</tr>

												
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_prec_vent') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_prec_vent') . '</td>
												</tr>

												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_num_det') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_num_det') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_dsc_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_dsc_defi') . '</td>
												</tr>
											</table>
									</td>
									<td>
											<table class="table table-striped table-condensed" align="center" style="width: 100%;">
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_prc_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_prc_defi') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_lot_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_lot_defi') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_cco_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_cco_defi') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_ord_iniv') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_ord_iniv') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_mul_empr') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_mul_empr') . '</td>
												</tr>												
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_for_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_for_defi') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_ctc_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_ctc_defi') . '</td>
												</tr>
												<tr>
													<td colspan="2">
														<select id="defi_cod_cuen" name="defi_cod_cuen" class="form-control select2" style="width: 100%;">
															<option value="">Seleccione una opcion..</option>
															' . $lista_cuenta . '
														</select>
													</td>
												</tr>
											</table>
									</td>
								</tr>
							</table>
						</td>						
                    </tr>';

		$sHtml .= '<tr>
						<td colspan="4" style="width: 100%;" valign="top">
							<table class="table table-striped table-condensed" align="center" style="width: 100%;">
								<tr>
									<td>
											<table class="table table-striped table-condensed" align="center" style="width: 100%;">
												<tr>
													<td colspan="2" align="center">* Contabilidad</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_ped_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_ped_defi') . '</td>
												</tr>												
											</table>
									</td>
									<td>
											<table class="table table-striped table-condensed" align="center" style="width: 100%;">
												<tr>
													<td colspan="2" align="center">* Series Archivo Plano</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_sno_seri') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_sno_seri') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_can_seri') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_can_seri') . '</td>
												</tr>
											</table>
									</td>
									<td>
											<table class="table table-striped table-condensed" align="center" style="width: 100%;">
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_eval_defi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_eval_defi') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_ord_trab') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_ord_trab') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_ant_movi') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_ant_movi') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_cie_anti') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_cie_anti') . '</td>
												</tr>
											</table>
									</td>
								</tr>
								<tr>
									<td>
											<div class="row" style="margin: 0;">
												<div class="col-md-4" style="padding-left: 0; padding-right: 10px;">
													<table class="table table-striped table-condensed" align="center" style="width: 100%;">
														<tr>
															<td colspan="2" align="center">* L. de Precios</td>
														</tr>
														<tr>
															<td>' . $ifu->ObjetoHtmlLBL('defi_lis_prec') . '</td>
															<td>' . $ifu->ObjetoHtml('defi_lis_prec') . '</td>
														</tr>	
														<tr>
															<td>' . $ifu->ObjetoHtmlLBL('defi_lis_prep') . '</td>
															<td>' . $ifu->ObjetoHtml('defi_lis_prep') . '</td>
														</tr>	
														<tr>
															<td>' . $ifu->ObjetoHtmlLBL('defi_des_prec') . '</td>
															<td>' . $ifu->ObjetoHtml('defi_des_prec') . '</td>
														</tr>													
													</table>
												</div>
												<div class="col-md-4" style="padding-left: 0; padding-right: 10px;">
													<table class="table table-striped table-condensed" align="center" style="width: 100%;">
														<tr>
															<td colspan="2" align="center">* Retencion</td>
														</tr>
														<tr>
															<td>' . $ifu->ObjetoHtmlLBL('defi_tip_comp') . '</td>
															<td>' . $ifu->ObjetoHtml('defi_tip_comp') . '</td>
														</tr>
														<tr>
															<td>' . $ifu->ObjetoHtmlLBL('defi_cod_trtc') . '</td>
															<td>' . $ifu->ObjetoHtml('defi_cod_trtc') . '</td>
														</tr>
														<tr>
															<td>' . $ifu->ObjetoHtmlLBL('defi_cod_retiva') . '</td>
															<td>' . $ifu->ObjetoHtml('defi_cod_retiva') . '</td>
														</tr>
													</table>
												</div>
												<div class="col-md-4" style="padding-left: 0; padding-right: 0;">
													<table class="table table-striped table-condensed" align="center" style="width: 100%;">
														<colgroup>
															<col style="width: 35%;">
															<col style="width: 65%;">
														</colgroup>
														<tr>
															<td colspan="2" align="center">* Tipo Documento</td>
														</tr>
														<tr>
															<td style="white-space: nowrap;">' . $ifu->ObjetoHtmlLBL('defi_cod_tidu') . '</td>
															<td>' . $ifu->ObjetoHtml('defi_cod_tidu') . '</td>
														</tr>
														<tr>
															<td style="white-space: nowrap;">' . $ifu->ObjetoHtmlLBL('defi_cod_libro') . '</td>
															<td>' . $ifu->ObjetoHtml('defi_cod_libro') . '</td>
														</tr>
													</table>
												</div>
											</div>
									</td>
								</tr>
							</table>
						</td>						
                    </tr>';
		$sHtml .= '<tr>
                        <td>' . $ifu->ObjetoHtmlLBL('defi_cod_crtr') . '</td>
                        <td colspan="3">
							<select id="defi_cod_crtr" name="defi_cod_crtr" class="form-control select2" style="width: 100%;">
								<option value="">Seleccione una opcion..</option>
								' . $lista_crtr . '
							</select>
						</td>
                    </tr>';
		$sHtml .= '<tr>
						<td colspan="4" style="width: 100%;" valign="top">
							<table class="table table-striped table-condensed" align="center" style="width: 100%;">
								<tr>
									<td>
											<table class="table table-striped table-condensed" align="center" style="width: 100%;">
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_tip_cons') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_tip_cons') . '</td>
													<td>' . $ifu->ObjetoHtmlLBL('defi_tip_rese') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_tip_rese') . '</td>
													<td>' . $ifu->ObjetoHtmlLBL('defi_tom_pre') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_tom_pre') . '</td>
													<td>' . $ifu->ObjetoHtmlLBL('defi_prod_rec') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_prod_rec') . '</td>
													<td>' . $ifu->ObjetoHtmlLBL('defi_barr_si') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_barr_si') . '</td>
												</tr>
												<tr>
													<td>' . $ifu->ObjetoHtmlLBL('defi_tip_roma') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_tip_roma') . '</td>
													<td>' . $ifu->ObjetoHtmlLBL('defi_mat_prim') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_mat_prim') . '</td>
													<td>' . $ifu->ObjetoHtmlLBL('defi_ing_xml') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_ing_xml') . '</td>
													<td>' . $ifu->ObjetoHtmlLBL('defi_sin_fact') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_sin_fact') . '</td>
													<td>' . $ifu->ObjetoHtmlLBL('defi_mod_can') . '</td>
													<td>' . $ifu->ObjetoHtml('defi_mod_can') . '</td>
												</tr>
											</table>
									</td>
								</tr>
							</table>
						</td>						
                    </tr>';
		$sHtml .= '</table>';

		$oReturn->assign("divFormularioCli", "innerHTML", $sHtml);
		$oReturn->script('generaSelect2();');
	} catch (Exception $e) {
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}

function seleccionarTran($aForm = '', $tran_cod, $id = 0)
{
	//Definiciones
	global $DSN_Ifx, $DSN;

	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oReturn = new xajaxResponse();

	//variables de session
	$idempresa = $_SESSION['U_EMPRESA'];

	try {

		//lectura sucia
		//////////////

		$sql = "select  *				
							from saedefi, saetran where					
							tran_cod_tran    = defi_cod_tran and					
							defi_cod_empr    = $idempresa and					
							defi_cod_modu    = 10	and
							tran_cod_tran    = '$tran_cod' and
							defi_cod_defi    = '$id'
							order by tran_cod_sucu,1  ";
		if ($oIfx->Query($sql)) {
			if ($oIfx->NumFilas() > 0) {
				$defi_cod  		= $oIfx->f('defi_cod_defi');
				$tran_cod  		= $oIfx->f('tran_cod_tran');
				$tran_nom  		= $oIfx->f('tran_des_tran');
				$tran_secu 		= $oIfx->f('defi_trs_defi');
				$tran_sucu 		= $oIfx->f('tran_cod_sucu');
				$defi_mos_bode 	= $oIfx->f('defi_mos_bode');
				$defi_fact_defi = $oIfx->f('defi_fact_defi');
				$defi_cod_tidu	= $oIfx->f('defi_cod_tidu');

				$defi_otr_defi  = $oIfx->f('defi_otr_defi');
				$defi_prec_vent  = $oIfx->f('defi_prec_vent');
				$defi_ret_defi	= $oIfx->f('defi_ret_defi');
				$defi_prd_defi  = $oIfx->f('defi_prd_defi');
				$defi_nov_mos 	= $oIfx->f('defi_nov_mos');
				$defi_can_defi	= $oIfx->f('defi_can_defi');
				$defi_pro_prov  = $oIfx->f('defi_pro_prov');
				$defi_lot_clpv  = $oIfx->f('defi_lot_clpv');
				$defi_ctc_defi  = $oIfx->f('defi_ctc_defi');
				$defi_det_dmov  = $oIfx->f('defi_det_dmov');
				$defi_iva_incl  = $oIfx->f('defi_iva_incl');
				$defi_num_det   = $oIfx->f('defi_num_det');
				$defi_dsc_defi  = $oIfx->f('defi_dsc_defi');

				$defi_prc_defi  = $oIfx->f('defi_prc_defi');
				$defi_lot_defi  = $oIfx->f('defi_lot_defi');
				$defi_cco_defi  = $oIfx->f('defi_cco_defi');
				$defi_ord_iniv  = $oIfx->f('defi_ord_iniv');
				$defi_mul_empr  = $oIfx->f('defi_mul_empr');
				$defi_cod_cuen  = $oIfx->f('defi_cod_cuen');
				$defi_for_defi  = $oIfx->f('defi_for_defi');
				$defi_ped_defi  = $oIfx->f('defi_ped_defi');
				$defi_sno_seri  = $oIfx->f('defi_sno_seri');
				$defi_can_seri  = $oIfx->f('defi_can_seri');

				$defi_eval_defi = $oIfx->f('defi_eval_defi');
				$defi_ord_trab  = $oIfx->f('defi_ord_trab');
				$defi_ant_movi  = $oIfx->f('defi_ant_movi');
				$defi_cie_anti  = $oIfx->f('defi_cie_anti');
				$defi_lis_prec  = $oIfx->f('defi_lis_prec');

				$defi_lis_prep  = $oIfx->f('defi_lis_prep');
				$defi_des_prec  = $oIfx->f('defi_des_prec');

				$defi_tip_comp  = $oIfx->f('defi_tip_comp');
				$defi_cod_trtc  = $oIfx->f('defi_cod_trtc');
				$defi_cod_retiva = $oIfx->f('defi_cod_retiva');
				$defi_cod_libro = $oIfx->f('defi_cod_libro');
				$defi_cod_crtr  = $oIfx->f('defi_cod_crtr');
				$defi_tip_cons  = $oIfx->f('defi_tip_cons');

				$defi_tip_rese  = $oIfx->f('defi_tip_rese');
				$defi_tom_pre   = $oIfx->f('defi_tom_pre');
				$defi_prod_rec  = $oIfx->f('defi_prod_rec');
				$defi_barr_si   = $oIfx->f('defi_barr_si');
				$defi_tip_roma  = $oIfx->f('defi_tip_roma');
				$defi_mat_prim  = $oIfx->f('defi_mat_prim');
				$defi_ing_xml   = $oIfx->f('defi_ing_xml');
				$defi_sin_fact  = $oIfx->f('defi_sin_fact');
				$defi_mod_can  = $oIfx->f('defi_mod_can');


				$defi_tip_defi  = $oIfx->f('defi_tip_defi');
				$defi_iva_defi  = $oIfx->f('defi_iva_defi');
				$defi_pro_defi	= $oIfx->f('defi_pro_defi');
				$defi_cos_defi  = $oIfx->f('defi_cos_defi');
				$defi_cost_defi = $oIfx->f('defi_cost_defi');
				$defi_ice_defi  = $oIfx->f('defi_ice_defi');

				$oReturn->assign('defi_cod_defi', 'value', 	$defi_cod);
				$oReturn->assign('tran_cod', 'value', 		$tran_cod);
				$oReturn->assign('tran_nom', 'value', 		$tran_nom);
				$oReturn->assign('tran_secu', 'value',		$tran_secu);
				$oReturn->assign('tran_sucu', 'value',		$tran_sucu);
				$oReturn->script("selectItemByValue(`tran_sucu`,`$tran_sucu`);");

				$oReturn->assign('defi_fact_defi', 'value',	$defi_fact_defi);
				$oReturn->assign('defi_num_det', 'value',	$defi_num_det);

				$oReturn->assign('defi_num_det', 'value',	$defi_num_det);
				$oReturn->assign('defi_dsc_defi', 'value',	$defi_dsc_defi);
				$oReturn->assign('defi_cod_cuen', 'value',	$defi_cod_cuen);
				$oReturn->script("selectItemByValue(`defi_cod_cuen`,`$defi_cod_cuen`);");

				$oReturn->assign('defi_for_defi', 'value',	$defi_for_defi);
				$oReturn->assign('defi_can_seri', 'value',	$defi_can_seri);

				$oReturn->assign('defi_tip_comp', 'value',	$defi_tip_comp);
				$oReturn->assign('defi_cod_trtc', 'value',	$defi_cod_trtc);
				$oReturn->assign('defi_cod_retiva', 'value',	$defi_cod_retiva);

				$oReturn->assign('defi_cod_tidu', 'value',	$defi_cod_tidu);
				$oReturn->assign('defi_cod_libro', 'value',	$defi_cod_libro);
				$oReturn->assign('defi_cod_crtr', 'value',	$defi_cod_crtr);
				$oReturn->script("selectItemByValue(`defi_for_defi`,`$defi_for_defi`);");
				$oReturn->script("selectItemByValue(`defi_tip_comp`,`$defi_tip_comp`);");
				$oReturn->script("selectItemByValue(`defi_cod_trtc`,`$defi_cod_trtc`);");
				$oReturn->script("selectItemByValue(`defi_cod_retiva`,`$defi_cod_retiva`);");
				$oReturn->script("selectItemByValue(`defi_cod_tidu`,`$defi_cod_tidu`);");
				$oReturn->script("selectItemByValue(`defi_cod_libro`,`$defi_cod_libro`);");
				$oReturn->script("selectItemByValue(`defi_cod_crtr`,`$defi_cod_crtr`);");

				$oReturn->script("setRadioByValue(`defi_tip_defi`,`$defi_tip_defi`);");
				$oReturn->script("setRadioByValue(`defi_iva_defi`,`$defi_iva_defi`);");
				$oReturn->script("setRadioByValue(`defi_pro_defi`,`$defi_pro_defi`);");
				$oReturn->script("setRadioByValue(`defi_cos_defi`,`$defi_cos_defi`);");
				$oReturn->script("setRadioByValue(`defi_cost_defi`,`$defi_cost_defi`);");
				$oReturn->script("setRadioByValue(`defi_ice_defi`,`$defi_ice_defi`);");

				$oReturn->script("setCheckboxByValue(`defi_mos_bode`,`$defi_mos_bode`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_otr_defi`,`$defi_otr_defi`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_prec_vent`,`$defi_prec_vent`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_ret_defi`,`$defi_ret_defi`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_prd_defi`,`$defi_prd_defi`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_nov_mos`,`$defi_nov_mos`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_can_defi`,`$defi_can_defi`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_pro_prov`,`$defi_pro_prov`, ['1']);");
				$oReturn->script("setCheckboxByValue(`defi_lot_clpv`,`$defi_lot_clpv`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_ctc_defi`,`$defi_ctc_defi`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_det_dmov`,`$defi_det_dmov`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_iva_incl`,`$defi_iva_incl`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_prc_defi`,`$defi_prc_defi`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_lot_defi`,`$defi_lot_defi`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_cco_defi`,`$defi_cco_defi`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_ord_iniv`,`$defi_ord_iniv`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_mul_empr`,`$defi_mul_empr`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_ped_defi`,`$defi_ped_defi`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_sno_seri`,`$defi_sno_seri`, ['1']);");
				$oReturn->script("setCheckboxByValue(`defi_eval_defi`,`$defi_eval_defi`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_ord_trab`,`$defi_ord_trab`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_ant_movi`,`$defi_ant_movi`, ['1']);");
				$oReturn->script("setCheckboxByValue(`defi_cie_anti`,`$defi_cie_anti`, ['1']);");
				$oReturn->script("setCheckboxByValue(`defi_lis_prec`,`$defi_lis_prec`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_lis_prep`,`$defi_lis_prep`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_des_prec`,`$defi_des_prec`, ['0']);");
				$oReturn->script("setCheckboxByValue(`defi_tip_cons`,`$defi_tip_cons`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_tip_rese`,`$defi_tip_rese`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_tom_pre`,`$defi_tom_pre`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_prod_rec`,`$defi_prod_rec`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_barr_si`,`$defi_barr_si`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_tip_roma`,`$defi_tip_roma`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_mat_prim`,`$defi_mat_prim`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_ing_xml`,`$defi_ing_xml`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_sin_fact`,`$defi_sin_fact`, ['S']);");
				$oReturn->script("setCheckboxByValue(`defi_mod_can`,`$defi_mod_can`, ['S']);");
			}
		}
		$oIfx->Free();
	} catch (Exception $e) {
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}


function guardar_tran($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;

	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oReturn = new xajaxResponse();

	$idempresa 	= $_SESSION['U_EMPRESA'];
	$user_ifx 	= $_SESSION['U_USER_INFORMIX'];

	//LECTURA SUCIA
	//////////////

	$tran_cod  		= $aForm['tran_cod'];
	$tran_nom  		= $aForm['tran_nom'];
	$tran_secu 		= $aForm['tran_secu'];
	$tran_sucu 		= $aForm['tran_sucu'];
	$defi_mos_bode 	= val_check_inv($aForm['defi_mos_bode'], 'S', 'N');
	$defi_fact_defi = $aForm['defi_fact_defi'];
	$defi_otr_defi  = val_check_inv($aForm['defi_otr_defi'],  0,   1);
	$defi_prec_vent  = val_check_inv($aForm['defi_prec_vent'],  0,   1);

	$defi_ret_defi	= val_check_inv($aForm['defi_ret_defi'],  0,   1);
	$defi_prd_defi  = val_check_inv($aForm['defi_prd_defi'], 'S', 'N');
	$defi_nov_mos 	= val_check_inv($aForm['defi_nov_mos'], 'S', 'N');
	$defi_can_defi	= val_check_inv($aForm['defi_can_defi'],  0,   1);
	$defi_pro_prov  = val_check_inv($aForm['defi_pro_prov'],  1,   0);
	$defi_lot_clpv  = val_check_inv($aForm['defi_lot_clpv'], 'S', 'N');
	$defi_ctc_defi  = val_check_inv($aForm['defi_ctc_defi'],  0,   1);
	$defi_det_dmov  = val_check_inv($aForm['defi_det_dmov'], 'S', 'N');
	$defi_iva_incl  = val_check_inv($aForm['defi_iva_incl'], 'S', 'N');
	$defi_num_det   = $aForm['defi_num_det'];
	$defi_dsc_defi  = $aForm['defi_dsc_defi'];

	$defi_prc_defi  = val_check_inv($aForm['defi_prc_defi'],  0,   1);
	$defi_lot_defi  = val_check_inv($aForm['defi_lot_defi'],  0,   1);
	$defi_cco_defi  = val_check_inv($aForm['defi_cco_defi'],  0,   1);
	$defi_ord_iniv  = val_check_inv($aForm['defi_ord_iniv'], 'S', 'N');
	$defi_mul_empr  = val_check_inv($aForm['defi_mul_empr'],  0,   1);
	$defi_cod_cuen  = $aForm['defi_cod_cuen'];
	$defi_for_defi  = $aForm['defi_for_defi'];
	$defi_ped_defi  = val_check_inv($aForm['defi_ped_defi'], 'S', 'N');
	$defi_sno_seri  = val_check_inv($aForm['defi_sno_seri'],  1,   0);
	$defi_can_seri  = $aForm['defi_can_seri'];

	$defi_eval_defi = val_check_inv($aForm['defi_eval_defi'], 'S', 'N');
	$defi_ord_trab  = val_check_inv($aForm['defi_ord_trab'],  0,   1);
	$defi_ant_movi  = val_check_inv($aForm['defi_ant_movi'],  1,   0);
	$defi_cie_anti  = val_check_inv($aForm['defi_cie_anti'],  1,   0);
	$defi_lis_prec  = val_check_inv($aForm['defi_lis_prec'],  0,   1);

	$defi_lis_prep  = val_check_inv($aForm['defi_lis_prep'],  0,   1);
	$defi_des_prec  = val_check_inv($aForm['defi_des_prec'],  0,   1);

	$defi_tip_comp  = $aForm['defi_tip_comp'];
	$defi_cod_trtc  = $aForm['defi_cod_trtc'];
	$defi_cod_retiva = $aForm['defi_cod_retiva'];
	$defi_cod_tidu  = $aForm['defi_cod_tidu'];
	$defi_cod_libro = $aForm['defi_cod_libro'];
	$defi_cod_crtr  = $aForm['defi_cod_crtr'];
	$defi_tip_cons  = val_check_inv($aForm['defi_tip_cons'], 'S', 'N');

	$defi_tip_rese  = val_check_inv($aForm['defi_tip_rese'], 'S', 'N');
	$defi_tom_pre   = val_check_inv($aForm['defi_tom_pre'], 'S', 'N');
	$defi_prod_rec  = val_check_inv($aForm['defi_prod_rec'], 'S', 'N');
	$defi_barr_si   = val_check_inv($aForm['defi_barr_si'], 'S', 'N');
	$defi_tip_roma  = val_check_inv($aForm['defi_tip_roma'], 'S', 'N');
	$defi_mat_prim  = val_check_inv($aForm['defi_mat_prim'], 'S', 'N');
	$defi_ing_xml   = val_check_inv($aForm['defi_ing_xml'], 'S', 'N');
	$defi_sin_fact  = val_check_inv($aForm['defi_sin_fact'], 'S', 'N');
	$defi_mod_can  = val_check_inv($aForm['defi_mod_can'], 'S', 'N');


	$defi_tip_defi  = $aForm['defi_tip_defi'];
	$defi_iva_defi  = $aForm['defi_iva_defi'];
	$defi_pro_defi	= $aForm['defi_pro_defi'];
	$defi_cos_defi  = $aForm['defi_cos_defi'];
	$defi_cost_defi = $aForm['defi_cost_defi'];
	$defi_ice_defi  = $aForm['defi_ice_defi'];



	if (!$defi_fact_defi) {
		$defi_fact_defi = 0;
	}

	if (!$defi_cod_libro) {
		$defi_cod_libro = 0;
	}

	if (!$defi_dsc_defi) {
		$defi_dsc_defi = 'null';
	}



	try {
		// commit
		$oIfx->QueryT('BEGIN WORK;');

		// SAETRAN
		$sql = "insert into saetran ( tran_cod_tran, 		tran_cod_modu,		tran_cod_empr, 		tran_des_tran ,
									  trans_tip_tran,		trans_tip_comp,		tran_cod_tret,		tran_cod_sucu )
							  values( '$tran_cod', 			10,					$idempresa,			'$tran_nom',
									  '',					'',					'',					$tran_sucu	
							         )";
		$oIfx->QueryT($sql);

		// SAEDEFI
		$sql = "insert into saedefi ( defi_cod_modu,		defi_cod_tran , 	defi_cod_empr ,		defi_tip_defi , 
									  defi_can_defi, 		defi_iva_defi , 	defi_cos_defi , 	defi_cost_defi , 
									  defi_cco_defi, 		defi_lot_defi , 	defi_cac_defi , 	defi_dsc_defi , 
									  defi_prc_defi, 		defi_prd_defi , 	defi_otr_defi , 	defi_ped_defi , 
									  defi_ret_defi, 		defi_trs_defi , 	defi_pro_defi , 	defi_ctc_defi , 
									  defi_for_defi, 		defi_tip_comp , 	defi_det_dmov , 	defi_lot_clpv , 
									  defi_lis_prec, 		defi_mul_empr , 	defi_ice_defi , 	defi_cod_trtc , 
									  defi_ord_iniv, 		defi_iva_incl , 	defi_cod_cuen , 	defi_lis_prep , 
									  defi_pro_prov, 		defi_sno_seri , 	defi_can_seri , 	defi_des_prec , 
									  defi_cod_sucu, 		defi_eval_defi, 	defi_ord_trab , 	defi_cie_anti , 
									  defi_ant_movi, 		defi_cod_tidu , 	defi_tip_cons , 	defi_tip_rese , 
									  defi_tom_pre , 		defi_prod_rec , 	defi_barr_si  , 	defi_baj_ing  , 
									  defi_ocul_cos, 		defi_nov_mos  , 	defi_fact_defi, 	defi_mos_bode , 
									  defi_mos_fact, 		defi_cod_libro, 	defi_num_det  , 	defi_tip_roma , 
									  defi_mat_prim, 		defi_cod_retiva, 	defi_cod_crtr , 	defi_ing_xml  , 
									  defi_sin_fact, 		defi_mer_defi,		defi_prec_vent,		defi_mod_can )
							 values ( 10, 					'$tran_cod',		$idempresa,			'$defi_tip_defi',
									  $defi_can_defi,		'$defi_iva_defi',	'$defi_cos_defi',	'$defi_cost_defi',	
									  '$defi_cco_defi',     '$defi_lot_defi',   '',   				$defi_dsc_defi,
									  '$defi_prc_defi',     '$defi_prd_defi',   '$defi_otr_defi',   '$defi_ped_defi', 
									  '$defi_ret_defi',     '$tran_secu', 		'$defi_pro_defi',   '$defi_ctc_defi',
									  '$defi_for_defi',     '$defi_tip_comp',   '$defi_det_dmov',   '$defi_lot_clpv',  
									  '$defi_lis_prec',     '$defi_mul_empr',   '$defi_ice_defi',   '$defi_cod_trtc',
									  '$defi_ord_iniv',     '$defi_iva_incl',   '$defi_cod_cuen',   '$defi_lis_prep',
									  '$defi_pro_prov',     '$defi_sno_seri',   '$defi_can_seri',   '$defi_des_prec',
									  '$tran_sucu',			'$defi_eval_defi',  '$defi_ord_trab',   '$defi_cie_anti',
									  '$defi_ant_movi',     '$defi_cod_tidu',   '$defi_tip_cons',   '$defi_tip_rese', 
									  '$defi_tom_pre',      '$defi_prod_rec',   '$defi_barr_si',    '',
									  '',                   '$defi_nov_mos',    $defi_fact_defi,  '$defi_mos_bode',
									  '',					$defi_cod_libro,  '$defi_num_det',    '$defi_tip_roma',
									  '$defi_mat_prim',     '$defi_cod_retiva', '$defi_cod_crtr',   '$defi_ing_xml',
									  '$defi_sin_fact',     '',					'$defi_prec_vent', 	'$defi_mod_can'									  
									)";
		$oIfx->QueryT($sql);

		$oIfx->QueryT('COMMIT WORK;');
		$oReturn->alert('Transaccion Ingresado Correctamente...');
	} catch (Exception $e) {
		// rollback
		$oIfx->QueryT('ROLLBACK WORK;');
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}


function update_tran_frame($aForm = '')
{
	//Definiciones
	global $DSN_Ifx, $DSN;

	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$oCon = new Dbo;
	$oCon->DSN = $DSN;
	$oCon->Conectar();

	$oIfx = new Dbo;
	$oIfx->DSN = $DSN_Ifx;
	$oIfx->Conectar();

	$oReturn = new xajaxResponse();

	$idempresa 	= $_SESSION['U_EMPRESA'];
	$user_ifx 	= $_SESSION['U_USER_INFORMIX'];

	//LECTURA SUCIA
	//////////////

	$defi_cod_defi	= $aForm['defi_cod_defi'];
	$tran_cod  		= $aForm['tran_cod'];
	$tran_nom  		= $aForm['tran_nom'];
	$tran_secu 		= $aForm['tran_secu'];
	$tran_sucu 		= $aForm['tran_sucu'];
	$defi_mos_bode 	= val_check_inv($aForm['defi_mos_bode'], 'S', 'N');
	$defi_fact_defi = $aForm['defi_fact_defi'];
	$defi_otr_defi  = val_check_inv($aForm['defi_otr_defi'],  0,   1);
	$defi_prec_vent  = val_check_inv($aForm['defi_prec_vent'],  0,   1);
	$defi_ret_defi	= val_check_inv($aForm['defi_ret_defi'],  0,   1);
	$defi_prd_defi  = val_check_inv($aForm['defi_prd_defi'], 'S', 'N');
	$defi_nov_mos 	= val_check_inv($aForm['defi_nov_mos'], 'S', 'N');
	$defi_can_defi	= val_check_inv($aForm['defi_can_defi'],  0,   1);
	$defi_pro_prov  = val_check_inv($aForm['defi_pro_prov'],  1,   0);
	$defi_lot_clpv  = val_check_inv($aForm['defi_lot_clpv'], 'S', 'N');
	$defi_ctc_defi  = val_check_inv($aForm['defi_ctc_defi'],  0,   1);
	$defi_det_dmov  = val_check_inv($aForm['defi_det_dmov'], 'S', 'N');
	$defi_iva_incl  = val_check_inv($aForm['defi_iva_incl'], 'S', 'N');
	$defi_num_det   = $aForm['defi_num_det'];
	$defi_dsc_defi  = $aForm['defi_dsc_defi'];

	$defi_prc_defi  = val_check_inv($aForm['defi_prc_defi'],  0,   1);
	$defi_lot_defi  = val_check_inv($aForm['defi_lot_defi'],  0,   1);
	$defi_cco_defi  = val_check_inv($aForm['defi_cco_defi'],  0,   1);
	$defi_ord_iniv  = val_check_inv($aForm['defi_ord_iniv'], 'S', 'N');
	$defi_mul_empr  = val_check_inv($aForm['defi_mul_empr'],  0,   1);
	$defi_cod_cuen  = $aForm['defi_cod_cuen'];
	$defi_for_defi  = $aForm['defi_for_defi'];
	$defi_ped_defi  = val_check_inv($aForm['defi_ped_defi'], 'S', 'N');
	$defi_sno_seri  = val_check_inv($aForm['defi_sno_seri'],  1,   0);
	$defi_can_seri  = $aForm['defi_can_seri'];

	$defi_eval_defi = val_check_inv($aForm['defi_eval_defi'], 'S', 'N');
	$defi_ord_trab  = val_check_inv($aForm['defi_ord_trab'],  0,   1);
	$defi_ant_movi  = val_check_inv($aForm['defi_ant_movi'],  1,   0);
	$defi_cie_anti  = val_check_inv($aForm['defi_cie_anti'],  1,   0);
	$defi_lis_prec  = val_check_inv($aForm['defi_lis_prec'],  0,   1);

	$defi_lis_prep  = val_check_inv($aForm['defi_lis_prep'],  0,   1);
	$defi_des_prec  = val_check_inv($aForm['defi_des_prec'],  0,   1);

	$defi_tip_comp  = $aForm['defi_tip_comp'];
	$defi_cod_trtc  = $aForm['defi_cod_trtc'];
	$defi_cod_retiva = $aForm['defi_cod_retiva'];
	$defi_cod_tidu  = $aForm['defi_cod_tidu'];
	$defi_cod_libro = $aForm['defi_cod_libro'];
	$defi_cod_crtr  = $aForm['defi_cod_crtr'];
	$defi_tip_cons  = val_check_inv($aForm['defi_tip_cons'], 'S', 'N');

	$defi_tip_rese  = val_check_inv($aForm['defi_tip_rese'], 'S', 'N');
	$defi_tom_pre   = val_check_inv($aForm['defi_tom_pre'], 'S', 'N');
	$defi_prod_rec  = val_check_inv($aForm['defi_prod_rec'], 'S', 'N');
	$defi_barr_si   = val_check_inv($aForm['defi_barr_si'], 'S', 'N');
	$defi_tip_roma  = val_check_inv($aForm['defi_tip_roma'], 'S', 'N');
	$defi_mat_prim  = val_check_inv($aForm['defi_mat_prim'], 'S', 'N');
	$defi_ing_xml   = val_check_inv($aForm['defi_ing_xml'], 'S', 'N');
	$defi_sin_fact  = val_check_inv($aForm['defi_sin_fact'], 'S', 'N');
	$defi_mod_can  = val_check_inv($aForm['defi_mod_can'], 'S', 'N');


	$defi_tip_defi  = $aForm['defi_tip_defi'];
	$defi_iva_defi  = $aForm['defi_iva_defi'];
	$defi_pro_defi	= $aForm['defi_pro_defi'];
	$defi_cos_defi  = $aForm['defi_cos_defi'];
	$defi_cost_defi = $aForm['defi_cost_defi'];
	$defi_ice_defi  = $aForm['defi_ice_defi'];

	if (!$defi_fact_defi) {
		$defi_fact_defi = 0;
	}

	if (!$defi_cod_libro) {
		$defi_cod_libro = 0;
	}

	try {
		// commit
		$oIfx->QueryT('BEGIN WORK;');

		// SAETRAN
		$sql = "update saetran set tran_des_tran  = '$tran_nom',
								   tran_cod_sucu  = $tran_sucu where
								   tran_cod_empr  = $idempresa and
								   tran_cod_tran  = '$tran_cod' ";
		$oIfx->QueryT($sql);

		// SAEDEFI
		$sql = "update saedefi   set    defi_tip_defi = '$defi_tip_defi',
										defi_can_defi =  $defi_can_defi,	
										defi_iva_defi =  '$defi_iva_defi',
										defi_cos_defi =  '$defi_cos_defi',
										defi_cost_defi=  '$defi_cost_defi',						
									    defi_cco_defi =  '$defi_cco_defi',
										defi_lot_defi =  '$defi_lot_defi', 	
										defi_dsc_defi =  '$defi_dsc_defi', 
									    defi_prc_defi =  '$defi_prc_defi',
										defi_prd_defi =  '$defi_prd_defi',	
										defi_otr_defi =  '$defi_otr_defi',
										defi_prec_vent =  '$defi_prec_vent',
										defi_ped_defi =  '$defi_ped_defi',   
									    defi_ret_defi =  '$defi_ret_defi',  
										defi_trs_defi =  '$tran_secu',
										defi_pro_defi =  '$defi_pro_defi', 	
										defi_ctc_defi =  '$defi_ctc_defi',
									    defi_for_defi =  '$defi_for_defi',   
										defi_tip_comp =  '$defi_tip_comp',	
										defi_det_dmov =  '$defi_det_dmov',
										defi_lot_clpv =  '$defi_lot_clpv',   
									    defi_lis_prec =  '$defi_lis_prec', 
										defi_mul_empr =  '$defi_mul_empr',
										defi_ice_defi =  '$defi_ice_defi',
										defi_cod_trtc =  '$defi_cod_trtc', 
										defi_ord_iniv =  '$defi_ord_iniv',    
										defi_iva_incl =  '$defi_iva_incl',
										defi_cod_cuen =  '$defi_cod_cuen',
										defi_lis_prep =  '$defi_lis_prep', 
									    defi_pro_prov =  '$defi_pro_prov', 
										defi_sno_seri =  '$defi_sno_seri',
										defi_can_seri =  '$defi_can_seri',
										defi_des_prec =  '$defi_des_prec',
									    defi_cod_sucu =  '$tran_sucu',	
										defi_eval_defi=  '$defi_eval_defi',
										defi_ord_trab =  '$defi_ord_trab',
										defi_cie_anti =  '$defi_cie_anti',
										defi_ant_movi =  '$defi_ant_movi',
										defi_cod_tidu =  '$defi_cod_tidu',
										defi_tip_cons =  '$defi_tip_cons',
										defi_tip_rese =  '$defi_tip_rese', 
									    defi_tom_pre  =  '$defi_tom_pre',
										defi_prod_rec =  '$defi_prod_rec', 
										defi_barr_si  =  '$defi_barr_si',   	 
									  	defi_nov_mos  =  '$defi_nov_mos',  
										defi_fact_defi=  $defi_fact_defi,
										defi_mos_bode =  '$defi_mos_bode',
									   	defi_cod_libro=  '$defi_cod_libro', 
										defi_num_det  =  '$defi_num_det',   
										defi_tip_roma =  '$defi_tip_roma', 
									    defi_mat_prim =  '$defi_mat_prim', 
										defi_cod_retiva = '$defi_cod_retiva',
										defi_cod_crtr =  '$defi_cod_crtr',
										defi_ing_xml  =  '$defi_ing_xml',
									    defi_sin_fact =  '$defi_sin_fact',
										defi_mod_can = '$defi_mod_can'
										where
										defi_cod_empr =  $idempresa and
										defi_cod_defi =  $defi_cod_defi ";
		$oIfx->QueryT($sql);

		$oIfx->QueryT('COMMIT WORK;');
		$oReturn->alert('Transaccion Actualizado Correctamente...');
	} catch (Exception $e) {
		// rollback
		$oIfx->QueryT('ROLLBACK WORK;');
		$oReturn->alert($e->getMessage());
	}

	return $oReturn;
}


/* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
/* PROCESO DE REQUEST DE LAS FUNCIONES MEDIANTE AJAX NO MODIFICAR */
$xajax->processRequest();
/* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
