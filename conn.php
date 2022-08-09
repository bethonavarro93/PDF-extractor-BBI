<?php

function get_email($email_like)
{
    // conexion php pdo
    $db = new PDO('mysql:host=localhost;dbname=UnoEE', 'Mercanet', 'killsa');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # consulta sql server pdo
    $sql = "SELECT DISTINCT f015_email FROM [dbo].[t015_mm_contactos] WHERE f015_email like'%$email_like%'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}
