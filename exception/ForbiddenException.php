<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc\exception;


class ForbiddenException extends \Exception
{
    protected $code = 403;
    protected $message = "you don`t has permission to access this page";
}
