<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $page_title = 'User Transaction List in KOBAR Connect';
        $page_description = 'Berikut adalah daftar transaksi user yang melakukan pemesanan online di aplikasi KOBAR Connect';

        return view('pages.transaction.list_transaction', compact('page_title', 'page_description'));
    }

    public function get_transactions(Request $request){
        $pagination = $request->get("pagination");
        $limit = $pagination["perpage"] ?? 10;
        $current_page = $pagination["page"];
        $total_page = $pagination["page"];
        $sort_object = $request->get("sort");

        $sort = $sort_object["sort"] ?? 'asc';
        $field = $sort_object["field"] ?? 'created_at';

        // filter
        $status = $request->get("status");
        $start_date = $request->get("start_date");
        $end_date = $request->get("end_date");
        $name = $request->get("name");

        $query = Transaction::with("tour:id,name");

        if(isset($name)){
            $query->where("name", "LIKE", "%$name%");
        }

        if(isset($start_date)){
            $query->whereDate("visit_date", ">=", $start_date);
        }

        if(isset($end_date)){
            $query->whereDate("visit_date", "<=", $end_date);
        }

        if(isset($status)){
            $query->where("status", $status);
        }

        $total_data = $query->count();
        $transactions = $query->skip(($current_page - 1 ) * $limit)
                                ->take($limit)
                                ->orderBy($field, $sort)
                                ->get();

        return response()->json([
            "meta" => [
                "sort" => $sort,
                "field"=> $field,
                "page"=> $current_page,
                "pages"=> ceil($total_data / $limit),
                "perpage" => $limit,
                "total" => $total_data,
            ],
            "data"=> $transactions
        ]);
    }
}
