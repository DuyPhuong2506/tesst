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
        'update_success' => 'Event update success',
        'update_fail' => 'Event update fail',
        'delete_success' => 'Event delete success',
        'delete_fail' => 'Event delete fail',
        'list_fail' => 'Event list fail',
        'list_null' => 'Event list null',
        'detail_fail' => 'Event detail fail',
        'not_found' => 'The wedding event not found !',
        'send_check_seat_subject' => '席次確認依頼',

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
                'required' => 'The time line field is required',
                'array' => 'Must be an array.',
                'date_format' => 'The date format is invalid.',
                'after' => 'The time can not be duplicate',
                'after_or_equal' => 'The time can not be duplicate',
                'min' => 'The time line 1-2 time item',
                'max' => 'The time line 1-2 time item'
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
                'max' => 'The greeting_message can not be greater than :max characters',
                'required' => 'The greeting message is required !'
            ],
            'thank_you_message' => [
                'max' => 'The thank you messages can not be greater than :max characters',
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
                'before' => 'The time must be before couple edit date'
            ],
            'couple_edit_date' => [
                'required' => 'The couple edit date is required',
                'date_format' => 'The couple edit date format is invalid',
                'before' => 'The couple edit date must be before wedding date',
                'after' => 'The couple edit date must be after wedding date the guest invitation response date'
            ],
            'token' => [
                'required' => 'The token is required !',
                'exists' => 'The token does not exist !'
            ],
            'time_table' => [
                'array' => 'The event must be an array.',
                'date_format' => 'The time table date format is invalid',
                'after_or_equal' => 'The time could not be loop',
                'after' => 'The time could not be loop',
                'required' => 'The time is required'
            ],
            'id' => [
                'required' => 'The event id is required',
                'exists' => 'The event id does not exists',
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
        'not_found' => 'User not found !',

        'validation' => [
            'email' => [
                'required' => 'The email field is required',
                'regex' => 'The email field format is invalid',
                'max' => 'The email can not be greater than :max characters',
                'unique' => 'The email can not be use now',
                'exists' => 'We have sent you an email, please reset your password within 1 hour'
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
                'required' => 'The phone is required',
                'numeric' => 'The phone must be numeric',
                'digits_between' => 'The phone must be 10-11 characters'
            ],
            'company_name' => [
                'required' => 'The company_name field is required',
                'max' => 'The company_name can not be greater than :max characters'
            ],
            'post_code' => [
                'required' => 'The post code is required',
                'digits' => 'The post code is 7 characters',
                'numeric' => 'The post code is number characters'
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
                'max' => 'The email can not be greater than :max characters',
                'unique' => 'The email is exist',
                'different' => 'The groom email can not be same bride email'
            ]
        ]
    ],
    'wedding_card' => [
        'create_success' => 'Wedding card create success',
        'create_fail' => 'Wedding card create fail',
        'update_success' => 'Wedding card update success',
        'update_fail' => 'Wedding card update fail',
        'delete_success' => 'Wedding card delete success',
        'delete_fail' => 'Wedding card delete fail',
        'send_mail_sucess' => 'The mail send success',
        'send_mail_fail' => 'The mail send fail',
        'subject_to_staff' => 'WEB招待状確認依頼',

        'validation' => [
            'template_card_id' => [
                'required' => 'The template card id is required',
                'exists' => 'The template card does not exist'
            ],
            'content' => [
                'required' => 'The content is required',
                'max' => 'The wedding card content can not be greater than :max characters'
            ],
            'couple_photo' => [
                'required' => 'The couple photo is required',
                'mimes' => 'The couple photo must be JPG, PNG type',
                'max' => 'The couple photo can not be greater than 10Mb',
            ],
            'wedding_price' => [
                'required' => 'The wedding price is required',
                'digits_between' => 'The wedding price is 0~6 number characters',
            ]
        ]
    ],
    'bank_account' => [
        'create_success' => 'Bank account create success',
        'create_fail' => 'Bank account create fail',
        'update_success' => 'Bank account update success',
        'update_fail' => 'Bank account update fail',
        'delete_success' => 'Bank account delete success',
        'delete_fail' => 'Bank account delete fail',
        'not_exist' => 'The bank account does not exists !',

        'validation' => [
            'bank_name' => [
                'required' => 'The bank name is required',
                'max' => 'The bank account can not be greater than :max characters',
            ],
            'bank_branch' => [
                'required' => 'The bank branch is required',
                'max' => 'The bank account can not be greater than :max characters',
            ],
            'account_number' => [
                'required' => 'The account number is required',
                'digits' => 'The account number is :digits number characters',
            ],
            'card_type' => [
                'required' => 'The card type is required',
                'max' => 'The card type can not be greater than :max characters',
            ],
            'holder_name' => [
                'required' => 'The holder name is required',
                'max' => 'The holder name can not be greater than :max characters',
            ],
            'wedding_card_id' => [
                'required' => 'The wedding card id is required',
                'exists' => 'The wedding card id does not exist',
            ],
            'bank_account' => [
                'array' => 'The data type must be an array',
                'required' => 'The bank account data is required',
                'max' => 'The banks account can not be greater than :max items'
            ],
        ]
    ],
    'participant' => [
        'create_success' => 'Participant create success',
        'create_fail' => 'Participant create fail',
        'update_success' => 'Participant update success',
        'update_fail' => 'Participant update fail',
        'delete_success' => 'Participant delete success',
        'delete_fail' => 'Participant delete fail',
        'list_fail' => 'The list is fail',
        'detail_fail' => 'Participant detail fail',
        'not_found' => 'Participant not found',
        'max_remote' => 'This table is can not greater than 6 guest participant',

        'validation' => [
            'is_only_party' => [
                'required' => 'The is only party is required',
                'boolean' => 'The is only party is boolean',
            ],
            'first_name' => [
                'required' => 'The first name is required',
                'max' => 'The participant first name can not greater than :max characters',
            ],
            'last_name' => [
                'required' => 'The last name is required',
                'max' => 'The participant last name can not greater than :max characters',
            ],
            'relationship_couple' => [
                'required' => 'The couple relationship is required',
                'max' => 'The participant last name can not greater than :max characters',
            ],
            'email' => [
                'required' => 'The email field is required',
                'max' => 'The email can not be greater than :max characters',
                'regex' => 'The email format is invalid'
            ],
            'post_code' => [
                'required' => 'The post code is required',
                'digits' => 'The post code is :digits characters',
                'numeric' => 'The post code is numeric',
            ],
            'address' => [
                'required' => 'The address is required',
                'max' => 'The address can not be greater than :max characters',
            ],
            'phone' => [
                'required' => 'The phone is required',
                'digits_between' => 'The phone must be 10~11 characters'
            ],
            'customer_type' => [
                'required' => 'The customer type is required',
                'numeric' => 'The customer type must be numeric',
            ],
            'task_content' => [
                'max' => 'The task content can not be greater than :max characters',
            ],
            'free_word' => [
                'max' => 'The free word can not be greater than :max characters',
            ],
            'bank_account_id' => [
                'required' => 'The bank account id is required',
                'exists' => 'The bank account does not exists'
            ],
            'bank_order' => [
                'required' => 'The bank account id is required',
                'min' => 'The bank account value can not be less than :min',
                'max' => 'The bank account value can not be greater than :max',
                'numeric' => 'The bank order must be a numeric',
            ],
            'is_send_wedding_card' => [
                'required' => 'The is send wedding card is required',
                'boolean' => 'The is send wedding card must be an boolean'
            ],
            'id' => [
                'required' => '必須項目に入力してください。',
                'exists' => 'The participant id does not exists',
                'numeric' => 'The id must be numeric',
            ],
            'customer_relatives' => [
                'first_name' => [
                    'required' => 'The customer relative is require',
                    'max' => 'The customer relative first name can not be greater than :max'
                ],
                'last_name' => [
                    'required' => 'The customer relative is require',
                    'max' => 'The customer relative last name can not be greater than :max',
                ],
                'relationship' => [
                    'max' => 'The customer relative last name can not be greater than :max',
                ]
            ],
            'join_status' => [
                'required' => 'The join status is required',
                'deadline' => 'The join status is deadline',
                'numeric' => 'The join status is numeric',
            ]
        ]
    ],
    'customer_task' => [
        'create_success' => 'Customer task create success',
        'create_fail' => 'Customer task create fail',
        'update_success' => 'Customer task update success',
        'update_fail' => 'Customer task update fail',
        'delete_success' => 'Customer task delete success',
        'delete_fail' => 'Customer task delete fail',

        'validation' => [
            'name' => [
                'required' => 'Customer task name is required',
            ],
            'description' => [
                'required' => 'Customer task description is required'
            ]
        ]
    ],
];
