<?php
require 'vendor/autoload.php';
$parser = new \Smalot\PdfParser\Parser();
$pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';


// // Parse PDF file and build necessary objects.
// $parser = new \Smalot\PdfParser\Parser();
// $pdf = $parser->parseFile('En_proceso/nomRptVolantePago (2)_extractPDFpages_Page0003.pdf');
// $text = $pdf->getText();
// echo "Texto del PDF: <br>";
// var_dump($text);
// echo "<br><br>Extracción del correo <br><br>";
// $pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';
// preg_match($pattern, $text, $matches);
// echo "<h1><code>" . $matches[0] . "</code></h1>";
// $subject = explode(" ", $text);
// echo $subject[29];
// var_dump($subject);
// print_r($subject[0]);


# FASE 1: Extraer el correo del PDF

# FASE 2: Renombrar el PDF con el correo extraído

$directorio = opendir("./En_proceso");
while ($archivo = readdir($directorio)) {

    if($archivo != '.' && $archivo != '..'){
        $pdf = $parser->parseFile("./En_proceso/$archivo");
        $text = $pdf->getText();
        preg_match($pattern, $text, $matches);
        echo "<h1><code>" . $matches[0] . "</code></h1>";
        $subject = explode(" ", $text);
        echo $subject[29];
        var_dump($subject);
        print_r($subject[0]);
        rename("./En_proceso/$archivo", "./Enviados/$matches[0].pdf");
    }


    // echo "<h1>".$archivo."</h1> <br>";

    // $pdf = $parser->parseFile("En_proceso/$archivo");
    // $text = $pdf->getText();
    // preg_match($pattern, $text, $matches);
    // echo "<h1><code>" . $matches[0] . "</code></h1>";


    // // Comprobamos que el archivo no sea un directorio
    // if (!is_dir($archivo)) {

    //     $nuevoNombre = preg_replace('/[0-9]+/', '', $archivo);
    //     $rutaArchivo1 = "En_proceso/" . $archivo;
    //     $rutaArchivo2 = "Enviados/" . $nuevoNombre;
    //     if (rename($rutaArchivo1, $rutaArchivo2)) {

    //         echo ("El archivo " . $rutaArchivo1 . " se ha renombrado a " . $rutaArchivo2);
    //     } else {
    //         echo ("El archivo " . $rutaArchivo1 . " no se ha renombrado correctamente");
    //     }
    // }
}


// rename ("En_proceso/nomRptVolantePago (2)_extractPDFpages_Page0003.pdf", "Enviados/$matches[0].pdf");


# FASE 3: Enviar el correo con el PDF adjunto