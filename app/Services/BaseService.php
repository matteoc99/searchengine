<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{

    /**
     * @var Model
     */
    protected Model $model;

    protected bool $withTrashed = false;

    public function __construct()
    {
        $this->model = app($this->model());
    }

    abstract protected function model(): string;

    public static function instance(): static
    {
        return app(static::class);
    }

    public function getMorphClass(): string
    {
        return $this->model->getMorphClass();
    }

    public function withoutTrashed()
    {
        $this->withTrashed = false;
        return $this;
    }

    public function findOrFail($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function findOrFailTrashed($id, $columns = ['*'])
    {
        $q = $this->model->query();
        $q->withTrashed();
        return $q->where($this->model->getKeyName(), $id)->firstOrFail($columns);
    }

    public function query(): Builder
    {
        return $this->model->query();
    }

    public function withTrashed()
    {
        $this->withTrashed = true;
        return $this;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    public function findTrashed($id, $columns = ['*'])
    {
        $q = $this->model->query();
        $q->withTrashed();
        return $q->where($this->model->getKeyName(), $id)->first($columns);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function firstWhere(array $filters)
    {
        return $this->model->where($filters)->first();
    }

    public function where(array $filters): Builder
    {
        return $this->model->where($filters);
    }

    public function allWhere(array $filters)
    {
        return $this->model->where($filters)->get();

    }

    public function make($data)
    {
        $class = $this->model();
        $model = new $class();
        $model->fill($data);
        return $model;
    }

    public function delete(Model $model): Model
    {
        $model->delete();
        return $model;
    }

    public function forceDelete(Model $model): ?bool
    {
        return $model->forceDelete();
    }

    public function updateOrCreate(array $unique, array $data=[]): Model
    {
        $first = $this->firstWhere($unique);
        if ($first) {
            return $this->update($first, $data);
        } else {
            return $this->create(array_merge($unique, $data));
        }
    }


    public function update(Model $model, $data, &$changes = null): Model
    {
        $model->fill($data);
        $changes = $model->getChanges();
        $model->save();
        return $model;
    }

    public function save(Model $model, $options = []): bool
    {
        return $model->save($options);
    }

    public function create($data)
    {
        $class = $this->model();
        $model = new $class();
        $model->fill($data);
        $model->save();
        return $model;
    }

}

