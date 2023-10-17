<?php

namespace Botble\Gift\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Gift\Models\Gift;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Botble\Table\DataTables;

class GiftTable extends TableAbstract
{
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, Gift $gift)
    {
        parent::__construct($table, $urlGenerator);

        $this->model = $gift;

        $this->hasActions = true;
        $this->hasFilter = true;

        if (!Auth::user()->hasAnyPermission(['gift.edit', 'gift.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function (Gift $item) {
                if (!Auth::user()->hasPermission('gift.edit')) {
                    return BaseHelper::clean($item->name);
                }
                return Html::link(route('gift.edit', $item->getKey()), BaseHelper::clean($item->name));
            })
            ->editColumn('checkbox', function (Gift $item) {
                return $this->getCheckbox($item->getKey());
            })
            ->editColumn('created_at', function (Gift $item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function (Gift $item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function (Gift $item) {
                return $this->getOperations('gift.edit', 'gift.destroy', $item);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
               'id',
               'name',
               'created_at',
               'status',
           ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('gift.create'), 'gift.create');
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('gift.deletes'), 'gift.destroy', parent::bulkActions());
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }

    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }
}
