<?php
$file = file_get_contents('resources/views/dashboard/client.blade.php');

// Supprimer les marqueurs de conflit en gardant le code "HEAD" (votre version)
$file = preg_replace('/<<<<<<< HEAD.*?=======/s', '', $file);
$file = preg_replace('/>>>>>>> .*/m', '', $file);

file_put_contents('resources/views/dashboard/client.blade.php', $file);
echo "Conflits nettoyés!\n";
