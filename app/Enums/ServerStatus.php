<?php

namespace App\Enums;

enum ServerStatus: int
{
    case Unknown = 0;
    case Connected = 1;
    case Failed = 2;
}
