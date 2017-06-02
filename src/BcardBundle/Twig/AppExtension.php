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
            'replaceIds' => new \Twig_Filter_Method($this, 'replaceIds'),
        );
    }
    public function replaceIds($text,$type){
        $text = str_replace('id="','id="'.$type.'_',$text);
        $text = str_replace("id='",'id="'.$type.'_',$text);
        return $text;
    }

    public function getName()
    {
        return 'app_extension';
    }
}