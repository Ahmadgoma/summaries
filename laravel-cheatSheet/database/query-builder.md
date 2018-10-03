# Query Builder

* [Retrieving Results](#retrieving-results)
    * Retrieving All Rows From A Table
    * Retrieving A Single Row / Column From A Table
    * [Chunking Results](#chunking-results)
    * [Aggregates](#aggregates)
    * [Selects](#selects)
    * [Raw Expressions](#raw-expressions)
* [Joins](#joins)
* [Unions](#unions)

## Retrieving Results
**Retrieving All Rows From A Table**
```php
public function index()
{
    $users = DB::table('users')->get();

    return view('user.index', ['users' => $users]);
}
```
The get method returns an `Illuminate\Support\Collection` containing the results where each result is an instance of the PHP `stdClass` object so you can call it like `$user->name;`.

**Retrieving A Single Row / Column From A Table**
```php
$user = DB::table('users')->where('name', 'John')->first();

echo $user->name;
```
you may extract a single value from a record using the `value` method.
```php
$email = DB::table('users')->where('name', 'John')->value('email');
```

**Retrieving A List Of Column Values**
```php
$titles = DB::table('roles')->pluck('title');
# or
$roles = DB::table('roles')->pluck('title', 'name');
```

### Chunking Results
If you need to work with thousands of database records, consider using the `chunk` method. This method retrieves a small chunk of the results at a time and feeds each chunk into a `Closure` for processing.
```php
DB::table('users')->orderBy('id')->chunk(100, function ($users) {
    foreach ($users as $user) {
        //
    }
});
```
You may stop further chunks from being processed by returning `false` from the `Closure`:
```php
DB::table('users')->orderBy('id')->chunk(100, function ($users) {
    // Process the records...
    return false;
});
```

### Aggregates
The query builder also provides a variety of aggregate methods such as `count`, `max`, `min`, `avg`, and `sum`.
```php
$users = DB::table('users')->count();
# or
$price = DB::table('orders')->max('price');
# or
$price = DB::table('orders')
                ->where('finalized', 1)
                ->avg('price');
```
**Determining If Records Exist**
```php
return DB::table('orders')->where('finalized', 1)->exists();
# or
return DB::table('orders')->where('finalized', 1)->doesntExist();
```

### Selects
**Specifying A Select Clause**
```php
$users = DB::table('users')->select('name', 'email as user_email')->get();
```
The `distinct` method allows you to force the query to return distinct results.
```php
$users = DB::table('users')->distinct()->get();
```
You can add a column to its existing `select` clause.
```php
$query = DB::table('users')->select('name');

$users = $query->addSelect('age')->get();
```

### Raw Expressions
you can use the `DB::raw` method:
```php
$users = DB::table('users')
                    ->select(DB::raw('count(*) as user_count, status'))
                    ->where('status', '<>', 1)
                    ->groupBy('status')
                    ->get();
```
Instead of using `DB::raw`, you may also use the following methods to insert a raw expression.

**selectRaw**
```php
$orders = DB::table('orders')
                ->selectRaw('price * ? as price_with_tax', [1.0825])
                ->get();
```
**whereRaw / orWhereRaw**
```php
$orders = DB::table('orders')
                ->whereRaw('price > IF(state = "TX", ?, 100)', [200])
                ->get();
```
**havingRaw / orHavingRaw**
```php
$orders = DB::table('orders')
                ->select('department', DB::raw('SUM(price) as total_sales'))
                ->groupBy('department')
                ->havingRaw('SUM(price) > ?', [2500])
                ->get();
```
**orderByRaw**
```php
$orders = DB::table('orders')
                ->orderByRaw('updated_at - created_at DESC')
                ->get();
```

## Joins
**Inner Join Clause**
```php
$users = DB::table('users')
            ->join('contacts', 'users.id', '=', 'contacts.user_id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.*', 'contacts.phone', 'orders.price')
            ->get();
```

**Left Join Clause**
```php
$users = DB::table('users')
            ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
            ->get();
```

**Cross Join Clause**
```php
$users = DB::table('sizes')
            ->crossJoin('colours')
            ->get();
```

**Advanced Join Clauses**
```sql
DB::table('users')
        ->join('contacts', function ($join) {
            $join->on('users.id', '=', 'contacts.user_id')->orOn(...);
        })
        ->get();
```
If you would like to use a "where" style clause on your joins, you may use the `where` and `orWhere` methods on a join.
```sql
DB::table('users')
        ->join('contacts', function ($join) {
            $join->on('users.id', '=', 'contacts.user_id')
                 ->where('contacts.user_id', '>', 5);
        })
        ->get();
```

**Sub-Query Joins**

You may use the `joinSub`, `leftJoinSub`, and `rightJoinSub` methods to join a query to a sub-query.
```sql
$latestPosts = DB::table('posts')
                   ->select('user_id', DB::raw('MAX(created_at) as last_post_created_at'))
                   ->where('is_published', true)
                   ->groupBy('user_id');

$users = DB::table('users')
        ->joinSub($latestPosts, 'latest_posts', function($join) {
            $join->on('users.id', '=', 'latest_posts.user_id');
        })->get();
```

## Unions
