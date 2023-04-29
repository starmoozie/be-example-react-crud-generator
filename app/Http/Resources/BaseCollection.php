<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    const LOGIN_ROUTE = 'login';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        $resource = $this->collection->first();

        return [
            'success' => true,
            'message' => __("message.success_{$request->method()}", ['menu' => str_replace('-', '', $request->segment(3))]),
            'columns' => $resource ? $this->handleProperties($resource, $request) : []
        ];
    }

    public function handleProperties($model, $request)
    {
        $all_properties = $this->getAvailableProperties($model);

        // Unset hidden properties from model
        $available_properties = array_diff($all_properties, $model->getHidden());

        // Identity is login route, then add token attribute
        if ($request->route()->getName() === Self::LOGIN_ROUTE) {
            $available_properties = [...$available_properties, ...['token']];
        }

        foreach ($available_properties as $key => $value) {
            // Identity if not eager loaded
            if (is_numeric($key)) {
                $data[] = [
                    'name' => $value,
                    'key'  => $value
                ];
            } else {
                if (($value instanceof \Illuminate\Support\Collection && !$value->isEmpty()) || (!$value instanceof \Illuminate\Support\Collection && $value)) {
                    $attribute = $value && implode('.', $value->getFillable());
                    $data[] = [
                        "name" => $key,
                        "key"  => "{$key}.{$attribute}"
                    ];
                }
            }
        }

        return $data ?? [];
    }

    /**
     * Handle relationship data
     * 
     * @param string $key
     * @param Model|Collection $value
     * @return Model|Collection
     */
    public function handleRelationship($key, $value, $model)
    {
        $folder_name = \ucwords(\Str::camel($key));
        $resources = $model->{$key} instanceof Collection ? 'Collection' : 'Resource';
        $namespace = "\App\Http\Resources\\{$folder_name}{$resources}";

        return new $namespace($value);
    }

    public function getAvailableProperties($model)
    {
        return [
            ...[$model->getKeyName()], // Get primary key name
            ...$model->getFillable(), // Get model fillable
            ...$model->getAppends(), // Get model appends
            ...$model->getRelations() // Get model relationships
        ];
    }
}
