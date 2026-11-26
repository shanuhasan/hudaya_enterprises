<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Creates (or promotes) an admin account. There is no public registration.
 *   php artisan admin:create
 *   php artisan admin:create owner@company.com --name="Owner"
 */
class CreateAdmin extends Command
{
    protected $signature = 'admin:create {email? : Login email} {--name= : Display name} {--password= : Password (prompted when omitted)}';

    protected $description = 'Create an admin account for the Hudaya Enterprises dashboard';

    public function handle(): int
    {
        $email = $this->argument('email') ?? $this->ask('Email');
        $name = $this->option('name') ?? $this->ask('Name', 'Administrator');
        $password = $this->option('password') ?? $this->secret('Password (min. 10 characters)');

        $validator = Validator::make(
            compact('email', 'name', 'password'),
            [
                'email' => ['required', 'email', 'max:190'],
                'name' => ['required', 'string', 'max:120'],
                'password' => ['required', 'string', 'min:10'],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::query()->updateOrCreate(
            ['email' => strtolower($email)],
            ['name' => $name, 'password' => Hash::make($password)],
        );
        $user->forceFill(['is_admin' => true])->save();

        $this->info("Admin account ready: {$user->email}");

        return self::SUCCESS;
    }
}
