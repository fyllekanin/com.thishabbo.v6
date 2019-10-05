<?php

return array(

    // set your paypal credential


    'client_id' => 'AbqW_Z73zTKh4ABcSB3tvfn544vYNh3a0DuJUNauSr7UpbgSxjkzEHUYjb8I28j_f3wT96a4RSY648ju',

    'secret' => 'EN4QJ1CQy6v1JA2V9bCFm61KQ8ZoNU8mrm730dYwGbuTa3h8V29MS7Zt-3uHtanO2KUyrPBoAi1aWCel',




    /**

     * SDK configuration 

     */

    'settings' => array(

        /**

         * Available option 'sandbox' or 'live'

         */

        'mode' => 'live',



        /**

         * Specify the max request time in seconds

         */

        'http.ConnectionTimeOut' => 30,



        /**

         * Whether want to log to a file

         */

        'log.LogEnabled' => true,



        /**

         * Specify the file that want to write on

         */

        'log.FileName' => storage_path() . '/logs/paypal.log',



        /**

         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'

         *

         * Logging is most verbose in the 'FINE' level and decreases as you

         * proceed towards ERROR

         */

        'log.LogLevel' => 'FINE'

    ),

);