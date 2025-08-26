# Finance Module Documentation

## Overview
The Finance module is a comprehensive financial management system integrated into the HRM application. It provides tools for tracking company finances, managing balance sheets, monitoring expenses, budgets, and generating financial reports.

## Table of Contents
1. [Getting Started](#getting-started)
2. [Balance Sheet](#balance-sheet)
3. [Dashboard](#dashboard)
4. [Expenses](#expenses)
5. [Budgets](#budgets)
6. [Reports](#reports)
7. [Technical Implementation](#technical-implementation)
8. [API Endpoints](#api-endpoints)
9. [Troubleshooting](#troubleshooting)

---

## Getting Started

### Accessing Finance Module
1. Login as an **Admin** user
2. Navigate to **Admin Dashboard**
3. Click on **Finance** in the sidebar menu
4. Choose from available options:
   - Dashboard
   - Balance Sheet
   - Expenses
   - Budgets
   - Reports

### Required Permissions
- Only users with **Admin** role can access the Finance module
- Authentication middleware: `auth:admin`

---

## Balance Sheet

The Balance Sheet is a simple yet powerful tool for tracking your company's financial position. It shows what you own (Assets), what you owe (Liabilities), and your company's value (Equity).

### Core Concept
**Assets = Liabilities + Equity**

When this equation balances, your financial records are correct.

### Features

#### 1. Three Main Sections

##### 📈 **ASSETS (Things We Own)**
- **Cash**: Money in bank accounts and cash on hand
- **Money from Clients**: Payments owed to you by customers
- **Equipment**: Computers, furniture, and other business equipment
- **Custom Items**: Add your own asset categories

##### 💳 **LIABILITIES (Things We Owe)**
- **Unpaid Bills**: Bills you need to pay to suppliers
- **Contractor Payments**: Money owed to freelancers and contractors
- **Taxes**: Tax payments due to government
- **Custom Items**: Add your own liability categories

##### 📊 **EQUITY (Company Value)**
- **Owner Investment**: Money you put into the business
- **Profits**: Money the business has earned and kept
- **Custom Items**: Add your own equity categories

#### 2. User Interface Features

##### Balance Indicator
- ✅ **Green Checkmark**: Assets = Liabilities + Equity (Balanced)
- ❌ **Red X**: Assets ≠ Liabilities + Equity (Not Balanced)
- Shows the difference amount when not balanced

##### Input Features
- **Currency Symbol**: Automatic $ symbol display
- **Number Formatting**: Automatic comma separation (e.g., 1,234.56)
- **Decimal Support**: Up to 2 decimal places
- **Tooltips**: Hover over ℹ️ icons for explanations

##### Visual Design
- **Color Coding**:
  - Assets: Green border
  - Liabilities: Yellow/Warning border
  - Equity: Blue/Info border
- **Large Numbers**: Easy-to-read font sizes
- **Professional Layout**: Clean, organized sections

#### 3. Data Management

##### Auto-Save
- Changes are automatically saved every 1 second
- No need to click save manually
- Status indicator shows last save time

##### Monthly Data
- Use date picker to select month/year
- Each month's data is stored separately
- Load previous months instantly
- Format: YYYY-MM (e.g., 2024-08)

##### Add/Remove Items
- **Add Items**: Click the ➕ button in each section
- **Remove Items**: Click the 🗑️ button (only for custom items)
- **Default Items**: Cannot be deleted (Cash, Bills, etc.)
- **Custom Names**: Edit item names by clicking on them

#### 4. Export & Print

##### CSV Export
- Click **Export CSV** button
- Downloads file: `balance_sheet_YYYY-MM.csv`
- Includes all sections, totals, and balance status
- Opens in Excel or Google Sheets

##### Print View
- Click **Print** button
- Clean, professional print layout
- Removes buttons and interactive elements
- Fits on standard 8.5x11" paper

### How to Use Balance Sheet

#### Step 1: Select Month
1. Use the date picker in the top-right corner
2. Select the month and year you want to work with
3. Data loads automatically

#### Step 2: Enter Your Assets
1. Click in the **Cash** field
2. Enter the amount you have in bank accounts
3. Enter **Money from Clients** (accounts receivable)
4. Enter **Equipment** value
5. Add custom assets if needed using ➕ button

#### Step 3: Enter Your Liabilities
1. Enter **Unpaid Bills** (accounts payable)
2. Enter **Contractor Payments** owed
3. Enter **Taxes** due
4. Add custom liabilities if needed

#### Step 4: Enter Your Equity
1. Enter **Owner Investment** (money you put in)
2. Enter **Profits** (retained earnings)
3. Add custom equity items if needed

#### Step 5: Check Balance
- Look at the balance indicator at the top
- If green ✅: You're balanced!
- If red ❌: Check your numbers

#### Step 6: Save & Export
- Data auto-saves as you type
- Export to CSV for backup
- Print for physical records

### Example Balance Sheet

```
ASSETS
Cash: $15,000
Money from Clients: $8,500
Equipment: $12,000
Total Assets: $35,500

LIABILITIES
Unpaid Bills: $3,200
Contractor Payments: $1,800
Taxes: $2,000
Total Liabilities: $7,000

EQUITY
Owner Investment: $20,000
Profits: $8,500
Total Equity: $28,500

Balance Check: $35,500 = $35,500 ✅
```

---

## Dashboard

The Finance Dashboard provides an overview of your financial metrics.

### Features
- **Total Revenue**: $25,000 (example)
- **Total Expenses**: $8,500 (example)
- **Net Profit**: $16,500 (calculated)
- **Budget Remaining**: $50,000 (example)

### Visual Elements
- Colorful metric cards
- Icons for each financial category
- Quick overview of financial health

---

## Expenses

Manage and track all business expenses.

### Features
- **Add New Expenses**: Button to create new expense entries
- **Expense Table**: View all expenses in organized table
- **Categories**: Organize expenses by type
- **Status Tracking**: Approved, Pending, etc.
- **Actions**: View, Edit, Delete expenses

### Table Columns
- Date
- Description
- Category
- Amount
- Status
- Actions

---

## Budgets

Monitor budget allocation and spending.

### Features
- **Budget Categories**: Operations, Marketing, HR, IT
- **Progress Bars**: Visual spending indicators
- **Percentage Used**: Shows budget utilization
- **Color Coding**:
  - Green: Under 70% used
  - Yellow: 70-90% used
  - Red: Over 90% used

### Example Budgets
- Operations: $6,500 / $10,000 (65%)
- Marketing: $8,000 / $10,000 (80%)
- HR: $4,500 / $10,000 (45%)
- IT: $9,500 / $10,000 (95%)

---

## Reports

Generate various financial reports.

### Available Reports
1. **Monthly Report**: Comprehensive monthly financial summary
2. **Expense Report**: Detailed breakdown of all expenses
3. **Budget Analysis**: Budget vs actual spending analysis
4. **Cash Flow**: Income and outflow tracking
5. **Profit & Loss**: Revenue and expense comparison
6. **Quarterly Report**: Quarterly financial overview

### Features
- **Generate Button**: Click to create each report
- **Visual Icons**: Each report has distinct icon
- **Professional Cards**: Clean, organized layout

---

## Technical Implementation

### File Structure
```
app/Http/Controllers/Admin/FinanceController.php
routes/admin.php (finance routes)
resources/js/Pages/Backend/Finance/
├── Dashboard.vue
├── BalanceSheet.vue
├── Expenses.vue
├── Budgets.vue
└── Reports.vue
resources/js/Components/Backend/Sidebar.vue (updated)
storage/app/ (balance sheet data files)
```

### Data Storage
Balance Sheet data is stored as JSON files in Laravel storage:
- **File Format**: `balance_sheet_YYYY-MM.json`
- **Location**: `storage/app/`
- **Structure**:
```json
{
  "assets": [
    {"name": "Cash", "amount": 15000, "tooltip": "Money in bank accounts"},
    {"name": "Money from Clients", "amount": 8500, "tooltip": "Customer payments owed"}
  ],
  "liabilities": [...],
  "equity": [...],
  "totals": {
    "assets": 35500,
    "liabilities": 7000,
    "equity": 28500,
    "liabilitiesEquity": 35500
  },
  "balanced": true
}
```

### Frontend Technology
- **Vue.js 3**: Reactive components
- **Composition API**: Modern Vue.js patterns
- **Axios**: HTTP requests
- **Bootstrap 5**: Responsive styling
- **FontAwesome**: Icons

### Backend Technology
- **Laravel 10**: PHP framework
- **Inertia.js**: SPA-like experience
- **Carbon**: Date manipulation
- **Storage Facade**: File operations

---

## API Endpoints

### Balance Sheet Endpoints

#### GET `/admin-home/finance/balance-sheet`
- **Description**: Load Balance Sheet page
- **Returns**: Vue component with current month data
- **Authentication**: Admin required

#### POST `/admin-home/finance/balance-sheet/save`
- **Description**: Save balance sheet data
- **Parameters**:
  - `date`: Month in YYYY-MM format
  - `data`: JSON object with balance sheet data
- **Returns**: Success/error response

#### GET `/admin-home/finance/balance-sheet/load/{date}`
- **Description**: Load specific month's data
- **Parameters**: `date` - Month in YYYY-MM format
- **Returns**: JSON balance sheet data

#### GET `/admin-home/finance/balance-sheet/export/{date}`
- **Description**: Export balance sheet to CSV
- **Parameters**: `date` - Month in YYYY-MM format
- **Returns**: CSV file download

### Other Finance Endpoints

#### GET `/admin-home/finance/dashboard`
- **Description**: Finance dashboard
- **Returns**: Dashboard view

#### GET `/admin-home/finance/expenses`
- **Description**: Expenses management
- **Returns**: Expenses view

#### GET `/admin-home/finance/budgets`
- **Description**: Budget management
- **Returns**: Budgets view

#### GET `/admin-home/finance/reports`
- **Description**: Financial reports
- **Returns**: Reports view

---

## Troubleshooting

### Common Issues

#### Balance Sheet Not Loading
**Problem**: Page shows error or blank screen
**Solutions**:
1. Check if user has admin permissions
2. Verify routes are registered: `php artisan route:list`
3. Clear cache: `php artisan cache:clear`
4. Check storage permissions: `chmod -R 775 storage/`

#### Auto-Save Not Working
**Problem**: Changes don't save automatically
**Solutions**:
1. Check browser console for JavaScript errors
2. Verify API endpoints are accessible
3. Check storage directory permissions
4. Test manual save API call

#### Data Not Persisting
**Problem**: Data disappears after page reload
**Solutions**:
1. Check storage directory exists and is writable
2. Verify JSON file creation in `storage/app/`
3. Check Laravel logs: `tail -f storage/logs/laravel.log`
4. Test with manual file creation

#### Export/Print Not Working
**Problem**: CSV export or print fails
**Solutions**:
1. Check if data exists for the selected month
2. Verify browser allows downloads
3. Check popup blockers for print function
4. Test with different browsers

#### Balance Not Calculating
**Problem**: Totals show incorrect values
**Solutions**:
1. Check for non-numeric input values
2. Verify JavaScript calculations in browser console
3. Refresh page to reload data
4. Check for decimal precision issues

### Debug Mode
Enable debug mode in Laravel to see detailed error messages:
1. Set `APP_DEBUG=true` in `.env` file
2. Check `storage/logs/laravel.log` for errors
3. Use browser developer tools for frontend issues

### Performance Tips
1. **Large Datasets**: Consider pagination for expense lists
2. **File Storage**: Archive old balance sheet files periodically
3. **Caching**: Implement caching for frequently accessed data
4. **Database**: Consider moving to database storage for production

---

## Security Considerations

### Authentication
- All finance routes require admin authentication
- Middleware: `auth:admin`
- Session-based authentication

### Data Protection
- Balance sheet data stored server-side
- No sensitive data in frontend JavaScript
- File permissions restrict access

### Input Validation
- Numeric validation for amounts
- Date format validation
- XSS protection on text inputs

### Recommendations
1. **Regular Backups**: Backup balance sheet files regularly
2. **Access Logs**: Monitor finance module access
3. **Data Encryption**: Consider encrypting sensitive financial data
4. **User Audit**: Track who makes financial changes

---

## Future Enhancements

### Planned Features
1. **Multi-Currency Support**: Handle different currencies
2. **Chart Integration**: Visual graphs and charts
3. **Email Reports**: Scheduled email reports
4. **Mobile App**: Dedicated mobile application
5. **Integration**: Connect with accounting software
6. **Approval Workflow**: Multi-level approval process

### Database Migration
Consider migrating from file storage to database for:
- Better performance with large datasets
- Advanced querying capabilities
- Data relationships and integrity
- Concurrent user support

---

## Support

### Getting Help
1. **Documentation**: Read this file thoroughly
2. **Laravel Docs**: https://laravel.com/docs
3. **Vue.js Docs**: https://vuejs.org/guide/
4. **Issue Tracking**: Create GitHub issues for bugs

### Contact Information
- **Developer**: Your Development Team
- **Project**: HRM Finance Module
- **Version**: 1.0.0
- **Last Updated**: August 2024

---

*This documentation covers the complete Finance module implementation. For technical support or feature requests, please contact the development team.*