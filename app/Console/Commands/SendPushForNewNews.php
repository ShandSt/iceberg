<?php

namespace App\Console\Commands;

use App\Models\News;
use App\User;
use App\Service\Push\Contracts\PushMessageContract;
use App\Jobs\SendPushToUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPushForNewNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:send:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $message;

    public function __construct(PushMessageContract $message)
    {
        parent::__construct();
        $this->message = $message;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $news = News::where('sended', null)->get();

        if($news) {
            foreach ($news as $news) {
                $payload = [
                    'aps' => [
                        'alert' => [
                            'title' => "Новость",
                            'body' => $news->title,
                        ],
                        'type' => 'newsNotification',
                        'sound' => 'default',
                    ],
                ];
                $this->message->setMessage("Новость " . $news->title);
                $this->message->setPayload($payload);
                $news->sended = 1;
                $news->save();
                foreach (User::where('status', User::STATUS_ACTIVE)->get() as $user) {
                    SendPushToUser::dispatch($user, $this->message);
                }
            }
        }else{
        }
    }
}
