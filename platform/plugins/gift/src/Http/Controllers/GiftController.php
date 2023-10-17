<?php

namespace Botble\Gift\Http\Controllers;

use Botble\Gift\Http\Requests\GiftRequest;
use Botble\Gift\Models\Gift;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Gift\Tables\GiftTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Gift\Forms\GiftForm;
use Botble\Base\Forms\FormBuilder;

class GiftController extends BaseController
{
    public function index(GiftTable $table)
    {
        PageTitle::setTitle(trans('plugins/gift::gift.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        PageTitle::setTitle(trans('plugins/gift::gift.create'));

        return $formBuilder->create(GiftForm::class)->renderForm();
    }

    public function store(GiftRequest $request, BaseHttpResponse $response)
    {
        $gift = Gift::query()->create($request->input());

        event(new CreatedContentEvent(GIFT_MODULE_SCREEN_NAME, $request, $gift));

        return $response
            ->setPreviousUrl(route('gift.index'))
            ->setNextUrl(route('gift.edit', $gift->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Gift $gift, FormBuilder $formBuilder)
    {
        PageTitle::setTitle(trans('core/base::forms.edit_item', ['name' => $gift->name]));

        return $formBuilder->create(GiftForm::class, ['model' => $gift])->renderForm();
    }

    public function update(Gift $gift, GiftRequest $request, BaseHttpResponse $response)
    {
        $gift->fill($request->input());

        $gift->save();

        event(new UpdatedContentEvent(GIFT_MODULE_SCREEN_NAME, $request, $gift));

        return $response
            ->setPreviousUrl(route('gift.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Gift $gift, Request $request, BaseHttpResponse $response)
    {
        try {
            $gift->delete();

            event(new DeletedContentEvent(GIFT_MODULE_SCREEN_NAME, $request, $gift));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $gift = Gift::query()->findOrFail($id);
            $gift->delete();
            event(new DeletedContentEvent(GIFT_MODULE_SCREEN_NAME, $request, $gift));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
