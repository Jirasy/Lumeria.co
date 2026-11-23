<x-app-layout>
    <x-slot:title>{{ __('users.title') }}</x-slot:title>

    <x-slot:header>
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h1 class="h2 fw-bold mb-1">{{ __('users.title') }}</h1>
                <p class="text-muted mb-0">{{ __('users.subtitle') }}</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-lumeria">{{ __('users.add') }}</a>
        </div>
    </x-slot:header>

    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    <div class="card account-section">
        <div class="card-body p-3 p-md-4">
            <form method="get" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end mb-4">
                <div class="col-12 col-md">
                    <label for="q" class="form-label fw-semibold">{{ __('users.search_label') }}</label>
                    <input id="q" name="q" type="search" value="{{ $search }}" class="form-control">
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label for="role" class="form-label fw-semibold">{{ __('users.role') }}</label>
                    <select id="role" name="role" class="form-select">
                        <option value="">{{ __('users.all_roles') }}</option>
                        @foreach (\App\Models\User::ROLES as $r)
                            <option value="{{ $r }}" @selected($role === $r)>{{ __('users.role_' . $r) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-auto d-flex gap-2">
                    <button type="submit" class="btn btn-lumeria flex-fill">{{ __('users.search') }}</button>
                    @if ($search !== '' || $role !== '')
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark">{{ __('users.reset') }}</a>
                    @endif
                </div>
            </form>

            <p class="text-muted small mb-2">{{ __('users.total', ['count' => $users->total()]) }}</p>

            @if ($users->isEmpty())
                <p class="mb-0">{{ __('users.empty') }}</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle stack-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('users.col_name') }}</th>
                                <th scope="col">{{ __('users.col_email') }}</th>
                                <th scope="col">{{ __('users.col_role') }}</th>
                                <th scope="col">{{ __('users.col_joined') }}</th>
                                <th scope="col"><span class="visually-hidden">{{ __('users.col_actions') }}</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $u)
                                <tr>
                                    <td data-label="{{ __('users.col_name') }}">
                                        <span class="d-inline-flex align-items-center gap-2">
                                            <x-user-avatar :user="$u" :size="32" class="flex-shrink-0" />
                                            <span class="fw-semibold">{{ $u->name }}</span>
                                            @if ($u->is(auth()->user()))
                                                <span class="badge text-bg-secondary">{{ __('users.you') }}</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td data-label="{{ __('users.col_email') }}" class="text-break">{{ $u->email }}</td>
                                    <td data-label="{{ __('users.col_role') }}">
                                        <span class="badge {{ $u->isAdmin() ? 'text-bg-dark' : 'text-bg-light border' }}">{{ __('users.role_' . $u->role) }}</span>
                                    </td>
                                    <td data-label="{{ __('users.col_joined') }}">{{ $u->created_at?->translatedFormat('d M Y') }}</td>
                                    <td data-label="" class="text-md-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-dark">{{ __('users.edit') }}</a>
                                            @unless ($u->is(auth()->user()))
                                                <form method="post" action="{{ route('admin.users.destroy', $u) }}"
                                                      onsubmit="return confirm(@js(__('users.confirm_delete', ['name' => $u->name])));">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('users.delete') }}</button>
                                                </form>
                                            @endunless
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
