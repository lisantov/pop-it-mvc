<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SiteTest extends TestCase
{
    //Метод, возвращающий набор тестовых данных
    public static function additionProviderLogin(): array
    {
        return [
            ['GET', ['login' => '', 'password' => ''],
                '<span class="error"></span>'
            ],
            ['POST', ['login' => '', 'password' => ''],
                [
                    '<span class="error">Поле login обязательно</span>',
                    '<span class="error">Поле password обязательно</span>',
                ],
            ],
            ['POST', ['login' => 'admin', 'password' => 'wrong_password'],
                '<h3 class="message">Неправильные логин или пароль</h3>',
            ],
            ['POST', ['login' => 'admin', 'password' => 'admin'],
                'Вы уже вошли в аккаунт',
            ],
        ];
    }

    protected function setUp(): void
    {
        //Установка переменной среды
        $_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/..';

       //Создаем экземпляр приложения
       $GLOBALS['app'] = new Src\Application(new Src\Settings([
           'app' => include $_SERVER['DOCUMENT_ROOT'] . '/config/app.php',
           'db' => include $_SERVER['DOCUMENT_ROOT'] . '/config/db.php',
           'path' => include $_SERVER['DOCUMENT_ROOT'] . '/config/path.php',
       ]));

       //Глобальная функция для доступа к объекту приложения
       if (!function_exists('app')) {
           function app()
           {
               return $GLOBALS['app'];
           }
       }
    }

    #[DataProvider('additionProviderLogin')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function testLogin(string $httpMethod, array $userData, string | array $message): void
    {
        // Создаем заглушку для класса Request.
        $request = $this->createMock(Src\Request::class);
        // Переопределяем метод all() и свойство method
        $request->expects($this->any())
            ->method('all')
            ->willReturn($userData);
        $request->method = $httpMethod;

        //Сохраняем результат работы метода в переменную
        $result = (new Controllers\Site())->login($request);

        if (!empty($result)) {
            //Проверяем варианты с ошибками валидации
            if (gettype($message) === 'array') {
                foreach($message as $mes) {
                    $mes = '/' . preg_quote($mes, '/') . '/';
                    $this->expectOutputRegex($mes);
                }
                return;
            } else {
                $message = '/' . preg_quote($message, '/') . '/';
                $this->expectOutputRegex($message);
                return;
            }
        }
    }
}