<?php

namespace App\Http\Controllers;

use App\Models\RestaurantSetting;
use App\Models\BusinessHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = RestaurantSetting::first();
        $businessHours = BusinessHour::orderBy('day_of_week')->get();
        return view('settings.index', compact('settings', 'businessHours'));
    }

    public function update(Request $request)
    {
        $section = $request->input('section', 'general');

        if ($section === 'hours') {
            return $this->updateBusinessHours($request);
        }

        if ($section === 'tax_loyalty') {
            $data = $request->validate([
                'tax_rate'               => 'required|numeric|min:0|max:100',
                'tax_name'               => 'required|string|max:50',
                'loyalty_points_per_100' => 'nullable|numeric|min:0',
                'loyalty_point_value'    => 'nullable|numeric|min:0',
                'min_redeem_points'      => 'nullable|integer|min:0',
            ]);
            $data['loyalty_enabled'] = $request->boolean('loyalty_enabled');
            RestaurantSetting::updateOrCreate(['slug' => 'main'], $data);
            return back()->with('success', 'Tax & Loyalty settings saved.');
        }

        // General section
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'tagline'         => 'nullable|string',
            'address'         => 'nullable|string',
            'phone'           => 'nullable|string|max:30',
            'email'           => 'nullable|email',
            'currency'        => 'nullable|string|max:10',
            'currency_code'   => 'nullable|string|max:5',
            'timezone'        => 'nullable|string|max:60',
            'receipt_footer'  => 'nullable|string',
            'logo'            => 'nullable|image|max:2048',
        ]);

        $existing = RestaurantSetting::first();
        if ($request->hasFile('logo')) {
            if ($existing?->logo) Storage::disk('public')->delete($existing->logo);
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        RestaurantSetting::updateOrCreate(['slug' => 'main'], $data);
        return back()->with('success', 'General settings saved.');
    }

    public function updateBusinessHours(Request $request)
    {
        if (!$request->hours) return back()->with('error', 'No hours data received.');
        foreach ($request->hours as $dayId => $hours) {
            BusinessHour::where('id', $dayId)->update([
                'is_open'     => isset($hours['is_open']),
                'open_time'   => $hours['open_time'] ?? '09:00',
                'close_time'  => $hours['close_time'] ?? '22:00',
            ]);
        }
        return back()->with('success', 'Business hours updated.');
    }
}
