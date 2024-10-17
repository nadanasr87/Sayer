<?php

namespace App\Repositories;

use App\Contracts\Repositories\CityRepositoryInterface;
use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CityRepository implements CityRepositoryInterface
{
    public function __construct(protected City $city)
    {
    }

    public function add(array $data): string|object
    {
        $city = $this->city->newInstance();
        foreach ($data as $key => $column) {
            $city[$key] = $column;
        }
        $city->save();
        return $city;
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->city->with($relations)->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->city->get();
    }

    public function getListWhere(string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $key = explode(' ', $searchValue);

        return $this->city
            ->when(isset($key) , function($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->paginate($dataLimit);
    }

    public function update(string $id, array $data): bool|string|object
    {
        $city = $this->city->find($id);
        foreach ($data as $key => $column) {
            $city[$key] = $column;
        }
        $city->save();
        return $city;
    }

    public function delete(string $id): bool
    {
        $city = $this->city->find($id);
        $city->translations()->delete();
        $city->delete();

        return true;
    }

    public function getFirstWithoutGlobalScopeWhere(array $params, array $relations = []): ?Model
    {
        return $this->city->withoutGlobalScope('translate')->where($params)->first();
    }

    public function getAll(): Collection
    {
        return $this->city->all();
    }

    public function getWithCoordinateWhere(array $params): ?Model
    {
        return $this->city->withoutGlobalScopes()->selectRaw("*,ST_AsText(ST_Centroid(`coordinates`)) as center")->where($params)->first();
    }

    public function getExportList(Request $request): Collection
    {
        $key = explode(' ', $request['search']);
        return $this->city->withCount(['stores','deliverymen'])
            ->when(isset($key) , function($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->get();
    }

    public function getLatest(array $relations = []): ?Model
    {
        return $this->city->with($relations)->latest()->first();
    }

    public function cityModuleSetupUpdate(string $id, array $data, array $moduleData): bool|string|object
    {
        $city = $this->city->find($id);
        foreach ($data as $key => $column) {
            $city[$key] = $column;
        }
        $city->modules()->sync($moduleData);
        $city->save();
        return $city;
    }

    public function getWithCountLatest(array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->city->withCount($relations)->latest()->paginate($dataLimit);
    }

    public function getActiveListExcept(array $params): Collection
    {
        return $this->city->whereNot($params)->active()->get();
    }
}
