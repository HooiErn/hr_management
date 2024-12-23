<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'company_name' => 'HRTech Inc.',
            'contact_person' => 'John Doe',
            'address' => '123 Tech Lane, Silicon Valley',
            'country' => 'Malaysia',
            'city' => 'Johor',
            'state' => 'Johor Bahru',
            'postal_code' => '84105',
            'email' => 'info@Hrtech.com',
            'phone_number' => '023-456-7890',
            'mobile_number' => '018-765-4321',
            'fax' => '123-456-7891',
            'website_url' => 'https://www.techsolutions.com',
        ]);
    }
}
