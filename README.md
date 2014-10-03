# QUIQQER Site Types Package

_German_

Das Site Types Package erweitert QUIQQER um einige standard Seitentypen.
Mit diesen Seitentypen lassen sich schnell Webseiten erstellen.

Das Plugin bietet alle wichtigen Seitentype für eine Websete / Homepage.


# Installation

```json
{
    "repositories": [
        {
            "type": "composer",
            "url": "http://update.quiqqer.com/"
        }
    ]
}

```
Package Name:

+ quiqqer/sitetypes


## Installation dev

```json
{
    "type": "vcs",
    "url": "git@dev.quiqqer.com:quiqqer/package-sitetypes.git"
}
```

```bash
php var/composer/composer.phar --working-dir="var/composer/" require "quiqqer/sitetypes:dev-dev"
```

## Seitentypen

- Standard Suche
- Standard Liste
- Standard Inhaltsliste
- Standard Sitemap
- Standard Block Sitemap