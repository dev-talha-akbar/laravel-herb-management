<?php

namespace App\Helpers;

use App\Models\Item;

trait HasItems
{
    private function withNonExistingItems()
    {
        $request = $this->crud->request;

        $request->merge([
            'items' => []
        ]);

        $itemableTypesCollection = collect($this->itemableTypes);

        $types = $itemableTypesCollection->map(function ($type) {
            return $type[1];
        });

        foreach ($types as $type) {
            $itemsCollection = collect($request->{$type});

            $oldItems = $itemsCollection->filter(function ($item) {
                return is_numeric($item);
            })->all();

            $newItems = $itemsCollection->filter(function ($item) {
                return !is_numeric($item);
            })->map(function ($item) use ($type) {
                return  [
                    'type' => $type,
                    'value' => $item
                ];
            });

            $newItemValues = $newItems->map(function ($item) {
                return $item['value'];
            });

            $newItemsArray = $newItems->all();

            Item::insert($newItemsArray);

            $newItemsInDb = Item::where('type', $type)
                ->whereIn('value', $newItemValues)
                ->get(['id'])->pluck('id')->all();


            $request->merge([
                'items' => array_merge($request->items, $oldItems, $newItemsInDb)
            ]);

            $this->crud->removeField($type);
        }

        $this->crud->addField([
            'label'     => 'Items',
            'type'      => 'itemable',
            'name'      => 'items',
            'entity'    => 'items',
            'attribute' => 'value',
            'model'     => "App\Models\Item",
            'pivot'     => true
        ]);
    }
}
