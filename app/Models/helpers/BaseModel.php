<?php

namespace App\Models\helpers;

use Closure;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\QueriesRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin Builder
 * @method static Model|Collection|null static $this find($id, $columns = ['*']) Find a model by its primary key.
 * @method static Model|Builder|null first($columns = ['*']) Execute the query and get the first result.
 * @method static Model|Builder firstOrFail($columns = ['*']) Execute the query and get the first result or throw an exception.
 * @method static Model|$this create(array $attributes = []) Save a new model and return the instance.
 * @method static bool update(array $attributes = [], array $options = []) Update the model in the database.
 * @method static Model|static firstOrCreate(array $attributes = [], array $values = []) Get the first record matching the attributes or create it.
 * @method static Collection|Builder[] get($columns = ['*']) Execute the query as a "select" statement.
 * @method static mixed value($column) Get a single column's value from the first result of a query.
 * @method static mixed pluck($column) Get a single column's value from the first result of a query.
 * @method static void chunk($count, callable $callback) Chunk the results of the query.
 * @method static \Illuminate\Support\Collection lists($column, $key = null) Get an array with the values of a given column.
 * @method static Paginator simplePaginate($perPage = null, $columns = ['*'], $pageName = 'page') Paginate the given query into a simple paginator.
 * @method static int increment($column, $amount = 1, array $extra = []) Increment a column's value by a given amount.
 * @method static int decrement($column, $amount = 1, array $extra = []) Decrement a column's value by a given amount.
 * @method static void onDelete(Closure $callback) Register a replacement for the default delete function.
 * @method static Model[] getModels($columns = ['*']) Get the hydrated models without eager loading.
 * @method static array eagerLoadRelations(array $models) Eager load the relationships for the models.
 * @method static array loadRelation(array $models, $name, Closure $constraints) Eagerly load the relationship on a set of models.
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and') Add a basic where clause to the query.
 * @method static Builder|QueriesRelationships whereHas(string $relation, Closure $callback = null, string $operator = '>=', int $count = 1) Add a relationship count / exists condition to the query with where clauses.
 * @method static Builder orWhere($column, $operator = null, $value = null) Add an "or where" clause to the query.
 * @method static Builder has($relation, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null) Add a relationship count condition to the query.
 * @method static Model|Collection|Builder[]|Builder|null find(mixed $id, array $columns = ['*'])
 * @method static Builder orderBy($column, $direction = 'asc')
 * @method static Builder select($columns = ['*'])
 * @method static QueryBuilder whereRaw($sql, array $bindings = [])
 * @method static QueryBuilder whereBetween($column, array $values)
 * @method static QueryBuilder whereNotBetween($column, array $values)
 * @method static QueryBuilder whereNested(Closure $callback)
 * @method static QueryBuilder addNestedWhereQuery($query)
 * @method static QueryBuilder whereExists(Closure $callback)
 * @method static QueryBuilder whereNotExists(Closure $callback)
 * @method static QueryBuilder whereIn($column, $values)
 * @method static QueryBuilder whereNotIn($column, $values)
 * @method static QueryBuilder whereNull($column)
 * @method static QueryBuilder whereNotNull($column)
 * @method static QueryBuilder orWhereRaw($sql, array $bindings = [])
 * @method static QueryBuilder orWhereBetween($column, array $values)
 * @method static QueryBuilder orWhereNotBetween($column, array $values)
 * @method static QueryBuilder orWhereExists(Closure $callback)
 * @method static QueryBuilder orWhereNotExists(Closure $callback)
 * @method static QueryBuilder orWhereIn($column, $values)
 * @method static QueryBuilder orWhereNotIn($column, $values)
 * @method static QueryBuilder orWhereNull($column)
 * @method static QueryBuilder orWhereNotNull($column)
 * @method static QueryBuilder whereDate($column, $operator, $value)
 * @method static QueryBuilder whereDay($column, $operator, $value)
 * @method static QueryBuilder whereMonth($column, $operator, $value)
 * @method static QueryBuilder whereYear($column, $operator, $value)
 * @method static QueryBuilder max($column)
 * @method static Model|static updateOrCreate(array $attributes, array $values = [])
 */
class BaseModel extends Model
{
    use Validation;

    public static function getRelationInlineAttributes(string $relation, array $attributes): string
    {
        return join(':', [$relation, join(',', $attributes)]);
    }

        public static function getModelJoinedTo($string = null, $with = '.', $tableName = null): string
    {
        return join($with, [$tableName ?? static::TABLE_NAME, $string ?? static::getIdAttributeName()]);
    }
}
