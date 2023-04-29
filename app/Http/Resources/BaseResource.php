<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class BaseResource extends JsonResource
{
    const LOGIN_ROUTE = 'login';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Get all properties from the resource
        $available_properties = $this->handleProperties($request);

        foreach ($available_properties as $key => $value) {
            // Identity if not eager loaded
            if (is_numeric($key)) {
                $data[$value] = $this->{$value};
            } else {
                $data[\Str::camel($key)]   = $this->handleRelationship($key, $value);
            }
        }

        return $data;
    }

    public function handleProperties($request)
    {
        $all_properties = $this->getAvailableProperties();
        // Unset hidden properties from model
        $available_properties = array_diff($all_properties, $this->getHidden());

        // Identity is login route, then add token attribute
        if ($request->route()->getName() === Self::LOGIN_ROUTE) {
            $available_properties = [...$available_properties, ...['token']];
        }

        return $available_properties;
    }

    /**
     * Handle relationship data
     * 
     * @param string $key
     * @param Model|Collection $value
     * @return Model|Collection
     */
    public function handleRelationship($key, $value)
    {
        $folder_name = \Str::ucfirst(\Str::singular($key));
        $resources = $this->{$key} instanceof Collection ? 'Collection' : 'Resource';
        $namespace = "\App\Http\Resources\\{$folder_name}\\{$resources}";

        return new $namespace($value);
    }

    public function getAvailableProperties()
    {
        return [
            ...[$this->getKeyName()], // Get primary key name
            ...$this->getFillable(), // Get model fillable
            ...$this->getAppends(), // Get model appends
            ...$this->getRelations() // Get model relationships
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'message' => __("message.success_{$request->method()}", ['menu' => str_replace('-', '', $request->segment(3))]),
        ];
    }
}
