<?php

# VENDOR AUTOLOAD
require 'vendor/autoload.php';

# CARGA LA LIBRERÍA DE PDFPARSER
$parser = new \Smalot\PdfParser\Parser();

# PHPMAILER LIBRERÍA
use PHPMailer\PHPMailer\PHPMailer;

# PATRÓN DE BÚSQUEDA
$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';

# RUTA DONDE SE ENCUENTRAN LOS ARCHIVOS PDF A PROCESAR
$url_route_in_process = "./En_proceso/";

# RUTA DONDE SE ALMACENAN LOS ARCHIVOS PDF PROCESADOS
$url_route_sent = "./Success/";

# DEFINIR LA EXTENSIÓN DE LOS ARCHIVOS
$extension = ".pdf";

# CONTEO DE CADA PROCESO
$conteo = 0;

# ABRIENDO EL DIRECTORIO
$directory = opendir($url_route_in_process);

echo "INICIANDO DEL PROGRAMA..." . "\n\n\n";

# RECORRIENDO EL DIRECTORIO PARA OBTENER LOS ARCHIVOS
while ($archivo = readdir($directory)) {
    
    # COMPROBAR QUE EL ARCHIVO NO SEA UN DIRECTORIO
    if ($archivo != '.' && $archivo != '..') {
        
        echo "PROCESO #" . $conteo ++ . "\n";
        
        # LEER EL ARCHIVO
        $pdf = $parser->parseFile($url_route_in_process . $archivo);

        echo "[1.] Lee archivo $archivo ..." . "\n";

        # OBTENER EL TEXTO DEL PDF
        $text = $pdf->getText();

        echo "[2.] Obtiene datos del archivo $archivo ..." . "\n";


        # BUSCAR EL CORREO EN EL TEXTO DEL PDF Y GUARDARLO EN UNA VARIABLE $matches
        preg_match($pattern, $text, $matches);

        echo "[3.] Identificando  $matches[0] ..." . "\n";

        # IMPRIMIR EL CORREO EN PANTALLA
        echo "[4.] Correo  $matches[0] identificado ..." . "\n";

        # EXTRACTAR EL NOMBRE DEL ARCHIVO
        #$subject = explode(" ", $text);

        # IMPRIME LA DATA DEL ARCHIVO
        #var_dump($subject);

        # RENOMBRAR CADA ARCHIVO PDF CON EL CORREO ENCONTRADO
        rename($url_route_in_process . $archivo, $url_route_sent . $matches[0] . $extension);

        echo "[5.] Renombrando archivo $archivo por $matches[0] ..." . "\n";


        # ENVIAR CORREO ELECTRÓNICO CON EL PDF PROCESADO

        # INSTANCIAR LA CLASE PHPMailer
        $mail = new PHPMailer;

        # CONFIGURAR EL SERVIDOR DE CORREO
        $mail->isSMTP();

        # CONFIGURAR EL SERVIDOR DE CORREO
        $mail->Host = 'smtp.office365.com';

        # CONFIGURA LA  AUTENTICACIÓN SMTP
        $mail->SMTPAuth = true;

        # CONFIGURAR EL USUARIO DE CORREO
        $mail->Username = 'domiciliostostao@bbi.com.co';

        # CONFIGURAR LA CONTRASEÑA DE CORREO
        $mail->Password = 'Tostao2021*';

        # CONFIGURA LA SEGURIDAD TLS
        $mail->SMTPSecure = 'tls';

        # CONFIGURAR EL PORT SMTP
        $mail->Port = 587;

        # CONFIGURAR EL CORREO DE ENVIÓ
        $mail->setFrom('domiciliostostao@bbi.com.co', 'Comprobante pago de Nomina');

        # CONFIGURAR EL CORREO DE RECEPCIÓN
        // $mail->addAddress($matches[0]);
        $mail->addAddress('alberto.navarro@bbi.com.co');

        # configurar copia de correo
        $mail->addCC('esleydergranados@hotmail.com');
        $mail->addCC('btho.navarro93@gmail.com');
        $mail->addCC('ivan.rincon@bbi.com.co');

        # VALIDA SI ES HTML
        $mail->isHTML(false);

        # CONFIGURAR EL ASUNTO DEL CORREO con mes y año actual
        $mail->Subject = 'Comprobante pago de Nomina Julio de 2022';

        # CONFIGURAR EL CUERPO DEL CORREO
        $mail->Body = 'Comprobante pago de Nomina Julio de 2022';

        # CONFIGURAR EL ARCHIVO ADJUNTO
        $mail->addAttachment($url_route_sent . $matches[0] . $extension);

        # ENVIAR EL CORREO
        $mail->send();

        echo "[6.] Mail enviado al correo $matches[0] ..." . "\n\n";

        echo "PROCESO TERMINADO" . "\n\n\n";

    }
}

echo "PROGRAMA TERMINADO" . "\n\n\n";

