<?php


namespace greenweb\addon\controller;


use greenweb\addon\Addon;

class ClientController extends Controller
{
    public function view($template, $title, $breadcrump, $data, $requirelogin = false)
    {
        $uri = Addon::getTemplateUri($template);

        return [
            'pagetitle'    => $title,
            'breadcrumb'   => [$this->vars['modulelink'] => $breadcrump],
            'templatefile' => $this->app->config['ClientViewTemplatePath'].$uri,
            'requirelogin' => $requirelogin,
            'vars'         => $data,
        ];
    }
}