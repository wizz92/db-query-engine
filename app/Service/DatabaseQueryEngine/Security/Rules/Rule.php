<?php
namespace App\Service\DatabaseQueryEngine\Security\Rules;

/**
 * Class Rule
 * @package App\Service\DatabaseQueryEngine\Security\Rules
 */
abstract class Rule
{
    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $error
     */
    protected function setErrorMessage(string $error)
    {
        $this->errorMessage = $error;
    }
}
