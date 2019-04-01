<?php if (isset($iddiagnostico)) {?>
		<input name="iddiagnostico" type="text" id="iddiagnostico" size="2" readonly="readonly" style="display: none" value="<?php echo $iddiagnostico ?>"/>
<?php } ?>
<table style="width: 980px">
	<tr>
		<td><p><span class="style_subtitulo">Informaci&oacute;n del Beneficiario</span></p></td>
	</tr>
	<tr>
		<td>
			<span class="style_texto_input"><strong>Afiliado Nro.:</strong>
				<input name="nroafiliado" type="text" id="nroafiliado" size="9" readonly="readonly" value="<?php echo $rowLeeAfiliado['nroafiliado'] ?>" class="style_input_readonly" />
			</span>
			<span class="style_texto_input"><strong>Apellido y Nombre :</strong>
				<input name="apellidoynombre" type="text" id="apellidoynombre" readonly="readonly" value="<?php echo $rowLeeAfiliado['apellidoynombre'] ?>" size="60" class="style_input_readonly"/>
				<input name="nroorden" type="text" id="nroorden" size="2" readonly="readonly" style="display: none" value="<?php echo $nroorden ?>"/>
				<input name="estafiliado" type="text" id="estafiliado" size="2" readonly="readonly" style="display: none" value="<?php echo $estafiliado ?>"/>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<span class="style_texto_input"><strong>Tipo: <?php echo $tipoAfiliado ?></strong></span>
			<span class="style_texto_input"><strong><?php echo $estadoAfiliado ?></strong></span>
		</td>
	</tr>
	<tr>
		<td>
			<span class="style_texto_input"><strong>Documento:</strong>
				<input name="nrodocumento" type="text" id="nrodocumento" readonly="readonly" value="<?php echo $rowLeeAfiliado['nrodocumento'] ?>" size="11" class="style_input_readonly"/>
			</span>
			<span class="style_texto_input"><strong>C.U.I.L.:</strong>
				<input name="cuil" type="text" id="cuil" readonly="readonly" value="<?php echo $rowLeeAfiliado['cuil'] ?>" size="11" class="style_input_readonly"/>
			</span>
			<span class="style_texto_input"><strong>Fecha Nacimiento: </strong>
				<input name="fechanacimiento" type="text" id="fechanacimiento" readonly="readonly" value="<?php echo invertirFecha($rowLeeAfiliado['fechanacimiento']) ?>" size="10" class="style_input_readonly"/>
			</span>
			<span class="style_texto_input"><strong>Edad Actual: </strong>
				<input name="edad" type="text" id="edad" readonly="readonly" value="<?php echo $rowLeeAfiliado['edadactual'] ?>" size="3" class="style_input_readonly"/>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<span class="style_texto_input"><strong>Fecha de Diagnostico:</strong>
				<input name="fechadiagnostico" type="text" id="fechadiagnostico" readonly="readonly" value="<?php echo invertirFecha($rowDiabetes['fechadiagnostico']) ?>" size="12" class="style_input_readonly"/>
			</span>
			<span class="style_texto_input"><strong>Edad al Diagnostico:</strong>
				<input name="edaddiagnostico" type="text" id="edaddiagnostico" readonly="readonly" value="<?php echo $rowDiabetes['edaddiagnostico'] ?>" size="5" maxlength="3" class="style_input_readonly"/>
			</span>		
		</td>
	</tr>
</table>