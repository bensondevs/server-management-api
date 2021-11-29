<?php

namespace App\Http\Requests\Newsletters;

use Illuminate\Foundation\Http\FormRequest;

class FindNewsletterRequest extends FormRequest
{
    private $newsletter;

    public function getNewsletter()
    {
        return $this->newsletter = ($this->newsletter) ?:
            Newsletter::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
