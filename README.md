# laravel-db-mutex

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Travis](https://img.shields.io/travis/ivanomatteo/laravel-db-mutex.svg?style=flat-square)]()
[![Total Downloads](https://img.shields.io/packagist/dt/ivanomatteo/laravel-db-mutex.svg?style=flat-square)](https://packagist.org/packages/ivanomatteo/laravel-db-mutex)


This library implements a mutex mechanism, using a polymorphic "one to many" relationship.

When calling usingDbMutex(), if not alredy exists, a row matching the current model type, id and the specified "name" field (the default name is "default"),
will be added in the db_mutexes table.

On that row will be applied a "lock for update" (the db engine in use must support it), eusuring the mutual exclusion.

In this way you can avoid to put the lock on the table containing your data (possible bottle neck),
preserving the read/write capability for all request that do not need a mutual exclusion.


## Install

```bash
composer require ivanomatteo/laravel-db-mutex

php artisan migrate

```


## Usage

Write a few lines about the usage of this package.

```php
// add HasDbMutex trait to your model

use HasDbMutex; // ( IvanoMatteo\LaravelDbMutex\HasDbMutex )


$m = YourModel::find(1);

$m->usingDbMutex(function(){ 
    // this code will run in mutual exclusion 
    // for all request calling it 
    // on the record with id 1
    sleep(5); 
    echo "done!";  
});


$m->usingDbMutex(function(){ 
    // this code will run in mutual exclusion 
    // for all request calling it 
    // on the record with id 1, with "foo" identifier
    sleep(5); 
    echo "done!";  
},null,"foo");


$m->usingDbMutex(function(){ 
    /* 
        in this case we will use also an optimistic lock mechanism
        we can provide the previous value of 
            - counter 
                more reliable but slower, the counter value since is incremented inside a "read lock"
                can't never be the same
                
            and/or

            - model_updated_at (updated_at timestamp of the model)
                can be used if you are making modification on the model
                (model_updated_at would not make sense if your modifications are applied only to someting else)
                less reliable but faster, the updated_at field of the model
                can be read outside of the "read lock" 
                
        if the values do not match the currents, a 412 http error will be returned
        
        NOTE: these values must came from the current REQUEST, not from the retrieved models
    */
},
    [
        'counter' => 10,
        'model_updated_at' => '2020-12-28 14:56:44',
    ] 
);  
// in the case, you want to use the counter value, obviously you need to load the previous value
// when reading the data. You could you use withDbMutex scope as explained below.


// there is also the  withDbMutex scope
YourModel::withDbMutex()->find(1); //will add the "default" dbmutex data
YourModel::withDbMutex('foo')->find(1); //will add the "foo" dbmutex data

```
### Warning
When reading the model with the "dbmutex" relation information,
it's possible that you have to wait for the lock became avaible on that rows.

It's recommended to load it, only if that one is necessary, 
for example if you need to use the optimistic lock mechanism with the counter value.

For the same reason, it's also recommended to load the minimum number of dbmutex related rows.


## Testing

Run the tests with:

```bash
vendor/bin/phpunit
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Security

If you discover any security-related issues, please email ivanomatteo@gmail.com instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.
