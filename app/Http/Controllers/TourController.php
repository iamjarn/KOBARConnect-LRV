<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInvoice;

use App\Models\User;
use App\Models\TourPlace;
use App\Models\TourImage;
use App\Models\Category;
use App\Models\Transaction;
use Hash;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log as SysLog;
use App\Models\Log;

class TourController extends Controller
{

    use GeneralTrait;

    public function __construct(){
        \Midtrans\Config::$serverKey = config("midtrans.SERVER_KEY");
        \Midtrans\Config::$isProduction = config("midtrans.IS_PRODUCTION");
    }

    public function index(Request $request)
    {
        $keyword = $request->get("keyword");
        $category = $request->get("category");

        $page_title = 'Tour Places in KOBAR';
        $page_description = 'Berikut adalah daftar wisata yang terdata di dalam aplikasi KOBAR Connect';
        $data = TourPlace::with(["category"])
                ->where(function($query) use($keyword, $category){
                    if(!is_null($keyword)){
                        $query->where("name", "LIKE", "%$keyword%");
                    }
                    if(!is_null($category)){
                        $query->where("id_category", $category);
                    }
                })
                ->paginate(8);
        $categories = Category::pluck("name", "id");

        return view('pages.list_tour', compact(
            'data',
            'page_title',
            'page_description',
            'categories',
            'category',
            'keyword'
        ));
    }

    public function form($id = null){
        $page_type = isset($id) ? "EDIT" : "CREATE" ;
        $categories = Category::pluck("name", "id");
        $image_count = 5;
        $page_title = isset($id) ? "Edit Data Lokasi" : "Buat Data Lokasi";

        $data = null;
        if(isset($id)){
            $data = TourPlace::find($id);
        }

        return view('pages.tours.form_tour', compact(
            "categories",
            "page_type",
            "data",
            "image_count",
            "page_title"
        ));
    }

    public function delete($id){
        $TourPlace = TourPlace::findOrFail($id);
        $result = $TourPlace->delete();

        if($result){

            Log::create([
                'id_user' => Auth::user()->id,
                'name' => "hapus wisata ".$id
            ]);
            return redirect()->route("tours")->with("success", "Berhasil Hapus Data");
        }
        return redirect()->back()->with("error", "Gagal Hapus Data");
    }

