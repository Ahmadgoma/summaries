# Getting Started & Raw SQL Queries

* [Getting Started & Raw SQL Queries](#getting-started--raw-sql-queries)
* [Query Builder](#query-builder)

## Getting Started & Raw SQL Queries
Laravel makes interacting with databases extremely simple across a variety of database backends using either `raw SQL`, the fluent `query builder`, and the `Eloquent ORM`.

Currently, Laravel supports four databases:
* `MySQL`
* `PostgreSQL`
* `SQLite`
* `SQL Server`

The database configuration for your application is located at `config/database.php`. Laravel also allows you using multiple database connections.

Once you have configured your database connection, you may run queries using the `DB` facade. The `DB` facade provides methods for each type of query: `select`, `update`, `insert`, `delete`, and `statement`. Also you can use `PDO` named bindings when write the queries.

If you would like to receive each SQL query executed by your application, you may use the `listen` method. This method is useful for logging queries or debugging. You may register your query listener in a `service provider`.

You may use the `transaction` method on the `DB` facade to run a set of operations within a database transaction. or use the manual transactions functions like `beginTransaction`, `rollBack` and `commit`.

The `DB` facade's transaction methods control the transactions for both the `query builder` and `Eloquent ORM`.

## Query Builder

Laravel's database query builder provides a convenient, fluent interface to creating and running database queries. It can be used to perform most database operations in your application and works on all supported database systems.

The Laravel query builder uses `PDO` parameter binding to protect your application against SQL injection attacks. There is no need to clean strings being passed as bindings.

**Query builder allows you when retrieving the data from:**
* Retrieve all rows from the database.
* Retrieve a single row / column value form the returned result.
* Retrieve a list of column values.
* You can chunk the results.
* You can use the aggregation functions.
* determine if the records `exists` or `doesntExist`.


**Selects**
With `select` function you can specify a specific columns to return.

Raw Expressions
Joins
Unions
Where Clauses
    Parameter Grouping
    Where Exists Clauses
    JSON Where Clauses
Ordering, Grouping, Limit, & Offset
Conditional Clauses
Inserts
Updates
    Updating JSON Columns
    Increment & Decrement
Deletes
Pessimistic Locking
