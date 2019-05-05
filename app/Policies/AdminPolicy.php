<?php

namespace App\Policies;

use App\Permission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function logAdmin(User $user){
    	return !empty(Permission::getAdminAllPermission($user['id']));
    }

	public function UserControlPermission(User $user){
    	return Permission::checkIfAdminGranted($user['id'], "UPDATE", "UserControl");
	}

	public function TicketControlPermission(User $user){
		return Permission::checkIfAdminGranted($user['id'], "UPDATE", "TicketControl");
	}

	public function BulletinControlPermission(User $user){
		return Permission::checkIfAdminGranted($user['id'], "UPDATE", "BulletinControl");
	}

	public function AdminControlPermission(User $user){
		return Permission::checkIfAdminGranted($user['id'], "UPDATE", "AdminControl");
	}

	public function NodeControlPermission(User $user){
		return Permission::checkIfAdminGranted($user['id'], "UPDATE", "NodeControl");
	}

	public function RelayControlPermission(User $user){
		return Permission::checkIfAdminGranted($user['id'], "UPDATE", "RelayControl");
	}

	public function GroupControlPermission(User $user){
		return Permission::checkIfAdminGranted($user['id'], "UPDATE", "GroupControl");
	}

	public function MerchandiseControlPermission(User $user){
		return Permission::checkIfAdminGranted($user['id'], "UPDATE", "MerchandiseControl");
	}

	public function SupportControlPermission(User $user){
		return Permission::checkIfAdminGranted($user['id'], "UPDATE", "SupportControl");
	}

	public function MarketingControlPermission(User $user){
		return Permission::checkIfAdminGranted($user['id'], "UPDATE", "MarketingControl");
	}
	public function SystemControlPermission(User $user){
		return Permission::checkIfAdminGranted($user['id'], "UPDATE", "SystemControl");
	}
}

