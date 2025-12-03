<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student\Inquiry;

class InquiryPolicy
{
    public function viewAny(User $user): bool { return $user->can('inquiry.viewAny'); }
    public function view(User $user, Inquiry $inquiry): bool { return $user->can('inquiry.view') || $this->owns($user, $inquiry); }
    public function create(User $user): bool { return $user->can('inquiry.create'); }
    public function update(User $user, Inquiry $inquiry): bool { return $user->can('inquiry.update') || $this->owns($user, $inquiry); }
    public function delete(User $user, Inquiry $inquiry): bool { return $user->can('inquiry.delete'); }

    private function owns(User $user, Inquiry $inquiry): bool {
        return $inquiry->user_id && $inquiry->user_id === $user->id;
    }
}
