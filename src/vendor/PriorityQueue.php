<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 7/27/20, 6:31 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iDijkstra\Vendor;


class PriorityQueue {

    private $size;
    public $liststart;
    private $comparator;

    function __construct($comparator) {
        $this->size = 0;
        $this->liststart = null;
        $this->listend = null;
        $this->comparator = $comparator;
    }

    function add($x) {
        $this->size = $this->size + 1;

        if($this->liststart == null) {
            $this->liststart = new PriorityList($x);
        } else {
            $node = $this->liststart;
            $comparator = $this->comparator;
            $newnode = new PriorityList($x);
            $lastnode = null;
            $added = false;
            while($node) {
                if ($this->compareWeights($newnode, $node) < 0) {
                    // newnode has higher priority
                    $newnode->next = $node;
                    if ($lastnode == null) {
                        //print "last node is null\n";
                        $this->liststart = $newnode;
                    } else {
                        $lastnode->next = $newnode;
                    }
                    $added = true;
                    break;
                }
                $lastnode = $node;
                $node = $node->next;
            }
            if (!$added) {
                $lastnode->next = $newnode;
            }
        }
    }

    function debug() {
        $node = $this->liststart;
        $i = 0;
        if (!$node) {
            print "<< No nodes >>\n";
            return;
        }
        while($node) {
            print "[$i]=" . $node->data[1] . " (" . $node->data[0] . ")\n";
            $node = $node->next;
            $i++;
        }
    }

    function size() {
        return $this->size;
    }

    function peak() {
        return $this->liststart->data;
    }

    function remove() {
        $x = $this->peak();
        $this->size = $this->size - 1;
        $this->liststart = $this->liststart->next;
        return $x;
    }

    public function compareWeights($a, $b)
    {
        return $a->data[0] - $b->data[0];
    }
}

