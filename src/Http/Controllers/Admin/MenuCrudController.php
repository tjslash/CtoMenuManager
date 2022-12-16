<?php

namespace Tjslash\CtoMenuManager\Http\Controllers\Admin;

use Tjslash\CtoMenuManager\Http\Requests\MenuRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Tjslash\CtoMenuManager\Models\Menu;
use Tjslash\CtoPageManager\Models\Page;

/**
 * Class MenuCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MenuCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Menu::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/menu');
        CRUD::setEntityNameStrings(
            trans('tjslash::cto-menu-manager.menu_item'), 
            trans('tjslash::cto-menu-manager.menu_items')
        );
        CRUD::denyAccess('show');
        CRUD::disableDetailsRow();
        if (backpack_pro()) {
            CRUD::enableExportButtons();
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'id',
            'label' => 'ID'
        ]);

        CRUD::addColumn([
            'name' => 'title',
            'label' => trans('tjslash::cto-menu-manager.title'),
            'type' => 'closure',
            'function' => function(Menu $menu) {
                if (!backpack_pro() || $menu->url === null) return $menu->title;
                return '<a target="_blank" href="' . $menu->url . '">' . $menu->title . '</a>';
            },
            'searchLogic' => function (Builder $query, array $column, string $searchTerm) {
                return $query->orWhere('title', 'like', "%{$searchTerm}%");
            }
        ]);

        CRUD::addColumn([
            'name' => 'parents',
            'label' => trans('tjslash::cto-menu-manager.parents'),
            'type' => 'relationship',
            'attribute' => 'title',
        ]);

        CRUD::addColumn([
           'name' => 'childs',
           'type' => 'relationship_count', 
           'label' => trans('tjslash::cto-menu-manager.submenu'),
           'suffix' => ' шт.'
        ]);

        CRUD::addColumn([
            'name' => 'priority',
            'label' => trans('tjslash::cto-menu-manager.priority')
        ]);

        CRUD::addColumn([
            'type' => 'check',
            'name' => 'active',
            'label' => trans('tjslash::cto-menu-manager.active')
        ]);

        if (backpack_pro()) {
            $this->setupFilters();
        }
    }

    /**
     * Set filters
     * 
     * @see https://backpackforlaravel.com/docs/4.1/crud-filters
     * @return void
     */
    protected function setupFilters()
    {
        CRUD::addFilter([
                'type' => 'text',
                'name' => 'title',
                'label'=> trans('tjslash::cto-menu-manager.title')
            ], 
            false, 
            fn($value) => CRUD::addClause('where', 'title', 'LIKE', "%$value%")
        );

        CRUD::addFilter([
                'name' => 'parents',
                'type' => 'select2',
                'label' => trans('tjslash::cto-menu-manager.parents')
            ], 
            fn() => Menu::whereHas('childs')->get()->pluck('title', 'id')->toArray(),
            fn($value) => $this->crud
                ->query
                ->whereHas('parents', fn($query) => $query->where('laravel_reserved_1.id', $value))
        );

        CRUD::addFilter([
                'type' => 'text',
                'name' => 'priority',
                'label'=> trans('tjslash::cto-menu-manager.priority')
            ], 
            false, 
            fn($value) => CRUD::addClause('where', 'priority', $value)
        );

        CRUD::addFilter([
                'name' => '_blank',
                'type' => 'dropdown',
                'label' => trans('tjslash::cto-menu-manager.new_window')
            ], [
                1 => trans('tjslash::cto-menu-manager.yes'),
                0 => trans('tjslash::cto-menu-manager.no'),
            ],
            fn($value) => CRUD::addClause('where', '_blank', $value)
        );

        CRUD::addFilter([
                'name' => 'active',
                'type' => 'dropdown',
                'label' => trans('tjslash::cto-menu-manager.active')
            ], [
                1 => trans('tjslash::cto-menu-manager.yes'),
                0 => trans('tjslash::cto-menu-manager.no'),
            ],
            fn($value) => CRUD::addClause('where', 'active', $value)
        );
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MenuRequest::class);

        CRUD::addField([
            'name' => 'title',
            'label' => trans('tjslash::cto-menu-manager.title'),
        ]);

        CRUD::addField([
             'label' => trans('tjslash::cto-menu-manager.parents'),
             'type' => 'select2_multiple',
             'name' => 'parents',
             'entity' => 'parents',
             'model' => Menu::class,
             'attribute' => 'title',
             'pivot' => true,
             'options' => (function ($query) {
                 return $query->orderBy('title', 'ASC')->get();
             }),
        ]);

        CRUD::addField([
            'label' => trans('tjslash::cto-menu-manager.url'),
            'name' => 'url',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'label' => trans('tjslash::cto-menu-manager.page'),
            'type' => 'select2',
            'name' => 'page_id',
            'entity' => 'page',
            'model' => Page::class,
            'attribute' => 'title',
            'allows_null' => true,
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'priority',
            'label' => trans('tjslash::cto-menu-manager.priority'),
            'type' => 'number',
            'value' => 0
        ]);

        CRUD::addField([
            'name' => '_blank',
            'label' => trans('tjslash::cto-menu-manager.new_window'),
            'type' => 'checkbox',
        ]);

        CRUD::addField([
            'name' => 'active',
            'label' => trans('tjslash::cto-menu-manager.active'),
            'type' => 'checkbox',
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
