<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\FileConstant;

class UploadPreSignedRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file_place' => [
                'required', 
                function ($attribute, $value, $fail) {
                    $valueArr = explode('.', $value);
                    if (strtolower($valueArr[count($valueArr) - 1]) !== $valueArr[count($valueArr) - 1]) {
                        return $fail('The ' . $attribute . ' not strtolower');
                    }elseif (!in_array($valueArr[count($valueArr) - 1],  FileConstant::TYPE_FILE_PLACE)) {
                        return $fail('The ' . $attribute . ' not the required format');
                    }
                }],
        ];
    }
}
