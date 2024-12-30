<?php

namespace App\Http\Controllers;

use App\Http\BrowserDetection;
use App\Models\Currency;
use App\Models\Ilce;
use App\Models\Mahalle;
use App\Models\MoneyAccount;
use App\Models\Sehir;
use App\Models\UserAddress;
use App\Models\UserLoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }
        $currencies = [];

        foreach (Currency::all() as $c) {
            $currencies[$c->id] = $c;
        }

        $sehirler = [];
        $ilceler = [];
        $mahalleler = [];
        $adresler = UserAddress::where("user_id", Auth::id())->get();

        foreach ($adresler as $adres) {
            if (!isset($sehirler[$adres->sehir_id])) {
                $sehirler[$adres->sehir_id] = Sehir::find($adres->sehir_id)->name;
            }
            if (!isset($ilceler[$adres->ilce_id])) {
                $ilceler[$adres->ilce_id] = Ilce::find($adres->ilce_id)->name;
            }
            if (!isset($mahalleler[$adres->mahalle_id])) {
                $mahalleler[$adres->mahalle_id] = Mahalle::find($adres->mahalle_id)->name;
            }
        }

        $logins = UserLoginHistory::where("user_id", Auth::id())->orderBy("created_at", "DESC")->limit(10)->get();
        foreach ($logins as $login) {
            $parsed = [];
            parse_str($login->params, $parsed);
            if (isset($parsed["user_agent"])) {
                $parsed["browser"] = (new BrowserDetection())->getAll($parsed["user_agent"]);
            }
            $login["parsed_params"] = $parsed;
        }

        return view('user.index', [
            "user" => Auth::user(),
            "accounts" => MoneyAccount::where("user_id", Auth::id())->get(),
            "currencies" => $currencies,
            "addresses" => $adresler,
            "sehirler" => $sehirler,
            "ilceler" => $ilceler,
            "mahalleler" => $mahalleler,
            "logins" => $logins,
        ]);
    }

    function addAddress(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        return view('user.address', [
            "sehirler" => Sehir::all(),
        ]);
    }

    function postAddress(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $validated = $request->validate([
            "sehirId" => "required|numeric",
            "ilceId" => "required|numeric",
            "mahalleId" => "required|numeric",
            "address" => "required",
        ]);

        $sehir = Sehir::where("key", $validated["sehirId"])->first();
        $ilce = Ilce::where("key", $validated["ilceId"])->first();
        $mahalle = Mahalle::where("key", $validated["mahalleId"])->first();

        UserAddress::create([
            "user_id" => Auth::id(),
            "sehir_id" => $sehir->id,
            "ilce_id" => $ilce->id,
            "mahalle_id" => $mahalle->id,
            "address" => $validated["address"],
        ]);

        return redirect("/user");
    }

    function deleteAddress(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $addr = UserAddress::find($request->get("id"));

        if ($addr == null || $addr->user_id != Auth::id()) {
            $addr = null;
        }

        if ($addr == null) {
            return response()->json(["success" => false, "message" => "Adres bulunamadı"]);
        }

        $addr->delete();

        return response()->json(["success" => true, "message" => "Adres silindi"]);
    }

    function ajaxSehirler(Request $request)
    {
        $sehirler = [];
        foreach (Sehir::all() as $sehir) {
            $sehirler[$sehir->key] = $sehir->name;
        }
        return response()->json($sehirler);
    }

    function ajaxIlceler(Request $request)
    {
        $ilceler = [];
        foreach (Ilce::where("sehir_key", $request->get("sehirKey"))->get() as $ilce) {
            $ilceler[$ilce->key] = $ilce->name;
        }
        return response()->json($ilceler);
    }

    function ajaxMahalleler(Request $request)
    {
        $mahalleler = [];
        foreach (Mahalle::where("ilce_key", $request->get("ilceKey"))->get() as $mahalle) {
            $mahalleler[$mahalle->key] = $mahalle->name;
        }
        return response()->json($mahalleler);
    }
}
