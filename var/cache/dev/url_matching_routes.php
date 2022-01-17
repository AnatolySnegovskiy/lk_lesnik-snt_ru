<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/dashboard' => [[['_route' => 'dashboard', '_controller' => 'App\\Controller\\DashboardController::index'], null, null, null, false, false, null]],
        '/profile' => [[['_route' => 'profile', '_controller' => 'App\\Controller\\ProfileController::index'], null, null, null, false, false, null]],
        '/users/list' => [[['_route' => 'user_list', '_controller' => 'App\\Controller\\UserListController::index'], null, null, null, false, false, null]],
        '/users/add' => [[['_route' => 'app_add_user', '_controller' => 'App\\Controller\\UserListController::add'], null, null, null, false, false, null]],
        '/' => [[['_route' => 'index', '_controller' => 'App\\Controller\\Index::index'], null, null, null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\LoginController::index'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\LoginController::logOut'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/users/re(?'
                    .'|move/([^/]++)(*:32)'
                    .'|setpassword/([^/]++)(*:59)'
                .')'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:95)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        32 => [[['_route' => 'app_remove_user', '_controller' => 'App\\Controller\\UserListController::remove'], ['id'], null, null, false, true, null]],
        59 => [[['_route' => 'app_reset_password_user', '_controller' => 'App\\Controller\\UserListController::resetPassword'], ['id'], null, null, false, true, null]],
        95 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
