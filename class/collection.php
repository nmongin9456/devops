<?php
class Collection implements IteratorAggregate, ArrayAccess{

	private $items;

	public function __construct(array $items) {
		$this->items = $items;
	}

	public function get($key){
		if($this->has($key)){
			return($this->items[$key]);
		}else{	
			return false;
		}
	}

	public function set($key, $value){		
		
		$this->items[$key] = $value;
		
	}

	public function has($key){
		return array_key_exists($key, $this->items);
	}
	
	public function offsetExists($offset){
		return $this->has($offset);
	}

	public function offsetGet($offset){
		return $this->get($offset);
	}

	public function offsetSet($offset, $value){
		return $this->set($offset, $value);
	}

	public function offsetUnset($offset){
		if ($this->has($offset)){
			unset($this->items[$offset]);
		}
		
	}

	public function getIterator(){
		return new ArrayIterator($this->items);
	}

	public function lists($key, $value){
		$results = [];
		foreach($this->items as $item){
			$results[$item[$key]] = $item[$value]; 
		}
		return new Collection($results);
	}

	public function extract($key){
		$results = [];
		foreach($this->items as $item){
			$results[] = $item[$key];
		}
		return new Collection($results);
	}

	public function join($glue){
		return implode($glue, $this->items);
	}

	public function max($key = false){
		if($key){
			return $this->extract($key)->max();
		}else{
			return max($this->items);
		}
	}

	public function min(){
		if($key){
			return $this->extract($key)->min();
		}else{
			return min($this->items);
		}
	}


	public function first(){
		return $this->items[0];
	}

	public function last(){
		return $this->items[$this->count()-1];
	}

	public function count(){
		return count($this->items);
	}
}