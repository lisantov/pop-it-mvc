<?php
//Путь до директории с конфигами
const DIR_CONFIG = '/../config';

//Добавляем пользовательскую функцию автозагрузки классов
spl_autoload_register(function ($className) {
    $paths = include __DIR__ . DIR_CONFIG . '/path.php';
    $className = str_replace('\\', '/', $className);

    foreach ($paths['classes'] as $path) {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/$paths[root]/$path/$className.php")) {
            require_once $_SERVER['DOCUMENT_ROOT'] . "/$paths[root]/$path/$className.php";
        }
    }
});

//Функция, возвращающая массив всех настроек приложения
function getConfigs(string $path = DIR_CONFIG): array
{
    $settings = [];
    foreach (scandir(__DIR__ . $path) as $file) {
        $name = explode('.', $file)[0];
        if (!empty($name)) {
            $settings[$name] = include __DIR__ . "$path/$file";
        }
    }
    return $settings;
}

return new Src\Application(new Src\Settings(getConfigs()));