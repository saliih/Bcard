<?php
/**
 * Created by PhpStorm.
 * User: salah
 * Date: 22/03/2016
 * Time: 16:45
 */

namespace BcardBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AppExtension extends \Twig_Extension
{
    private $container;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            'convertSVG' => new \Twig_Filter_Method($this, 'convertSVG'),
        );
    }

    public function convertSVG($file)
    {
        $path = $this->container->getParameter('kernel.root_dir') . '/../web/uploads/svg/' . $file;
        return file_get_contents($path);

    }

    public function getName()
    {
        return 'app_extension';
    }
}