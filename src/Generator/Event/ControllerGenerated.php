<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 14:58
 */

namespace Kix\GeneratorBundle\Generator\Event;

use PhpParser\Node;
use Kix\GeneratorBundle\Generator\ControllerSpec;

class ControllerGenerated extends GeneratorEvent
{

    const EVENT_NAME = 'controller.generated';

    /**
     * @var ControllerSpec
     */
    private $controllerSpec;

    /**
     * @var Node[]
     */
    private $ast;

    /**
     * @param ControllerSpec $controllerSpec
     * @param Node[]         $ast
     */
    public function __construct(ControllerSpec $controllerSpec, $ast)
    {
        $this->controllerSpec = $controllerSpec;
        $this->ast = $ast;
    }

    /**
     * @return ControllerSpec
     */
    public function getControllerSpec()
    {
        return $this->controllerSpec;
    }

    /**
     * @return Node[]
     */
    public function getAst()
    {
        return $this->ast;
    }



}