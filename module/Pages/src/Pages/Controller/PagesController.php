<?php

namespace Pages\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PagesController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function presentacionesAction()
    {
        return new ViewModel();
    }

    public function fichatecnicaAction()
    {
        return new ViewModel();
    }


}

