<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MKYCommon extends Facade
{
    protected static function getFacadeAccessor() {
      return 'MKYCommon';
    }
}
