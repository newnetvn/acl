<?php

namespace Newnet\Acl\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Acl\Http\Requests\UserRequest;
use Newnet\Acl\Repositories\AdminRepository;
use Newnet\Acl\Repositories\AdminRepositoryInterface;

class UserController extends Controller
{
    /**
     * @var AdminRepositoryInterface|AdminRepository
     */
    private $userRepository;

    public function __construct(AdminRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $users = $this->userRepository->paginate($request->input('max', 20));

        return view('acl::admin.user.index', compact('users'));
    }

    public function create()
    {
        \AdminMenu::activeMenu('user');

        return view('acl::admin.user.create');
    }

    public function store(UserRequest $request)
    {
        $item = $this->userRepository->createWithRoles($request->all(), $request->input('roles', []));

        if ($request->input('continue')) {
            return redirect()
                ->route('admin.user.edit', $item->id)
                ->with('success', __('acl::user.notification.created'));
        }

        return redirect()
            ->route('admin.user.index')
            ->with('success', __('acl::user.notification.created'));
    }

    public function edit($id)
    {
        \AdminMenu::activeMenu('user');

        $item = $this->userRepository->find($id);

        return view('acl::admin.user.edit', compact( 'item'));
    }

    public function update($id, UserRequest $request)
    {
        $item = $this->userRepository->updateAndSyncRoles($id, $request->all(), $request->input('roles', []));

        if ($request->input('continue')) {
            return redirect()
                ->route('admin.user.edit', $item->id)
                ->with('success', __('acl::user.notification.created'));
        }

        return redirect()
            ->route('admin.user.index')
            ->with('success', __('acl::user.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->userRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('acl::user.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('admin.user.index')
            ->with('success', __('acl::user.notification.deleted'));
    }
}
