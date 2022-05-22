<?php

$file = $_FILES['upload'];
$dir = '/img/ckeditor_uploads/';
$basename = basename($file['name']);
$explode = explode('.', $basename);
array_shift($explode);
$ext = strtolower(implode('.', $explode));

$filename = uniqid('', false) . '.' . $ext;
$filepath = $dir . $filename;

if (move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $filepath)) {
    echo '{"url": "'.$filepath.'"}';
}
exit;