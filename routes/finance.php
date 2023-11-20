<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountGroupController;
use App\Http\Controllers\AccountingVoucherSettingController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\ContraController;
use App\Http\Controllers\CostCentreCategoryController;
use App\Http\Controllers\CostCentreController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinanceDashboardController;
use App\Http\Controllers\Income\IncomeCategoryController;
use App\Http\Controllers\Income\IncomeController;
use App\Http\Controllers\Income\IncomeReceiptController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\OutstandingPayableController;
use App\Http\Controllers\OutstandingReceivableController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\Report\AccountDayBookController;
use App\Http\Controllers\Report\AccountGroupSummaryController;
use App\Http\Controllers\Report\BalanceSheetController;
use App\Http\Controllers\Report\CashBankBooksController;
use App\Http\Controllers\Report\CashFlowController;
use App\Http\Controllers\Report\ExpenseReportController;
use App\Http\Controllers\Report\FundFlowController;
use App\Http\Controllers\Report\GroupCashFlowController;
use App\Http\Controllers\Report\IncomeReportController;
use App\Http\Controllers\Report\LedgerCashFlowController;
use App\Http\Controllers\Report\ProfitLossAccountController;
use App\Http\Controllers\Report\ProfitLossReportController;
use App\Http\Controllers\Report\TrialBalanceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'finance'], function () {

    Route::group(['prefix' => 'accounting'], function () {

        Route::controller(AccountGroupController::class)->prefix('groups')->group(function () {

            Route::get('/', 'index')->name('accounting.groups.index');
            Route::get('list', 'groupList')->name('accounting.groups.list');
            Route::get('create', 'create')->name('accounting.groups.create');
            Route::post('store', 'store')->name('accounting.groups.store');
            Route::get('edit/{id}', 'edit')->name('accounting.groups.edit');
            Route::post('update/{id}', 'update')->name('accounting.groups.update');
            Route::delete('delete/{id}', 'delete')->name('accounting.groups.delete');
        });

        Route::controller(ChartOfAccountController::class)->prefix('chart-of-accounts')->group(function () {

            Route::get('/', 'index')->name('accounting.charts.index');
            Route::get('list', 'chartAccountList')->name('accounting.charts.list');
            Route::get('edit/{id}', 'edit')->name('accounting.charts.edit');
            Route::post('update/{id}', 'update')->name('accounting.charts.update');
        });

        Route::controller(BankController::class)->prefix('banks')->group(function () {

            Route::get('/', 'index')->name('accounting.banks.index');
            Route::get('create', 'create')->name('accounting.banks.create');
            Route::post('store', 'store')->name('accounting.banks.store');
            Route::get('edit/{bankId}', 'edit')->name('accounting.banks.edit');
            Route::post('update/{bankId}', 'update')->name('accounting.banks.update');
            Route::delete('delete/{bankId}', 'delete')->name('accounting.banks.delete');
        });

        Route::controller(AccountController::class)->prefix('accounts')->group(function () {

            Route::get('/', 'index')->name('accounting.accounts.index');
            Route::get('account/create/modal', 'accountCreateModal')->name('accounting.accounts.create.modal');
            Route::get('account/ledger/view/{id}/{by}/{fromDate?}/{toDate?}/{userId?}', 'ledger')->name('accounting.accounts.ledger');
            Route::get('account/ledger/print/{id}/{by}', 'ledgerPrint')->name('accounting.accounts.ledger.print');
            Route::post('store', 'store')->name('accounting.accounts.store');
            Route::get('edit/{id}', 'edit')->name('accounting.accounts.edit');
            Route::post('update/{id}', 'update')->name('accounting.accounts.update');
            Route::delete('delete/{accountId}', 'delete')->name('accounting.accounts.delete');
            Route::get('voucher/list/{id}/{by}', 'voucherList')->name('accounting.accounts.voucher.list');
            Route::get('account/closing/balance/{accountId}', 'accountClosingBalance')->name('accounting.accounts.closing.balance');
        });

        Route::group(['prefix' => 'cost-centres'], function () {

            Route::controller(CostCentreCategoryController::class)->prefix('categories')->group(function () {

                Route::get('create', 'create')->name('cost.centres.categories.create');
                Route::post('store', 'store')->name('cost.centres.categories.store');
                Route::get('edit/{id}', 'edit')->name('cost.centres.categories.edit');
                Route::post('update/{id}', 'update')->name('cost.centres.categories.update');
                Route::delete('delete/{id}', 'delete')->name('cost.centres.categories.delete');
            });

            Route::controller(CostCentreController::class)->prefix('/')->group(function () {

                Route::get('/', 'index')->name('cost.centres.index');
                Route::get('list/of/cost/centres', 'listOfCostCentres')->name('cost.centres.list.of.cost.centres');
                Route::get('create', 'create')->name('cost.centres.create');
                Route::post('store', 'store')->name('cost.centres.store');
                Route::get('edit/{id}', 'edit')->name('cost.centres.edit');
                Route::post('update/{id}', 'update')->name('cost.centres.update');
                Route::delete('delete/{id}', 'delete')->name('cost.centres.delete');
            });
        });
    });

    Route::group(['prefix' => 'vouchers'], function () {

        Route::controller(JournalController::class)->prefix('journals')->group(function () {

            Route::get('/', 'index')->name('vouchers.journals.index');
            Route::get('create', 'create')->name('vouchers.journals.create');
            Route::get('show/{id}', 'show')->name('vouchers.journals.show');
            Route::post('store', 'store')->name('vouchers.journals.store');
            Route::get('edit/{id}', 'edit')->name('vouchers.journals.edit');
            Route::post('update/{id}', 'update')->name('vouchers.journals.update');
            Route::delete('delete/{id}', 'delete')->name('vouchers.journals.delete');
            Route::get('customer/closing/balance/{account_id}', 'userWiseCustomerClosingBalance')->name('vouchers.journals.user.wise.customer.closing.balance');
            Route::get('account/closing/balance/{account_id}', 'accountClosingBalance')->name('vouchers.journals.account.closing.balance');
            Route::post('cost/centre/prepare/{account_id?}', 'costCentrePrepare')->name('vouchers.journals.cost.centre.prepare');
        });

        Route::controller(ReceiptController::class)->prefix('receipts')->group(function () {

            Route::get('/', 'index')->name('vouchers.receipts.index');
            Route::get('show/{id}', 'show')->name('vouchers.receipts.show');
            Route::get('create/{mode}', 'create')->name('vouchers.receipts.create');
            Route::get('edit/{id}', 'edit')->name('vouchers.receipts.edit');
            Route::post('update/{id}', 'update')->name('vouchers.receipts.update');
            Route::post('store', 'store')->name('vouchers.receipts.store');
            Route::delete('delete/{id}', 'delete')->name('vouchers.receipts.delete');
            Route::get('customer/closing/balance/{account_id}', 'userWiseCustomerClosingBalance')->name('vouchers.receipts.user.wise.customer.closing.balance');
        });

        Route::controller(PaymentController::class)->prefix('payments')->group(function () {

            Route::get('/', 'index')->name('vouchers.payments.index');
            Route::get('show/{id}', 'show')->name('vouchers.payments.show');
            Route::get('create/{mode}', 'create')->name('vouchers.payments.create');
            Route::post('store', 'store')->name('vouchers.payments.store');
            Route::get('edit/{id}', 'edit')->name('vouchers.payments.edit');
            Route::post('update/{id}', 'update')->name('vouchers.payments.update');
            Route::delete('delete/{id}', 'delete')->name('vouchers.payments.delete');
            Route::get('customer/closing/balance/{account_id}', 'userWiseCustomerClosingBalance')->name('vouchers.payments.user.wise.customer.closing.balance');
        });

        Route::controller(ContraController::class)->prefix('contras')->group(function () {

            Route::get('/', 'index')->name('vouchers.contras.index');
            Route::get('create/{mode}', 'create')->name('vouchers.contras.create');
            Route::post('store', 'store')->name('vouchers.contras.store');
            Route::get('show/{id}', 'show')->name('vouchers.contras.show');
            Route::get('edit/{id}', 'edit')->name('vouchers.contras.edit');
            Route::post('update/{id}', 'update')->name('vouchers.contras.update');
            Route::delete('delete/{id}', 'delete')->name('vouchers.contras.delete');
        });

        Route::controller(ExpenseController::class)->prefix('expenses')->group(function () {

            Route::get('/', 'index')->name('vouchers.expenses.index');
            Route::get('create/{mode}', 'create')->name('vouchers.expenses.create');
            Route::post('store', 'store')->name('vouchers.expenses.store');
            Route::get('show/{id}', 'show')->name('vouchers.expenses.show');
            Route::get('edit/{id}', 'edit')->name('vouchers.expenses.edit');
            Route::post('update/{id}', 'update')->name('vouchers.expenses.update');
            Route::delete('delete/{id}', 'delete')->name('vouchers.expenses.delete');
        });
    });

    Route::controller(IncomeController::class)->prefix('income')->as('income.')->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::get('accounts/by/ajax', 'getIncomeAccountsByAjax')->name('accounts.by.ajax');
        Route::get('account/quick/add/modal', 'incomeAccountQuickAddModal')->name('account.quick.add.modal');

        Route::controller(IncomeReceiptController::class)->prefix('receipts')->group(function () {

            Route::get('/{id}', 'index')->name('receipts.index');
            Route::get('show/{id}', 'show')->name('receipts.show');
            Route::get('create/{id}', 'create')->name('receipts.create');
            Route::post('store/{id}', 'store')->name('receipts.store');
            Route::get('edit/{id}', 'edit')->name('receipts.edit');
            Route::post('update/{id}', 'update')->name('receipts.update');
            Route::delete('delete/{id}', 'delete')->name('receipts.delete');
        });

        Route::controller(IncomeCategoryController::class)->prefix('categories')->group(function () {

            Route::get('/', 'index')->name('categories.index');
            Route::post('/store', 'store')->name('categories.store');
            Route::get('/edit/{id}', 'edit')->name('categories.edit');
            Route::post('/update/{id}', 'update')->name('categories.update');
            Route::delete('/delete/{id}', 'delete')->name('categories.delete');
            Route::get('/all/category', 'allIncomeCategory')->name('categories.all.category');
        });
    });

    Route::group(['prefix' => 'settings'], function () {

        Route::controller(AccountingVoucherSettingController::class)->prefix('voucher')->group(function () {

            Route::get('/', 'index')->name('finance.voucher.settings.index');
            Route::post('update', 'update')->name('finance.voucher.settings.update');
        });
    });

    Route::controller(FinanceDashboardController::class)->prefix('dashboard')->group(function () {

        Route::get('/', 'index')->name('finance.dashboard.index');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::group(['prefix' => 'daily/profit/loss'], function () {

            Route::get('/', [ProfitLossReportController::class, 'index'])->name('reports.profit.loss.index');
            Route::get('sale/purchase/profit', [ProfitLossReportController::class, 'salePurchaseProfit'])->name('reports.profit.sale.purchase.profit');
            Route::get('filter/sale/purchase/profit/filter', [ProfitLossReportController::class, 'filterSalePurchaseProfit'])->name('reports.profit.filter.sale.purchase.profit');
            Route::get('print', [ProfitLossReportController::class, 'printProfitLoss'])->name('reports.profit.loss.print');
        });

        Route::controller(ExpenseReportController::class)->prefix('expenses')->group(function () {

            Route::get('/', 'index')->name('reports.expenses.index');
            Route::get('print', 'print')->name('reports.expenses.print');
        });

        Route::controller(IncomeReportController::class)->prefix('incomes')->group(function () {

            Route::get('/', 'index')->name('reports.incomes.index');
            Route::get('print', 'print')->name('reports.incomes.print');
        });

        Route::controller(AccountDayBookController::class)->prefix('day-book')->group(function () {

            Route::get('/', 'index')->name('reports.daybook.index');
            Route::get('print', 'print')->name('reports.daybook.print');
        });

        Route::controller(TrialBalanceController::class)->prefix('trial-balance')->group(function () {

            Route::get('/', 'index')->name('reports.trial.balance.index');
            Route::get('view', 'trialBalanceDataView')->name('reports.trial.balance.data.view');
            Route::get('print', 'print')->name('reports.trial.balance.print');
        });

        Route::controller(ProfitLossAccountController::class)->prefix('profit-loss-account')->group(function () {

            Route::get('index/{fromDate?}/{toDate?}', 'index')->name('reports.profit.loss.account.index');
            Route::get('view', 'profitLossAccountDataView')->name('reports.profit.loss.account.data.view');
            Route::get('print', 'profitLossAccountDataPrint')->name('reports.profit.loss.account.print');
        });

        Route::controller(BalanceSheetController::class)->prefix('balance-sheet')->group(function () {

            Route::get('/', 'index')->name('reports.balance.sheet.index');
            Route::get('balance/sheet/data/view', 'balanceSheetDataView')->name('reports.balance.sheet.data.view');
            Route::get('print', 'balanceSheetDataPrint')->name('reports.balance.sheet.data.print');
        });

        Route::controller(CashFlowController::class)->prefix('cash-flow')->group(function () {

            Route::get('/', 'index')->name('reports.cash.flow.index');
            Route::get('view', 'cashFlowView')->name('reports.cash.flow.data.view');
            Route::get('print', 'cashFlowPrint')->name('reports.cash.flow.data.print');

            Route::controller(GroupCashFlowController::class)->prefix('group-cash-flow')->group(function () {

                Route::get('index/{groupId}/{cashFlowSide}/{fromDate?}/{toDate?}', 'index')->name('reports.group.cash.flow.index');
                Route::get('view/{groupId}/{cashFlowSide}', 'groupCashFlowView')->name('reports.group.cash.flow.view');
                Route::get('print/{groupId}/{cashFlowSide}', 'groupCashFlowPrint')->name('reports.group.cash.flow.print');
            });

            Route::controller(LedgerCashFlowController::class)->prefix('ledger-cash-flow')->group(function () {

                Route::get('ledger/cashflow/index/{accountId}/{cashFlowSide}/{fromDate?}/{toDate?}', 'index')->name('reports.ledger.cash.flow.index');
                Route::get('ledger/cashflow/blade-view/{accountId}/{cashFlowSide}', 'ledgerCashFlowView')->name('reports.ledger.cash.flow.blade.view');
                Route::get('ledger/cashflow/print/{accountId}/{cashFlowSide}', 'ledgerCashFlowBladePrint')->name('reports.ledger.cash.flow.print');
            });
        });

        Route::controller(FundFlowController::class)->prefix('fund-flow')->group(function () {

            Route::get('/', 'index')->name('reports.fund.flow.index');
            Route::get('view', 'fundFlowDataView')->name('reports.fund.flow.data.view');
            Route::get('print', 'fundFlowDataPrint')->name('reports.fund.flow.data.print');
        });

        Route::controller(CashBankBooksController::class)->prefix('cash-bank-books')->group(function () {

            Route::get('/', 'index')->name('reports.cash.bank.books.index');
            Route::get('view', 'cashBankBooksView')->name('reports.cash.bank.books.view');
            Route::get('print', 'cashBankBooksPrint')->name('reports.cash.bank.books.print');
        });

        Route::controller(OutstandingReceivableController::class)->prefix('outstanding-receivables')->group(function () {

            Route::get('/', 'index')->name('reports.outstanding.receivable.index');
            Route::get('view', 'outstandingReceivableDataView')->name('reports.outstanding.receivable.data.view');
            Route::get('print', 'outstandingReceivableDataPrint')->name('reports.outstanding.receivable.data.print');
        });

        Route::controller(OutstandingPayableController::class)->prefix('outstanding-payables')->group(function () {

            Route::get('/', 'index')->name('reports.outstanding.payable.index');
            Route::get('view', 'outstandingPayableDataView')->name('reports.outstanding.payable.data.view');
            Route::get('print', 'outstandingPayableDataPrint')->name('reports.outstanding.payable.data.print');
        });

        Route::controller(AccountGroupSummaryController::class)->prefix('group-summary')->group(function () {

            Route::get('index/{groupId}/{fromDate?}/{toDate?}', 'index')->name('reports.group.summary.index');
            Route::get('view/{groupId}', 'groupSummaryView')->name('reports.group.summary.view');
        });
    });
});
