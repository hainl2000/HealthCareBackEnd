<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PaginationParams extends Enum
{
    const RecordsPerPage = 10;
    const FirstPage = 1;
    const GetAllItems = -1;
}
