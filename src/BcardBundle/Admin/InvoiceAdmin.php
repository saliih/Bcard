<?php

namespace BcardBundle\Admin;

use BcardBundle\Admin\BaseAdmin as Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Admin\AdminInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class InvoiceAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
    );

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name');

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('dcr')
            ->add('name')
            ->add('email')
            ->add('tel')
            ->add('adresse')
            ->add('act',null,array('editable'=>true))
           ;
        $listMapper->add('_action', 'actions', array(
            'actions' => array(
                'print' => array("template"=>"BcardBundle:Invoice:bt.html.twig"),
            )
        ));

    }
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('edit');
        $collection->remove('delete');
        $collection->remove('show');
    }
}
