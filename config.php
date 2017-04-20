<?php
use NN\Logic\UserLogic;
use NN\Service\SearchService;

return [
    SearchService::class => \DI\object(SearchService::class),
    'UserLogic' => \DI\object(UserLogic::class)
];