<?php
/**
 * Created by PhpStorm.
 * User: salah
 * Date: 24/06/2016
 * Time: 10:11
 */

namespace BcardBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin as Admin;

class BaseAdmin extends Admin
{
    protected $maxPerPage = 20;
    protected $maxPageLinks = 10;
    protected $datagridValues = array(
        '_page' => 1,
        '_per_page' => 25,
        '_sort_order' => 'DESC',
    );
    protected $perPageOptions = array(25, 50, 75, 100, 125, 150);
    protected $listModes = array();

    public function getExportFormats()
    {
        return array();
    }

}