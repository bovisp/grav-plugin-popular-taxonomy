<?php

namespace Grav\Plugin;

use Grav\Common\GravTrait;

class PopularTaxonomy
{
    use GravTrait;

    /**
     * Get taxonomy list with only tags of the child pages.
     * 
     * @return array
     */
    public function get($taxonomy, $truncate = false, $max = 10)
    {
        $current = self::getGrav()['page'];

        $taxonomyCollection = [];

        foreach ($current->children() as $child) {
            if (array_key_exists($taxonomy, $child->taxonomy())) {
                $taxonomyCollection[] = array_values($child->taxonomy()[$taxonomy]);
            }
        }

        $taxonomyCounts = array_count_values($this->flatten($taxonomyCollection, true));

        arsort($taxonomyCounts);

        if ($truncate) {
            $taxonomyCounts = array_slice($taxonomyCounts, 0, $max);
        }

        return array_keys($taxonomyCounts);
    }

    /**
     * Flattens a multidimensional array. If you pass shallow, the array will only be flattened a single level.
     * 
     * Code: Copyright (c) 2014 Maciej A. Czyzewski
     * License: MIT
     * Repo: https://github.com/maciejczyzewski/bottomline
     *
     * __::flatten([1, 2, [3, [4]]], [flatten]);
     *      >> [1, 2, 3, 4]
     *
     * @param      $array
     * @param bool $shallow
     *
     * @return array
     *
     */
    private function flatten($array, $shallow = false)
    {
        $output = [];
        foreach ($array as $value) {
            if (is_array($value)) {
                if (!$shallow) {
                    $value = flatten($value, $shallow);
                }
                foreach ($value as $valItem) {
                    $output[] = $valItem;
                }
            } else {
                $output[] = $value;
            }
        }
        return $output;
    }
}
