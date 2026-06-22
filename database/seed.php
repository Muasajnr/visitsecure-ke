<?php
/**
 * database/seed.php
 *
 * Seeds / resets demo accounts with known passwords.
 * Safe to run multiple times (upserts demo data).
 *
 * Usage:
 *   php database/seed.php
 *   http://localhost/visitsecure-ke/database/seed.php
 */

require_once __DIR__ . '/../app/bootstrap.php';

$superPassword = 'Admin@123';
$demoPassword  = 'Demo@123';

$superHash = password_hash($superPassword, PASSWORD_BCRYPT);
$demoHash  = password_hash($demoPassword, PASSWORD_BCRYPT);

echo "VisitSecure KE — seeding demo accounts...\n\n";

// ── 1. Super Admin ──────────────────────────────────────────────────────────
$updated = DB::run(
    "UPDATE users SET password_hash = ?, is_active = 1 WHERE email = ? AND role = 'super_admin'",
    [$superHash, 'admin@visitsecure.ke']
);
if ($updated === 0) {
    User::create([
        'org_id'      => null,
        'role'        => 'super_admin',
        'full_name'   => 'Platform Super Admin',
        'email'       => 'admin@visitsecure.ke',
        'password'    => $superPassword,
        'auto_verify' => true,
    ]);
    echo "[+] Created super_admin: admin@visitsecure.ke\n";
} else {
    echo "[+] Reset super_admin password: admin@visitsecure.ke\n";
}

// ── 2. Demo organisation (Bihi Properties) ──────────────────────────────────
$org = Organization::findBySlug('bihi-properties');
if (!$org) {
    $orgId = (int) Organization::create([
        'name'    => 'Bihi Properties Ltd',
        'slug'    => 'bihi-properties',
        'email'   => 'info@bihiproperties.co.ke',
        'phone'   => '0711111111',
        'address' => 'Bihi Towers, Nairobi CBD',
        'subscription_status' => 'trial',
    ]);
    $org = Organization::find($orgId);
    echo "[+] Created demo organisation: Bihi Properties Ltd\n";
} else {
    $orgId = (int) $org['id'];
    Organization::toggleActive($orgId, 1);
    Organization::updateStatus($orgId, 'trial');
    echo "[+] Using existing organisation: {$org['name']} (id {$orgId})\n";
}

// ── 3. Building → Floor → Room ───────────────────────────────────────────────
$building = DB::one("SELECT id FROM buildings WHERE org_id = ? AND name = 'Bihi Towers' LIMIT 1", [$orgId]);
if (!$building) {
    $buildingId = (int) Building::create($orgId, [
        'name'    => 'Bihi Towers',
        'address' => 'Nairobi CBD',
    ]);
    echo "[+] Created building: Bihi Towers\n";
} else {
    $buildingId = (int) $building['id'];
}

$floor = DB::one("SELECT id FROM floors WHERE org_id = ? AND building_id = ? AND name = 'Floor 6' LIMIT 1", [$orgId, $buildingId]);
if (!$floor) {
    $floorId = (int) Floor::create($orgId, $buildingId, ['name' => 'Floor 6', 'floor_number' => 6]);
    echo "[+] Created floor: Floor 6\n";
} else {
    $floorId = (int) $floor['id'];
}

$room = DB::one("SELECT id FROM rooms WHERE org_id = ? AND floor_id = ? AND name = 'Conference 6' LIMIT 1", [$orgId, $floorId]);
if (!$room) {
    $roomId = (int) Room::create($orgId, $floorId, [
        'name'      => 'Conference 6',
        'room_type' => 'conference',
        'firm_name' => 'Cap Africa Consulting',
    ]);
    echo "[+] Created room: Conference 6\n";
} else {
    $roomId = (int) $room['id'];
}

// ── 4. Demo users (all roles) ───────────────────────────────────────────────
$demoUsers = [
    ['email' => 'orgadmin@bihi.demo',  'role' => 'org_admin',     'full_name' => 'Bihi Org Admin',    'room_id' => null],
    ['email' => 'gateman@bihi.demo',   'role' => 'gateman',       'full_name' => 'James Kariuki',     'room_id' => null],
    ['email' => 'host@bihi.demo',      'role' => 'host',          'full_name' => 'Wanjiru Kamau',     'room_id' => $roomId],
    ['email' => 'events@bihi.demo',    'role' => 'event_manager', 'full_name' => 'Peter Ochieng',     'room_id' => null],
    ['email' => 'visitor@bihi.demo',   'role' => 'visitor',       'full_name' => 'Achieng Otieno',    'room_id' => null],
];

foreach ($demoUsers as $u) {
    $existing = DB::one("SELECT id FROM users WHERE email = ?", [$u['email']]);
    if ($existing) {
        DB::run(
            "UPDATE users SET password_hash = ?, org_id = ?, role = ?, full_name = ?, room_id = ?, is_active = 1, email_verified_at = COALESCE(email_verified_at, NOW()) WHERE id = ?",
            [$demoHash, $u['role'] === 'visitor' ? null : $orgId, $u['role'], $u['full_name'], $u['room_id'], $existing['id']]
        );
        echo "[+] Reset {$u['role']}: {$u['email']}\n";
    } else {
        User::create([
            'org_id'      => $u['role'] === 'visitor' ? null : $orgId,
            'role'        => $u['role'],
            'full_name'   => $u['full_name'],
            'email'       => $u['email'],
            'phone'       => '0700000000',
            'password'    => $demoPassword,
            'auto_verify' => true,
            'room_id'     => $u['room_id'],
        ]);
        echo "[+] Created {$u['role']}: {$u['email']}\n";
    }
}

// ── Summary ─────────────────────────────────────────────────────────────────
echo "\n";
echo "══════════════════════════════════════════════════════════════\n";
echo "  TEST ACCOUNTS — copy these credentials to sign in\n";
echo "══════════════════════════════════════════════════════════════\n";
echo "\n";
echo "  Role            Email                      Password\n";
echo "  ──────────────  ─────────────────────────  ──────────\n";
echo "  Super Admin     admin@visitsecure.ke       Admin@123\n";
echo "  Org Admin       orgadmin@bihi.demo         Demo@123\n";
echo "  Gateman         gateman@bihi.demo          Demo@123\n";
echo "  Host            host@bihi.demo             Demo@123\n";
echo "  Event Manager   events@bihi.demo           Demo@123\n";
echo "  Visitor         visitor@bihi.demo          Demo@123\n";
echo "\n";
echo "  Demo org: Bihi Properties Ltd (Bihi Towers → Floor 6 → Conference 6)\n";
echo "  Login URL: " . BASE_URL . "/login\n";
echo "\n";
echo "  Change these passwords after testing in production.\n";
echo "══════════════════════════════════════════════════════════════\n";
