<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set('black_banner.uploader.filesystem', 'black_sylius_banner');

    $containerConfigurator->extension('doctrine', [
        'orm' => [
            'auto_generate_proxy_classes' => '%kernel.debug%',
            'entity_managers' => [
                'default' => [
                    'auto_mapping' => true,
                    'mappings' => [
                        'BlackSyliusBannerPlugin' => [
                            'type' => 'xml',
                            'dir' => '/config/doctrine'
                        ]
                    ]
                ]
            ]
        ]
    ]);

    $containerConfigurator->extension('knp_gaufrette', [
        'adapters' => [
            'black_sylius_banner' => [
                'safe_local' => [
                    'directory' => '%sylius_core.public_dir%/media/banner/',
                    'create' => true
                ]
            ]
        ],
        'filesystems' => [
            'black_sylius_banner' => [
                'adapter' => '%black_banner.uploader.filesystem%'
            ]
        ],
        'stream_wrapper' => null
    ]);

    $containerConfigurator->extension('liip_imagine', [
        'loaders' => [
            'black_sylius_banner' => [
                'stream' => [
                    'wrapper' => 'gaufrette://black_sylius_banner/'
                ]
            ]
        ],
        'filter_sets' => [
            'black_sylius_banner' => [
                'data_loader' => 'black_sylius_banner',
                'filters' => [
                    'upscale' => [
                        'min' => [1200, 400]
                    ],
                    'thumbnail' => [
                        'size' => [1200, 400],
                        'mode' => 'inbound'
                    ]
                ]
            ]
        ]
    ]);

    $containerConfigurator->extension('sylius_grid', [
        'templates' => [
            'filter' => [
                'banner_channel' => '@BlackSyliusBannerPlugin/Admin/Grid/Filter/channel.html.twig']
        ],
        'grids' => [
            'black_sylius_banner' => [
                'driver' => [
                    'name' => 'doctrine/orm',
                    'options' => [
                        'class' => 'expr:parameter("black_sylius_banner.model.banner.class")'
                    ]
                ],
                'fields' => [
                    'code' => [
                        'type' => 'string',
                        'label' => 'sylius.ui.code'
                    ],
                    'name' => [
                        'type' => 'string',
                        'label' => 'sylius.ui.name'
                    ]
                ],
                'filters' => [
                    'code' => [
                        'label' => 'sylius.ui.code',
                        'type' => 'string'
                    ],
                    'name' => [
                        'label' => 'sylius.ui.name',
                        'type' => 'string'
                    ],
                    'channel' => [
                        'type' => 'banner_channel',
                        'label' => 'sylius.ui.channel'
                    ]
                ],
                'actions' => [
                    'main' => [
                        'create' => [
                            'type' => 'create'
                        ]
                    ],
                    'item' => [
                        'update' => [
                            'type' => 'update'
                        ],
                        'delete' => [
                            'type' => 'delete'
                        ]
                    ]
                ]
            ]
        ]
    ]);
};
