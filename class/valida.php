<?php
class validacion
{
	private $url;
	private $mail;
	private $letra;
	private $numero;
	
	public function campos_vacio(){
		 foreach ($_POST as $indice => $valor) {
			if ($valor==""){
				?>
				<script language="JavaScript" type="text/javascript">
					alert ("Campos vac\u00EDos"); 
				</script>
				<?
            	exit();
			}
		}
	}
	//validacion formato de correo 
	public function valida_correo($correo){
		$this->mail=filter_var($correo,FILTER_VALIDATE_EMAIL);
		if (!$this->mail==true){
			?>
			<script language="JavaScript" type="text/javascript">
				alert ("Correo inv\u00E1lido"); 
			</script>
			<?
			exit();
			
		}
	}
	public function valida_correo_antiguo($correo){ 
   		if (ereg("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$", $correo ) ) {  
   		} 
		else {
			?>
			<script language="JavaScript" type="text/javascript">
				alert ("Correo inv\u00E1lido"); 
			</script>
			<?  
			exit();
   		} 
	}
	
	//validacion formato de correo
	public function valida_fono($fono){
		$this->numero=filter_var($fono,FILTER_VALIDATE_INT);
		if (!$this->numero==true){
			?>
			<script language="JavaScript" type="text/javascript">
				alert ("Telefono inv\u00E1lido"); 
			</script>
			<?
			exit();
		}
	}
	
	
	
	//---------VALIDACION FORMATO RUT-------------
	public function formato_rut($rut_param){
		$parte4 = substr($rut_param, -1); // seria solo el numero verificador 
    	$parte3 = substr($rut_param, -4,3); // la cuenta va de derecha a izq  
    	$parte2 = substr($rut_param, -7,3);  
        $parte1 = substr($rut_param, 0,-7); //de esta manera toma todos los caracteres desde el 8 hacia la izq 
		$rut=$parte1.".".$parte2.".".$parte3."-".$parte4;
		return $rut;
	}
	
	public function valida_rut($r){
		$r=strtoupper(preg_replace('/(\.)|(\-)|[ ]|[\,]|[\']/','',$r));
		$sub_rut=substr($r,0,strlen($r)-1);
		$sub_dv=substr($r,-1);
		$x=2;
		$s=0;
		for ( $i=strlen($sub_rut)-1;$i>=0;$i-- ){
			if ( $x >7 ){
				$x=2;
			}
			$s += $sub_rut[$i]*$x;
			$x++;
		}
		$dv=11-($s%11);
		if ( $dv==10 ){
			$dv='K';
		}
		if ( $dv==11 ){
			$dv='0';
		}
		if ( $dv==$sub_dv ){
			$id=$this->formato_rut($r);
			return $id;
		}
		else{
			?>
			<script language="JavaScript" type="text/javascript">
				alert ("Rut inv&aacutelido"); 
			</script>
			<?
			exit();
		}
	}
	
	//*****************************-VALIDACION LARGO DE CARACTERES-*********************************
	
	public function largo_5_60($cadena){
		$cantidad = strlen($cadena);
		if($cantidad > 60 || $cantidad < 5){
			?>
			<script language="JavaScript" type="text/javascript">
				alert ("Campo: máximo 60 y minimo 5 caracteres"); 
			</script>
			<?
			exit();
		}
	}
	public function largo_5_150($cadena){
		$cantidad = strlen($cadena);
		if($cantidad > 150 || $cantidad < 5){
			?>
			<script language="JavaScript" type="text/javascript">
				alert ("Campo: máximo 150 y minimo 5 caracteres"); 
			</script>
			<?
			exit();
		}
	}
	public function tiempo_sesion(){
		$fecha_inicio = $_SESSION["tiempo_sesion"];
    	$fecha_actual = date("Y-n-j H:i:s");
    	$tiempo = (strtotime($fecha_actual)-strtotime($fecha_inicio));
		//comparamos el tiempo transcurrido
    	 if($tiempo >= 600) {
    	 	 session_destroy(); // destruyo la sesión
    	 	 header("Location: ../index.php"); //envío al usuario a la pag. de autenticación
      		//sino, actualizo la fecha de la sesión
    	}
		else {
    		$_SESSION["tiempo_sesion"] = $fecha_actual;
   		} 
	}
}
?>