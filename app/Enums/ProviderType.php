<?php

namespace App\Enums;

enum ProviderType: int
{
    case GitHub = 1;
    case GitLab = 2;
    case Bitbucket = 3;
}
