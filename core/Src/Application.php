<?php

namespace Src;
class Application
{
    public Settings $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function run(): void
    {
        echo $this->settings->getRootPath();
    }
}