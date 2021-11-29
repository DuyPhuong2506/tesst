<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'login' => [
        'admin' => [
            'login_fail' => 'Your email or password is not correct !',
        ],
        'couple' => [
            'login_fail' => 'Your email or password is not correct !',
        ]
    ],
    'place' => [
        'create_success' => 'Place create success',
        'create_fail' => 'Place create fail',
        'update_success' => 'Place update success',
        'update_fail' => 'Place update fail',
        'delete_success' => 'Place delete success',
        'delete_fail' => 'Place delete fail',

        'validation' => [
            'name' => [
                'required' => 'The name field is required',
                'max' => 'The name field can not be greater than :max characters'
            ],
            'restaurant_id' => [
                'required' => 'The restaurant_id field is required',
                'exists' => 'Restaurant is not exists or is not active'
            ],
            'table_positions' => [
                'array' => 'Must be an array',
                'required' => 'The table_positions field is required',
                'integer' => 'Must be an integer',
                'max' => 'The table_positions field can not be greater than :max characters'
            ],
            
        ]
    ],
    'admin_staff' => [
        'delete_success' => 'Admin staff delete success',
        'delete_fail' => 'Admin staff delete fail'
    ],
    'couple' => [
        'validation' => [
            'username' => [
                'required' => 'The username field is required'
            ],
            'password' => [
                'required' => 'The password field is required'
            ]
        ]
    ],
    'event' => [
        'create_success' => 'Event create success',
        'create_fail' => 'Event create fail',
        'update_sucess' => 'Event update success',
        'update_fail' => 'Event update fail',
        'delete_success' => 'Event delete success',
        'delete_fail' => 'Event delete fail',
        'list_fail' => 'Event list fail',
        'list_null' => 'Event list null',
        'detail_fail' => 'Event detail fail',

        'validation' => [
            'title' => [
                'required' => 'The title field is required',
                'max' => 'The title field can not be greater than :max characters'
            ],
            'date' => [
                'required' => 'The date field is required',
                'date_format' => 'The date format is invalid.',
                'after' => 'The date you selected must be after today',
                'was_held' => 'Existed event held in this day and place'
            ],
            'pic_name' => [
                'required' => 'The pic_name field is required',
                'max' => 'The pic_name field can not be greater than :max characters'
            ],
            'time_line' => [
                'required' => 'The time_line field is required',
                'array' => 'Must be an array.',
                'date_format' => 'The date format is invalid.',
                'after' => 'The time you selected is duplicated'
            ],
            'place' => [
                'required' => 'The place field is required',
                'exists' => 'Place is not exists or is not active'
            ],
            'is_close' => [
                'boolean' => 'Must be a boolean'
            ],
            'table_map_image' => [
                'max' => 'The time_map_image can not be greater than :max characters'
            ],
            'greeting_message' => [
                'max' => 'The greeting_message can not be greater than :max characters'
            ],
            'couple_name' => [
                'required' => 'The couple_name field is required',
                'max' => 'The couple_name can not be greater than :max characters'
            ],
            'allow_remote' => [
                'required' => 'The couple_name field is required',
                'boolean' => 'Must be a boolean'
            ],
            'guest_invitation_response_date' => [
                'required' => 'The guest invitation response date is required',
                'date_format' => 'The guest invitation response date format is invalid',
                'before_or_equal' => 'The guest invitation response is before the wedding date'
            ],
            'couple_edit_date' => [
                'required' => 'The couple edit date is required',
                'date_format' => 'The couple edit date format is invalid',
                'before_or_equal' => 'The guest invitation response is before the wedding date'
            ]
        ]
    ],
    'user' => [
        'create_success' => 'User create success',
        'create_fail' => 'User create fail',
        'update_sucess' => 'User update success',
        'update_fail' => 'User update fail',
        'delete_success' => 'User delete success',
        'delete_fail' => 'User delete fail',
        'list_fail' => 'User list fail',
        'detail_fail' => 'User detail fail',
        'password_success' => 'Update password success',
        'password_fail' => 'Update password fail',
        'token_success' => 'Token success',
        'token_fail' => 'Token fail',
        'password_verify_fail' => 'Old password is not correct !',
        'existed' => 'Failed ! User is existed !',

        'validation' => [
            'email' => [
                'required' => 'The email field is required',
                'regex' => 'The email field format is invalid',
                'max' => 'The email can not be greater than :max characters',
                'unique' => 'The email can not be use now'
            ],
            'password' => [
                'required' => 'The password field is required',
                'min' => 'The password between 8～255 characters',
                'max' => 'The password between 8～255 characters',
                'regex' => 'The password format is invalid',
                'confirmed' => 'The confirm password is not same'
            ],
            'token' => [
                'required' => 'The token is required',
                'exists' => 'The token is not exists'
            ]
        ]
    ],
    'restaurant' => [
        'validation' => [
            'restaurant_name' => [
                'required' => 'The restaurant_name field is required',
                'max' => 'The restaurant_name can not be greater than :max characters',
            ],
            'contact_name' => [
                'required' => 'The contact_name field is required',
                'max' => 'The contact_name can not be greater than :max characters',
            ],
            'phone' => [
                'required' => 'The phone field is required',
                'numeric' => 'The phone is numeric',
                'digits_between' => 'The phone number must be 10~11 characters'
            ],
            'company_name' => [
                'required' => 'The company_name field is required',
                'max' => 'The company_name can not be greater than :max characters'
            ],
            'post_code' => [
                'required' => 'The post_code field is required',
                'digits' => 'The post_code is :digits'
            ],
            'address' => [
                'required' => 'The address field is required',
                'max' => 'The address can not be greater than :max characters'
            ],
            'guest_invitation_response_num' => [
                'required' => 'The guest_invitation_response_num field is required',
                'numeric' => 'Must be numeric',
                'max' => 'Guest_invitation_response_num value can not be greater than :max',
                'min' => 'Guest_invitation_response_num value can not be greater than :min'
            ],
            'couple_edit_num' => [
                'required' => 'The couple_edit_num field is required',
                'numeric' => 'Must be numeric',
                'max' => 'Guest invitation response num value can not be greater than :max',
                'min' => 'Guest invitation response num value can not be greater than :min'
            ]
        ]
    ],
    'mail' => [
        'send_success' => 'Mail send success',
        'send_fail' => 'Mail send fail',

        'validation' => [
            'email' => [
                'required' => 'The email field is required',
                'regex' => 'The email format is invalid',
                'exists' => 'The email is not exists or disabled',
                'max' => 'The email can not be greater than :max characters'
            ]
        ]
    ]
];
