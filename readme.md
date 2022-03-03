# Laravel Blamable

## Setup

Install the package via Composer :

```sh
$ composer require astalpaert/laravel-blamable
```

## Usage

### Implement Blamable fields

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

```php
use Astalpaert\LaravelBlamable\Traits\Blamable;
use Illuminate\Database\Eloquent\Model;

class DummyModel extends Model
{
    use Blamable;
}
```

## Configuration

### Implement User model
Note the 'name' attribute is used as the blamable user. This will be configurable in future.
```
public function getNameAttribute()
{
    //
}
```