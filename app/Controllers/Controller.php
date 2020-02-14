<?php

namespace App\Controllers;

class Controller
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if ($this->container->get($property)) {
            return $this->container->get($property);
        }
    }

    protected function render($response, $template, $vars = [])
    {
        $this->container->view->render($response, $template, $vars);
    }

    protected function renderPartial($template, $vars = [])
    {
        return $this->view->getEnvironment()->render($template, $vars);
    }

    protected function addCsrfToken(array &$data)
    {
        $data['csrf_name']=$this->csrf->getTokenName();
        $data['csrf_value']=$this->csrf->getTokenValue();
    }

    protected function log($tip, $model, $polje, $model_stari = null)
    {
        $this->logger->log($tip, $model, $polje, $model_stari);
    }
}
