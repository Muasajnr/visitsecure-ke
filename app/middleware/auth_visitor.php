<?php
/** app/middleware/auth_visitor.php */
Auth::requireRole(['visitor']);
return true;
