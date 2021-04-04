<?php

namespace Wuori\UniqueGmailValidation\UniqueGmail;

use Illuminate\Contracts\Validation\Rule;

class UniqueGmail implements Rule
{
    /** @var string */
    protected $attribute;

    /** @var string */
    protected $col_name;

    /** @var string */
    protected $model_class;

    public function __construct(string $model_class = '\User', string $col_name = 'email')
    {
        $this->attribute = null;
        $this->col_name = $col_name;
        $this->model_class = $model_class;
    }

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;

        $is_unique = true;

        $all_gmails = $this->model_class::where($this->col_name, 'like', '%gmail.')->get([$this->col_name]);

        \Log::info(print_r($all_gmails, true));

        return $is_unique;
    }

    public function message(): string
    {
        return 'Gmail varient exists!';
        // $validValues = implode(', ', $this->validValues);

        // return __('validationRules::messages.enum', [
        //     'attribute' => $this->attribute,
        //     'validValues' => $validValues,
        // ]);
    }
}
