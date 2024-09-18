<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\GroupUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name' => $row['full_name'],
            'dob' => $row['dob'],
            'country' => $row['nationality'],
            'phone_number' => $row['phone'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'status' => $row['status'],
        ]);
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|min:3',
            'dob' => 'required|date',
            'nationality' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'groups' => 'required|array',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|string|in:1,0',
        ];
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }
}
