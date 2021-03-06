# Magento Twig Theme

Ever imagined building a Magento 2 Frontend with ease? No more hazzle with Layout XML Updates and phtml templates? Here is your solution. Our module provides a drop-in solution for creating simple Magento 2 Themes based on Twig templates. It is quick and easy to port existing frontends or create new ones from scratch.

## Installation

Please note: the module is not yet publicly available on packagist. Repositories have to be added manually.

    composer config repositories.wamoco-twig-theme vcs git@github.com:Wamoco/magento-twig-theme.git

    composer require wamoco/twig-theme
    bin/magento module:enable Wamoco_TwigTheme
    bin/magento setup:upgrade

## Getting started

Our blank theme can be used as a starting point or for reference. Follow these steps on a default M2 installation:

    composer config repositories.wamoco-twig-theme-blank vcs https://github.com/Wamoco/twig-theme-blank.git
    composer require wamoco/twig-theme-blank
    bin/magento setup:upgrade

Apply the theme in backend under `Content -> Configuration -> Design Configuration`.

## Documentation

[Read the docs](https://wamoco.github.io/magento-twig-theme-docs)

## Team

| <a href="https://github.com/bka" target="_blank">**bka**</a> | <a href="https://github.com/tomtone" target="_blank">**tomtone**</a> |
| :---: |:---:|
| <img src="https://avatars2.githubusercontent.com/u/584644?s=200&v=3" width="200"> | <img src="https://avatars2.githubusercontent.com/u/2018438?s=200&v=3" width="200"> |
| <a href="http://github.com/bka" target="_blank">`github.com/bka`</a> | <a href="http://github.com/tomtone" target="_blank">`github.com/tomtone`</a> |

---

## Support

- Website at <a href="https://www.wamoco.de" target="_blank">`wamoco.de`</a>

## License

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)

- **[MIT license](http://opensource.org/licenses/mit-license.php)**
- Copyright 2020 © <a href="https://wamoco.de" target="_blank">Wamoco</a>.
