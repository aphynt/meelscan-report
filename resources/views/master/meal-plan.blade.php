@include('layout.head')
@include('layout.topbar')
@include('layout.sidebar')
<div class="content">
    <div class="container-fluid">
        <div class="mc-page-header">
            <div>
                <h1 class="mc-page-title">Meal Plan</h1>
                <p class="mc-page-subtitle">Rencanakan kebutuhan porsi, prepared portion, dan estimasi unit cost.</p>
            </div>
        </div>
        <div class="mc-grid-2">
            <div class="mc-panel mc-form-card">
                <div class="mc-panel-head">
                    <div>
                        <div class="mc-panel-title">Create / Update Meal Plan</div>
                        <div class="mc-panel-subtitle">Tanggal + meal type yang sama akan diperbarui.</div>
                    </div>
                </div>
                <div class="mc-panel-body">
                    <form method="POST" action="{{ route('master.mealPlan.store') }}">@csrf<div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Plan Date</label><input type="date"
                                    name="plan_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6"><label class="form-label">Meal Type</label><select name="meal_type"
                                    class="form-select">
                                    <option value="breakfast">Breakfast</option>
                                    <option value="lunch">Lunch</option>
                                    <option value="dinner">Dinner</option>
                                </select></div>
                            <div class="col-md-4"><label class="form-label">Planned Portion</label><input type="number"
                                    min="0" name="planned_portion" class="form-control" required></div>
                            <div class="col-md-4"><label class="form-label">Prepared Portion</label><input type="number"
                                    min="0" name="prepared_portion" class="form-control"></div>
                            <div class="col-md-4"><label class="form-label">Unit Cost</label><input type="number"
                                    min="0" step="0.01" name="unit_cost" class="form-control"></div>
                            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes"
                                    class="form-control" placeholder="Optional note"></textarea></div>
                            <div class="col-12"><button class="btn btn-primary"><i
                                        class="mdi mdi-content-save-outline me-1"></i>Save Meal Plan</button></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mc-panel">
                <div class="mc-panel-head">
                    <div>
                        <div class="mc-panel-title">Planning Notes</div>
                        <div class="mc-panel-subtitle">Foundation for plan vs actual & waste analytics</div>
                    </div>
                </div>
                <div class="mc-panel-body">
                    <div class="mc-mini-kpi mt-2">
                        <div class="mc-mini-kpi-icon"><i class="mdi mdi-chart-timeline-variant"></i></div>
                        <div>
                            <div class="mc-mini-kpi-label">Next Development</div>
                            <div class="mc-mini-kpi-value">Plan vs Actual Consumption</div>
                        </div>
                    </div>
                    <div class="mc-mini-kpi">
                        <div class="mc-mini-kpi-icon"><i class="mdi mdi-cash-multiple"></i></div>
                        <div>
                            <div class="mc-mini-kpi-label">Cost Insight</div>
                            <div class="mc-mini-kpi-value">Estimated Cost & Waste</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mc-panel mt-3">
            <div class="mc-panel-head">
                <div>
                    <div class="mc-panel-title">Meal Plans</div>
                    <div class="mc-panel-subtitle">Latest planning records</div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="mc-table-modern">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Meal</th>
                            <th>Planned</th>
                            <th>Prepared</th>
                            <th>Unit Cost</th>
                            <th>Notes</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>@forelse($plans as $plan)<tr>
                            <td>{{ $plan->plan_date }}</td>
                            <td><span class="mc-status orange">{{ ucfirst($plan->meal_type) }}</span></td>
                            <td><strong>{{ number_format($plan->planned_portion) }}</strong></td>
                            <td>{{ number_format($plan->prepared_portion) }}</td>
                            <td>Rp {{ number_format($plan->unit_cost,0,',','.') }}</td>
                            <td>{{ $plan->notes ?: '—' }}</td>
                            <td>
                                <form method="POST" action="{{ route('master.mealPlan.destroy',$plan->id) }}"
                                    onsubmit="return confirm('Delete this meal plan?')">@csrf @method('DELETE')<button
                                        class="btn btn-sm btn-light text-danger"><i
                                            class="mdi mdi-delete-outline"></i></button></form>
                            </td>
                        </tr>@empty<tr>
                            <td colspan="7">
                                <div class="mc-empty"><i class="mdi mdi-clipboard-text-outline"></i>No meal plan yet. If
                                    migration has not been run, run it first.</div>
                            </td>
                        </tr>@endforelse</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('layout.footer')
