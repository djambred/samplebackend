<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CostumerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$cost = DB::connection('dbcore')->table('costumers')->paginate(1); //menggunakan paginate
        //$cost = DB::connection('dbcore')->get('select * from costumers'); //cara normal
        $cost= DB::connection('dbcore')->table('costumers')->get(); //cara laravel
        return response()->json($cost);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $timestamp = \Carbon\Carbon::now()->toDateTimeString();
        $this->validate($request, [
            'name' => 'required',
        ]);

        $request['created_at'] = $timestamp;
        $request['updated_at'] = $timestamp;

        $cost = DB::connection('dbcore')->table('costumers')->insert($request->all());
        return response()->json(response("Berhasil ditambahkan"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Costumer  $costumer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $cost = DB::connection('dbcore')->table('costumers')->where('id', $id)->first();
        return response()->json($cost);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Costumer  $costumer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $cost = DB::connection('dbcore')->table('costumers')->where('id', $id)->get();
        return response()->json(" EDIT $cost");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Costumer  $costumer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $timestamp = \Carbon\Carbon::now()->toDateTimeString();
        $request['updated_at'] = $timestamp;
        $cost = DB::connection('dbcore')->table('costumers')->where('id', $id)->update($request->all());
        return response()->json("Berhasil Update Data");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Costumer  $costumer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $cost = DB::connection('dbcore')->table('costumers')->where('id', $id)->delete();
        return response()->json("Berhasil Hapus");
    }

    public function detail(){
        //tradisional
        //$data= DB::select('SELECT * FROM dbtransaction.transactions as C1 JOIN dbcore.costumers AS C2 JOIN dbhistory.histories AS c3 WHERE c1.costumer_id = c2.id = c3.costumer_id');
        //new era
        $data=DB::table(DB::raw('dbcore.costumers AS db1_tb1'))
            ->join(DB::raw('dbtransaction.transactions AS db2_tb2'),'db1_tb1.id','=','db2_tb2.costumer_id')
            ->join(DB::raw('dbhistory.histories AS db3_tb3'),'db1_tb1.id','=','db3_tb3.costumer_id')
            ->select('db1_tb1.id as id_pelanggan', 'db1_tb1.name as nama_pelanggan',
            'db2_tb2.id as id_transaksi','db2_tb2.nominal_transaksi as nominal_transaksi', 'db2_tb2.keterangan as keterangan_transaksi',
            'db3_tb3.status as status_transaksi', 'db3_tb3.updated_at as waktu_transaksi')
            ->get();

        return response()->json($data);
    }

}
