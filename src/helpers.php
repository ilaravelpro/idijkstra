<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 7/26/20, 6:20 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

function idijkstra_path($path = null)
{
    $path = trim($path, '/');
    return __DIR__ . ($path ? "/$path" : '');
}
