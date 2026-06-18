<?php
/** app/middleware/auth_host.php */
Auth::requireRole(['host']);
return true;
