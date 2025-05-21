<div class="page-content">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="card-title m-0 font-weight-semibold text-primary">{{ $page_title ?? 'Credit Transactions' }}</h5>
            <div class="card-actions">
                <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i> Print
                </button>

            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="creditTransactionsTable" class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="5%">#</th>
                            <th class="text-left">Order No</th>
                            <th class="text-right">Credit Amount</th>
                            <th class="text-center">Transaction Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($list_items) && count($list_items) > 0)
                            @foreach ($list_items as $key => $item)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td class="text-left font-weight-semibold">{{ $item->order->order_no ?? 'N/A' }}</td>
                                <td class="text-right text-success font-weight-bold">{{ number_format($item->pay_later_credit, 2) }}</td>
                                <td class="text-center">
                                    {{ $item->created_at ? date('d M Y | h:i A', strtotime($item->created_at)) : 'N/A' }}
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle mr-2"></i> No credit transactions found
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="2" class="text-right">Total Credit:</th>
                            <th class="text-right text-primary">
                                {{ isset($list_items) ? number_format($list_items->sum('pay_later_credit'), 2) : '0.00' }}
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table thead th {
        border-top: none;
        border-bottom: 1px solid #e0e0e0;
    }
    .table tfoot th {
        border-bottom: none;
        border-top: 2px solid #e0e0e0;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
</style>

<script>
    $(document).ready(function() {
        $('#creditTransactionsTable').DataTable({
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            pageLength: 10,
            responsive: true,
            order: [[3, 'desc']],
            columnDefs: [
                { orderable: false, targets: [0] }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search transactions...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries found",
                infoFiltered: "(filtered from _MAX_ total entries)"
            }
        });
        
        $('#exportBtn').click(function() {
            // Add export functionality here
            alert('Export functionality would be implemented here');
        });
    });
    </script>