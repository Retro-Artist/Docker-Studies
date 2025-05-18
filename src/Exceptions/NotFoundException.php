<?php
declare(strict_types=1);

namespace App\Exceptions;

/**
 * Not Found Exception
 */
class NotFoundException extends \Exception
{
    protected $message = 'Page not found';
    protected $code = 404;
}