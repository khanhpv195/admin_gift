<?php

register_page_template([
    'default' => 'Default',
]);

register_sidebar([
    'id'          => 'second_sidebar',
    'name'        => 'Second sidebar',
    'description' => 'This is a sample sidebar for gifttheme theme',
]);

RvMedia::setUploadPathAndURLToPublic();

Event::listen(\Illuminate\Routing\Events\RouteMatched::class, function () {
    dashboard_menu()
        ->removeItem('menu-id-1')
        ->removeItem('menu-id-2');
});