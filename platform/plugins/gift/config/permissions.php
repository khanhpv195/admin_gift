<?php

return [
    [
        'name' => 'Gifts',
        'flag' => 'gift.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'gift.create',
        'parent_flag' => 'gift.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'gift.edit',
        'parent_flag' => 'gift.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'gift.destroy',
        'parent_flag' => 'gift.index',
    ],
];
