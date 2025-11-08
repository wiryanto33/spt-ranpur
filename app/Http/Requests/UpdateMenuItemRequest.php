<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $menuItem = $this->route('menu_item');
        $id = is_object($menuItem) ? $menuItem->id : $menuItem;

        return [
            'name' => 'required|unique:menu_items,name,' . $id,
            'route' => 'required|unique:menu_items,route,' . $id,
            'permission_name' => 'required',
            'menu_group_id' => 'required|exists:menu_groups,id'
        ];
    }
}

