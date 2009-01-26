<?php

class Utils {

	//code is from http://snipplr.com/view/5813/days-ago-function/
	//This code takes the date, and then determines the difference in time between the passed in date and now
	public static function getHowLongAgo($date, $display = array('Year', 'Month', 'Day', 'Hour', 'Minute', 'Second'), $ago = 'Ago')
	{
    		$date = getdate(strtotime($date));
    		$current = getdate();
    		$p = array('year', 'mon', 'mday', 'hours', 'minutes', 'seconds');
    		$factor = array(0, 12, 30, 24, 60, 60);

    		for ($i = 0; $i < 6; $i++) {
        		if ($i > 0) {
            			$current[$p[$i]] += $current[$p[$i - 1]] * $factor[$i];
            			$date[$p[$i]] += $date[$p[$i - 1]] * $factor[$i];
        		}
        
			if ($current[$p[$i]] - $date[$p[$i]] > 1) {
        	    		$value = $current[$p[$i]] - $date[$p[$i]];
            			return $value . ' ' . $display[$i] . (($value != 1) ? 's' : '') . ' ' . $ago;
        		}
   		}
    
		return '';
	}
}
