<?php

namespace app\enums;

enum UserStatus: int
{
    case Active = 1;
    case Banned = 2;
}
