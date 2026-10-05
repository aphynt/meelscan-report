@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')
<div class="content">
    <div class="container-fluid">
        <div class="mc-page-header">
            <div>
                <h1 class="mc-page-title">Food Category</h1>
            </div>
        </div>
        <div class="mc-panel">
            <div class="mc-panel-head">
                <div>
                    <div class="mc-panel-title">Reference Meals</div>
                    <div class="mc-panel-subtitle">Read-only agar tidak mengganggu referensi existing.</div>
                </div><span class="mc-soft-chip">{{ $categories->count() }} categories</span>
            </div>
            <div class="table-responsive">
                <table class="mc-table-modern">
                    <thead>
                        <tr>
                            <th style="width:90px">ID</th>
                            <th>Category / Item</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>@forelse($categories as $cat)<tr>
                            <td>#{{ $cat->id }}</td>
                            <td><strong>{{ $cat->item }}</strong></td>
                            <td><span class="mc-status green">In Use</span></td>
                        </tr>@empty<tr>
                            <td colspan="3">
                                <div class="mc-empty">Table ref_meals was not found or has no data.</div>
                            </td>
                        </tr>@endforelse</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('layout.footer')
