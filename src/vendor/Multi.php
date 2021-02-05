<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/2/20, 9:59 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iDijkstra\Vendor;

class Multi
{
    public $nodes = array();

    public $path = [];
    public $paths = [];
    public $prev = [];
    public $ignors = [];
    public $dist = [];

    public function addedge($start, $end, $weight = 0)
    {
        if (!isset($this->nodes[$start])) {
            $this->nodes[$start] = array();
        }
        array_push($this->nodes[$start], new Edge($start, $end, $weight));
    }

    public function removenode($index)
    {
        array_splice($this->nodes, $index, 1);
    }

    public function paths_from($from)
    {
        $this->dist[$from] = 0;
        $visited = array();
        $Q = new PriorityQueue("compareWeights");
        $Q->add(array($this->dist[$from], $from));
        $nodes = $this->nodes;
        while ($Q->size() > 0) {
            list($distance, $u) = $Q->remove();
            if (isset($visited[$u])) continue;
            $visited[$u] = True;
            if (isset($nodes[$u]))
                foreach ($nodes[$u] as $edge) {
                    $alt = $this->dist[$u] + $edge->weight;
                    $end = $edge->end;
                    if (!isset($this->dist[$end]) || $alt < $this->dist[$end]) {
                        $this->prev[$u][] = $end;
                        $this->prev[$end][] = $u;
                        $this->dist[$end] = $alt;
                        $Q->add(array($this->dist[$end], $end));
                    }
                }
        }
        return array($this->dist, $this->prev);
    }

    public function paths_to($start, $dest)
    {
        foreach ($this->prev as $index => $items) {
            $this->ignors[] = [];
            if (count($items) > 0) $this->check($items, $index);
            else unset($this->prev[$index]);
        }
        $this->paths = [];
        $this->enumerate($dest, $start);
        return $this->paths;
    }

    private function check($items, $index) {
        $this->ignors[] = $index;
        foreach ($items as $i => $item) {
            if (!in_array($item, $this->ignors)) {
                if (!isset($this->prev[$item])) unset($this->prev[$index][$i]);
                else $this->check($this->prev[$item], $item);
                $this->ignors[] = $item;
            }
        }
    }

    private function enumerate($source, $dest)
    {
        array_unshift($this->path, $source);
        $discovered[] = $source;
        if ($source === $dest){
            $this->paths[] = $this->path;
        }
        else {
            if (!isset($this->prev[$source]) || (isset($this->prev[$source]) && !$this->prev[$source])) return;
            foreach ($this->prev[$source] as $child){
                if (!in_array($child, $discovered) && !in_array($child, $this->path)) $this->enumerate($child, $dest);
            }
        }
        array_shift($this->path);
        if (($key = array_search($source, $discovered)) !== false) unset($discovered[$key]);
    }

    public function getpaths($from, $to)
    {
        $this->paths_from($from);
        return $this->paths_to($from, $to);
    }

}


