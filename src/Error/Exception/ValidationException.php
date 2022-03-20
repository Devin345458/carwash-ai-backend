<?php
namespace App\Error\Exception;

use Cake\Http\Exception\BadRequestException;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

/**
 * Exception containing validation errors from the model. Useful for API
 * responses where you need an error code in response
 */
class ValidationException extends BadRequestException
{
    /**
     * List of validation errors that occurred in the model
     *
     * @var array
     */
    protected $_validationErrors = [];
    /**
     * How many validation errors are there?
     *
     * @var int
     */
    protected $_validationErrorCount = 0;

    /**
     * Constructor
     *
     * @param $entity_array
     * @param int $code         code to report to client
     */
    public function __construct($entity_array, $code = 422)
    {
        if (is_array($entity_array)) {
            foreach ($entity_array as $entity) {
                $this->_validationErrors = array_merge($this->_validationErrors, array_filter((array)$entity->getErrors()));
            }
        } else {
            $this->_validationErrors = array_filter((array)$entity_array->getErrors());
        }

        $this->_validationErrors = $this->humanizeErrors($this->_validationErrors);

        $flat = Hash::flatten($this->_validationErrors);
        $errorCount = $this->_validationErrorCount = count($flat);
        $this->message = __dn(
            'crud',
            'A validation error occurred',
            '{0} validation errors occurred',
            $errorCount,
            [$errorCount]
        );
        parent::__construct($this->message, $code);
    }

    public function humanizeErrors($errors, $internal_errors = [], $path = '')
    {
        foreach ($errors as $field => $error) {
            if (is_array($error)) {
                foreach ($error as $key => $item) {
                    if (is_array($item)) {
                        $internal_errors = $this->humanizeErrors($item, $internal_errors, Inflector::humanize($field) . '.' . $key . '.');
                    } else {
                        $internal_errors = Hash::insert($internal_errors, $path . Inflector::humanize($field), $item);
                    }
                }
            } else {
                $internal_errors = Hash::insert($internal_errors, $path . Inflector::humanize($path), $error);
            }
        }

        return $internal_errors;
    }

    /**
     * Returns the list of validation errors
     *
     * @return array
     */
    public function getValidationErrors()
    {
        return $this->_validationErrors;
    }

    /**
     * How many validation errors are there?
     *
     * @return int
     */
    public function getValidationErrorCount()
    {
        return $this->_validationErrorCount;
    }
}
