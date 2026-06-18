<?php
/** app/middleware/auth_superadmin.php */
Auth::requireRole(['super_admin']);
return true;
