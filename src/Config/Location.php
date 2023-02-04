<?php

namespace App\Config;

enum Location: string
{
    case STOWED = 'stowed';
    
    case SERVER = 'server';
    
    case LENT = 'lent';
    
    case RETURNED = 'returned';
    
    case SHELF = 'shelf';
    
    case LOST = 'lost';
}