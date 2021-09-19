<?php

if (!function_exists('checkAttemps')) {
    function saveAttemp(string $sessionUser, string $pathToSaveFile, int $maxAttemps = 5, int $timeExpire = 60)
    {

        if (!file_exists($pathToSaveFile) || !is_dir($pathToSaveFile)) {
            mkdir($pathToSaveFile, '0777');
        }

        $file = $pathToSaveFile . DIRECTORY_SEPARATOR . md5($sessionUser) . '.json';

        if (!file_exists($file) || !is_file($file)) {
            $handlerFile = fopen($file, 'a+');
            fclose($handlerFile);
        }

        $data = file_get_contents($file);
        $data = json_decode($data, true);

        $saveLog = function (string $file, array $data) {
            file_put_contents($file, json_encode($data));
        };

        if (empty($data) || (isset($data['last_try']) && $data['last_try'] < strtotime('now'))) {
            $data = [
                'attemp' => 1,
                'last_try' => strtotime('now') + $timeExpire
            ];
            $saveLog($file, $data);
            return true;
        }

        if ($data['attemp'] >= $maxAttemps) {
            $data = [
                'attemp' => $data['attemp'],
                'last_try' => strtotime('now') + $timeExpire
            ];
            $saveLog($file, $data);
            return false;
        }

        $data = [
            'attemp' => $data['attemp'] + 1,
            'last_try' => strtotime('now') + $timeExpire
        ];
        $saveLog($file, $data);
        return true;
    }

    function removeAttemp(string $sessionUser, string $pathToSaveFile)
    {
        $file = $pathToSaveFile . DIRECTORY_SEPARATOR . md5($sessionUser) . '.json';
        if (file_exists($file) && is_file($file)) {
            unlink($file);
        }
    }
}
