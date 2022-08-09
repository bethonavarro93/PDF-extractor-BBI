<?php




function get_email($email_like)
{
    $DB_DRIVER = "sqlsrv";
    $DB_HOST = "192.168.20.219";
    $DB_NAME = "UnoEE";
    $DB_USER = "mercanet";
    $DB_PASS = "killsa";

    $arrOptions = array(
        PDO::ATTR_EMULATE_PREPARES => FALSE,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
    );

    try {
        $conn = new PDO($DB_DRIVER . ":server=" . $DB_HOST . ";database=" . $DB_NAME, $DB_USER, $DB_PASS, $arrOptions);
    } catch (Exception $error) {
        die("El error de conexión es : " . $error->getMessage());
    }

    # CONSULTA SQL SERVER
    $sql = "SELECT DISTINCT f015_email AS mail FROM [dbo].[t015_mm_contactos] WHERE f015_email like'%$email_like%'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
