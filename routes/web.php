<?php

Route::group(['namespace' => 'Pyaesone17\QueueMonitor\App\Http\Controllers', 'as' => 'queue-monitor::', 'middleware' => 'web'],function ()
{
    Route::get('queue-monitor/{type}/manage','QueueMonitorController@manage')->name('getManage');
    Route::resource('queue-monitor','QueueMonitorController');
});
