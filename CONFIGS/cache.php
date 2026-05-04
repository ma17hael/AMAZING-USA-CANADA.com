<?php

function cache_get($key) {
    $file = sys_get_temp_dir() . "/amz_cache_" . md5($key);

    if (!file_exists($file)) {
        return false;
    }

    $data = file_get_contents($file);
    $cache = unserialize($data);

    if ($cache['expires'] < time()) {
        unlink($file);
        return false;
    }

    return $cache['value'];
}

function cache_set($key, $value, $ttl = 3600) {
    $file = sys_get_temp_dir() . "/amz_cache_" . md5($key);

    $data = [
        'expires' => time() + $ttl,
        'value' => $value
    ];

    file_put_contents($file, serialize($data));
}