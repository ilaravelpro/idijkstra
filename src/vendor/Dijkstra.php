<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 7/26/20, 10:33 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iDijkstra\Vendor;

class Dijkstra
{
    public $path = [];
    public $paths = [];
    public $prev = [];
    public $ignors = [];

    public function __construct($start, $end, $routes = [])
    {
        $prev = [];
        $prev[$start] = [];
        foreach ($routes as $points)
            foreach ($points as $index => $point){
                $next = isset($points[$index + 1]) ? $points[$index + 1] : null;
                $before = isset($points[$index - 1]) ? $points[$index - 1] : null;
                if (!isset($prev[$point])) $prev[$point] = [];
                if (!isset($prev[$next])) $prev[$next] = [];
                if ($next && !in_array($next, $prev[$point])){
                    $prev[$point][] = $next;
                    $prev[$next][] = $point;
                }
                if ($before && !in_array($point, $prev[$before])){
                    $prev[$before][] = $point;
                    $prev[$point][] = $before;
                }
                if ($next && in_array($next,[$end , $start]) && !in_array($point, $prev[$next])){
                    $prev[$next][] = $point;
                }
            }
        $this->prev = $prev;
        foreach ($prev as $index => $items) {
            if (count($items) > 0) $this->check($items, $index);
            else unset($this->prev[$index]);
        }
    }


    public function check($items, $index) {
        $this->ignors[] = $index;
        foreach ($items as $i => $item) {
            if (!in_array($item, $this->ignors)) {
                if (!isset($this->prev[$item])) unset($this->prev[$index][$i]);
                else $this->check($this->prev[$item], $item);
                $this->ignors[] = $item;
            }
        }
    }
    /**
     * Returns all shortest paths to $dest from the origin vertex $start in the graph.
     *
     * @param string $dest ID of the destination vertex
     *
     * @return array An array containing the shortest path and distance
     */
    public function get($start, $dest)
    {
        $this->paths = [];
        $this->enumerate($dest, $start);

        return array(
            'paths' => $this->paths,
            'dist' => $dest,
        );
    }

    /**
     * Enumerates the result of the multi-path Dijkstra as paths.
     *
     * @param string $source ID of the source vertex
     * @param string $dest ID of the destination vertex
     */
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
}
