<?php

use Model\Employee;
use Model\User;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Src\Auth\Auth;
class SiteTest extends TestCase
{
    public static function EmployeeMock()
    {
        return [
            'first_name' => 'тест',
            'last_name' => 'тест',
            'patronymic' => 'тест',
            'inn' => '1111111111',
            'snils' => '11111111111',
            'account_number' => '1111111111111111'
        ];
    }

    public static function FinancistUserMock()
    {
        return [
            'login' => md5(date('YmdHis').rand(0, 1000)),
            'password' => md5('test'),
            'role_id' => 2
        ];
    }

    public static function AdminUserMock()
    {
        return [
            'login' => md5(date('YmdHis').rand(0, 1000)),
            'password' => md5('test'),
            'role_id' => 1
        ];
    }
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

    //Метод, возвращающий набор тестовых данных
    public static function additionProviderAccrual(): array
    {
        return [
            ['GET', ['name' => '', 'amount' => '', 'month_left' => ''],
                '<h3 class="message"></h3>'
            ],
            ['POST', ['name' => '', 'amount' => '', 'month_left' => ''],
                [
                    '<span class="error">Поле name обязательно</span>',
                    '<span class="error">Поле amount обязательно</span>',
                    '<span class="error">Поле month_left обязательно</span>',
                ],
            ],
            ['POST', ['name' => 'Премия за буйство', 'amount' => 2500, 'month_left' => 3],
                '<h3 class="message">Надбавка успешно добавлена</h3>',
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

    #[DataProvider('additionProviderAccrual')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function testAddAcrual(string $httpMethod, array $userData, string | array $message): void
    {
        $employee = Employee::create(self::EmployeeMock());
        $user = User::create(array_merge(
            self::FinancistUserMock(),
            ['employee_id' => $employee->id],
        ));
        // Создаем заглушку для класса Request.
        $request = $this->createMock(Src\Request::class);
        // Переопределяем метод all() и свойство method
        $userData = array_merge(
            $userData,
            ['id' => $employee->id],
        );
        $request->expects($this->any())
            ->method('all')
            ->willReturn($userData);
        $request->method = $httpMethod;
        $request->set('id', $employee->id);
        Auth::login($user);

        //Сохраняем результат работы метода в переменную
        $result = (new Controllers\Site())->addAccrual($request);

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

        $employee->delete();
        $user->delete();
    }
}