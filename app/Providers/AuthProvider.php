<?php

namespace Providers;

use Src\Provider\AbstractProvider;
use Src\Route;

class AuthProvider extends AbstractProvider
{

    public function register(): void
    {
    }

    public function boot(): void
    {
        $authClass = $this->app->settings->getAuthClassName();
        $identityClass = $this->app->settings->getIdentityClassName();

        $uri = substr($_SERVER['REQUEST_URI'], strlen($this->app->settings->getRootPath()));
        if (!str_starts_with($uri, 'api')) {
            $authClass::init(new $identityClass);
        }
        $this->app->bind('auth', new $authClass);
    }
}