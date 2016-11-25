# Laravel Eloquent Model History

Provides tracking of Laravel Models for creating, updating and deleting events. When a model which use ModelHistory trait call created/updated/deleted, an entry is written to the database with which user updated the model and some information about model changes.


## Installation

Run the follwoing command

```
composer require rukhsar/modelhistory
```
Next, add the service provider in your array in `config/app.php` :

```php

'providers' => [
    ...
            Rukhsar\ModelHistoryServiceProvider::class,
    ...
],
```

Publish the database migration

```
php artisan vendor:publish --provider="Rukhsar\ModelHistoryServiceProvider"
```

and run

```
php artisan migration
```

This will setup a model_history table in your database.

## Use it


In you model, just add

```php
use Rukhsar\Traits\ModelHistory;
```

and within you class defination use it like below

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rukhsar\Traits\ModelHistory;

class Post extends Model
{
    use ModelHistory;


}
```

## Get the history of a particular model

For example for abouve model you can get the history by using

```
$post->history;
```
