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
            'login_fail' => '入力したアカウント情報に誤りがあります。正しいメールアドレスとパスワードを入力してください。',
        ],
        'couple' => [
            'login_fail' => '入力したアカウント情報に誤りがあります。正しいログインIDとパスワードを入力してください。パスワードが忘れた場合は式場と問い合わせください。',
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
                'required' => '必須項目に入力してください。',
                'max' => ':max文字列以内を入力してください。'
            ],
            'restaurant_id' => [
                'required' => '必須項目に入力してください。',
                'exists' => 'Restaurant is not exists or is not active'
            ],
            'table_positions' => [
                'array' => 'Must be an array',
                'required' => '必須項目に入力してください。',
                'integer' => 'Must be an integer',
                'max' => ':max文字列以内を入力してください。'
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
                'required' => '必須項目に入力してください。'
            ],
            'password' => [
                'required' => '必須項目に入力してください。'
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

        'validation' => [
            'title' => [
                'required' => '必須項目に入力してください。',
                'max' => ':max文字列以内を入力してください。'
            ],
            'date' => [
                'required' => '必須項目に入力してください。',
                'date_format' => 'The date format is invalid.',
                'after' => '今日より後の日付を入力してください。',
                'was_held' => '式の日時と会場が存在されました。別の日時と会場を選択してください。'
            ],
            'pic_name' => [
                'required' => '必須項目に入力してください。',
                'max' => ':max文字列以内を入力してください。'
            ],
            'time_line' => [
                'required' => '必須項目に入力してください。',
                'array' => 'Must be an array.',
                'date_format' => 'The date format is invalid.',
                'after' => '時間が重複しています。ご確認してください。',
                'after_or_equal' => '時間が重複しています。ご確認してください。',
                'min' => ':min文字列以内を入力してください。',
                'max' => ':max文字列以内を入力してください。'
            ],
            'place' => [
                'required' => '必須項目に入力してください。',
                'exists' => 'Place is not exists or is not active'
            ],
            'is_close' => [
                'boolean' => 'Must be a boolean'
            ],
            'table_map_image' => [
                'max' => ':max文字列以内を入力してください。'
            ],
            'greeting_message' => [
                'max' => ':max文字列以内を入力してください。',
                'required' => 'The greeting message is required !'
            ],
            'couple_name' => [
                'required' => '必須項目に入力してください。',
                'max' => ':max文字列以内を入力してください。'
            ],
            'allow_remote' => [
                'required' => '必須項目に入力してください。',
                'boolean' => 'Must be a boolean'
            ],
            'guest_invitation_response_date' => [
                'required' => '必須項目に入力してください。',
                'date_format' => 'The guest invitation response date format is invalid',
                'before' => '「式日時と席次新郎新婦編集期日より前の日付を選択してください。'
            ],
            'couple_edit_date' => [
                'required' => '必須項目に入力してください。',
                'date_format' => 'The couple edit date format is invalid',
                'before' => '「式日時より前の日付とWEB招待状ゲスト返答期日より後の日付を選択してください。',
                'after' => '「式日時より前の日付とWEB招待状ゲスト返答期日より後の日付を選択してください。'
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
        'password_verify_fail' => '古いパスワードが間違っています。ご確認してください。',
        'existed' => 'Failed ! User is existed !',
        'not_found' => 'このアカウントが存在しません。システム管理者と問い合わせください。',

        'validation' => [
            'email' => [
                'required' => '必須項目に入力してください。',
                'regex' => 'メールアドレスの形式が正しくありません。ご確認してください。',
                'max' => ':max文字列以内を入力してください。',
                'unique' => 'このアカウントが存在しません。システム管理者と問い合わせください。',
                'exists' => 'パスワードリセットのメールを送りました。\\n メール記載のリンクより1時間以内にパスワードを再設\\n定してください。'
            ],
            'password' => [
                'required' => '必須項目に入力してください。',
                'min' => '半角英数字8～255文字を入力してください。',
                'max' => '半角英数字8～255文字を入力してください。',
                'regex' => '半角英数字文字を入力してください。',
                'confirmed' => '上記の同じパスワードを入力してください。'
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
                'required' => '必須項目に入力してください。',
                'max' => ':max文字列以内を入力してください。',
            ],
            'contact_name' => [
                'required' => '必須項目に入力してください。',
                'max' => ':max文字列以内を入力してください。',
            ],
            'phone' => [
                'required' => '必須項目に入力してください。',
                'numeric' => '数字を入力してください。',
                'digits_between' => '半角英数字10～11文字を入力してください。'
            ],
            'company_name' => [
                'required' => '必須項目に入力してください。',
                'max' => ':max文字列以内を入力してください。'
            ],
            'post_code' => [
                'required' => '必須項目に入力してください。',
                'digits' => '半角数字7数字を入力してください。',
                'numeric' => '数字を入力してください。'
            ],
            'address' => [
                'required' => '必須項目に入力してください。',
                'max' => ':max文字列以内を入力してください。'
            ],
            'guest_invitation_response_num' => [
                'required' => '必須項目に入力してください。',
                'numeric' => 'Must be numeric',
                'max' => '半角数字1～180日前までの日数を入力してください。',
                'min' => '半角数字1～180日前までの日数を入力してください。'
            ],
            'couple_edit_num' => [
                'required' => '必須項目に入力してください。',
                'numeric' => 'Must be numeric',
                'max' => '半角数字1～180日前までの日数を入力してください。',
                'min' => '半角数字1～180日前までの日数を入力してください。'
            ]
        ]
    ],
    'mail' => [
        'send_success' => 'Mail send success',
        'send_fail' => 'Mail send fail',

        'validation' => [
            'email' => [
                'required' => '必須項目に入力してください。',
                'regex' => 'メールアドレスの形式が正しくありません。ご確認してください。',
                'exists' => '入力したアカウント情報に誤りがあります。正しいメールアドレスとパスワードを入力してください。',
                'max' => ':max文字列以内を入力してください。',
                'unique' => 'メールアドレスが存在しました。',
                'different' => 'メールアドレスがすでにシステムで使用されています。 別のメールアドレスを入力してください。'
            ]
        ]
    ]
];
