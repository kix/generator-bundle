<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 14:59
 */

namespace Kix\GeneratorBundle\Generator;

use Kix\GeneratorBundle\Generator\Event\ControllerGenerated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TwigViewGenerator extends TemplatedGenerator implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            ControllerGenerated::EVENT_NAME => array('onControllerGenerated', 10),
        ];
    }

    public function onControllerGenerated(ControllerGenerated $event)
    {
        $spec = $event->getControllerSpec();
        $controllerName = $spec->getName();

        $viewsPath = $spec->getBundle()->getPath() . '/Resources/views/' . $spec->getName() . '/';

        if (!realpath($viewsPath)) {
            mkdir($viewsPath, 0755, true);
        }

        foreach ($spec->getActions() as $actionName) {
            $view = $this->generateView(new ViewSpec(
                $controllerName,
                $actionName
            ));

            file_put_contents(
                $viewsPath . $actionName . '.html.twig',
                $view
            );
        }
    }

    public function generateView(ViewSpec $spec)
    {
        return $this->getTemplating()->render(
            'View/view.html.twig.twig',
            array(
                'spec' => $spec
            )
        );
    }

}