<?php

namespace App\Config;

enum State: int
{
    case VERIFIED = 1;

    case STAMPED = 2;

    case COLOURED = 4;

    case SCANNED = 8;
}