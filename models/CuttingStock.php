<?php

namespace app\models;

class CuttingStock {
	private $runLimit = 10000;
	
	private $block;
	private $qty;
	private $comb;
	private $tempcomb;
	private $limit;
	private $mapList;

	private $max;
	private $total;
	private $counter = 0;
	private $waste = 0;
	private $store;
	private $count = 0;
	
	public function __construct($max,$block,$quantity) {    
	    for($i = 0; $i < count($block); $i++) {
	    	if($block[$i] > $max) {
	    		echo 'error bits too long';
				exit(1);
	    	}
	    }
	    if(count($block) != count($quantity)) {
	    	echo 'not same params';
			exit(1);
	    }

	    $this->max = $max;
	    $this->block = $block;
		$this->qty = $quantity;
		$this->total = count($block);

		$this->doIt();
	}


	public function hasMoreCombinations() {
		return ($this->count < $this->counter);
	}


	public function nextCombination() {
		$map = $this->mapList[$this->count];
		$this->count++;
		return $map;
	}
	

	private function doIt() {
		$this->initialize();
	      /*for(int i = 0;i < stock.size();i++)
			{
	    	  for(int j = 0;j < stock.get(i).comb.length;j++)
	    	  {
	    		if(stock.get(i).comb[j] > 0)
				System.out.println(block[j]+"  *  "+$this->stock.get(i).comb[j]);
	    	  }
			}*/
	}


	private function initialize() {
	    $this->store = [];
	    $this->mapList = [];
	    $this->waste = 0;
	    $this->counter = 0;
	    $this->sort();
	    $this->calculate($this->store);
	    /*wast_array = store.toArray();
	    if(wast_array.length > 0)
	    {
	      System.out.println("Consider reusing the following remains");    
	      for(int i = wast_array.length-1;i> = 0;i--)
	      {
	        System.out.println(($this->counter+i-wast_array.length+1)+" "+wast_array[$i]);
	      }
	      //out.println(" < /table> < br> < br > ");
	    }
	    System.out.println("No of pieces req = "+$this->counter);    
	    System.out.println("Waste = "+$this->waste);*/
	}


	private function sort() {
		$tmp = null;
		$swap = null;
		do {
			$swap = false;
			for($j = 0;$j < $this->total-1;$j++) {
				if($this->block[$j+1] > $this->block[$j]) {
					$tmp = $this->block[$j];
					$this->block[$j] = $this->block[$j+1];
					$this->block[$j+1] = $tmp;

					$tmp = $this->qty[$j];
					$this->qty[$j] = $this->qty[$j+1];
					$this->qty[$j+1] = $tmp;
					$swap = true;
				}
			}
		} while ($swap);
	}
	
	
	private function initLimit() {
		$this->limit = array_fill(0, $this->total, 0);
		for($i = 0; $i < $this->total; $i++) {
			$div = $this->max / $this->block[$i];
			$this->limit[$i] = ($this->qty[$i] > $div) ? $div : $this->qty[$i];
		}
	}


