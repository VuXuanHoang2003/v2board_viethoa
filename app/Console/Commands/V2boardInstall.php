<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use App\Models\User;
use App\Utils\Helper;
use Illuminate\Support\Facades\DB;

class V2boardInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'v2board:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cài đặt v2board';

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
    public function handle()
    {
        try {
            $this->info("__     ______  ____                      _  ");
            $this->info("\ \   / /___ \| __ )  ___   __ _ _ __ __| | ");
            $this->info(" \ \ / /  __) |  _ \ / _ \ / _` | '__/ _` | ");
            $this->info("  \ V /  / __/| |_) | (_) | (_| | | | (_| | ");
            $this->info("   \_/  |_____|____/ \___/ \__,_|_|  \__,_| ");
            if (\File::exists(base_path() . '/.env')) {
                abort(500, 'V2board đã được cài đặt, nếu bạn cần cài đặt lại, vui lòng xóa tệp .lock trong thư mục');
            }

            if (!copy(base_path() . '/.env.example', base_path() . '/.env')) {
                abort(500, 'Không thể sao chép các tệp môi trường, vui lòng kiểm tra quyền của thư mục');
            }
            $this->saveToEnv([
                'APP_KEY' => 'base64:' . base64_encode(Encrypter::generateKey('AES-256-CBC')),
                'DB_HOST' => $this->ask('Vui lòng nhập địa chỉ cơ sở dữ liệu（Nhập:localhost）', 'localhost'),
                'DB_DATABASE' => $this->ask('Vui lòng nhập tên cơ sở dữ liệu'),
                'DB_USERNAME' => $this->ask('Vui lòng nhập tên người dùng cơ sở dữ liệu'),
                'DB_PASSWORD' => $this->ask('Vui lòng nhập mật khẩu cơ sở dữ liệu')
            ]);
            \Artisan::call('config:clear');
            \Artisan::call('config:cache');
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                abort(500, 'Kết nối cơ sở dữ liệu không thành công');
            }
            $file = \File::get(base_path() . '/database/install.sql');
            if (!$file) {
                abort(500, 'Tệp cơ sở dữ liệu không tồn tại');
            }
            $sql = str_replace("\n", "", $file);
            $sql = preg_split("/;/", $sql);
            if (!is_array($sql)) {
                abort(500, 'Định dạng tệp cơ sở dữ liệu không chính xác');
            }
            $this->info('Đang nhập cơ sở dữ liệu, vui lòng đợi ...');
            foreach ($sql as $item) {
                try {
                    DB::select(DB::raw($item));
                } catch (\Exception $e) {
                }
            }
            $this->info('Quá trình nhập cơ sở dữ liệu đã hoàn tất');
            $email = '';
            while (!$email) {
                $email = $this->ask('Vui lòng nhập email quản trị viên?');
            }
            $password = '';
            while (!$password) {
                $password = $this->ask('Vui lòng nhập mật khẩu quản trị viên?');
            }
            if (!$this->registerAdmin($email, $password)) {
                abort(500, 'Đăng ký tài khoản quản trị viên không thành công, vui lòng thử lại');
            }

            $this->info('Mọi thứ đã sẵn sàng');
            $this->info('Truy cập http(s)://trang web của bạn/admin để vào trang quản lý');
            \File::put(base_path() . '/.lock', time());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    private function registerAdmin($email, $password)
    {
        $user = new User();
        $user->email = $email;
        if (strlen($password) < 8) {
            abort(500, 'Độ dài tối thiểu của mật khẩu quản trị viên là 8 ký tự');
        }
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->uuid = Helper::guid(true);
        $user->token = Helper::guid();
        $user->is_admin = 1;
        return $user->save();
    }

    private function saveToEnv($data = [])
    {
        function set_env_var($key, $value)
        {
            if (! is_bool(strpos($value, ' '))) {
                $value = '"' . $value . '"';
            }
            $key = strtoupper($key);

            $envPath = app()->environmentFilePath();
            $contents = file_get_contents($envPath);

            preg_match("/^{$key}=[^\r\n]*/m", $contents, $matches);

            $oldValue = count($matches) ? $matches[0] : '';

            if ($oldValue) {
                $contents = str_replace("{$oldValue}", "{$key}={$value}", $contents);
            } else {
                $contents = $contents . "\n{$key}={$value}\n";
            }

            $file = fopen($envPath, 'w');
            fwrite($file, $contents);
            return fclose($file);
        }
        foreach($data as $key => $value) {
            set_env_var($key, $value);
        }
        return true;
    }
}
