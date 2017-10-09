<?php

/**
 * 堆排序
 * 
 * 利用二叉堆的两条特性：
 * 1.堆中任意节点的值总是不大于(不小于)其子节点的值；
 * 2.堆总是一棵完全树；
 * 
 * 由此得出堆元素之间的关系如下：
 * 1.索引为i的父结点的索引是 floor((i-1)/2);
 * 2.索引为i的左孩子的索引是 (2*i+1);
 * 3.索引为i的右孩子的索引是 (2*i+2);
 *
 * 排序的过程：将数组调整为堆结构，交换调整后的数组第一个和最后一个元素，调整剩下的[1..n-1]元素为堆结构，交换第一个和最后一个元素，如此反复，直至完成所有的交换
 */
class MaxHeapSort
{
    public function sort($arr)
    {
        $length = count($arr);

        // 初次构建最大堆结构
        $this->buildHeap($arr);

        // 交换数组首尾元素
        $this->swap($arr, 0, $length - 1);

        // 由于此前调整过整个堆结构，大体有序，现在只需调整第一个元素的位置，使之重新恢复堆结构，重复调整剩余堆结构并交换首尾
        $i = 2;
        while ($length - $i > 0) {
            $this->adjust($arr, 0, $length - $i);
            $this->swap($arr, 0, $length - $i);
            $i ++;
        }

        return $arr;
    }

    /**
     * 首次构建最大堆
     */
    public function buildHeap(&$arr)
    {
        // 按照堆的特性，父节点位于数组的左半部分
        $length = count($arr);
        $parentIndex = $length / 2 - 1;
        for ($i = $parentIndex; $i >= 0; $i --) {
            $this->adjust($arr, $i, $length - 1);
        }
    }

    /**
     * 调整顺序，使之符合大堆的特性
     *
     * @param array $arr 待调整的数组
     * @param int $index 待调整的数组结构的起始索引位置
     * @param int $maxIndex 待调整的最大索引位置（超出的忽略）
     */
    public function adjust(&$arr, $index, $maxIndex)
    {

        while (true) {
            
            $indexMax = $index;
            $indexLeft = $index * 2 + 1;
            $indexRight = $index * 2 + 2;

            if ($indexLeft < $maxIndex && $arr[$indexMax] < $arr[$indexLeft]) {
                $indexMax = $indexLeft;
            }

            if ($indexRight < $maxIndex && $arr[$indexMax] < $arr[$indexRight]) {
                $indexMax = $indexRight;
            }

            // 交换 indexMax 和 index 的位置
            if ($index != $indexMax) {
                $this->swap($arr, $index, $indexMax);
                $index = $indexMax;
            } else {
                break;
            }
        }
    }

    public function swap(&$arr, $i, $j)
    {
        if ($i != $j) {
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }
    }
}

$beforeSort = [3, 9, 16, 5, 7, 2, 1, 46, 34, 67, 28, 77, 1, 5, 99, 0, 37];

$maxHeapSort = new MaxHeapSort();
$sorted = $maxHeapSort->sort($beforeSort);

print_r(json_encode($sorted));
