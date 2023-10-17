<?php

namespace Botble\Gift\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Gift\Http\Requests\GiftRequest;
use Botble\Gift\Models\Gift;

class GiftForm extends FormAbstract
{
    public function buildForm(): void
    {
        $this
            ->setupModel(new Gift)
            ->setValidatorClass(GiftRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'text', [
                'label'      => 'Mô tả',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => "Mô tả danh mục",
                    'data-counter' => 200,
                ],
            ])
            ->add('logo','mediaImage',[
                'label' => 'Logo',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => 'Logo',
                ],
            ])
            ->add('price_from', 'text', [
                'label'      => 'Giá từ',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => "Giá từ",
                    'data-counter' => 120,
                ],
            ])
            ->add('price_to', 'text', [
                'label'      => 'Giá đến',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => "Giá đến",
                    'data-counter' => 120,
                ],
            ])
            ->add('special_gift_id', 'customSelect', [
                'label' => 'Danh mục',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => $this->getCategories(),
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }

    private function getCategories()
    {
        $categories = [];
        return $categories;
    }
}
