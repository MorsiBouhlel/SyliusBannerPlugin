<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Banner Plugin</h1>

<p align="center">Add banner/carousel to your shop</p>

## Warning
The following instructions are compatible with Bootstrap and Webpack Encore. This plugin is in beta.

## Installation

1. Install [Sylius](https://docs.sylius.com/en/latest/book/installation/installation.html)
2.  Import the configuration

```yaml
# config/packages/sylius_banner.yaml
imports:
    - { resource: "@BlackSyliusBannerPlugin/config/app/config.php" }
```

3. Import routing

```yaml
# config/routes/sylius_banner.yaml
black_sylius_banner_shop:
    resource: "@BlackSyliusBannerPlugin/config/routes/shop.yaml"

black_sylius_banner_admin:
    resource: "@BlackSyliusBannerPlugin/config/routes/admin.yaml"
    prefix: '/%sylius_admin.path_name%'

black_sylius_banner_api:
    resource: "@BlackSyliusBannerPlugin/config/routes/api.yaml"
    
```

4. Register the bundle:

```php
<?php

// config/bundles.php

return [
    // ...
    Black\SyliusBannerPlugin\BlackSyliusBannerPlugin::class => ['all' => true],
];
```

5. Add the bundle and dependencies in your `composer.json`

`composer require black/sylius-banner-plugin:^1.0.0@dev`

6. Execute migration

```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```
 
7. Render the template

```twig
# In the desired twig file

{{ render(url('black_sylius_banner_shop_banner_partial', {
    'code': 'banner1',
    'template': '@BlackSyliusBannerPlugin/_banner.html.twig' // optional (default template)
})) }}
```

__Tip:__ Replace the content of `Homepage/_banner.html.twig` with this snippet and use template
events!
## Quickstart Installation (docker)

1. Run `composer create-project pocky/modern-plugin-skeleton ProjectName` or clone this project

2. From the plugin skeleton root directory, run the following commands:

```bash
$ sudo chmod -Rf 777 tests/Application/var
$	docker-compose exec php php -d memory_limit=-1 /usr/bin/composer install
$	docker-compose exec nodejs yarn --cwd tests/Application install
$	docker-compose exec php tests/Application/bin/console doctrine:database:create --if-not-exists -vvv
$	docker-compose exec php tests/Application/bin/console doctrine:schema:create -vvv
$	docker-compose exec php tests/Application/bin/console assets:install tests/Application/public -vvv
$	docker-compose exec nodejs yarn --cwd tests/Application build
$	docker-compose exec php tests/Application/bin/console cache:warmup -vvv
$	docker-compose exec php tests/Application/bin/console sylius:fixtures:load -n
```

### Quality tools

```bash
$ docker-compose exec php composer validate --ansi --strict
$ docker-compose exec php vendor/bin/phpstan analyse -c phpstan.neon -l max src/
$ docker-compose exec php vendor/bin/psalm
$ docker-compose exec php vendor/bin/phpspec run --ansi -f progress --no-interaction
$ docker-compose exec php vendor/bin/phpunit --colors=always
$ docker-compose exec php vendor/bin/behat --profile docker --colors --strict -vvv --no-interaction
``` 
__ProTip__ use `Makefile` ;)
## Override

This plugin use the default [bootstrap carousel](https://getbootstrap.com/docs/4.0/components/carousel/). You don't need any configuration.

If you want to use another carousel, feel free to override.

## Complete configuration

```yaml
parameters:
    black_banner.uploader.filesystem: "black_sylius_banner"
        
doctrine:
    orm:
        auto_generate_proxy_classes: "%kernel.debug%"
        entity_managers:
            default:
                auto_mapping: true

knp_gaufrette:
    adapters:
        black_sylius_banner:
            safe_local:
                directory: "%sylius_core.public_dir%/media/banner/"
                create: true
    filesystems:
        black_sylius_banner:
            adapter: "%black_banner.uploader.filesystem%"
    stream_wrapper: ~

liip_imagine:
    loaders:
        black_sylius_banner:
            stream:
                wrapper: gaufrette://black_sylius_banner/
    filter_sets:
        black_sylius_banner:
            data_loader: black_sylius_banner
            filters:
                upscale: { min: [1200, 400] }
                thumbnail: { size: [1200, 400], mode: inbound }
                
sylius_grid:
    templates:
        filter:
            banner_channel: '@BlackSyliusBannerPlugin/Admin/Grid/Filter/channel.html.twig'
    grids:
        black_sylius_banner:
            driver:
                name: doctrine/orm
                options:
                    class: 'expr:parameter("black_sylius_banner.model.banner.class")'
            fields:
                code:
                    type: string
                    label: sylius.ui.code
                name:
                    type: string
                    label: sylius.ui.name
            filters:
                code:
                    label: sylius.ui.code
                    type: string
                name:
                    label: sylius.ui.name
                    type: string
                channel:
                    type: banner_channel
                    label: sylius.ui.channel
            actions:
                main:
                    create:
                        type: create
                item:
                    update:
                        type: update
                    delete:
                        type: delete
```

## License and Copyright

tl;dr:
- Modifications must be shared,
- It's possible to use this plugin in a commercial project,
- A commercial license is available.

This project is licensed under [EUPL-1.2](https://joinup.ec.europa.eu/collection/eupl/eupl-text-eupl-12). This license implies that if you modify this plugin, you must share those modifications (like AGPL). However, the EUPL-1.2 license applies only on this plugin and this is not viral (like LGPL).

If you don't want to follow this or do not want to use EUPL-1.2 licensed software, you must buy commercial license. [Contact us](docs/SUPPORT.md) for more information.

## Credits
Created by [Alexandre Balmes](https://alexandre.balmes.co).
Some files related to slide upload are 99% from [Sylius Core](https://github.com/sylius/sylius). We will see if
it was a realy good idea to not use the default ImageInterface ;)

## Sponsors
This project was made possible thanks to the support of:

[![Vanoix.com](https://vanoix.com/assets/vanoix125.png "Vanoix gives me some time")](https://vanoix.com)
