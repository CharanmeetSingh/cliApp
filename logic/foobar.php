<?php

for ($i=1; $i<=100; $i++) {
	if ($i != 100) {
		$comma = ", ";
	}else {
		$comma = "";
	}
	
	if ($i%3 == 0 && $i%5 != 0) {
		echo "foo".$comma;
	}elseif ($i%5 == 0 && $i%3 != 0){
		echo "bar".$comma;
	}elseif ($i%3 ==0 && $i%5==0) {
		echo "foobar".$comma;
	}else {
		echo $i.$comma;
	}
}
