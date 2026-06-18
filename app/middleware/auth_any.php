<?php
/** app/middleware/auth_any.php - any logged-in user */
Auth::requireLogin();
return true;
