<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Http\Controllers\AuthController as BaseAuthController;

class AuthController extends BaseAuthController
{
      /**
     * @var string
     */
    protected $view = 'admin::pages.login';
}
