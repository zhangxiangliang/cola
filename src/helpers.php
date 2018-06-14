<?php

if (!function_exists('templates')) {
    function templates()
    {
        $dotenv = new \Symfony\Component\Dotenv\Dotenv;
        $paths = [getenv("HOME").'/.cola', __DIR__.'/../.env'];
        $templates = [];

        foreach ($paths as $path) {
            if (!is_file($path)) {
                continue;
            }

            $dotenv->load($path);
            $origin = explode(',', getenv('SYMFONY_DOTENV_VARS'));

            foreach ($origin as $key) {
                $templates[$key] = getenv($key);
            }
        }

        return $templates;
    }
}
