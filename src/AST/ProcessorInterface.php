<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 18:45
 */

namespace Kix\GeneratorBundle\AST;

interface ProcessorInterface
{

    public function process($ast);

}