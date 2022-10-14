<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc\exception;


class NotFoundException extends \Exception
{
    protected $code = 404;
    protected $message = '页面不存在';
}
