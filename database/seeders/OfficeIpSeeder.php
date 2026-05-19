<?php

namespace Database\Seeders;

use App\Models\OfficeIp;
use Illuminate\Database\Seeder;

class OfficeIpSeeder extends Seeder
{
    public function run(): void
    {
        $ips = [
            '103.135.95.106',
            '210.212.226.114',
            '127.0.0.1',
        ];

        foreach ($ips as $ip) {
            OfficeIp::create(['ip_address' => $ip]);
        }
    }
}
