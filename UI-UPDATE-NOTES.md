# Meelscan Report — Modern UI + New Functional Menus

Update ini menambahkan menu dan fitur yang sebelumnya hanya ada pada rancangan UI.

## Menu baru

### Overview
- Dashboard V2
- Live Monitoring

### Meal Management
- Meal Transactions (menggunakan Consumption Data existing)
- Employees
- Visitors

### Analytics
- Consumption Analytics
- Peak Hours
- Order Type Analysis
- Rating & Feedback
- Face Verification

### Reports
- Daily Report
- Monthly Report
- Employee History

### Master Data
- Meal Plan
- Food Category
- Mess Location

### System
- Settings
- User Management

## Dashboard V2
Dashboard sekarang juga menampilkan:
- 6 KPI (total, breakfast, lunch, dinner, rating, alert)
- consumption per hour
- meal proportion
- order type distribution
- 7-day consumption trend
- live meal activity
- attention required
- recent rating

## Database tambahan
Ada 2 migration baru:
- `meal_plans`
- `meal_settings`

Jalankan pada project Laravel lengkap:

```bash
php artisan migrate
```

Tanpa migration, menu analytics/report/live monitoring tetap dapat menggunakan `attendance_logs`. Menu Meal Plan dan penyimpanan Settings akan menampilkan pesan bahwa migration perlu dijalankan.

## Export existing tetap dipertahankan
File berikut sengaja tidak diubah dari paket modern UI sebelumnya:
- `app/Http/Controllers/ConsumptionDataController.php`
- `app/Exports/ConsumptionExport.php`

Route `consumptionData.export` tetap menggunakan method `exportExcel` existing. Daily Report dan Monthly Report hanya mengarahkan tombol export ke export existing dengan parameter period yang sesuai.

## Catatan
Source ZIP yang diberikan tidak memiliki `artisan`, `bootstrap/`, dan `vendor/`, sehingga pengujian runtime Laravel penuh tidak dapat dilakukan dari paket ini. PHP controller/migration sudah diperiksa syntax-nya, dan inline JavaScript pada view baru sudah diperiksa syntax-nya.
