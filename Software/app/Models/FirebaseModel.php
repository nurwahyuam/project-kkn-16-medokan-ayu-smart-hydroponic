<?php

namespace App\Models;

use App\Libraries\FirebaseClient;

/**
 * Base class for all Firebase-backed Models. Holds the shared
 * FirebaseClient dependency so each concrete Model only needs to
 * declare its own node name and query methods.
 */
abstract class FirebaseModel
{
    protected FirebaseClient $firebase;

    public function __construct(FirebaseClient $firebase)
    {
        $this->firebase = $firebase;
    }
}
