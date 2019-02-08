<?php

return [
    "telas" => [
        "Menu Principal" => [
            "menu" => ['type' => 'title'],
            "itens" => [
                "Dashboard" => [
                    "menu" => ['type' => 'link', "route" => "home", "icon-class" => "ti-home"],
                    "permissions" => [
                        'dashboard.view' => 'Visualizar página',
                    ]
                ],
                "Credenciais" => [
                    "menu" => ['type' => 'sub-menu', "icon-class" => "fa fa-address-book-o"],
                    "itens" => [
                        "Perfis" => [
                            "menu" => ["type" => "link", "route" => "credentials.profiles.index"],
                            "permissions" => [
                                'credentials.profiles.list' => 'Listar',
                                'credentials.profiles.create' => 'Cadastrar',
                                'credentials.profiles.show' => 'Detalhes',
                                'credentials.profiles.edit' => 'Editar',
                                'credentials.profiles.delete' => 'Apagar'
                            ]
                        ],
                        "Usuários" => [
                            "menu" => ["type" => "link", "route" => "credentials.users.index"],
                            "permissions" => [
                                'credentials.users.list' => 'Listar',
                                'credentials.users.create' => 'Cadastrar',
                                'credentials.users.edit' => 'Editar',
                                'credentials.users.delete' => 'Apagar'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    "system" => [

    ]
];
