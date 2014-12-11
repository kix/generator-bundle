<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 15:24
 */

namespace Kix\GeneratorBundle\Generator;

use Kix\GeneratorBundle\Generator\Event\ControllerGenerated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HamlViewGenerator implements EventSubscriberInterface
{
    /**
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            ControllerGenerated::EVENT_NAME => array('onControllerGenerated', 20),
        ];
    }

    public function onControllerGenerated(ControllerGenerated $event)
    {
        $spec = $event->getControllerSpec();
        $controllerName = $spec->getName();

        echo "Generating Haml views for controller {$spec->getName()}:\n";

        foreach ($spec->getActions() as $actionName) {
            $this->generateView(new ViewSpec(
                $controllerName,
                $actionName
            ));
        }
    }

    public function generateView(ViewSpec $spec)
    {
        echo "  {$spec->getAction()}\n";
    }

}