<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminEvaluationsController extends Controller
{
  public function index()
  {
    $hasEventId = Schema::hasColumn('evaluations', 'event_id');

    $query = DB::table('evaluations as eval')
      ->join('users as reviewer', 'reviewer.id', '=', 'eval.evaluator_id')
      ->leftJoin('papers as paper', 'paper.paper_id', '=', 'eval.paper_id')
      ->leftJoin('events as paper_event', 'paper_event.event_id', '=', 'paper.event_id');

    if ($hasEventId) {
      $query->leftJoin('events as direct_event', 'direct_event.event_id', '=', 'eval.event_id');
    }

    $eventNameExpr = $hasEventId
      ? "COALESCE(direct_event.event_name, paper_event.event_name, 'Unknown Event')"
      : "COALESCE(paper_event.event_name, 'Unknown Event')";

    $evaluations = $query
      ->selectRaw('eval.evaluation_id')
      ->selectRaw('eval.score')
      ->selectRaw('eval.comment')
      ->selectRaw('eval.evaluated_at')
      ->selectRaw("CONCAT(reviewer.firstname, ' ', reviewer.lastname) as reviewer_name")
      ->selectRaw($eventNameExpr . ' as event_name')
      ->orderByDesc('eval.evaluated_at')
      ->get()
      ->map(function ($row): array {
        $fullComment = trim((string) ($row->comment ?? ''));
        $preview = $fullComment === ''
          ? 'No comment provided.'
          : (mb_strlen($fullComment) > 120 ? mb_substr($fullComment, 0, 120) . '...' : $fullComment);

        $score = (float) $row->score;
        $scoreDisplay = fmod($score, 1.0) === 0.0
          ? (string) (int) $score
          : number_format($score, 1);

        return [
          'id' => (int) $row->evaluation_id,
          'reviewer_name' => trim((string) ($row->reviewer_name ?? 'Unknown Reviewer')),
          'avatar' => strtoupper(mb_substr(trim((string) ($row->reviewer_name ?? 'U')), 0, 1)),
          'date' => optional($row->evaluated_at)->format('Y-m-d')
            ?? date('Y-m-d', strtotime((string) $row->evaluated_at)),
          'score' => $scoreDisplay,
          'event_name' => strtoupper((string) ($row->event_name ?? 'Unknown Event')),
          'comment_preview' => $preview,
          'comment_full' => $fullComment,
        ];
      });

    return view('admin.evaluations', [
      'evaluations' => $evaluations,
    ]);
  }
}
