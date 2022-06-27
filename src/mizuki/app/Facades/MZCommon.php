<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MZCommon extends Facade
{
    protected static function getFacadeAccessor() {
      return 'MZCommon';
    }
}
