# Feature Map

| Menu | Route | Data source |
|---|---|---|
| Dashboard | `/dashboard` | attendance_logs |
| Live Monitoring | `/live-monitoring` | attendance_logs + meal_settings optional |
| Meal Transactions | `/consumption-data` | Existing feature |
| Employees | `/employees` | Existing feature |
| Visitors | `/visitors` | attendance_logs.visitor_name |
| Consumption Analytics | `/analytics/consumption` | attendance_logs |
| Peak Hours | `/analytics/peak-hours` | attendance_time |
| Order Type Analysis | `/analytics/order-types` | order_type |
| Rating & Feedback | `/analytics/ratings` | rating, remarks |
| Face Verification | `/analytics/face-verification` | similarity_score, confidence_score, is_real_face, photo_path |
| Daily Report | `/reports/daily` | attendance_logs + existing export |
| Monthly Report | `/reports/monthly` | attendance_logs + existing export |
| Employee History | `/reports/employee-history` | attendance_logs.nik |
| Meal Plan | `/master/meal-plan` | meal_plans (new migration) |
| Food Category | `/master/food-category` | ref_meals |
| Mess Location | `/master/mess-location` | attendance_logs.position |
| Settings | `/settings` | meal_settings (new migration) |
| User Management | `/user-management` | users |
