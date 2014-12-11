<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 15:05
 */

namespace Kix\GeneratorBundle\Generator;

class ViewSpec
{

    private $bundle;

    private $controller;

    private $action;

    public function __construct($controller, $action)
    {
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

}