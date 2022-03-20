<?php


namespace App\Error;

use Cake\Controller\Controller;
use Cake\Error\ExceptionRenderer;
use Exception;

class TestExceptionRenderer extends ExceptionRenderer
{
    public function __construct(Exception $exception)
    {
        parent::__construct($exception);
        $this->error = $exception;
        $this->_getController();
    }

    protected function _getController(): Controller
    {
        throw $this->error;
    }
}
