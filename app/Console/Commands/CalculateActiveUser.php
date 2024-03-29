<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
class CalculateActiveUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    //供我们调用的命令
    protected $signature = 'larabbs:calculate-active-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成活跃用户';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    //最终执行的方法
    public function handle(User $user)
    {
        //
        $this->info('开始计算');
        $user->calculateAndCacheActiveUsers();

        $this->info('生成成功');

    }
}
