<?php

/*
 * By adding type hints and enabling strict type checking, code can become
 * easier to read, self-documenting and reduce the number of potential bugs.
 * By default, type declarations are non-strict, which means they will attempt
 * to change the original type to match the type specified by the
 * type-declaration.
 *
 * In other words, if you pass a string to a function requiring a float,
 * it will attempt to convert the string value to a float.
 *
 * To enable strict mode, a single declare directive must be placed at the top
 * of the file.
 * This means that the strictness of typing is configured on a per-file basis.
 * This directive not only affects the type declarations of parameters, but also
 * a function's return type.
 *
 * For more info review the Concept on strict type checking in the PHP track
 * <link>.
 *
 * To disable strict typing, comment out the directive below.
 */

declare(strict_types=1);

class Tournament
{
    

    public function tally(string $scores): string {
        $stats = [];

        
        $lines = array_filter(explode("\n", $scores));
        foreach ($lines as $line) {
            [$team1, $team2, $result] = explode(';', $line);

            // Initialize team stats if not present
            foreach ([$team1, $team2] as $team) {
                if (!isset($stats[$team])) {
                    $stats[$team] = ['MP' => 0, 'W' => 0, 'D' => 0, 'L' => 0, 'P' => 0];
                }
                $stats[$team]['MP']++;
            }

            // Apply match result
            switch ($result) {
                case 'win':
                    $stats[$team1]['W']++;
                    $stats[$team1]['P'] += 3;
                    $stats[$team2]['L']++;
                    break;
                case 'loss':
                    $stats[$team1]['L']++;
                    $stats[$team2]['W']++;
                    $stats[$team2]['P'] += 3;
                    break;
                case 'draw':
                    $stats[$team1]['D']++;
                    $stats[$team2]['D']++;
                    $stats[$team1]['P'] += 1;
                    $stats[$team2]['P'] += 1;
                    break;
            }
        }

        
        uksort($stats, function ($a, $b) use ($stats) {
            return $stats[$b]['P'] <=> $stats[$a]['P'] ?: strcmp($a, $b);
        });

        
        $lines = ["Team                           | MP |  W |  D |  L |  P"];
        foreach ($stats as $team => $s) {
            $lines[] = sprintf(
                "%-30s | %2d | %2d | %2d | %2d | %2d",
                $team, $s['MP'], $s['W'], $s['D'], $s['L'], $s['P']
            );
        }

        return implode("\n", $lines);
    }

}
