<?php
namespace App\Constants;

class CustomerConstant
{
    const JOIN_STATUS_CONFIRM = null;
    const JOIN_STATUS_CANCEL = 0;
    const JOIN_STATUS_APPROVED = 1;
    const JOIN_STATUS_JOIN_OFFLINE = 2;

    const CUSTOMER_TASK_SPEECH = 'speech';

    const RESPONSE_CARD_STATUS = [
        0 => "欠席", #JOIN_STATUS_CANCEL
        1 => "リモート参加", #JOIN_STATUS_APPROVED
        2 => "出席", #JOIN_STATUS_JOIN_OFFLINE
    ];
}