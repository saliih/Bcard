<?php

namespace BcardBundle\Admin;

use BcardBundle\Admin\BaseAdmin as Admin;
use BcardBundle\Entity\Template;
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

class FontsAdmin extends Admin
{


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name');

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name',  null, array('label' => 'Nom'))
            ->add('css',  null, array('label' => 'CSS Font'))
            ->add('link', null, array('label' => 'Lien'))
           ;
        $listMapper->add('_action', 'actions', array(
            'actions' => array(
                'edit' => array(),
                'delete' => array(),
            )
        ));

    }

    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper->add('name', null, array('label' => 'Nom'))
        ->add('css',  null, array('label' => 'CSS Font'));
        $formMapper->add('link', null, array('label' => 'Lien'));

    }
}
