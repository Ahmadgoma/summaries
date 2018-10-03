# Getting Started & Raw SQL Queries

* [Configuration](#configuration)
    * Read & Write Connections
    * Using Multiple Database Connections
* [Running Raw SQL Queries](#running-raw-sql-queries)
    * Running A Select Query
    * Running An Insert Statement
    * Running An Update Statement
    * Running A Delete Statement
    * Running A General Statement
* [Listening For Query Events](#listening-for-query-events)
* [Database Transactions](#database-transactions)
    * Handling Deadlocks
    * Manually Using Transactions


### Configuration
You can set your configuration from <code>config/database.php</code>.

**Read & Write Connections**
```php
'mysql' => [
    'read' => [
        'host' => ['192.168.1.1'],
    ],
    'write' => [
        'host' => ['196.168.1.2'],
    ],
    'sticky'    => true,
    'driver'    => 'mysql',
    'database'  => 'database',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
],
```

**Using Multiple Database Connections**
```php
$users = DB::connection('foo')->select(...);
```
You can get PDO instance using
```php
$pdo = DB::connection()->getPdo();
```

### Running Raw SQL Queries
**Running A Select Query**
```php
public function index()
{
    $users = DB::select('select * from users where active = ?', [1]);

    // Or by Using Named Bindings
    $results = DB::select('select * from users where id = :id', ['id' => 1]);

    return view('user.index', ['users' => $users]);
}
```

**Running An Insert Statement**
```php
public function store()
{
    DB::insert('insert into users (id, name) values (?, ?)', [1, 'Dayle']);
}
```

**Running An Update Statement**
```php
public function update()
{
    $affected = DB::update('update users set votes = 100 where name = ?', ['John']);
}
```

**Running A Delete Statement**
```php
public function delete()
{
    $deleted = DB::delete('delete from users');
}
```

**Running A General Statement**
```php
public function drop_users_table()
{
    DB::statement('drop table users');
}
```

### Listening For Query Events
```php
public function boot()
{
    DB::listen(function ($query) {
        // $query->sql
        // $query->bindings
        // $query->time
    });
}
```

### Database Transactions
```php
DB::transaction(function () {
    DB::table('users')->update(['votes' => 1]);

    DB::table('posts')->delete();
});
```

**Handling Deadlocks**
```php
DB::transaction(function () {
    DB::table('users')->update(['votes' => 1]);

    DB::table('posts')->delete();
}, 5);
```

**Manually Using Transactions**
```php
DB::beginTransaction();

// some queries here

// check here
if (!$query) {
    DB::rollBack();
}

DB::commit();
# or
DB::rollBack();
```
[More information here](http://fideloper.com/laravel-database-transactions)
