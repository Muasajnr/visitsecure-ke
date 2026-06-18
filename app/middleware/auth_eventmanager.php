<?php
/** app/middleware/auth_eventmanager.php */
Auth::requireRole(['event_manager', 'org_admin']);
return true;
