<?php

namespace Src;

use Error;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;
use Src\Auth\Auth;

class Application
{
    private Settings $settings;
    private Route $route;
    private Capsule $dbManager;
    private Auth $auth;
    //Список провайдеров приложения
    private array $providers = [];
    //Данные приложения
    private array $binds = [];

    public function __construct(Settings $settings)
    {
        //Привязываем класс со всеми настройками приложения
        $this->settings = $settings;
        $this->addProviders($settings->app['providers'] ?? []);
        $this->registerProviders();
        $this->bootProviders();
        //Привязываем класс маршрутизации с установкой префикса
        $this->route = Route::single()->setPrefix($this->settings->getRootPath());
        //Создаем класс менеджера для базы данных
        $this->dbManager = new Capsule();
        //Создаем класс для аутентификации на основе настроек приложения
        $this->auth = new $this->settings->app['auth'];

        //Настройка для работы с базой данных
        $this->dbRun();
        //Инициализация класса пользователя на основе настроек приложения
        $this->auth::init(new $this->settings->app['identity']);
    }

    //Заполнения списка провайдеров из массива
    public function addProviders(array $providers): void
    {
        foreach ($providers as $key => $class) {
            $this->providers[$key] = new $class($this);
        }
    }

    //Запуск методов register() у всех провайдеров
    private function registerProviders(): void
    {
        foreach ($this->providers as $provider) {
            $provider->register();
        }
    }

    //Запуск методов bootProviders() у всех провайдеров
    private function bootProviders(): void
    {
        foreach ($this->providers as $provider) {
            $provider->boot();
        }
    }

    //Публичный метод для добавления данных в приложение
    public function bind(string $key, $value): void
    {
        $this->binds[$key] = $value;
    }

    public function __get($key)
    {
        switch ($key) {
            case 'settings':
                return $this->settings;
            case 'route':
                return $this->route;
            case 'auth':
                return $this->auth;
        }
        if (array_key_exists($key, $this->binds)) {
            return $this->binds[$key];
        }
        throw new Error('Accessing a non-existent property');
    }

    private function dbRun()
    {
        $this->dbManager->addConnection($this->settings->getDbSetting());
        $this->dbManager->setEventDispatcher(new Dispatcher(new Container));
        $this->dbManager->setAsGlobal();
        $this->dbManager->bootEloquent();
    }

    public function run(): void
    {
        //Запуск маршрутизации
        $this->route->start();
    }
}