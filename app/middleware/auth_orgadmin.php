<?php
/** app/middleware/auth_orgadmin.php */
Auth::requireRole(['org_admin']);
return true;
