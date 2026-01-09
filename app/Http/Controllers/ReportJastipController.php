<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportJastipRequest;
use App\Models\Packages;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

class ReportJastipController extends Controller
{
  public function index()
  {
    return view('pages.report-jastip.index');
  }

  public function daily()
  {
    $cachedPackages = Cache::get('report-jastip.daily');
    return view('pages.report-jastip.daily', compact('cachedPackages'));
  }

  public function getDailyReportJastip(Request $request)
  {
    $packages = Packages::leftJoin('recipients', 'packages.recipient_id', '=', 'recipients.id')
      ->leftJoin('recipient_statuses', 'recipients.id', '=', 'recipient_statuses.recipient_id')
      ->leftJoin('recipient_locations', 'recipients.id', '=', 'recipient_locations.recipient_id')
      ->select('packages.id', 'packages.tracking_number', 'packages.weight', 'packages.pricing_option', 'packages.length', 'packages.width', 'packages.height', 'packages.cubic_weight', 'packages.price', 'recipients.name', 'recipient_statuses.status_id', 'recipient_locations.name as location')
      ->orderBy('recipients.name')
      ->whereDateBetween('packages.created_at', [now()->startOfDay()->format('Y-m-d H:i:s'), now()->endOfDay()->format('Y-m-d H:i:s')])
      ->get();

    return Cache::remember('report-jastip.daily', Carbon::parse('10 seconds'), function () use ($packages) {
      return view('pages.report-jastip._packages', compact('packages'))->render();
    });
  }

  public function getReportJastip(ReportJastipRequest $request)
  {
    $packages = Packages::leftJoin('recipients', 'packages.recipient_id', '=', 'recipients.id')
      ->leftJoin('recipient_statuses', 'recipients.id', '=', 'recipient_statuses.recipient_id')
      ->leftJoin('recipient_locations', 'recipients.id', '=', 'recipient_locations.recipient_id')
      ->select('packages.id', 'packages.tracking_number', 'packages.weight', 'packages.pricing_option', 'packages.length', 'packages.width', 'packages.height', 'packages.cubic_weight', 'packages.price', 'recipients.name', 'recipient_statuses.status_id', 'recipient_locations.name as location')
      ->orderBy('recipients.name');

    if (!empty($request->start_date) && !empty($request->end_date)) {
      $packages->whereBetween('packages.created_at', [Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s'), Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s')]);
    }

    $packages = $packages->get();

    return view('pages.report-jastip._packages', compact('packages'));
  }

  public function exportDailyPdf()
  {
    $packages = Packages::leftJoin('recipients', 'packages.recipient_id', '=', 'recipients.id')
      ->leftJoin('recipient_statuses', 'recipients.id', '=', 'recipient_statuses.recipient_id')
      ->leftJoin('recipient_locations', 'recipients.id', '=', 'recipient_locations.recipient_id')
      ->select('packages.id', 'packages.tracking_number', 'packages.weight', 'packages.pricing_option', 'packages.length', 'packages.width', 'packages.height', 'packages.cubic_weight', 'packages.price', 'recipients.name', 'recipient_statuses.status_id', 'recipient_locations.name as location')
      ->orderBy('recipients.name')
      ->whereDateBetween('packages.created_at', [now()->startOfDay()->format('Y-m-d H:i:s'), now()->endOfDay()->format('Y-m-d H:i:s')])
      ->get();

    $title = 'Laporan Harian - ' . now()->format('d M Y');
    $dateRange = now()->format('d M Y');
    $filename = 'laporan-jastip-harian-' . now()->format('Y-m-d') . '.pdf';

    $pdf = Pdf::loadView('pages.report-jastip._pdf', compact('packages', 'title', 'dateRange'))
      ->setPaper('a4', 'landscape');

    return $pdf->download($filename);
  }

  public function exportReportPdf(ReportJastipRequest $request)
  {
    $packages = Packages::leftJoin('recipients', 'packages.recipient_id', '=', 'recipients.id')
      ->leftJoin('recipient_statuses', 'recipients.id', '=', 'recipient_statuses.recipient_id')
      ->leftJoin('recipient_locations', 'recipients.id', '=', 'recipient_locations.recipient_id')
      ->select('packages.id', 'packages.tracking_number', 'packages.weight', 'packages.pricing_option', 'packages.length', 'packages.width', 'packages.height', 'packages.cubic_weight', 'packages.price', 'recipients.name', 'recipient_statuses.status_id', 'recipient_locations.name as location')
      ->orderBy('recipients.name');

    $dateRange = '';
    if (!empty($request->start_date) && !empty($request->end_date)) {
      $packages->whereBetween('packages.created_at', [Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s'), Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s')]);
      $dateRange = Carbon::parse($request->start_date)->format('d M Y') . ' - ' . Carbon::parse($request->end_date)->format('d M Y');
    }

    $packages = $packages->get();

    $title = 'Laporan Jastip' . (!empty($dateRange) ? ' (' . $dateRange . ')' : '');
    $filename = 'laporan-jastip-' . ($request->start_date ?? 'all') . '-' . ($request->end_date ?? 'all') . '.pdf';

    $pdf = Pdf::loadView('pages.report-jastip._pdf', compact('packages', 'title', 'dateRange'))
      ->setPaper('a4', 'landscape');

    return $pdf->download($filename);
  }
}
