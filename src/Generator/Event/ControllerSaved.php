<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 18:29
 */

namespace Kix\GeneratorBundle\Generator\Event;

class ControllerSaved extends GeneratorEvent
{

    const EVENT_NAME = 'controller.saved';

    /**
     * @var ControllerSpec
     */
    private $spec;

    /**
     * @var string
     */
    private $file;

    public function __construct($spec, $file)
    {
        $this->spec = $spec;
        $this->file = $file;
    }

    /**
     * @return \Kix\GeneratorBundle\Generator\ControllerSpec
     */
    public function getSpec()
    {
        return $this->spec;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

}