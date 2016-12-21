<?php

namespace App\Console\Commands;

use SleepingOwl\Admin\Commands\UserManagerCommand;

class AdminManagerCommand extends UserManagerCommand
{
    protected $name = 'sleepingowl:user';
    
    protected $description = 'Manage your administrators.';
    
    protected function getUsers()
    {
        $userClass = $this->getUserClass();
        
        $headers = ['id', 'name', 'email'];
        $users = $userClass::whereRole($userClass::ROLE_ADMIN)->get($headers);
        
        $this->table($headers, $users);
    }
    
    protected function createNewUser()
    {
        $userClass = $this->getUserClass();
        
        if (is_null($email = $this->ask('Email'))) {
            $this->error('You should specify email.');
            
            return;
        }
        
        if (! is_null($userClass::where('email', $email)->first())) {
            $this->error("User with same email [{$email}] exists.");
            
            return;
        }
        
        if (is_null($password = $this->secret('Password'))) {
            $this->error('You should specify password.');
            
            return;
        }
        
        $passwordConfirm = $this->secret('Password Confirm');
        
        if ($password !== $passwordConfirm) {
            $this->error('Password confirm failed.');
            
            return;
        }
        
        $name = $this->ask('User Name');
        
        try {
            $user = $userClass::create([
                'email'    => $email,
                'password' => $password,
                'name'     => $name,
                'role'     => $userClass::ROLE_ADMIN
            ]);
            
            $this->info("User [{$user->id}] created.");
        } catch (\Exception $e) {
            $this->error('Something went wrong. User not created');
            
            return;
        }
    }
}
