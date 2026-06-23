<?php
/** GET /orgadmin/visits/{id} */

$orgId = Auth::orgId();
$visitId = (int) $id;

$visit = Visit::find($visitId, $orgId);
if (!$visit) {
    flash('error', 'Visit not found.');
    redirect('/orgadmin/visits');
}

view('orgadmin/visit_view', [
    'pageTitle' => 'Visit — ' . $visit['visitor_name'],
    'visit'     => $visit,
]);
