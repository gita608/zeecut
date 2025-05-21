<div class="page-content">
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0">{{ $page_title ?? '' }}</h5>
        </div>
    </div>

        <div class="card-body">

            <div class="table-responsive">
                <table id="table1" class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order No</th>
                            <th>Credit</th>
                            <th>Created on</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($list_items))
                        @foreach ($list_items as $key => $item)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{$item->order->order_no}}</td>
                            <td>{{$item->pay_later_credit}}</td>
                            <td>
                                {{ $item->created_at ? date('h:i A | d-m-Y', strtotime($item->created_at)) : '' }}
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>

