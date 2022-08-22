<?php
namespace App\Service\DatabaseQueryEngine\Security\Exceptions;

use App\Service\DatabaseQueryEngine\Exceptions\Exception;
use Throwable;

/**
 * Class SecurityException
 * @package App\Service\DatabaseQueryEngine\Security\Exception
 */
class SecurityException extends Exception
{
    /**
     * @var array $errors;
     */
    private $errors;

    /**
     * SecurityException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param array $securityErrors
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null, array $securityErrors = [])
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $securityErrors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}