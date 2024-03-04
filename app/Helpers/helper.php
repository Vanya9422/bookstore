<?php

if (!function_exists('config')) {
    /**
     * Получение значения конфигурации.
     *
     * @param string $path Путь в точечной нотации, например 'repositories.bindings'.
     * @return mixed
     * @throws Exception
     */
    function config(string $path) {
        // Определяем разделитель точечной нотации
        $separator = '.';

        // Разделяем путь на части
        $keys = explode($separator, $path);

        // Подгружаем файл конфигурации на основе первого ключа
        $file = array_shift($keys);
        $configFile = __DIR__ . "/../../config/{$file}.php";

        // Проверяем, существует ли файл конфигурации
        if (!file_exists($configFile)) {
            throw new Exception("Config file {$file} does not exist.");
        }

        $config = require $configFile;

        // Итеративно уменьшаем массив, используя $keys для получения желаемого значения
        foreach ($keys as $key) {
            if (!isset($config[$key])) {
                throw new Exception("Config key {$path} does not exist.");
            }
            $config = $config[$key];
        }

        return $config;
    }

    if (!function_exists('env')) {
        /**
         * Получение значения переменной окружения.
         *
         * @param string $key Ключ переменной окружения.
         * @param mixed $default Значение по умолчанию, если переменная не установлена.
         * @return mixed
         */
        function env(string $key, $default = null) {
            $value = $_ENV[$key] ?? false;
            if ($value === false) {
                $value = getenv($key);
            }

            if ($value === false) {
                return $default;
            }

            // Обрабатываем значения, которые должны быть булевыми
            switch (strtolower($value)) {
                case 'true':
                case '(true)':
                    return true;
                case 'false':
                case '(false)':
                    return false;
                case 'empty':
                case '(empty)':
                    return '';
                case 'null':
                case '(null)':
                    return null;
            }

            // Обрабатываем кавычки, если они есть
            if (preg_match('/^"(.+)"$/', $value, $matches)) {
                return $matches[1];
            }

            return $value;
        }
    }

    if (!function_exists('findFiles')) {
        /**
         * @param string $folder
         * @return void
         */
        function findFiles(string $folder): void
        {
            $files = scandir($folder);

            foreach ($files as $file) {
                // Игнорируем текущую и родительскую директории
                if ($file != '.' && $file != '..') {
                    $path = $folder . '/' . $file;
                    if (is_file($path)) {
                        include_once $path;
                    } elseif (is_dir($path)) {
                        // Если это директория, вызываем функцию рекурсивно
                        findFiles($path);
                    }
                }
            }
        }
    }
}