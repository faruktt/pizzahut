<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $logo = Setting::getValue('site_logo');
        $favicon = Setting::getValue('site_favicon');
        $pointRatePoints = (float) Setting::getValue('point_rate_points', 1);
        $pointRateTaka   = (float) Setting::getValue('point_rate_taka', 1);
        $minRedeemPoints = (int) Setting::getValue('min_redeem_points', 1);
        $siteAddress     = Setting::getValue('site_address', 'House 14, Road 11, Block D, Gulshan-2, Dhaka');
        $sitePhone       = Setting::getValue('site_phone', '+880 1711-223344');
        $siteEmail       = Setting::getValue('site_email', 'contact@italianpizza.com');
        $openingHours    = Setting::getValue('opening_hours', 'Daily: 11:00 AM – 11:30 PM');
        $googleMapEmbed  = Setting::getValue('google_map_embed', '');

        return view('admin.settings.index', compact(
            'logo', 'favicon', 'pointRatePoints', 'pointRateTaka', 'minRedeemPoints',
            'siteAddress', 'sitePhone', 'siteEmail', 'openingHours', 'googleMapEmbed'
        ));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_address'     => 'nullable|string|max:500',
            'site_phone'       => 'nullable|string|max:100',
            'site_email'       => 'nullable|string|max:150',
            'opening_hours'    => 'nullable|string|max:200',
            'google_map_embed' => 'nullable|string',
        ]);

        Setting::setValue('site_address', $request->input('site_address', ''));
        Setting::setValue('site_phone', $request->input('site_phone', ''));
        Setting::setValue('site_email', $request->input('site_email', ''));
        Setting::setValue('opening_hours', $request->input('opening_hours', ''));
        Setting::setValue('google_map_embed', $request->input('google_map_embed', ''));

        return redirect()->route('admin.settings.index')->with('success', 'Address, contact information & Google map updated successfully!');
    }

    public function pointsIndex()
    {
        $pointRatePoints = (float) Setting::getValue('point_rate_points', 1);
        $pointRateTaka   = (float) Setting::getValue('point_rate_taka', 1);
        $minRedeemPoints = (int) Setting::getValue('min_redeem_points', 1);

        $totalCustomerPoints = (int) \App\Models\User::sum('reward_points');
        $totalRedeemedPoints = (int) \App\Models\Order::sum('points_used');
        $totalDiscountGiven  = (float) \App\Models\Order::sum('points_discount');

        return view('admin.settings.points', compact(
            'pointRatePoints', 'pointRateTaka', 'minRedeemPoints',
            'totalCustomerPoints', 'totalRedeemedPoints', 'totalDiscountGiven'
        ));
    }

    public function updatePoints(Request $request)
    {
        $request->validate([
            'point_rate_points' => 'required|numeric|min:1',
            'point_rate_taka'   => 'required|numeric|min:0.01',
            'min_redeem_points' => 'nullable|integer|min:1',
        ]);

        Setting::setValue('point_rate_points', $request->point_rate_points);
        Setting::setValue('point_rate_taka', $request->point_rate_taka);
        Setting::setValue('min_redeem_points', $request->input('min_redeem_points', 1));

        return redirect()->back()->with('success', 'Reward points conversion settings updated successfully!');
    }

    public function shippingIndex()
    {
        $deliveryCharge  = (float) Setting::getValue('delivery_charge', 70);
        $freeDeliveryMin = (float) Setting::getValue('free_delivery_min', 0);

        return view('admin.settings.shipping', compact('deliveryCharge', 'freeDeliveryMin'));
    }

    public function updateShipping(Request $request)
    {
        $request->validate([
            'delivery_charge'   => 'required|numeric|min:0',
            'free_delivery_min' => 'nullable|numeric|min:0',
        ]);

        Setting::setValue('delivery_charge', $request->delivery_charge);
        Setting::setValue('free_delivery_min', $request->input('free_delivery_min', 0));

        return redirect()->back()->with('success', 'Shipping / delivery charge updated successfully! Customers will be charged ৳ ' . number_format($request->delivery_charge) . ' on delivery orders.');
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        $this->deleteFile(Setting::getValue('site_logo'));
        Setting::setValue('site_logo', $this->upload($request->file('logo')));

        return redirect()->route('admin.settings.index')->with('success', 'Website logo updated.');
    }

    public function updateFavicon(Request $request)
    {
        $request->validate([
            'favicon' => 'required|file|mimes:ico,png,jpg,jpeg,svg,webp|max:1024',
        ]);

        $this->deleteFile(Setting::getValue('site_favicon'));
        Setting::setValue('site_favicon', $this->upload($request->file('favicon')));

        return redirect()->route('admin.settings.index')->with('success', 'Favicon updated.');
    }

    public function removeLogo()
    {
        $this->deleteFile(Setting::getValue('site_logo'));
        Setting::removeValue('site_logo');
        return redirect()->route('admin.settings.index')->with('success', 'Logo removed.');
    }

    public function removeFavicon()
    {
        $this->deleteFile(Setting::getValue('site_favicon'));
        Setting::removeValue('site_favicon');
        return redirect()->route('admin.settings.index')->with('success', 'Favicon removed.');
    }

    /**
     * Store branding files under public/uploads/brand and return relative path.
     */
    private function upload($file): string
    {
        $dir = public_path('uploads/brand');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($dir, $filename);
        return 'brand/' . $filename;
    }

    /**
     * Delete a branding file if it exists.
     */
    private function deleteFile(?string $path): void
    {
        if ($path && file_exists(public_path('uploads/' . $path))) {
            unlink(public_path('uploads/' . $path));
        }
    }
}
