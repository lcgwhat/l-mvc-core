<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc;


abstract class UserModel extends db\DbModel
{
    abstract public function getDisplayName():string;
}
