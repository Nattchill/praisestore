@extends('admin.layout')
@section('title', 'Customers')
@section('page-title', 'Customer Management')
@section('breadcrumb', 'Customers')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-users" style="color:var(--primary)"></i> All Customers ({{ $customers->total() }})</h2>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Orders</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $i => $customer)
                <tr>
                    <td style="color:var(--gray)">{{ $customers->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:36px;height:36px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;flex-shrink:0">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <strong>{{ $customer->name }}</strong>
                        </div>
                    </td>
                    <td style="color:var(--gray)">{{ $customer->email }}</td>
                    <td>
                        <span class="badge" style="background:#eff6ff;color:#1e40af">{{ $customer->orders_count }} orders</span>
                    </td>
                    <td style="color:var(--gray)">{{ $customer->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $customer->email_verified_at ? 'badge-active' : 'badge-inactive' }}">
                            <i class="fas fa-circle" style="font-size:7px"></i>
                            {{ $customer->email_verified_at ? 'Verified' : 'Unverified' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-secondary btn-sm btn-icon" title="View"><i class="fas fa-eye"></i></a>
                            <form action="{{ route('admin.customers.toggle', $customer) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $customer->email_verified_at ? 'btn-danger' : 'btn-success' }}" title="{{ $customer->email_verified_at ? 'Unverify' : 'Verify' }}">
                                    <i class="fas fa-{{ $customer->email_verified_at ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:var(--gray);padding:40px">No customers registered yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $customers->links() }}</div>
</div>
@endsection