    public function store(Request $request, $id = null){

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:10000',
            'latitude' => 'nullable|numeric|between:-999.9999999999,999.9999999999',
            'longitude' => 'nullable|numeric|between:-999.9999999999,999.9999999999',
            'id_category' => 'required|exists:category,id',
            'images.*.file' => 'file|mimes:jpeg,jpg,png|max:5120',
            'address'   => 'required|string|max:255',
            'operational_days'  => 'nullable|string|max:255',
            'adult_prices'  => 'nullable|integer|min:0',
            'kid_prices'    => 'nullable|integer|min:0',
            'transport_prices'  => 'nullable|integer|min:0'
        ]);

        $list_filename = [];
        $images_data = [];
        try {
            DB::beginTransaction();

            $files = $request->file("images");
            $files_atribute = $request->get("images");

            foreach($files_atribute as $index => $value){
                if($value["is_update"] == "1" || $value["is_delete"] == "1"){
                    $result = null;
                    if(isset($files[$index]["file"])){
                        $result = $this->upload($files[$index]["file"], null);
                        if(!$result["status"]){
                            throw new \Exception("Failed upload image");
                        }
                        array_push($list_filename, $result["data"]);
                    }
                    $images_data[] = [
                        "is_update" => $value["is_update"],
                        "is_delete" => $value["is_delete"],
                        "id" => $value["id"],
                        "path" => $result["data"] ?? null,
                    ];
                }
            }

            if(isset($id)){
                $tour = TourPlace::findOrFail($id);
            }else{
                $tour = new TourPlace;
            }

            $tour->name = $request->get("name");
            $tour->description  = $request->get("description");
            $tour->latitude  = $request->get("latitude");
            $tour->longitude  = $request->get("longitude");
            $tour->id_category  = $request->get("id_category");
            $tour->address  = $request->get("address");
            $tour->operational_days  = $request->get("operational_days");
            $tour->adult_prices  = $request->get("adult_prices") ?? 0;
            $tour->kid_prices  = $request->get("kid_prices") ?? 0;
            $tour->transport_prices  = $request->get("transport_prices") ?? 0;

            $tour->save();

            $array_new = [];
            foreach ($images_data as $value) {
                if($value["is_delete"] == "1" && isset($value["id"])){
                    $image = TourImage::find($value["id"]);
                    if($image){
                        $image->delete();
                    }
                }else if($value["is_update"] == "1"){
                    if(isset($value["id"])){
                        $image = TourImage::find($value["id"]);
                        if($image){
                            $image->path = $value["path"];
                            $image->save();
                        }
                    }else{
                        $array_new[] = [
                            "path"  => $value["path"]
                        ];
                    }
                }
            }

            $tour->images()->createMany($array_new);

            if(isset($id)){
                Log::create([
                    'id_user' => Auth::user()->id,
                    'name' => "update wisata ".$tour->id
                ]);
            }else{
                Log::create([
                    'id_user' => Auth::user()->id,
                    'name' => "buat wisata ".$tour->id
                ]);
            }
            DB::commit();
            return redirect()->route('tours')->with([
                "success" => "Data baru telah ditambahkan!"
            ]);

        } catch (\Exception $th) {
            return $th;
            DB::rollback();
            $this->remove_file($list_filename);
            return redirect()->back()->with([
                "error" => "Terjadi kesalahan pada server"
            ]);
        }
    }

    public function index_category()
    {
        $page_title = 'Tour Categories in KOBAR';
        $page_description = 'Berikut adalah kategori wisata yang terdata dalam KOBAR Connect';

        return view('pages.tours.list_category', compact('page_title', 'page_description'));
    }

    public function get_categories(Request $request){

        $pagination = $request->get("pagination");
        $limit = $pagination["perpage"] ?? 10;
        $current_page = $pagination["page"];
        $total_page = $pagination["page"];

        $total_data = Category::count();
        $categories = Category::skip(($current_page - 1 ) * $limit)->take($limit)->get([
            "id",
            "name",
            "is_enable_ticket",
            "created_at",
        ]);

        return response()->json([
            "meta" => [
                "sort" => "asc",
                "field"=> "id",
                "page"=> $current_page,
                "pages"=> ceil($total_data / $limit),
                "perpage" => $limit,
                "total" => $total_data,
            ],
            "data"=> $categories
        ]);
    }

    public function store_category(Request $request){
        $validated = $request->validate([
            'name' => 'required|max:255',
            'is_enable_ticket'  => 'required|integer'
        ]);

        try {

            $data = $request->all();
            $id = $request->get('id');

            if(isset($id)){
                $tour = Category::findOrFail($id);
                $tour->name = $request->get("name");
                $tour->updated_at = Carbon::now();
                $tour->is_enable_ticket = $request->get("is_enable_ticket");

                $result = $tour->save();
            }else{
                $result = Category::create($data);
            }

            if($result){
                if(isset($id)){
                    Log::create([
                        'id_user' => Auth::user()->id,
                        'name' => "update kategori ".$id
                    ]);
                }else{
                    Log::create([
                        'id_user' => Auth::user()->id,
                        'name' => "buat kategori ".$result->id
                    ]);
                }
                return redirect()->back()->with("success", "Berhasil tambah data");
            }
            return redirect()->back()->with("error", "Gagal tambah data");
        } catch (\Exception $th) {

            return redirect()->back()->with("error", "Gagal tambah data");
        }
    }

    public function delete_category($id){
        $Category = Category::findOrFail($id);
        $result = $Category->delete();

        if($result){
            Log::create([
                'id_user' => Auth::user()->id,
                'name' => "hapus kategori ".$id
            ]);
            return redirect()->back()->with("success", "Berhasil Hapus Data");
        }
        return redirect()->back()->with("error", "Gagal Hapus Data");
    }

    public function logs()
    {
        $page_title = 'Admin Logs in KOBAR';
        $page_description = 'Berikut log aktivitas yang dilakukan administrator KOBAR Connect dalam mengatur konten aplikasi ini';

        return view('pages.log_activity', compact('page_title', 'page_description'));
    }

    public function get_logs(Request $request){

        $pagination = $request->get("pagination");
        $limit = $pagination["perpage"] ?? 10;
        $current_page = $pagination["page"];
        $total_page = $pagination["pages"];

        $total_data = Log::count();
        $Log = Log::with('user')
                ->orderBy("created_at", "desc")
                ->skip(((int)$current_page - 1) * (int)$limit)
                ->take((int)$limit)
                ->get();

        return response()->json([
            "meta" => [
                "sort" => "asc",
                "field"=> "id",
                "page"=> $current_page,
                "pages"=> ceil($total_data / $limit),
                "perpage" => $limit,
                "total" => $total_data,
            ],
            "data"=> $Log
        ]);
    }

    // ===================================
    // API
    // ===================================
    
    public function tours(Request $request){
        $page = $request->get("page") ?? 0;
        $limit = 10;
        $skip = $page * $limit;

        $id_category = $request->get("category") ?? null;
        $keyword = $request->get("keyword") ?? null;
        $now = Carbon::now();
        $limit_date = Carbon::now()->addMonth(3);

        $result = TourPlace::with("category")
                        ->where(function($query) use ($id_category, $keyword){
                            if(isset($id_category)){
                                $query->where("id_category", $id_category);
                            }
                            if(isset($keyword)){
                                $query->where("name", "LIKE", "%$keyword%");
                            }
                        })
                        ->get();

        $response["current_page"] = $page;
        $response["next_page"] = count($result) < $limit ? null : $page + 1;
        $response["data"] = $result;
        return $response;
    }

    public function recommends(){
        $categories = Category::whereHas("tours")->get();
        $categories = collect($categories)->toArray();
        $now = Carbon::now();
        $limit_date = Carbon::now()->addMonth(3);
        foreach ($categories as $key => $value) {
            $tours = TourPlace::with("category")
                    ->where("id_category", $value["id"])
                    ->inRandomOrder()
                    ->limit(5)
                    ->get();

            $categories[$key]["recommends"] = $tours;
        }
        return $categories;
    }

    public function create_transaction(Request $request, $tour_id){

        $validated = $request->validate([

        ]);

        // Validate Payload
        $validator = Validator::make($request->all(), [
            'visit_date' => 'required|date',
            'adult_quantity' => 'required|integer',
            'child_quantity' => 'nullable|integer',
            'is_use_transport' => 'required|boolean',
            'name' => 'required|string|max:200',
            'identity_number' => 'required|string|max:200',
            'email' => 'required|email|string|max:200',
            'phone_number' => 'required|string|numeric',
            'address' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            $message =  $validator->errors();
            return response()->json($message, 400);
        }
        try {

            $tour = TourPlace::find($tour_id);

            $timestamp = Carbon::now()->timestamp;
            $rand = rand(111,999);
            $invoice_number = "INV/{$timestamp}/{$rand}";
            $kid_quantity = $request->get("child_quantity") ?? 0;

            // Create transaction
            $transaction = new Transaction;
            $transaction->id_tour_place = $tour_id;
            $transaction->invoice_number = $invoice_number;
            $transaction->visit_date = $request->get("visit_date");
            $transaction->name = $request->get("name");
            $transaction->identity_number = $request->get("identity_number");
            $transaction->email = $request->get("email");
            $transaction->phone_number = $request->get("phone_number");
            $transaction->address = $request->get("address");
            $transaction->adult_prices = $tour->adult_prices;
            $transaction->adult_quantity = $request->get("adult_quantity");
            $transaction->adult_total_prices = $tour->adult_prices * $transaction->adult_quantity;
            $transaction->kid_prices = $tour->kid_prices;
            $transaction->kid_quantity = $kid_quantity;
            $transaction->kid_total_prices = $tour->kid_prices * $transaction->kid_quantity;
            if($request->get("is_use_transport")){
                $transaction->transport_prices = $tour->transport_prices;
            }else{
                $transaction->transport_prices = 0;
            }
            $transaction->total_prices = $transaction->adult_total_prices + $transaction->kid_total_prices + $transaction->transport_prices;
            $transaction->created_at = Carbon::now();
            $transaction->save();

            if($transaction->total_prices > 0){
                // Create midtrans
                $params = array(
                    'transaction_details' => array(
                        'order_id' => $invoice_number,
                        'gross_amount' => $transaction->total_prices,
                    ),
                    'enabled_payments'  => ["gopay", "shopeepay", "echannel", "bni_va", "bca_va", "permata_va", "other_va"]
                );

                // Get Snap Payment Page URL
                $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
                $transaction->payment_url = $paymentUrl;
                $transaction->save();


                return response()->json([
                    "status"    => true,
                    "data"      => $paymentUrl
                ], 200);
            }else{
                return response()->json([
                    "status"    => true,
                    "data"      => null
                ], 200);
            }

        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                "status"    => false,
                "data"      => null
            ], 500);
        }
    }

    public function handleNotification(Request $request){
        try {
            DB::beginTransaction();

            $notif = new \Midtrans\Notification();
            $notif = $notif->getResponse();
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            $order = Transaction::with("tour")->where("invoice_number", $order_id)->first();
            if(is_null($order)){
                return response()->json([
                    "status"    => false,
                    "message"   => "Order Not Found"
                ], 500);
            }

            if(!isset(config("midtrans.payment_status")[$transaction])){
                return response()->json([
                    "status"    => false,
                    "message"   => "Payment Status Not Found"
                ], 500);
            }
            $order->status = config("midtrans.payment_status")[$transaction];
            if($transaction == "settlement"){
                $order->payment_at = $notif->settlement_time;
            }

            if(isset($notif->va_numbers)){
                $order->bank                    = $notif->va_numbers[0]->bank;
                $order->virtual_account_number  = $notif->va_numbers[0]->va_number;
            }else{
                if($type == "bank_transfer"){
                    $order->bank = $type;
                    $order->virtual_account_number = $notif->permata_va_number;
                }
            }

            if($type == "echannel"){
                $order->bank = $type;
                $order->virtual_account_number = $notif->biller_code." ".$notif->bill_key;
            }

            if($type == "gopay" || $type == "shopeepay"){
                $order->bank = $type;
            }

            $order->save();

            if($transaction == "pending" || $transaction == "settlement"){
                // Send Email
                // =========================
                Mail::to($order->email)->send(new SendInvoice($order));
            }


            DB::commit();
            return response()->json([
                "status"    => true,
                "message"   => "Success"
            ], 200);

        } catch (\Exception $e) {
            return $e;
            DB::rollBack();
            SysLog::error($e);
            return response()->json([
                "status"    => false,
                "message"   => "Error"
            ], 500);
        }
    }
}
