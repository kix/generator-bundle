<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 14:45
 */

namespace Kix\GeneratorBundle\Generator;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class ControllerSpec
{

    /**
     * @var BundleInterface
     */
    private $bundle;

    private $name;

    private $actions;

    private $baseClass;

    private $options;

    public function __construct(BundleInterface $bundle, $name, array $actions = array(), ControllerSpec\Options $options = null)
    {
        $this->bundle = $bundle;
        $this->name = $name;
        $this->actions = $actions;
        $this->baseClass = array('Symfony', 'Bundle', 'FrameworkBundle', 'Controller', 'Controller');

        if ($options) {
            $this->options = $options;
        } else {
            $this->options = new ControllerSpec\Options();
        }
    }

    /**
     * @return string|array
     */
    public function getBaseClass()
    {
        return $this->baseClass;
    }

    /**
     * @param string|array $baseClass
     */
    public function setBaseClass($baseClass)
    {
        $this->baseClass = $baseClass;
    }

    /**
     * @return BundleInterface
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return ControllerSpec\Options
     */
    public function getOptions()
    {
        return $this->options;
    }

}