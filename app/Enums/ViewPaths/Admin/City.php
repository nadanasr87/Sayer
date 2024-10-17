<?php

namespace App\Enums\ViewPaths\Admin;

enum City
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.city.index'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.city.index'
    ];

    const UPDATE = [
        URI => 'edit',
        VIEW => 'admin-views.city.edit'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

    const LATEST_MODULE_SETUP = [
        URI => 'module-setup',
        VIEW => 'admin-views.city.module-setup'
    ];

    const MODULE_SETUP = [
        URI => 'module-setup',
        VIEW => 'admin-views.city.module-setup'
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

    const DIGITAL_PAYMENT = [
        URI => 'digital-payment',
        VIEW => ''
    ];

    const CASH_ON_DELIVERY = [
        URI => 'cash-on-delivery',
        VIEW => ''
    ];

    const OFFLINE_PAYMENT = [
        URI => 'offline-payment',
        VIEW => ''
    ];

    const INSTRUCTION = [
        URI => 'instruction',
        VIEW => ''
    ];

    const CITY_FILTER = [
        URI => 'city-filter',
        VIEW => ''
    ];

    const MODULE_UPDATE = [
        URI => 'module-update',
        VIEW => ''
    ];

    const GET_COORDINATES = [
        URI => 'city/get-coordinates',
        VIEW => ''
    ];

    const GET_ALL_ZONE_COORDINATES = [
        URI => 'get-all-city-coordinates',
        VIEW => ''
    ];
}
