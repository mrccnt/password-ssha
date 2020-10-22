<?php

require_once 'vendor/autoload.php';

use MrCcnt\Password;

$hash = Password::hash('test', PASSWORD_BCRYPT);
$info = Password::getInfo($hash);
printf("Generated Hash:     %s\n", $hash);
printf("Test Password:      %s\n", Password::verify('test', $hash) ? 'Ok' : 'False');
printf("Info Algo:          %d\n", $info['algo']);
printf("Info Name:          %s\n", $info['algoName']);
printf("Info Options:       %s\n", json_encode($info['options']));
printf("Rehash bcrypt (10): %s\n", Password::needsRehash($hash, PASSWORD_BCRYPT, ['cost' => 10]) ? 'true' : 'false');
printf("Rehash bcrypt (14): %s\n", Password::needsRehash($hash, PASSWORD_BCRYPT, ['cost' => 14]) ? 'true' : 'false');
printf("Rehash ssha:        %s\n", Password::needsRehash($hash, PASSWORD_SSHA) ? 'true' : 'false');

echo PHP_EOL;

$hash = Password::hash('test', PASSWORD_SSHA);
$info = Password::getInfo($hash);
printf("Generated Hash:     %s\n", $hash);
printf("Test Password:      %s\n", Password::verify('test', $hash) ? 'Ok' : 'False');
printf("Info Algo:          %d\n", $info['algo']);
printf("Info Name:          %s\n", $info['algoName']);
printf("Info Options:       %s\n", json_encode($info['options']));
printf("Rehash bcrypt (10): %s\n", Password::needsRehash($hash, PASSWORD_BCRYPT, ['cost' => 10]) ? 'true' : 'false');
printf("Rehash bcrypt (14): %s\n", Password::needsRehash($hash, PASSWORD_BCRYPT, ['cost' => 14]) ? 'true' : 'false');
printf("Rehash ssha:        %s\n", Password::needsRehash($hash, PASSWORD_SSHA) ? 'true' : 'false');
