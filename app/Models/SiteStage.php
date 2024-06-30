<?php

namespace App\Models;

enum SiteStage: int
{

    case PENDING = 1;
    case PLAIN_HTTP = 2;
    case PUPPETEER = 3;


}
