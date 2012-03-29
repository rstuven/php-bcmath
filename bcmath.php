<?php
if( !function_exists( "bcdiv" ) )
{
    function truncate1 ($num, $digits = 0) {
        $shift = pow(10 , $digits);
        return ((floor($num * $shift)) / $shift);
    }

    function bcdiv($_ro, $_lo, $_scale=0)
    {
        return truncate1($_ro / $_lo, $_scale);
    }

    function bcadd($_ro, $_lo, $_scale=0)
    {
        return truncate1($_ro + $_lo, $_scale);
    }

    function bcmul($_ro, $_lo, $_scale=0)
    {
        return truncate1($_ro * $_lo, $_scale);
    }

    /**
     * my_bcmod - get modulus (substitute for bcmod)
     * string my_bcmod ( string left_operand, int modulus )
     * left_operand can be really big, but be carefull with modulus :(
     * by Andrius Baranauskas and Laurynas Butkus :) Vilnius, Lithuania
     **/
    function bcmod( $x, $y )
    {
        $oldx = $x;
        // how many numbers to take at once? carefull not to exceed (int)
        $take = 5;
        $mod = '';

        do
        {
            $a = (int)$mod.substr( $x, 0, $take );
            $x = substr( $x, $take );
            $mod = $a % $y;
        }
        while ( strlen($x) );

        return (int)$mod;
    }

    function bccomp($Num1,$Num2,$Scale=null) {
        // check if they're valid positive numbers, extract the whole numbers and decimals
        if(!preg_match("/^\+?(\d+)(\.\d+)?$/",$Num1,$Tmp1)||
            !preg_match("/^\+?(\d+)(\.\d+)?$/",$Num2,$Tmp2)) return('0');

        // remove leading zeroes from whole numbers
        $Num1=ltrim($Tmp1[1],'0');
        $Num2=ltrim($Tmp2[1],'0');

        // first, we can just check the lengths of the numbers, this can help save processing time
        // if $Num1 is longer than $Num2, return 1.. vice versa with the next step.
        if(strlen($Num1)>strlen($Num2)) return(1);
        else {
            if(strlen($Num1)<strlen($Num2)) return(-1);

            // if the two numbers are of equal length, we check digit-by-digit
            else {

                // remove ending zeroes from decimals and remove point
                $Dec1=isset($Tmp1[2])?rtrim(substr($Tmp1[2],1),'0'):'';
                $Dec2=isset($Tmp2[2])?rtrim(substr($Tmp2[2],1),'0'):'';

                // if the user defined $Scale, then make sure we use that only
                if($Scale!=null) {
                    $Dec1=substr($Dec1,0,$Scale);
                    $Dec2=substr($Dec2,0,$Scale);
                }

                // calculate the longest length of decimals
                $DLen=max(strlen($Dec1),strlen($Dec2));

                // append the padded decimals onto the end of the whole numbers
                $Num1.=str_pad($Dec1,$DLen,'0');
                $Num2.=str_pad($Dec2,$DLen,'0');

                // check digit-by-digit, if they have a difference, return 1 or -1 (greater/lower than)
                for($i=0;$i<strlen($Num1);$i++) {
                    if((int)$Num1{$i}>(int)$Num2{$i}) return(1);
                    else
                        if((int)$Num1{$i}<(int)$Num2{$i}) return(-1);
                }

                // if the two numbers have no difference (they're the same).. return 0
                return(0);
            }
        }
    }
}

