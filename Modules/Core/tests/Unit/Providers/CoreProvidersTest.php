<?php

use Modules\Core\Providers\CoreServiceProvider;
use Modules\Core\Providers\RouteServiceProvider;

describe('Core Module Providers', function () {
    test('CoreServiceProvider can be instantiated', function () {
        $app = app();
        $provider = new CoreServiceProvider($app);
        
        expect($provider)->toBeInstanceOf(CoreServiceProvider::class);
        expect(method_exists($provider, 'boot'))->toBe(true);
        expect(method_exists($provider, 'register'))->toBe(true);
    });
    
    test('RouteServiceProvider can be instantiated', function () {
        $app = app();
        $provider = new RouteServiceProvider($app);
        
        expect($provider)->toBeInstanceOf(RouteServiceProvider::class);
        expect(method_exists($provider, 'map'))->toBe(true);
        expect(method_exists($provider, 'boot'))->toBe(true);
    });
    
    test('CoreServiceProvider loads module resources', function () {
        $app = app();
        $provider = new CoreServiceProvider($app);
        
        expect(method_exists($provider, 'registerConfig'))->toBe(true);
        expect(method_exists($provider, 'registerTranslations'))->toBe(true);
        expect(method_exists($provider, 'registerViews'))->toBe(true);
    });
});
