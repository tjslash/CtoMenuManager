<?php

namespace Tjslash\BackpackMenuManager;

use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'tjslash';
    protected $packageName = 'backpack-menu-manager';
    protected $commands = [];
}
