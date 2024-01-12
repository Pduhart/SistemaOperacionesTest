<div class="col-sm-12" style="margin-top: 10px;">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">FICHA INFORMACIÓN CLIENTE</h3>
        </div>
        <div class="box-body no-padding">
            <?php
            $consulta = 
                "
                SELECT 
                    pro.id_pro,
                    pro.nombre_pro,
                    pro.nombre2_pro,
                    pro.apellido_paterno_pro,
                    pro.apellido_materno_pro,
                    pro.rut_pro,
                    pro.pasaporte_pro,
                    pro.fono_pro,
                    pro.fono2_pro,
                    pro.direccion_pro,
                    pro.direccion_trabajo_pro,
                    pro.correo_pro,
                    pro.correo2_pro,
                    pro.fecha_nacimiento_pro,
                    pro.profesion_promesa_pro,
                    nac.id_nac,
                    nac.nombre_nac,
                    com.id_com,
                    com.nombre_com,
                    reg.id_reg,
                    reg.nombre_reg,
                    sex.id_sex,
                    sex.nombre_sex,
                    civ.id_civ,
                    civ.nombre_civ,
                    est.id_est,
                    est.nombre_est,
                    prof.id_prof,
                    prof.nombre_prof
                FROM 
                    propietario_propietario AS pro
                    LEFT JOIN nacionalidad_nacionalidad AS nac ON nac.id_nac = pro.id_nac
                    INNER JOIN region_region AS reg ON reg.id_reg = pro.id_reg
                    INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com
                    INNER JOIN sexo_sexo  AS sex ON sex.id_sex = pro.id_sex
                    LEFT JOIN civil_civil  AS civ ON civ.id_civ = pro.id_civ
                    LEFT JOIN estudio_estudio  AS est ON est.id_est = pro.id_est
                    LEFT JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof
                WHERE 
                    pro.id_pro = ?
                ";
            $conexion->consulta_form($consulta,array($id_pro));
            $fila = $conexion->extraer_registro_unico();
            ?>
            <table class="table table-condensed" style="font-size: 16px;">
                <tbody>
                    <tr>
                        <th class="cabecera_tabla active">Rut</th>
                        <td><?php echo utf8_encode($fila['rut_pro']);?></td>

                        <th class="cabecera_tabla active">Fono</th>
                        <td><?php echo utf8_encode($fila['fono_pro']);?></td>

                        <th class="cabecera_tabla active">Pasaporte</th>
                        <td><?php echo utf8_encode($fila['pasaporte_pro']);?></td>

                        <th class="cabecera_tabla active">Sexo</th>
                        <td><?php echo utf8_encode($fila['nombre_sex']);?></td>
                    </tr>
                    <tr>
                        <th class="cabecera_tabla active">Primer Nombre</th>
                        <td><?php echo utf8_encode($fila['nombre_pro']);?></td>

                        <th class="cabecera_tabla active">Fono 2</th>
                        <td><?php echo utf8_encode($fila['fono2_pro']);?></td>

                        <th class="cabecera_tabla active">Dirección Trabajo</th>
                        <td><?php echo utf8_encode($fila['direccion_trabajo_pro']);?></td>

                        <th class="cabecera_tabla active">Nacionalidad</th>
                        <td><?php echo utf8_encode($fila['nombre_nac']);?></td>

                    </tr>
                    <tr>
                        <th class="cabecera_tabla active">Segundo Nombre</th>
                        <td><?php echo utf8_encode($fila['nombre2_pro']);?></td>

                        <th class="cabecera_tabla active">Correo</th>
                        <td><?php echo utf8_encode($fila['correo_pro']);?></td>

                        <th class="cabecera_tabla active">Estado Civil</th>
                        <td><?php echo utf8_encode($fila['nombre_civ']);?></td>

                        <th class="cabecera_tabla active">Región</th>
                        <td><?php echo utf8_encode($fila['nombre_reg']);?></td>
                    </tr>
                    <tr>
                        <th class="cabecera_tabla active">Apellido Paterno</th>
                        <td><?php echo utf8_encode($fila['apellido_paterno_pro']);?></td>

                        <th class="cabecera_tabla active">Correo 2</th>
                        <td><?php echo utf8_encode($fila['correo2_pro']);?></td>

                        <th class="cabecera_tabla active">Estudios</th>
                        <td><?php echo utf8_encode($fila['nombre_est']);?></td>

                        <th class="cabecera_tabla active">Comuna</th>
                        <td><?php echo utf8_encode($fila['nombre_com']);?></td>
                    </tr>
                    <tr>
                        <th class="cabecera_tabla active">Apelllido Materno</th>
                        <td><?php echo utf8_encode($fila['apellido_materno_pro']);?></td>

                        <th class="cabecera_tabla active">Dirección</th>
                        <td><?php echo utf8_encode($fila['direccion_pro']);?></td>

                        <th class="cabecera_tabla active">Profesión</th>
                        <td><?php echo utf8_encode($fila['nombre_prof']);?></td>

                        <th class="cabecera_tabla active">Fecha Nacimiento</th>
                        <td><?php echo date("d/m/Y",strtotime($fila['fecha_nacimiento_pro']));?></td>
                    </tr>
                    <tr>
                        <th class="cabecera_tabla active">Prof. para Promesa</th>
                        <td><?php echo utf8_encode($fila['profesion_promesa_pro']);?></td>

                        <th class="cabecera_tabla active"></th>
                        <td></td>

                        <th class="cabecera_tabla active"></th>
                        <td></td>

                        <th class="cabecera_tabla active"></th>
                        <td></td>
                    </tr>
                </tbody>
            </table>  

        </div>
    </div>
</div>