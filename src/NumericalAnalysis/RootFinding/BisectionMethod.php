<?php
namespace Math\NumericalAnalysis\RootFinding;

use Math\Functions\Special;

/**
 * Bisection's Method (also known as the Binary-search method)
 *
 * In numerical analysis, the Bisection method is a method for finding successively
 * better approximations to the roots (or zeroes) of a continuous, real-valued
 * function f(x). It starts with two points $a and $b, such that $a < $b and
 * f($a) and f($b) have different signs (one is positive, one is negative). This
 * lets us use the intermediate value theorem to prove that there is a root $p
 * such that $a < $p < $b. We initially set $p to be the average of $a and $b
 * and analyze the result of f($p). Based on the sign, we construct a new $p
 * that is either the average of $a and the original $p, or the average of the
 * original $p and $b. We continue doing this until our function evaluation
 * f($p) is within the tolerance set on our input.
 */
class BisectionMethod
{
    /**
     * Use the Bisection Method to find the x which produces $function(x) = 0.
     *
     * @param Callable $function f(x) callback function
     * @param number   $a        The start of the interval which contains a root
     * @param number   $b        The end of the interval which contains a root
     * @param number   $tol      Tolerance; How close to the actual solution we would like.

     * @return number
     */
    public static function solve(callable $function, $a, $b, $tol)
    {
        // Validate input arguments
        self::validate($function, $a, $b, $tol);

        // Initialize
        $dif = $tol + 1;

        while ($dif > $tol) {
            $f⟮a⟯ = call_user_func_array($function, [$a]);
            $p   = ($a + $b)/2; // construct the midpoint
            $f⟮p⟯ = call_user_func_array($function, [$p]);
            $dif = abs($f⟮p⟯);   // the magnitude of our function at the midpoint
            if (Special::sgn($f⟮p⟯) !== Special::sgn($f⟮a⟯)) {
                $b = $p; // the new endpoint is our original midpoint
            } else {
                $a = $p; // the new startpoint is our original endpoint
            }
        }

        return $p;
    }

    /**
     * Verify the input arguments are valid for correct use of the bisection
     * method. If the tolerance is less than zero, an Exception will be thrown.
     * If f($a) and f($b) have the same sign, we cannot use the intermediate
     * value theorem to guarantee a root is between $a and $b. This exposes the
     * risk of an endless loop, so we throw an Exception. If $a = $b, then clearly
     * we cannot run our loop as $a and $b will themselves be the midpoint, so we
     * throw an Exception.Finally, if $a > $b, we simply reserve them as if the
     * user input $b = $a and $a = $b so the new $a < $b.
     *
     * @param Callable $function f(x) callback function
     * @param number   $a        The start of the interval which contains a root
     * @param number   $b        The end of the interval which contains a root
     * @param number   $tol      Tolerance; How close to the actual solution we would like.
     *
     * @return bool
     * @throws Exception if $tol (the tolerance) is negative
     * @throws Exception if f($a) and f($b) share the same sign
     * @throws Exception if $a = $b
     */
    private static function validate(callable $function, $a, $b, $tol)
    {
        if ($tol < 0) {
            throw new \Exception('Tolerance must be greater than zero.');
        }

        $f⟮a⟯ = call_user_func_array($function, [$a]);
        $f⟮b⟯ = call_user_func_array($function, [$b]);
        if (Special::sgn($f⟮a⟯) === Special::sgn($f⟮b⟯)) {
            throw new \Exception('Input function has the same sign at the
                                  start and end of the interval. Choose start
                                  and end points such that the function
                                  evaluated at those points has a different
                                  sign (one positive, one negative).');
        }

        if (!($a > $b)) {
            if ($a === $b) {
                throw new \Exception('Start point and end point of interval
                                        cannot be the same.');
            } else {
                $temp_a = $b;
                $b = $a;
                $a = $temp_a;
            }
        }

        return true;
    }
}
