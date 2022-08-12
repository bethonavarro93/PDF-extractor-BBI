<?php

# VENDOR AUTOLOAD
require 'vendor/autoload.php';

# CARGA LA LIBRERÍA DE PDFPARSER
$parser = new \Smalot\PdfParser\Parser();

# CARGA LA LIBRERÍA DE PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;

# PATRÓN DE BÚSQUEDA DE CORREO ELECTRÓNICO
$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';

# RUTA DONDE SE ENCUENTRAN LOS ARCHIVOS PDF A PROCESAR
$url_route_in_process = "./En_proceso_nomina/";

# RUTA DONDE SE ALMACENAN LOS ARCHIVOS PDF PROCESADOS
$url_route_sent = "./Success_nomina/";

# DEFINIR LA EXTENSIÓN DE LOS ARCHIVOS
$extension = ".pdf";

# CONTEO DE CADA PROCESO
$conteo = 0;

# ABRIENDO EL DIRECTORIO
$directory = opendir($url_route_in_process);

echo "<br><br><br>INICIANDO DEL PROGRAMA..." . "\n\n\n";

# RECORRIENDO EL DIRECTORIO PARA OBTENER LOS ARCHIVOS
while ($archivo = readdir($directory)) {

    # COMPROBAR QUE EL ARCHIVO NO SEA UN DIRECTORIO
    if ($archivo != '.' && $archivo != '..') {

        echo "<br>PROCESO #" . $conteo++ . "\n";

        # LEER EL ARCHIVO
        $pdf = $parser->parseFile($url_route_in_process . $archivo);

        echo "<br>[1.] Lee archivo $archivo ..." . "\n";

        # OBTENER EL TEXTO DEL PDF
        $text = $pdf->getText();

        echo "<br>[2.] Obtiene datos del archivo $archivo ..." . "\n";


        # BUSCAR EL CORREO EN EL TEXTO DEL PDF Y GUARDARLO EN UNA VARIABLE $matches
        preg_match($pattern, $text, $matches);

        echo $matches[0];

        if ($matches[0] == NULL) {

            // crear archivo de error log
            $logFile = fopen("log_nomina.log", 'a+') or die("Error creando archivo");
            fwrite($logFile, "\n\n\n" . date("d/m/Y H:i:s") . " El PDF $archivo no se pudo enviar por el error ") or die("Error escribiendo en el archivo");
            fclose($logFile);
        } else {

            echo "<br>[3.] Identificando  $matches[0] ..." . "\n";

            # IMPRIMIR EL CORREO EN PANTALLA
            echo "<br>[4.] Correo  $matches[0] identificado ..." . "\n";

            # EXTRACTAR EL NOMBRE DEL ARCHIVO
            #$subject = explode(" ", $text);

            # IMPRIME LA DATA DEL ARCHIVO
            #var_dump($subject);

            # RENOMBRAR CADA ARCHIVO PDF CON EL CORREO ENCONTRADO
            rename($url_route_in_process . $archivo, $url_route_sent . $matches[0] . $extension);

            echo "<br>[5.] Renombrando archivo $archivo por $matches[0] ..." . "\n";


            # ENVIAR CORREO ELECTRÓNICO CON EL PDF PROCESADO

            # INSTANCIA LA CLASE PHPMailer
            $mail = new PHPMailer;

            # CONFIGURAR EL SERVIDOR DE CORREO
            $mail->isSMTP();

            # CONFIGURAR EL SERVIDOR DE CORREO
            $mail->Host = 'smtp.gmail.com';

            # CONFIGURA LA  AUTENTICACIÓN SMTP
            $mail->SMTPAuth = true;

            # CONFIGURAR EL USUARIO DE CORREO
            $mail->Username = 'nomina@bbi.com.co';

            # CONFIGURAR LA CONTRASEÑA DE CORREO
            // $mail->Password = 'BBI2022*';
            $mail->Password = 'xrzf uaqi xfir quiq';

            # CONFIGURA LA SEGURIDAD TLS
            $mail->SMTPSecure = 'tls';

            # CONFIGURAR EL PORT SMTP
            $mail->Port = 587;

            # CONFIGURAR EL CORREO DE ENVIÓ
            $mail->setFrom('nomina@bbi.com.co', 'Nomina BBI');

            # CONFIGURAR EL CORREO DE RECEPCIÓN
            //$mail->addAddress($matches[0]);
            $mail->addAddress('alberto.navarro@bbi.com.co');

            # configurar copia de correo
            // $mail->addCC('esleydergranados@hotmail.com');
            // $mail->addCC('btho.navarro93@gmail.com');
            // $mail->addCC('ivan.rincon@bbi.com.co');

            # configurar copia oculta de correo
            $mail->addBCC('nomina@bbi.com.co');

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

            echo "<br>[6.] Mail enviado al correo $matches[0] ..." . "\n\n";

            echo "<br><br><br>PROCESO TERMINADO" . "\n\n\n";
        }
    }
}

echo "<br><br><br>PROGRAMA TERMINADO" . "\n\n\n";
