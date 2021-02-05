<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 7/26/20, 6:18 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iDijkstra\Vendor;

class Edge
{

    public $start;
    public $end;
    public $weight;

    public function __construct($start, $end, $weight)
    {
        $this->start = $start;
        $this->end = $end;
        $this->weight = $weight;
    }
}
