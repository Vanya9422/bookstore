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

    if (!function_exists('back')) {
        /**
         * Возвращает пользователя на предыдущую страницу с возможностью добавления сообщения в сессию.
         *
         * @param string|array|null $message
         * @param string $type
         * @return void
         */
        function back(string|array $message = null, string $type = 'success'): void {
            if ($message) {
                session()->set($type, $message);
            }

            header('Location: ' . $_SERVER['HTTP_REFERER']);

            exit;
        }
    }

    if (!function_exists('timeElapsedString')) {
        /**
         * @param string $datetime
         * @param bool $full
         * @return string
         * @throws Exception
         */
        function timeElapsedString(string $datetime, bool $full = false): string
        {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);

            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;

            $string = array(
                'y' => 'год',
                'm' => 'месяц',
                'w' => 'неделя',
                'd' => 'день',
                'h' => 'час',
                'i' => 'минута',
                's' => 'секунда',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 'ов' : '');
                } else {
                    unset($string[$k]);
                }
            }

            if (!$full) $string = array_slice($string, 0, 1);

            return $string ? implode(', ', $string) . ' назад' : 'только что';
        }
    }

    if (!function_exists('formErrors')) {
        /**
         * @return array
         */
        function formErrors(): array
        {
            try {
                $errors = session()->get('validation_errors', []);
                $old = session()->get('old', []);
                return [$errors, $old];
            } catch (\DI\DependencyException|\DI\NotFoundException $e) {
                echo $e->getMessage();
            }
        }
    }

    if (!function_exists('session')) {
        /**
         * @return \App\Core\Session\SessionManager
         */
        function session(): \App\Core\Session\SessionManager {
            static $sessionInstance;
            if (!$sessionInstance) $sessionInstance = new \App\Core\Session\SessionManager();
            return $sessionInstance;
        }
    }

    if (!function_exists('jsone')) {

        function jsone() {
            return new class {
                public function response($data, $status = 200, $headers = []) {
                    header('Content-Type: application/json', true, $status);

                    foreach ($headers as $key => $value) {
                        header("$key: $value");
                    }

                    echo json_encode($data);
                    exit;
                }
            };
        }
    }
}