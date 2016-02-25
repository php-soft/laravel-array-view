# Laravel Array View

[![Build Status](https://travis-ci.org/php-soft/laravel-array-view.svg)](https://travis-ci.org/php-soft/laravel-array-view)

An array view engine for Laravel PHP Framework.

## Version Compatibility

 ArrayView  | Laravel
:-----------|:----------
 1.0.x      | 5.1.x
 1.1.x      | 5.1.x
 1.1.4      | 5.*

## Installation

```sh
$ composer require php-soft/laravel-array-view
```

Once this has finished, you will need to add the service provider to the `providers` array in your `app.php` config as follows:

```php
'providers' => [
    // ...
    PhpSoft\ArrayView\Providers\ArrayViewServiceProvider::class,
]
```

Next, also in the `app.php` config file, under the `aliases` array, you may want to add facades.

```php
'aliases' => [
    // ...
    'ArrayView' => PhpSoft\ArrayView\Facades\ArrayView::class,
]
```

## Usage

Code in controller (Example routes.php)

```php
<?php

Route::get('/articles/{id}', function ($id) {

    $article = Article::find($id);
    return response()->json(arrayView('article', [ 'article' => $article ]));
});
```
views/article.array.php

```php
<?php

$this->set('title', $article->title);
$this->set('author', function ($section) use ($article) {

    $section->set('name', $article->author->name);
});
```

This template generates the following object:

```php
[
    'title' => 'Example Title',
    'author' => [
        'name' => 'John Doe'
    ]
]
```

## Functions

Reference to https://github.com/huytbt/php-array-view#functions
