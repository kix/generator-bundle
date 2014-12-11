<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 15:34
 */

namespace Kix\GeneratorBundle\Generator;


use Kix\GeneratorBundle\Generator\Event\ControllerGenerated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Yaml\Yaml;

class RoutingGenerator implements EventSubscriberInterface
{

    private $kernelRoot;

    public function __construct($kernelRoot)
    {
        $this->kernelRoot = $kernelRoot;
    }

    /**
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            ControllerGenerated::EVENT_NAME => array('onControllerGenerated', 10),
        ];
    }

    public function onControllerGenerated(ControllerGenerated $event)
    {
        $spec = $event->getControllerSpec();

        $this->updateBundleRoutes($spec);
        $this->updateAppRoutes($spec);


    }

    private function updateBundleRoutes(ControllerSpec $spec)
    {
        $bundle = $spec->getBundle();
        $bundleRoutingFile = $bundle->getPath() . '/Resources/config/routing.yml';

        if (!realpath($bundleRoutingFile)) {
            return false;
        }

        $routing = Yaml::parse(file_get_contents($bundleRoutingFile));

        $prefix = strtolower($spec->getName());

        foreach ($spec->getActions() as $action) {
            $key = $prefix . '_' . strtolower($action);
            $routing[$key] = array(
                'path' => '/' . $action,
                'defaults' => array(
                    '_controller' => $bundle->getName() . ':' . $spec->getName() . ':' . $action,
                )
            );
        }

        file_put_contents($bundleRoutingFile, Yaml::dump($routing));
    }

    private function updateAppRoutes()
    {
        $appRoutingFile = $this->kernelRoot . '/config/routing.yml';

        if (!realpath($appRoutingFile)) {}

        $routing = Yaml::parse(file_get_contents($appRoutingFile));
    }


}