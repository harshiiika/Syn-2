<?php

return [
    App\Providers\AppServiceProvider::class,
    MongoDB\Laravel\MongoDBServiceProvider::class, // enables ->collection(), Mongo Eloquent/Auth
];
