<?php
/**
 * Created by PhpStorm.
 * User: kix
 * Date: 11/12/14
 * Time: 16:11
 */

namespace Kix\GeneratorBundle\Generator;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

abstract class TemplatedGenerator
{

    private $skeletonDirs;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * Sets an array of directories to look for templates.
     *
     * The directories must be sorted from the most specific to the most
     * directory.
     *
     * @param array $skeletonDirs An array of skeleton dirs
     */
    public function setSkeletonDirs($skeletonDirs)
    {
        $this->skeletonDirs = is_array($skeletonDirs) ? $skeletonDirs : array($skeletonDirs);
    }

    public function getTemplating()
    {
        if (!$this->skeletonDirs) {
            throw new \RuntimeException('Skeleton dirs were not set');
        }

        if (!$this->templating) {
            $this->templating = new \Twig_Environment(new \Twig_Loader_Filesystem($this->skeletonDirs), array(
                'debug'            => true,
                'cache'            => false,
                'strict_variables' => true,
                'autoescape'       => false,
            ));
        }

        return $this->templating;
    }

    public function render($template, $vars = array())
    {
        return $this->getTemplating()->render(
            $template,
            $vars
        );
    }

}