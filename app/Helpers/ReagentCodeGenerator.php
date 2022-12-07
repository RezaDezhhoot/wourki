<?php

namespace App\Helpers;

use App\User;

class ReagentCodeGenerator
{
    /**
     * Generate a random string, using a cryptographically secure 
     * pseudorandom number generator (random_int)
     *
     * This function uses type hints now (PHP 7+ only), but it was originally
     * written for PHP 5 as well.
     * 
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     * 
     * @param int $length      How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     */
    function random_str(
        int $length = 64,
        bool $use_numbers_only = false
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $keyspace = 'abcdefghijklmnopqrstuvwxyz';
        if($use_numbers_only){
            $keyspace = '0123456789';
        }
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
    public function generate(){
        $code = $this->random_str(2) . $this->random_str(5, true);
        if(User::where('reagent_code' , $code)->exists()){
            return $this->generate();
        }
        return $code;
    }
}
