<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdateTimeTableEventRequest extends ApiRequest
{
    public function rules()
    {
        $rules = [
            "time_table" => "array",
            "time_table.0.start" => "date_format:H:i|required",
            "time_table.0.end" => "date_format:H:i|after:time_table.0.start|required",
        ];

        $timeTable = request()->time_table;
        if(gettype($timeTable) == "array"){
            if(count($timeTable) > 1){
                for($i = 0; $i < count($timeTable) - 1; $i++){
                    $rules["time_table.".($i+1).".start"] = "date_format:H:i|after_or_equal:time_table.$i.end|required";
                    $rules["time_table.".($i+1).".end"] = "date_format:H:i|after:time_table.".($i+1).".start|required";
                }
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'time_table.array' => __('messages.event.validation.time_table.array'),
            'time_table.*.start.date_format' => __('messages.event.validation.time_table.date_format'),
            'time_table.*.start.required' => __('messages.event.validation.time_table.required'),
            'time_table.*.start.after_or_equal' => __('messages.event.validation.time_table.after_or_equal'),
            'time_table.*.end.date_format' => __('messages.event.validation.time_table.date_format'),
            'time_table.*.end.required' => __('messages.event.validation.time_table.required'),
            'time_table.*.end.after' => __('messages.event.validation.time_table.after'),
        ];
    }
}