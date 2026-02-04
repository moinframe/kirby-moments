<?php

use Moinframe\Moments\Tokens;

return function ($kirby) {
    return [
        'dialogs' => [
            'moments/token/create' => [
                'load' => function () {
                    return [
                        'component' => 'k-form-dialog',
                        'props' => [
                            'fields' => [
                                'name' => [
                                    'label' => t('moinframe.moments.panel.tokens.name'),
                                    'type' => 'text',
                                    'required' => true,
                                    'placeholder' => t('moinframe.moments.panel.tokens.name.placeholder'),
                                ],
                            ],
                            'submitButton' => t('moinframe.moments.panel.tokens.create'),
                        ],
                    ];
                },
                'submit' => function () {
                    $user = kirby()->user();
                    if (!$user) {
                        throw new \Exception('Unauthorized');
                    }

                    $name = trim(get('name', ''));
                    if (empty($name)) {
                        throw new \Exception('Token name is required');
                    }

                    $result = Tokens::create($user->id(), $name);

                    return [
                        'event' => 'moments-token.create',
                        'data' => $result,
                    ];
                },
            ],
            'moments/token/delete/(:any)' => [
                'load' => function (string $tokenId) {
                    return [
                        'component' => 'k-remove-dialog',
                        'props' => [
                            'text' => t('moinframe.moments.panel.tokens.delete.confirm'),
                        ],
                    ];
                },
                'submit' => function (string $tokenId) {
                    $user = kirby()->user();
                    if (!$user) {
                        throw new \Exception('Unauthorized');
                    }

                    Tokens::delete($user->id(), $tokenId);
                    return true;
                },
            ],
        ],
    ];
};
