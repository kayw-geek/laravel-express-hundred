<?php
/**
 * Created by PhpStorm.
 * @name:
 * @author: weikai
 * @date: dt
 */

namespace Weikaiii\Express;


use Illuminate\Support\Facades\Facade;

class ExpressFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'express';
    }
}