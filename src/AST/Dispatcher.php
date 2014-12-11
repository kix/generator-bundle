<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 18:48
 */

namespace Kix\GeneratorBundle\AST;

use Kix\GeneratorBundle\AST\ProcessorInterface;

class Dispatcher
{

    private $listeners = array();

    public function addProcessor(ProcessorInterface $listener)
    {
        $this->listeners []= $listener;
    }

    public function process($ast)
    {
        foreach ($this->listeners as $listener) {
            $ast = $listener->process($ast);
        }

        return $ast;
    }

}