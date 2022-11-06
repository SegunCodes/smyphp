<?php 
namespace SmyPhp\Core\Cli;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core
*/ 
final class Cli
{
    public function __construct()
    {
        $options = getopt(null,['start', 'port:', 'help']);

        if (isset($options['start']) && isset($options['port'])) {
            if (strlen($options['port']) < 4) {
                die("Invalid port length");
                exit;
            } elseif (strlen($options['port']) == 4) {
                echo "Your SmyPhp App is running. Listening on http://localhost:".$options['port']." \n";
                echo "\n";			
                passthru("php -S localhost:".$options['port']." -t routes");
            }
        }elseif (isset($options['start']) && !isset($options['port'])) {
            echo "Your SmyPhp App is running. Listening on http://localhost:8000 \n";
            echo "\n";			
            passthru("php -S localhost:8000 -t routes");
        }


        if (isset($options['help'])) {
            echo "Usage:  php smyphp [command] \n\n";

            echo " \n";

            echo "--start\tDisplays your application on the browser using the default port 8000 \n\n";

            echo "--start --port<number>\tDisplays your application on your preferred port\n";
        }

    }

}

