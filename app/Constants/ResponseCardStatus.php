<?php
namespace App\Constants;

class ResponseCardStatus
{
    /*
     * To search in list
     */
    const RESPONSE_CARD_STATUS = [
        1 => "出席", #Will come
        2 => "欠席", #Won't come 
        3 => "リモート参加", #Remote join
    ];

    /*
     * To get status constant
     */
    const WILL_COME = 1;
    const NOT_COME = 2;
    const REMOTE_JOIN = 3;
}