<?php

namespace Wuori\UniqueGmail;

use Illuminate\Contracts\Validation\Rule;

class UniqueGmail implements Rule
{
    /** @var string */
    protected $attribute;

    /** @var string */
    protected $model_class;

    /** @var string */
    protected $options;

    /**
     * Constructor
     *
     * @param Eloquent $model_class
     * @param array $options
     */
    public function __construct(string $model_class = null, array $options = [])
    {
        $this->attribute = null;
        $this->model_class = $model_class ?? \App\Models\User::class;
        $this->options = $options;
    }

    /**
     * Determine if a gmail varient already exists.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        // init
        $this->attribute = $attribute;
        $this->options['requested_email'] = $value;

        // grab all existing @gmail.com addresses
        $all_gmails = $this->model_class::where($this->attribute, 'like', '%gmail.com')
            ->get([$this->attribute]);

        // escape if nothing to compare
        if(!$all_gmails){
            return true;
        }

        // see if any gmail varients exist
        $this_email = $this->getNormalizedEmail($value);
        foreach($all_gmails as $existing){
            if($this->getNormalizedEmail($existing->{$this->attribute}) == $this_email){
                $this->options['existing_email'] = $existing->{$this->attribute};
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        // custom error message passed
        if(isset($this->options['message'])){
            $find = [':existing_email', ':requested_email'];
            $replace = [$this->options['existing_email'], $this->options['requested_email']];
            return str_replace($find, $replace, $this->options['message']);
        };

        // return default laravel 'unique' validation message
        return trans('validation.unique', ['attribute' => $this->attribute]);
    }

    /**
     * Get a normalized representation of an email address.
     *
     * @param string $email
     * @return string
     */
    public function getNormalizedEmail($email = ''): string
    {
        // init address
        $email = trim($email);
        $mail = strtolower($email);

        // check for and remove plus (+) appendages
        if($pos = strpos($email, '+')){
            $tld = strpos($email, '@');
            $tld = substr($email, $tld);
            $email = substr($email, 0, $pos) . $tld;
        }
        
        // remove any periods
        return str_replace('.', '', $email);
    }
}
