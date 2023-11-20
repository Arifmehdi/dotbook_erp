<?php

namespace Modules\HRM\Service;

use Modules\HRM\Interface\OffDaysRepositoryInterface;
use Modules\HRM\Interface\OffDaysServiceInterface;

class OffDayService implements OffDaysServiceInterface
{
    private $offDaysRepository;

    public function __construct(OffDaysRepositoryInterface $offDaysRepository)
    {
        $this->offDaysRepository = $offDaysRepository;
    }

    public function getByMonth(string $month, int $year): iterable
    {
        return $this->offDaysRepository->getMonthlyOffDays($month, $year);
    }

    public function getByYear(int $year): iterable
    {
        return $this->offDaysRepository->getYearlyOffDays($year);
    }
}
