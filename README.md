create-symfony-project
===

Composer plugin providing command for creating new projects based on Symfony
framework. This project aims to be an simpler alternative for [`symfony` binary](https://symfony.com/download) and its `new` subcommand. It's just simple wrapper around `composer create-project`,
similarly to `symfony new`. [Symfony release API](https://symfony.com/releases.json)
is used to determine version of the framework.

Installation
---

```
composer global require tomaszgasior/create-symfony-project
```

**This plugin is not stable.** There is small chance that it will break
your composer! To recover from fail, uninstall this package using:

```
composer global remove tomaszgasior/create-symfony-project --no-plugins
```

Usage
---

Start new Symfony project using **LTS version** and **minimal skeleton**:

```
composer create-symfony-project my_project
```

or

```
composer create-symfony-project my_project --lts --minimal
```

Start new Symfony project using **LTS version** and **full-stack skeleton**:

```
composer create-symfony-project my_project --full
```

or

```
composer create-symfony-project my_project --lts --full
```

Start new Symfony project using **current stable version**:

```
composer create-symfony-project my_project --current
```

or with **full-stack skeleton**:

```
composer create-symfony-project my_project --current --full
```

Install Symfony demo application:

```
composer create-symfony-project my_project --demo
```
