<?php

namespace App\Services\Errors;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ServiceException extends HttpException
{
    private ServiceExceptionData $exceptionData;

    public function __construct(ServiceExceptionData $exceptionData)
    {
        $statusCode = $exceptionData->getStatusCode();
        $message = $exceptionData->getType();

        parent::__construct($statusCode, $message);
        $this->exceptionData = $exceptionData;
    }

    public function getExceptionData(): ServiceExceptionData
    {
        return $this->exceptionData;
    }
}