<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StatusExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
          new TwigFilter('status', [$this, 'formatStatus'])
        ];
    }

    public function formatStatus(int $status)
    {
        switch($status)
        {
            case 0:
                return "aktywny";
            case 1:
                return "nieaktywny";
        }

        return "nieznany";
    }
}