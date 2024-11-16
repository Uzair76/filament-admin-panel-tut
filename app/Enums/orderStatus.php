<?php

namespace App\Enums;

enum orderStatus:string{
    case PENDING =   'pending';
    case PROCESSING =   'processing';
    case COMPLETED =   'completed';
    case DECLINED =   'declined';

}
