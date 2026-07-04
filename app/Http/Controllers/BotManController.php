<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Web\WebDriver;
use App\Conversations\ExampleConversation;
use Illuminate\Http\Request;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle(Request $request)
    {
        $botman = app('botman');
        // routes/botman.php registers commands during boot, so refresh the driver for this request.
        $botman->setDriver(DriverManager::loadFromName(WebDriver::class, config('botman', []), $request));

        $bufferLevel = ob_get_level();
        ob_start();

        try {
            $botman->listen();
        } catch (\Throwable $exception) {
            while (ob_get_level() > $bufferLevel) {
                ob_end_clean();
            }

            throw $exception;
        }

        $payload = ob_get_clean();

        if ($payload === '') {
            return response()->json([
                'status' => 200,
                'messages' => [],
            ]);
        }

        $decodedPayload = json_decode($payload, true);
        $status = (int) ($decodedPayload['status'] ?? 200);

        return response($payload, $status)->header('Content-Type', 'application/json');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
