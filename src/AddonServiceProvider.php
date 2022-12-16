<?php

namespace Tjslash\CtoMenuManager;

use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'tjslash';
    protected $packageName = 'cto-menu-manager';
    protected $commands = [];
}
