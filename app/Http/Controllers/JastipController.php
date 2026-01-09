<?php

namespace App\Http\Controllers;

use App\Http\Requests\Jastip\CreateRequest;
use App\Http\Requests\JastipRequest;
use App\Models\Packages;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JastipController extends Controller
{
  private $recipient, $package;

  public function __construct(Recipient $recipient, Packages $package)
  {
    $this->recipient = $recipient;
    $this->package = $package;
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('pages.jastip.index');
  }

  /**
   * Get Jastip
   */
  public function getJastip()
  {
    $data = $this->recipient->with(['packages', 'recipientStatus.status'])->whereHas('recipientStatus', function ($query) {
      $query->where('status_id', 1);
    })->whereDoesntHave('recipientLocation')->orderBy('name')->get();

    return DataTables::of($data)
      ->addIndexColumn()
      ->addColumn('action', function ($data) {
        $buttons = '<button class="btn icon btn-primary btn-detail me-1" data-uri="' . route('jastip.getDataById', $data->id) . '"><i class="bi bi-eye"></i></button>';
        $buttons .= '<a href="' . route("jastip.show", $data->id) . '" class="btn icon btn-info btn-edit me-1"><i class="bi bi-pencil-square"></i></a>';
        $buttons .= '<button class="btn icon btn-danger btn-delete" data-uri="' . route('jastip.destroy', $data->id) . '" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bi bi-trash"></i></button>';
        return $buttons;
      })
      ->toJson();
  }

  /**
   * Get Jastip by ID
   */
  public function getJastipById(string $id)
  {
    $data = $this->recipient->with(['packages', 'recipientStatus.status'])->findOrFail($id);
    return response()->json($data, 200);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {

    return view('pages.jastip.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(JastipRequest $request)
  {
    // dd($request->packages);
    // $recipient = new Recipient();
    // $recipient->name = $request->name;
    // $recipient->save();
    // dd($request);
    DB::transaction(function () use ($request) {
      $recipient = $this->recipient->create([
        'name' => $request->name,
      ]);

      // Auto-set pricing_option to kubikasi for all packages
      foreach ($request->packages as $package) {
        $package['pricing_option'] = 'kubikasi';
        $package['price'] = 0;
        $recipient->packages()->create($package);
      }

      $recipient->recipientStatus()->create([
        'status_id' => 1,
      ]);
    });



    return response()->json([
      'message' => 'Jastip Berhasil Ditambahkan',
      'redirect_uri' => route('jastip.index'),
    ], 200);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $recipient = $this->recipient->with(['packages', 'recipientStatus.status'])->findOrFail($id);
    return view('pages.jastip.edit', compact('recipient'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(JastipRequest $request, string $id)
  {
    $recipient = $this->recipient->findOrFail($id);
    $recipient->name = $request->name;
    $recipient->save();

    $recipient->packages()->delete();

    // Auto-set pricing_option to kubikasi for all packages
    foreach ($request->packages as $package) {
      $package['pricing_option'] = 'kubikasi';
      $package['price'] = 0;
      $recipient->packages()->create($package);
    }

    return response()->json([
      'message' => 'Jastip Berhasil Diubah',
      'redirect_home' => route('jastip.index'),
      'redirect_create' => route('jastip.create'),
    ], 200);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $recipient = $this->recipient->findOrFail($id);
    $recipient->packages()->delete();
    $recipient->recipientStatus()->delete();
    $recipient->delete();

    return response()->json([
      'message' => 'Jastip Berhasil Dihapus',
      'redirect_uri' => route('jastip.index'),
    ], 200);
  }
}
