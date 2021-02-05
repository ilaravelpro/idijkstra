<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 7/26/20, 6:18 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iDijkstra\Vendor;


class PriorityList {
    public $next;
    public $data;
    function __construct($data) {
        $this->next = null;
        $this->data = $data;
    }
}
