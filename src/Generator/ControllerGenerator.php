<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 14:45
 */

namespace Kix\GeneratorBundle\Generator;

use Kix\GeneratorBundle\AST\Dispatcher;
use Kix\GeneratorBundle\Generator\Event\ControllerGenerated;
use Kix\GeneratorBundle\Generator\Event\ControllerSaved;
use PhpParser\Node\Name;
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ControllerGenerator implements EventSubscriberInterface
{

    private $eventDispatcher;

    private $processorDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher, Dispatcher $dispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->processorDispatcher = $dispatcher;
    }

    /**
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
        );
    }

    /**
     * @param ControllerSpec $spec
     */
    public function generate(ControllerSpec $spec)
    {
        $printer = new PrettyPrinter();

        $ast = $this->doGenerate($spec);

        $ast = $this->processorDispatcher->process($ast);

        $event = new ControllerGenerated($spec, $ast);

        $this->eventDispatcher->dispatch(ControllerGenerated::EVENT_NAME, $event);

        $code = $printer->prettyPrint($ast);
        $controllerFile = $spec->getBundle()->getPath() . '/Controller/' . $spec->getName() .'Controller.php';

        file_put_contents(
            $controllerFile,
            "<?php\n" . $code
        );

        $event = new ControllerSaved($spec, $controllerFile);
        $this->eventDispatcher->dispatch(ControllerSaved::EVENT_NAME, $event);
    }

    /**
     * @param ControllerSpec $spec
     * @return \PhpParser\Node[]
     */
    private function doGenerate(ControllerSpec $spec)
    {
        $factory = new \PhpParser\BuilderFactory();

        $stmts = array();

        $stmts []= $factory->namespace(array(
            $spec->getBundle()->getNamespace(),
            'Controller'
        ));

        $class = $factory->class($spec->getName(). 'Controller');

        if ($spec->getBaseClass()) {
            $stmts []= $factory->use(
                $spec->getBaseClass()
            );

            $class->extend(
                new Name(array_slice($spec->getBaseClass(), -1, 1))
            );

            if ($spec->getOptions()->isStrictOOP()) {
                $class->makeFinal();
            }
        }

        foreach ($spec->getActions() as $action) {
            $class->addStmt($factory->method($action . 'Action'));
        }

        $stmts []= $class;

        $nodes = array_map(function($stmt) {
            return $stmt->getNode();
        }, $stmts);

        return $nodes;
    }

}