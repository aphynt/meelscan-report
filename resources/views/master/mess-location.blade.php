@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')
<div class="content">
    <div class="container-fluid">
        <div class="mc-page-header">
            <div>
                <h1 class="mc-page-title">Mess Location</h1>
            </div>
        </div>
        <div class="mc-panel">
            <div class="mc-panel-head">
                <div>
                    <div class="mc-panel-title">Location Utilization</div>
                    <div class="mc-panel-subtitle">Distinct position with transaction count and portions</div>
                </div><span class="mc-soft-chip">{{ $locations->count() }} locations</span>
            </div>
            <div class="table-responsive">
                <table class="mc-table-modern">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Location</th>
                            <th>Transactions</th>
                            <th>Portions</th>
                        </tr>
                    </thead>
                    <tbody>@forelse($locations as $i=>$loc)<tr>
                            <td><span class="mc-rank">{{ $i+1 }}</span></td>
                            <td><strong>{{ $loc->position }}</strong></td>
                            <td>{{ number_format($loc->transactions) }}</td>
                            <td>{{ number_format($loc->portions) }}</td>
                        </tr>@empty<tr>
                            <td colspan="4">
                                <div class="mc-empty">No position data available.</div>
                            </td>
                        </tr>@endforelse</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('layout.footer')
