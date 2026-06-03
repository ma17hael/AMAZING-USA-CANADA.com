<?php
namespace App\Core;

class Permission {
    const ACCESS_ADMIN_PANEL = "access_admin_panel";
    const MANAGE_SETTINGS = 'manage_settings';

    const VIEW_USERS = "view_users";
    const MANAGE_USERS = 'manage_users';

    const VIEW_MAPS = 'view_maps';
    const MANAGE_MAPS = 'manage_maps';
    const ACCESS_ALL_MAPS = 'access_all_maps';

    const VIEW_PACKS = 'view_packs';
    const MANAGE_PACKS = 'manage_packs';

    const VIEW_ORDERS = 'view_orders';
    const MANAGE_ORDERS = 'manage_orders';

    const MODERATE_REVIEWS = 'moderate_reviews';
}