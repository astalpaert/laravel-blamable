# Laravel Blamable 👈

This package allow you to track who created, updated and deleted Eloquent models in Laravel.


## Installation

You can install this package via composer using:
```sh
composer require astalpaert/laravel-blamable
```

The package will automatically register itself.


## Usage

### Implement Blamable fields

The package provides 2 convenient methods `addBlamableFields()` and `removeBlamableFields()` which you may use in your migrations to make sure that the database table has the required columns (`created_by`, `updated_by` and `deleted_by`).

```php
    public function up()
    {
        Schema::table('dummy_models', function (Blueprint $table) {
            $table->addBlamableFields();
        });
    }

    public function down()
    {
        Schema::table('dummy_models', function (Blueprint $table) {
            $table->removeBlamableFields();
        });
    }
```

### Implement Blamable logic

You may then use the `Blamable` trait on the model.

```php
use Astalpaert\LaravelBlamable\Traits\Blamable;
use Illuminate\Database\Eloquent\Model;

class DummyModel extends Model
{
    use Blamable;
    //use SoftDeletes;
}
```

Et voilà! The package will fill the blamable columns automatically after creating, updating and deleting the model.

## Configuration

To publish the config file to `config/astalpaert-blamable.php` run:

```sh
php artisan vendor:publish --provider="Astalpaert\LaravelBlamable\Providers\BlameableServiceProvider"
```

### Implement User model
By default, the `name` attribute of the authenticated User is used to fill the blamable column. This is configurable in the config under `attribute_name`:

```php
return [
    'user' => [
        'attribute_name' => 'name',
    ],
];
```

You may also use an accessor in the model for more flexibility:
```php
public function getNameAttribute(): string
{
    return "$this->first_name $this->last_name";
}
```
**Note**: The blamable fields are string columns, so make sure the name is a `string`.

### Implement default value

By default, when there is no authenticated user, the value `SYSTEM` is used to fill the blamable column. This is configurable in the config under `default`:

```php
return [
    'user' => [
        'default' => 'SYSTEM',
    ],
];
```
