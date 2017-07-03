<?php

/**
 * 一致性 hash 算法
 */

class ConsistentHash
{

    protected $nodes = []; // 服务节点的节点和位置的映射关系

    protected $positions = []; // 服务节点的位置和节点映射关系

    protected $virtualNodeNumber = 64; // 虚拟节点数量，通过此参数调整服务节点的负载均衡性

    /**
     * 散列函数，将任一 string 散列到数量为 32 位的桶之一
     */
    public function hash($str)
    {
        return sprintf('%u', crc32($str));
    }

    /**
     * 寻找 key 被服务的节点
     */
    public function lookup($key)
    {
        // 得到待分配 key 对应 hash 环的位置
        $point = $this->hash($key);

        // 取出第一个座位默认服务节点
        $serviceNode = current($this->positions);

        // 遍历节点，找出离 point 位置最近的节点
        // 如果找遍整个令牌环都没有找到匹配的，取第一个位置对应的服务节点
        foreach ($this->positions as $position => $node) {
            if ($point <= $position) {
                $serviceNode = $node;
                break;
            }
        }

        // 重置数组指针
        reset($this->positions);

        return $serviceNode;
    }

    /**
     * 添加一个服务节点
     *
     * @param string $node 节点的唯一标识，如：节点 IP
     */
    public function addNode($node)
    {
        // 不能重复添加节点
        if (isset($this->nodes[$node])) return;

        // 循环添加节点对应的虚拟节点的映射关系
        for ($i = 0; $i < $this->virtualNodeNumber; $i ++) {
            $position = $this->hash($node . '-' . $i);
            $this->nodes[$node][] = $position;
            $this->positions[$position] = $node;
        }

        // 将位置映射表按正序重新排序
        ksort($this->positions);
    }

    /**
     * 下线一个服务节点
     */
    public function delNode($node)
    {
        if (!isset($this->nodes[$node])) return;

        foreach ($this->nodes[$node] as $position) {
            unset($this->positions[$position]);
        }

        unset($this->nodes[$node]);
    }
}
