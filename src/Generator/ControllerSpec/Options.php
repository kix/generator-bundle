<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 19:10
 */

namespace Kix\GeneratorBundle\Generator\ControllerSpec;

class Options
{

    private $strictOOP = false;

    private $abstract = false;

    public function __construct($strictOOP = false, $abstract = false)
    {
        $this->strictOOP = $strictOOP;
        $this->abstract = $abstract;
    }

    public function isStrictOOP()
    {
        return $this->strictOOP === true;
    }

}