<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 15:27
 */

namespace Kix\GeneratorBundle\Generator;

use Kix\GeneratorBundle\Generator\Event\ControllerGenerated;
use Doctrine\Common\Annotations\PhpParser;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;

class UnitTestGenerator implements EventSubscriberInterface
{

    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
        $this->factory = new \PhpParser\BuilderFactory();
    }

    /**
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            ControllerGenerated::EVENT_NAME => 'onControllerGenerated'
        ];
    }

    public function onControllerGenerated(ControllerGenerated $event)
    {
        $spec = $event->getControllerSpec();

        $printer = new PrettyPrinter();
        $stmts = array();

        echo "Generating tests for {$spec->getName()}:\n";

        $stmts []= $this->factory->namespace(array(
            $spec->getBundle()->getNamespace(),
            'Tests',
            'Controller'
        ));

        $stmts []= $this->factory->use(
            'Symfony\Bundle\FrameworkBundle\Test\WebTestCase'
        );

        $class =  $this->factory->class($spec->getName() . 'ControllerTest')
                                ->extend('WebTestCase');

        foreach ($spec->getActions() as $action) {
            $class->addStmt($this->buildAction($action));
            echo "  test{$action}\n";
        }

        $stmts []= $class;

        $nodes = array_map(function($stmt) {
            return $stmt->getNode();
        }, $stmts);

        $code = $printer->prettyPrint($nodes);

        $testCaseFile = $spec->getBundle()->getPath() . '/Tests/Controller/' . $spec->getName() .'ControllerTest.php';

        file_put_contents(
            $testCaseFile,
            "<?php\n" . $code
        );
    }

    private function buildAction($action)
    {
        $demoAssertStmt = new \PhpParser\Node\Expr\MethodCall(
            new \PhpParser\Node\Expr\Variable('this'),
            'assertTrue',
            array(new \PhpParser\Node\Arg(
                new \PhpParser\Node\Scalar\String('false')
            ))
        );
        return $this->factory->method('test' . ucfirst($action) . 'Action')
                             ->addStmt($demoAssertStmt);
    }

}