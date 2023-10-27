<?php

return [
    'api_key' =>'Ta5ufGd1R9GIfRmMt72jQ',
    'api_secret' => '8DiDj0wmVrb3IROIWZezfF5hkf7zyBTX',
    'base_url' => 'https://api.zoom.us/v2/',
    'token_life' => 60 * 60 * 24 * 7, // In seconds, default 1 week
    'authentication_method' => 'jwt', // Only jwt compatible at present but will add OAuth2
    'max_api_calls_per_request' => '5', // how many times can we hit the api to return results for an all() request
    'ZOOM_CLIENT_KEY' => /* env('ZOOM_CLIENT_KEY','PeyPdg6QZ6qJMbfF7zQ'),*/'PeyPdg6QZ6qJMbfF7zQ',
    'ZOOM_CLIENT_SECRET' =>/* env('ZOOM_CLIENT_SECRET','WdsABtUFWmmoMnFJxePsUWPPZH39T8Wz'),*/'WdsABtUFWmmoMnFJxePsUWPPZH39T8Wz',
    'ZOOM_ACCOUNT_ID' => /*env('ZOOM_ACCOUNT_ID','5RhERupfR76wXMjTxGdqRg'),*/'5RhERupfR76wXMjTxGdqRg',
];
