<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class PlanChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

//        $this
//            ->labels(['One', 'Two', 'Three', 'Four', 'Five'])
//            ->options([
//            'legend' => [
//                'display' => false
//            ]
//        ]);
    }
}
