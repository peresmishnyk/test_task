<?php

namespace Peresmishnyk\Task\Services;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Renderer
{
    protected $twig;

    public function __construct(string $config_key)
    {
        $config = config($config_key);
        $template_path = $config['template_path'];
        $loader = new FilesystemLoader($template_path);
        $this->twig = new Environment($loader, $config['options']);

        $function = new \Twig\TwigFunction('route', function ($name, $params = []) {
            return route($name, $params);
        });

        $this->twig->addFunction($function);
    }

    public function setTemplateDirectory($dir)
    {
        $this->template_dir = $dir;
    }

    public function render($file, $args)
    {
        return $this->twig->render($file, $args);
    }
}