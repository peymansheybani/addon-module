<?php


namespace greenweb\addon\formatter;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
use vakata\database\Exception;
use Morilog\Jalali\CalendarUtils;
use greenweb\addon\component\Component;

class DateTime extends Component
{
    private $date;
    private $start_date;
    private $end_date;
    private $methodType;
    private $scrollingMethod;

    public function date($date)
    {
        $this->date = $date;

        return $this;
    }

    public function toJalali($date = null)
    {
        $this->date = $this->date ?? $date;
        $this->date = is_object($this->date) ? $this->date->format('Y-m-d H:i:s'):$this->date;
        $this->date = Jalalian::forge($this->date);

        return $this;
    }

    public function toGeo($date = null)
    {
        $this->date = $this->date ?? $date;
        $this->date = is_object($this->date) ? $this->date->format():$this->date;
        $baseDate = $this->getBaseDate();
        $this->date = CalendarUtils::toGregorianDate($baseDate[0], $baseDate[1], $baseDate[2]);

        return $this;
    }

    public function format($format = 'Y-m-d H:i:s')
    {
        return $this->date->format($format);
    }

    public function toString()
    {
        return $this->format();
    }

    public function diffDate($start_date, $end_date = null, $type = 'day')
    {
        if ($start_date == null) {
            throw new Exception('invalid start time');
        }

        $this->initializeData($start_date, $end_date)
            ->getMethodType($type);

        return $this->start_date->{$this->methodType}($this->end_date);
    }

    public function scrollingDate($date, $value, $type = 'day')
    {
        if ($this->isJalali($date)) {
            $this->date = $this->toGeo($date)->format();
        }

        $this->getScrollingMethod($type);
        $this->date = jdate($this->date)->{$this->scrollingMethod}($value)->format('Y-m-d H:i:s');
        $this->toGeo();

        return $this;
    }

    public function modify($date, $value)
    {
        if ($this->isJalali($date)) {
            $this->date = $this->toGeo()->format();
        }

        $time = strtotime($this->date.$value);
        $this->date = jdate($time)->format('Y-m-d H:i:s');
        $this->toGeo();

        return $this;
    }

    private function isJalali($date)
    {
        $this->date = $date;

        if ($this->getBaseDate()[0] < 1800) {
            return true;
        }

        return false;
    }

    private function getBaseDate()
    {
        return strpos($this->date, '-') ?
            explode('-', $this->date) :
            explode('/', $this->date);
    }

    private function convertDate($date)
    {
        if ($this->isJalali($date)) {
            $this->date = ($this->date) ?:Carbon::now();
            $date = $this->toGeo()->toString();
        }

        return $date;
    }

    private function initializeData($start_date, $end_date)
    {
        $this->start_date = $this->convertDate($start_date);
        $this->end_date = $this->convertDate($end_date);
        $this->start_date = Carbon::parse($this->start_date);
        $this->end_date = Carbon::parse($this->end_date);

        if ($this->start_date->gt($this->end_date)) {
            throw new \Exception('start date greater than end date');
        }

        return $this;
    }

    private function getMethodType(string $type)
    {
        $types = [
            'year' => 'diffInYears',
            'month' => 'diffInMonths',
            'day' => 'diffInDays',
            'hours' => 'diffInHours',
            'minute' => 'diffInMinutes',
        ];
        $this->methodType = $types[$type] ?? $types['day'];
    }

    private function getScrollingMethod($type) {
        $types = [
            'year' => 'addYears',
            'month' => 'addMonths',
            'day' => 'addDays',
            'hours' => 'addHours',
            'minute' => 'addMinutes',
        ];

        $this->scrollingMethod = $types[$type] ?? $types['day'];
    }
}