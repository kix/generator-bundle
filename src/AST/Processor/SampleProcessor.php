<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 18:44
 */

namespace Kix\GeneratorBundle\AST\Processor;

use Kix\GeneratorBundle\AST\ProcessorInterface;

class SampleProcessor implements ProcessorInterface
{
    public function process($ast)
    {
        return $ast;
    }


}