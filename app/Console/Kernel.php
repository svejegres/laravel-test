<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      // run cron job every 4 hours due to the currencylayer API free requests montly limit:
      $schedule->call(function () {
        $apiKey = getenv('CURRENCYLAYER_API_KEY');
        $apiEndpoint = 'http://www.apilayer.net/api/live?access_key=' . $apiKey . '&format=1';

        // get currency rates JSON from external API:
        $curlCall = curl_init($apiEndpoint);
        curl_setopt($curlCall, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curlCall);
        curl_close($curlCall);
        $rates = json_decode($json, true);

        // store obtained quote rates into database:
        foreach ($rates['quotes'] as $quote => $rate) {
          $checkQuote = DB::select('select quote from currency_rates where quote = ?', [$quote]);
          if ($checkQuote) {
            DB::update('update currency_rates set rate = ? where quote = ?', [$rate, $quote]);
          } else {
            DB::insert('insert into currency_rates (quote, rate) values (?, ?)', [$quote, $rate]);
          }
        }
      })->cron('0 */4 * * *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
