<?php

class Node
{

	public $next = null;

	public $previous = null;

	private $data = '';

	private $key = '';

	public function __construct($key, $data)
	{
		$this->key = $key;
		$this->data = $data;
	}

	public function setPrevious(Node $previous)
	{
		$this->previous = $previous;
	}

	public function setNext(Node $next)
	{
		$this->next = $next;
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getKey()
	{
		return $this->key;
	}
}

class LRUCache
{

	private $head = null;
	private $tail = null;

	private $limit = 0; // 容量

	private $hashMap = [];

	public function __construct($limit)
	{
		$this->limit = $limit;
		$this->head = new Node(null, null);
		$this->tail = new Node(null, null);
		$this->head->setNext($this->tail);
		$this->tail->setPrevious($this->head);
	}

	public function get($key)
	{
		if (!isset($this->hashMap[$key])) {
			return false;
		}

		$node = $this->hashMap[$key];
		$this->detach($node);
		$this->attach($node);
	}

	public function set($key, $data)
	{
		if (!isset($this->hashMap[$key])) {
			if (count($this->hashMap) >= $this->limit) {
				$this->detach($this->tail->previous);
				unset($this->hashMap[$key]);
			}
			$node = new Node($key, $data);
			$this->attach($node);
			return true;
		}

		$node = $this->hashMap[$key];
		$node->setData($data);
		$this->detach($node);
		$this->attach($node);
	}

	public function delete($key)
	{
		if (!isset($this->hashMap[$key])) {
			return false;
		}

		$node = $this->hashMap[$key];
		$this->detach($node);
		unset($this->hashMap[$key]);
	}

	public function detach(Node $node)
	{
		$node->previous->setNext($node->next);
		$node->next->setPrevious($node->previous);
	}

	public function attach(Node $node)
	{
		$this->hashMap[$node->getKey()] = $node;
		$node->setPrevious($this->head);
		$node->setNext($this->head->next);
		$this->head->next->setPrevious($node);
		$this->head->setNext($node);
	}
}