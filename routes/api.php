<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HolidayController;
use App\Models\Holiday;

Route::get('/insert_holiday/{year}', [HolidayController::class, 'extractPublicHolidaysByYear']);

Route::get('/test-insert', function() {
    
    $testData = [
        'day' => 'Rabu', 
        'date' => '2023-12-04',
        'date_formatted' => '04 December 2024',
        'month' => 'December',
        'name' => 'Birthday',
        'description' => 'Birth',
        'is_holiday' => '1',
        'type' => 'Regional Holiday',
        'type_id' => '4',
                                        
    ];

    try {
        
        Holiday::insert($testData);
        return "Data inserted successfully!";
    } catch (\Exception $e) {
        return "Failed to insert: " . $e->getMessage();
    }
});

//$region_array = [
//  'Johor',
//  'Kedah',
//  'Kelantan',
//  'Kuala Lumpur',
//  'Labuan',
//  'Melaka',
//  'Negeri Sembilan',
//  'Pahang',
//  'Penang',
//  'Perak',
//  'Perlis',
//  'Putrajaya',
//  'Sarawak',
//  'Selangor',
//  'Terengganu' ];

//private $related_region = [
//  'Johore' => 'Johor',
//  'KL' => 'Kuala Lumpur',
//  'Malacca' => 'Melaka',
//  'Pulau Pinang' => 'Penang' ];


//example on how to do data insertion 
//_/api/holidays/region/year/2023?regions[]=Selangor&regions[]=Perak&regions[]=Johor&regions[]=Sarawak&regions[]=KL

Route::get('/holidays/region/year/{year}', [HolidayController::class, 'extractPublicHolidaysByRegionAndYear']);