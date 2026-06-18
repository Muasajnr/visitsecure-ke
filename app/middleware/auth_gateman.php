<?php
/** app/middleware/auth_gateman.php */
Auth::requireRole(['gateman']);
return true;
