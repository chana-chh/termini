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

    protected function page($naziv = 'page')
    {
        $query = [];
        parse_str($this->request->getUri()->getQuery(), $query);
        $page = isset($query[$naziv]) ? (int)$query[$naziv] : 1;
        return $page;
    }

    protected function data()
    {
        $data = $this->request->getParams();
        unset($data['csrf_name']);
        unset($data['csrf_value']);
        return $data;
    }

    protected function dataId(&$data, $id = 'id')
    {
        /*
            Ovo je kod izmene kad se uklanja id iz data
            ali da bi radilo mora prvo da se pozove $this->data()
            pa da se prosledi ovde kao $data, ako id u data (formi) nema naziv id
            onda se dodaje i naziv id-a kao drugi parametar

            Koliko je ovo korisno/zbunjujuce i kako bi se objedinilo?
        */
        $id = $data[$id];
        unset($data[$id]);
        return $id;
    }
}
