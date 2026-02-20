<?php

namespace SmartDaddy\CatalogBrand\Models;

if (trait_exists(\SmartDaddy\UserActivity\Traits\TracksUserActivity::class)) {
    class Brand extends BaseBrand
    {
        use \SmartDaddy\UserActivity\Traits\TracksUserActivity;
    }
} else {
    class Brand extends BaseBrand
    {
    }
}
