<?php

namespace App\Http\Controllers;

use App\Models\Holiday; 
use afiqiqmal\MalaysiaHoliday\MalaysiaHoliday;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function extractPublicHolidaysByYear($year)
{
    try {
        $malaysiaHoliday = new MalaysiaHoliday();

        
        $holidays = $malaysiaHoliday->fromAllState()->ofYear($year)->get();
        //echo "<pre>";
        //print_r($holidays["data"]["collection"][0]["data"]);
        //exit();
        
        Log::info('API Response:', ['holidays' => $holidays]);

        $publicHolidays = [];
        
        if (isset($holidays["data"]["collection"]) && !empty($holidays["data"]["collection"])) {
            foreach ($holidays["data"]["collection"] as $collection) {
                if (isset($collection["data"]) && !empty($collection["data"])) {
                    foreach ($collection["data"] as $data) {
                        if (is_array($data)) {
                            
                            $publicHolidays[] = [
                                'day' => $data['day'],
                                'date' => $data['date'],
                                'date_formatted' => $data['date_formatted'],
                                'month' => $data['month'],
                                'name' => $data['name'],
                                'description' => $data['description'],
                                'is_holiday' => $data['is_holiday'],
                                'type' => $data['type'],
                                'type_id' => $data['type_id'],
                                'region' => $collection['region'] ?? 'National',
                            ];
                        }
                    }
                }
            }
        } else {
            Log::warning('No holidays found for year:', ['year' => $year]);
            return "No holidays found for the specified year.";
        }

        
        if (!empty($publicHolidays)) {
            Holiday::insert($publicHolidays); 
            return "Data inserted successfully!";
        } else {
            return "No holidays to insert.";
        }
    } catch (\Exception $e) {
        Log::error('Failed to insert holidays:', ['error' => $e->getMessage()]);
        return "Failed to insert: " . $e->getMessage();
    }

}

public function extractPublicHolidaysByRegionAndYear($year, Request $request)
{
    $regions = $request->input('regions'); 

    if (!is_array($regions) || empty($regions)) {
        return response()->json(['message' => 'No regions provided or invalid format.'], 400);
    }

    try {
        $malaysiaHoliday = new MalaysiaHoliday();

        foreach ($regions as $region) {
            
            $holidays = $malaysiaHoliday->fromState($region)->ofYear($year)->get();

            Log::info('API response for region:', ['region' => $region, 'holidays' => $holidays]);

            if (!isset($holidays['data']) || empty($holidays['data'])) {
                Log::warning('No holidays found for region:', ['region' => $region]);
                continue; 
            }

            foreach ($holidays['data'] as $holidayData) {
                $regionName = $holidayData['regional']; 

                if (isset($holidayData['collection'])) {
                    foreach ($holidayData['collection'] as $collection) {
                        if (isset($collection['data']) && !empty($collection['data'])) {
                            foreach ($collection['data'] as $data) {
                                if (is_array($data)) {

                                    if (!$data['is_holiday']) {
                                        continue; 
                                    }

                                    
                                    $existingHoliday = Holiday::where('date', $data['date'])
                                        ->where('name', $data['name'])
                                        ->first();

                                    if ($existingHoliday) {
                                        
                                        $existingRegions = explode(', ', $existingHoliday->region);
                                        if (!in_array($regionName, $existingRegions)) {
                                            $existingRegions[] = $regionName;
                                            $existingHoliday->region = implode(', ', $existingRegions);
                                            $existingHoliday->save();
                                        }
                                    } else {
                                        
                                        Holiday::create([
                                            'day' => $data['day'],
                                            'date' => $data['date'],
                                            'date_formatted' => $data['date_formatted'],
                                            'month' => $data['month'],
                                            'name' => $data['name'],
                                            'description' => $data['description'],
                                            'is_holiday' => $data['is_holiday'],
                                            'type' => $data['type'],
                                            'type_id' => $data['type_id'],
                                            'region' => $regionName, 
                                        ]);
                                    }
                                } else {
                                    Log::error('Invalid public holiday data format:', ['data' => $data]);
                                }
                            }
                        }
                    }
                }
            }
        }

        return response()->json(['message' => 'Holidays for the specified regions and year processed successfully!'], 200);
    } catch (\Exception $e) {
        Log::error('Failed to insert holidays:', ['error' => $e->getMessage()]);
        return response()->json(['message' => 'Failed to insert: ' . $e->getMessage()], 500);
    }
}

}