<?php

/**
 * 最小堆排序，和最大堆大体相似，唯一不同点为最大堆是从小到大排序，最小堆是从大到小排序
 */
class MinHeapSort
{

    public function sort($arr)
    {
        $length = count($arr);
        $this->buildHeap($arr);

        $this->swap($arr, 0, $length - 1);

        $i = 2;
        while ($length - $i > 0) {
            $this->adjust($arr, 0, $length - $i);
            $this->swap($arr, 0, $length - $i);
            $i ++;
        }

        return $arr;
    }

    public function buildHeap(&$arr)
    {
        $length = count($arr);
        $parentIndex = $length / 2 - 1;

        for ($i = $parentIndex; $i >= 0; $i --) {
            $this->adjust($arr, $i, $length - 1);
        }
    }

    public function adjust(&$arr, $index, $maxIndex)
    {
        while (true) {
            $indexMin = $index;
            $indexLeft = $index * 2 + 1;
            $indexRight = $index * 2 + 2;

            if ($indexLeft < $maxIndex && $arr[$indexMin] > $arr[$indexLeft]) {
                $indexMin = $indexLeft;
            }

            if ($indexRight < $maxIndex && $arr[$indexMin] > $arr[$indexRight]) {
                $indexMin = $indexRight;
            }

            if ($indexMin != $index) {
                $this->swap($arr, $indexMin, $index);
                $index = $indexMin;
            } else {
                break;
            }
        }
    }

    public function swap(&$arr, $i, $j)
    {
        $temp = $arr[$i];
        $arr[$i] = $arr[$j];
        $arr[$j] = $temp;
    }
}


$beforeSort = [3, 9, 16, 5, 7, 2, 1, 46, 34, 67, 28, 77, 1, 5, 99, 0, 37];

$minHeapSort = new MinHeapSort();
$sorted = $minHeapSort->sort($beforeSort);

print_r(json_encode($sorted));