	private function calculate($store) {
		$this->initLimit();
		$start = true;
		$chaloo = true;
		$best = 0;
		$sum = 0;
		$this->comb = array_fill(0, $this->total, 0);

		$runCounter = 0;
		
		while($start) {


			$start = ($runCounter++ < $this->runLimit);

			
			////out.println("At start again");                           // DELETE IT
			$this->combinations();

//			for($i = 0; $i < $this->total; $i++)                         //CHECK.......
//				echo $this->block[$i].' '. $this->comb[$i].', ';

			$sum = 0;
			for($i = 0; $i < $this->total; $i++) {
				$sum += $this->block[$i] * $this->comb[$i];
				if($sum > $this->max) {
					$sum = 0;
					break;
				}
			}
			
			//if($sum > 0) echo 'sum='.$sum.', ';

			if($sum > 0) {//if a comb suited
				if($sum == $this->max) { // if best comb found
					//echo 'sum='.$sum.', ';                      //  DELETE IT
					$this->showComb(0,$store);  //print comb
					$this->resetComb();
					$this->updateLimit();
					$best = 0;
					$sum = 0;
				} else if($sum > $best) {
					$best = $sum;
					$this->tempcomb = array_fill(0, $this->total, 0);
					for($i = 0; $i < $this->total; $i++) // storing best comb in tempComb[]
						$this->tempcomb[$i] = $this->comb[$i];
					$sum = 0;
				}
			}
			for($i = 0; $i < $this->total; $i++) {  // to check whether all comb done
				if($this->comb[$i] != $this->limit[$i]) {
					$chaloo = true;
					break;
				}
				$chaloo = false;
			}
			if(!$chaloo) {// when all comb completed
				//for($i = 0;$i < $this->total;$i++) // storing best comb in tempComb[] ...Testing
				//    //out.print(tempcomb[$i]);
				//for($i = 0;$i < $this->total;$i++) // storing best comb in tempComb[] ...Testing
				//    //out.print(comb[$i]);
				$this->showComb($best,$store);
				updateLimit();
				resetComb();
				$best = 0;
			}////out.println("B4 start loop");                            // DELETE IT
			for($i = 0; $i < $this->total; $i++) { // To end while loop when no more pieces left
				if($this->qty[$i] == 0 && $i != $this->total-1)
					continue;
				else if($i == $this->total-1 && $this->qty[$i] == 0)
					$start = false;
				break;
			}/*//out.println("After start loop");                            // DELETE IT
				for($i = 0;$i < $this->total;$i++)                                         ////////
				//out.print(qty[$i]);                                 /////////
				//out.println(); */                                            //////////
		} // while
	}
	
	
	private function showComb($a, $store) {
		$this->counter++;

		$flag = false;
		//out.println(" < font color = \"brown\"> == ==  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  </font> < br > Piece no "+counter+" < br > ---------- < br > ");
		if($a == 0) {
			$tempMap = [];
			for($i = 0;$i < $this->total;$i++)
				if($this->comb[$i] != 0) {
					$tempMap[$this->block[$i]] = $this->comb[$i];
					//System.out.println(block[$i]+"  *  "+comb[$i]);
					$this->qty[$i] -= $this->comb[$i]; //  deduct samples from stock(qty) which are already printed
					if(($this->qty[$i] - $this->comb[$i]) < 0) {
						$flag = true; // to return and not recursively call.
					}
				}

			if($flag) {
				$this->mapList[] = $tempMap;
				return;
			}
			$this->showComb(0,$store);
		} else {
			$tempMap = [];
			for($i = 0; $i < $this->total; $i++)
				if($this->tempcomb[$i] != 0) {
					$tempMap[$this->block[$i]] = $this->tempcomb[$i];
					//System.out.println(block[$i]+"  ggg  "+tempcomb[$i]);
				}

			$this->mapList[] = $tempMap; 
			//out.println("----------");
			echo "\nThis piece remains = ".($max-$a);
			;
			//System.out.println("\nThis piece remains = "+(max-a));
			$waste += $this->max - $a;
			array_push($store, $this->max - $a);
			for($i = 0; $i < $this->total; $i++)
				$this->qty[$i] = $this->qty[$i] - $this->tempcomb[$i];

			for($i = 0; $i < $this->total; $i++) {
				if(($this->qty[$i] - $this->comb[$i]) < 0) {
					return;			
				}
			}
			showComb($a,$store);
		}
	}
	
	
	private function combinations() {
		for($i = $this->total-1;;) {
			if($this->comb[$i] != $this->limit[$i]) {
				$this->comb[$i]++;
				break;
			} else {
				if($i == 0 && $this->comb[0] != $this->limit[0])
					$i = $this->total-1;
				else {
					$this->comb[$i] = 0;
					$i--;
				}
			}
		}
	}


	private function updateLimit() {
		for($i = 0; $i < $this->total; $i++) {
			if($this->qty[$i] < $this->limit[$i])
				$this->limit[$i] = $this->qty[$i];
		}
	}


	private function resetComb() {
		for($i = 0; $i < $this->total; $i++) // reset comb[]
			$this->comb[$i] = 0;
	}

}