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

class TemplateAdmin extends Admin
{


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name');

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('picture')
            ->addIdentifier('name', null, array('label' => 'Titre'))
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
        $formMapper
            ->add('name');
        $fileFieldOptions = array('required' => false);
        $fileFieldOptions['data_class'] = null;
        $fileFieldOptions['required'] = false;
        //$fileFieldOptions['label'] = "Attachez un fichier";

        $formMapper->add('uploaded_file_recto', FileType::class, $fileFieldOptions);
        $formMapper->add('recto', HiddenType::class, array());
        $formMapper->add('uploaded_file_verso', FileType::class, $fileFieldOptions);
        $formMapper->add('verso', HiddenType::class, array());
    }

    public function prePersist($object)
    {
        $this->manageFileUpload($object);
    }

    public function preUpdate($object)
    {
        $this->manageFileUpload($object);
    }

    private function manageFileUpload($object)
    {
        $uploadpath = $this->getConfigurationPool()->getContainer()->getParameter('kernel.root_dir')
            . '/../web/uploads/' ;
        if ($object->getUploadedFileRecto() instanceof UploadedFile) {
            $object->upload($uploadpath, "recto" );
        }
        if ($object->getUploadedFileVerso() instanceof UploadedFile) {
            $object->upload($uploadpath, "verso" );
        }
    }
}
