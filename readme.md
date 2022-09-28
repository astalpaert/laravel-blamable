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
    //use SoftDeletes;
}
```

## Configuration

### Implement User model
Note the 'name' attribute is used as the default blamable user. This is configurable in the config under ```attribute_name```. 
Make sure to provide an accessor in the model.
```
public function getNameAttribute()
{
    //
}
```

### Implement default value

Note the value 'SYSTEM' is used as the default blamable user. This is configurable in the config under ```default```.