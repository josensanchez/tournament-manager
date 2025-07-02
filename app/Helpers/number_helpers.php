<?php

function is_power_of_two(int $n): bool
{
    if ($n <= 1) {
        return false;
    }

    return ($n & ($n - 1)) === 0;
}
