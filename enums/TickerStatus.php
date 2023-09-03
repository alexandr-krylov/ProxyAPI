<?php

namespace app\enums;

enum TickerStatus: int
{
    case Active = 1;
    case Redempted = 2;
    case Canceled = 3;
}
