<?php
namespace App\Error;

use App\Error\Exception\ValidationException;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Error\ExceptionRenderer;
use Cake\Http\Response;
use Cors\Routing\Middleware\CorsMiddleware;
use Exception;

/**
 * Exception renderer for the JsonApiListener
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class JsonApiExceptionRenderer extends ExceptionRenderer
{
    /**
     * Renders validation errors and sends a 422 error code
     *
     * @param ValidationException $error Exception instance
     * @return Response
     */
    public function validation(ValidationException $error): Response
    {
        $url = $this->controller->getRequest()->getRequestTarget();
        /** @var int $status */
        $status = $code = $error->getCode();
        try {
            $this->controller->setResponse($this->controller->getResponse()->withStatus($status));
        } catch (Exception $e) {
            $status = 422;
            $this->controller->setResponse($this->controller->getResponse()->withStatus($status));
        }

        $sets = [
            'code' => $code,
            'url' => h($url),
            'message' => $error->getMessage(),
            'error' => $error,
            'errorCount' => $error->getValidationErrorCount(),
            'errors' => $error->getValidationErrors(),
        ];
        $this->controller->set($sets);
        $this->controller->viewBuilder()->setOption(
            'serialize',
            ['code', 'url', 'message', 'errorCount', 'errors']
        );

        return $this->_outputMessage('error400');
    }

    /**
     * Generate the response using the controller object.
     *
     * If there is no specific template for the raised error (normally there won't be one)
     * swallow the missing view exception and just use the standard
     * error format. This prevents throwing an unknown Exception and seeing instead
     * a MissingView exception
     *
     * @param string $template The template to render.
     * @return Response
     */
    protected function _outputMessage(string $template): Response
    {
        if (Configure::read('debug')) {
            $data = $this->_getErrorData();
            if ($data) {
                $this->controller->viewBuilder()->setVars($data, false);
                $this->controller->viewBuilder()->setOption(
                    'serialize',
                    ['code', 'url', 'message', 'exception', 'trace']
                );
            }

        }
        $data = $this->controller->viewBuilder()->getVars();
        $_default = ['success' => false];
        $array = array_merge($_default, $data);
        $array['_serialize'] = array_keys($array);
        if (($key = array_search('_serialize', $array['_serialize'])) !== false) {
            unset($array['_serialize'][$key]);
        }
        $this->controller->viewBuilder()->setOption('serialize', $array['_serialize']);

        return parent::_outputMessage($template);
    }

    /**
     * Helper method used to generate extra debugging data into the error template
     *
     * @return array debugging data
     */
    protected function _getErrorData(): array
    {
        $data = [];

        $viewVars = $this->controller->viewBuilder()->getVars();
        $serialize = $this->controller->viewBuilder()->getOption('serialize');
        if (!empty($serialize)) {
            foreach ($serialize as $v) {
                $data[$v] = $viewVars[$v];
            }
        }

        if (!empty($viewVars['error']) && Configure::read('debug')) {
            $data['exception'] = [
                'class' => get_class($viewVars['error']),
                'code' => $viewVars['error']->getCode(),
                'message' => $viewVars['error']->getMessage(),
            ];

//            if (!isset($data['trace'])) {
//                $data['trace'] = Debugger::formatTrace($viewVars['error']->getTrace(), [
//                    'format' => 'array',
//                    'args' => false,
//                ]);
//            }
        }

        return $data;
    }


    /**
     * Returns the current controller.
     *
     * @return Controller
     */
    protected function _getController(): Controller
    {
        $controller = parent::_getController();
        $cors = new CorsMiddleware();
        $controller->response = $cors->addHeaders(
            $controller->getRequest(),
            $controller->getResponse()
        );
        $controller->RequestHandler->respondAs('json');
        return $controller;
    }

    /**
     * Helper method to get query log.
     *
     * @return array Query log.
     */
    protected function _getQueryLog(): array
    {
        $queryLog = [];
        $sources = ConnectionManager::configured();
        foreach ($sources as $source) {
            $logger = ConnectionManager::get($source)->getLogger();
            if (method_exists($logger, 'getLogs')) {
                $queryLog[$source] = $logger->getLogs();
            }
        }

        return $queryLog;
    }
}
