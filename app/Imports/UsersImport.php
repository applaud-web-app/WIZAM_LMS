<?php
// App/Imports/UsersImport.php
namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $user = User::create([
            'title'        => $row['title'],
            'name'         => $row['name'],
            'email'        => $row['email'],
            'phone_number' => $row['phone_number'],
            'dob'          => $row['dob'],
            'password'     => Hash::make($row['password']),
            'status'       => $row['status'] ?? 1,
        ]);

        // Assigning a role to the user (assuming a 'User' role exists)
        $user->assignRole('User');

        return $user;
    }
}
