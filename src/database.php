<?php

require __DIR__ . '/../vendor/autoload.php';
use ParagonIE\EasyDB\EasyDB;

define("SITE_KEY", "dfertyuiooiuyb556");

function connect() {
    $db = \ParagonIE\EasyDB\Factory::create('mysql:host=localhost;dbname=nafdac', 'root', 'root');
    return $db;
}

function apiToken($session_uid) {
    $key = md5(SITE_KEY.$session_uid);
    return hash('sha256', $key.$_SERVER['REMOTE_ADDR']);
}

// function close_connection() {
//     $db = connect();
//     $db = null;
//     return true;
// }

// $rows = $db->run('SELECT * FROM comments WHERE blogpostid = ? ORDER BY created ASC', $_GET['blogpostid']);
// foreach ($rows as $row) {
//     $template_engine->render('comment', $row);
// }
?>